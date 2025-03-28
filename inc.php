<?php

use KlaviyoAPI\KlaviyoAPI;

class Klaviyo_Profile_Rewards_Sync {
  
  public const UMETA_KEY__PROFILE_ID = 'klaviyo_profile_id';
  public const UMETA_KEY__REWARD_POINTS = '_reward_points';
  
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

      $response = $this->klaviyo->Profiles->getProfiles(
          null, // $additional_fields_profile
          null, // $fields_profile
          'equals(email,"' . $user_email . '")'
      );

      if ( is_array($response) ) {
        $found_profile = $response['data'][0] ?? false;

        if ( is_array( $found_profile ))  {
          $klaviyo_profile_id = $found_profile['id'];
        }
      }
    }
    catch ( Exception $e) {
      // TODO add logging
      echo('<pre>' . print_r( $e, 1) . '</pre>' );
    }

    return $klaviyo_profile_id;
  }


  /**
   * Makes an API request to Klaviyo and updates 'reward_points' attribute
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

      $update_response = $this->klaviyo->Profiles->updateProfile( $klaviyo_profile_id, $profile_partial_update_query );

      if ( is_array($update_response) ) {
        // TODO 
        echo('RESPONSE<pre>' . print_r( $e, 1) . '</pre>' );
      }
    }
    catch ( Exception $e) {
      // TODO add logging
      echo('EXCEPTION<pre>' . print_r( $e, 1) . '</pre>' );
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
}