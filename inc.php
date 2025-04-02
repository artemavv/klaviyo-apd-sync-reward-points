<?php

use KlaviyoAPI\KlaviyoAPI;

class Klaviyo_Profile_Rewards_Sync {
  
  public const UMETA_KEY__PROFILE_ID = 'klaviyo_profile_id';
  public const UMETA_KEY__REWARD_POINTS = '_reward_points';
  // public const UMETA_KEY__SYNCED_TO_KLAVIO = 'klaviyo_sync_status';

  /**
   * Secret API Key to get access to the Klaviyo API
   * @var string
   */
  private $api_key = '';
  
  /**
   * An instance of KlaviyoAPI
   * @var object
   */
  private $klaviyo;
  
  public function __construct( $api_key = '' ) {
    
    if ( ! $api_key) {
      $apd_options = get_option( 'apd_options' );
      $api_key = $apd_options['_klaviyo_api_key_for_rewards'] ?? false;
    }

    if ( $api_key ) {
      $this->api_key = $api_key;

      $this->klaviyo = new KlaviyoAPI( $api_key );
    }
  }
  
  // TODO Add API status check
  public function is_ok() {
    $is_ok = false;
    
    if ( is_object( $this->klaviyo ) ) {
      $is_ok = true;
    }
    
    return $is_ok;
  }
  
  /**
   * Makes an API request to Klaviyo and gets the profile ID (if present)
   * @param string $user_email
   * @param object $klaviyo_api instance of KlaviyoAPI
   * 
   * @return int | false
   */
  public function get_klaviyo_profile_id( $user_email ) {

    $klaviyo_profile_id = false;

    try {

      $this->log( 'Getting Klaviyo profile ID for ' . $user_email );
      $response = $this->klaviyo->Profiles->getProfiles(
          null, // $additional_fields_profile
          null, // $fields_profile
          'equals(email,"' . $user_email . '")'
      );

      if ( is_array($response) ) {
        $found_profile = $response['data'][0] ?? false;

        if ( is_array( $found_profile ))  {
          $klaviyo_profile_id = $found_profile['id'];
          $this->log( 'Email: ' . $user_email . ' -> Klaviyo profile ID: ' . $klaviyo_profile_id );
        }
      }
    }
    catch ( Exception $e) {
      // Log the error into WooCommerce logging system
      $this->log( 'Klaviyo API Error: ' . $e->getMessage() );
    }

    return $klaviyo_profile_id;
  }

  /**
   * Finds N users who are not yet synced to Klavio
   * 
   * @param int $limit
   * 
   * @return array
   */
  public function get_users_to_update_bulk( $limit = 9000 ) {

    $user_data = array();
    
    global $wpdb;
    
    $prefix = $wpdb->prefix;
    
    $sql = $wpdb->prepare( "SELECT u.ID, u.user_email, um.meta_value AS reward_points FROM {$prefix}users AS u
      LEFT JOIN {$prefix}usermeta AS um ON u.ID = um.user_id AND um.meta_key = %s   
      LEFT JOIN {$prefix}users_klaviyo_data AS ukd ON u.ID = ukd.user_id
      WHERE ukd.user_id IS NULL AND um.user_id IS NOT NULL
      ORDER BY u.ID DESC
      LIMIT %d", self::UMETA_KEY__REWARD_POINTS, $limit );
    
    $user_data = $wpdb->get_results( $sql, ARRAY_A );
    
    $min_id = $user_data[0]['ID'];
    $max_id = $user_data[count($user_data) - 1]['ID'];

    return array(
      'min_id' => $min_id,
      'max_id' => $max_id,
      'user_data' => $user_data
    );
  }

  /*

    We have a custom SQL table to store the Klaviyo data for each user

CREATE TABLE `wp_users_klaviyo_data` (
  `user_id` int NOT NULL PRIMARY KEY,
  `status` tinytext NOT NULL,
  `klaviyo_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
  */

  /**
   * Marks users as synced to Klaviyo
   * 
   * @param array $user_ids
   */
  public function mark_users_as_synced( $user_ids ) {

    global $wpdb;
    
    $prefix = $wpdb->prefix;
    
    $sql = "INSERT INTO {$prefix}users_klaviyo_data (user_id, status, klaviyo_id) VALUES ";

    foreach ( $user_ids as $user_id ) {
      $sql .= "(" . $user_id . ", 'synced', ''),";
    }

    $sql = rtrim( $sql, ',' );

    $wpdb->query( $sql );

  }

  /**
   * Makes an API request to Klaviyo to update reward points for multiple profiles
   * 
   * @param array $userdata
   * 
   * @return bool
   */
  public function update_profiles_bulk( $users ) {

    $user_profiles = array();

    // Prepare the profiles data for the API request
    // @see https://developers.klaviyo.com/en/reference/bulk_import_profiles
    foreach ( $users as $user ) {
      $user_profiles[] = [
        'type' => 'profile',
        'attributes' => [
          'email' => $user['user_email'], 
          'properties' => [
            'reward_points' => $user['reward_points']
          ]
        ]
      ];
    }
    
    // Prepare the bulk update query for the Klaviyo API 
    $query = [
      'data' => [ 
        'type' => 'profile-bulk-import-job',
        'attributes' => [
          'profiles' => [
            'data' => $user_profiles
          ]
        ]
      ]
    ];

    try {
      
      $response = $this->klaviyo->Profiles->bulkImportProfiles(
          $query,
          $this->api_key
      );

      if ( is_array($response) ) {
        $this->log( 'Bulk response: ' . print_r( $response, 1) );
        return true;
      }
    }
    catch ( Exception $e) {
      // Log the error into WooCommerce logging system
      $this->log( 'Klaviyo API Error: ' . $e->getMessage() );
    }

    return false;
  }


  /**
   * Makes an API request to Klaviyo and updates 'reward_points' attribute for a single profile
   * 
   * @param string $klaviyo_profile_id
   * @param int $reward_points
   * @param object $klaviyo_api instance of KlaviyoAPI
   * 
   * @return int | false
   */
  public function update_klaviyo_reward_points( $klaviyo_profile_id, $reward_points ) {

    $update_response = false;
    
    try {

      $profile_partial_update_query = [
        "data" => [ 
          "type"         => "profile", 
          "id"           => $klaviyo_profile_id, 
          "attributes"   => [ 
            "properties" => [ "reward_points" => $reward_points ]
          ]
        ]
      ];

      $this->log( 'Updating Klaviyo profile ID: ' . $klaviyo_profile_id . ' with reward points: ' . $reward_points );

      $update_response = $this->klaviyo->Profiles->updateProfile( $klaviyo_profile_id, $profile_partial_update_query );

      if ( is_array($update_response) ) {

        $this->log( 'Update response: ' . print_r( $update_response, 1) );
      }
    }
    catch ( Exception $e) {
      // Log the error into WooCommerce logging system
      $this->log( 'Klaviyo API Error: ' . $e->getMessage() );
    }

    return $update_response;
  }
  
  public function get_users_without_klaviyo_id() {
    
    $user_ids = array();
    
    global $wpdb;
    
    $prefix = $wpdb->prefix;
    
    $sql = $wpdb->prepare( "SELECT u.ID FROM {$prefix}users AS u
      LEFT JOIN {$prefix}usermeta AS um ON u.ID = um.user_id AND um.meta_key = %s
      WHERE um.user_id IS NULL
      ORDER BY u.ID DESC
      LIMIT 10" , self::UMETA_KEY__PROFILE_ID );
    
    $results = $wpdb->get_results( $sql, ARRAY_A );
    
    foreach ( $results as $result ) {
      $user_ids[] = $result['ID'];
    }
    
    return $user_ids;
  }
  
  
  public function update_user_klaviyo_id( $user_id, $klaviyo_profile_id ) {
    
    add_user_meta( $user_id, self::UMETA_KEY__PROFILE_ID, $klaviyo_profile_id );
    
  }

  /**
   * Log the message into WooCommerce log
   * 
   * @param string $message
   */
  public function log( $message ) {
    if ( function_exists( 'wc_get_logger' ) ) {
      $logger = wc_get_logger();
      $logger->info( $message );
    }
  }
  
}