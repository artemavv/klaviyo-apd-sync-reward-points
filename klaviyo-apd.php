<?php
/**
 * Plugin Name: Klaviyo - Sync user reward points
 * Plugin URI:
 * Description: Updates user's rewards points via Klaviyo API.
 * Author: Artem
 * Version: 0.4.1
 * Author URI:
 */

require 'vendor/autoload.php';
require 'inc.php';

include_once( ABSPATH . 'wp-includes/pluggable.php' );


/**
 * Callback function for 'process_user_profile_for_klaviyo' action
 * 
 * It is to be executed for each user profile which does not have Klaviyo ID or has not synced its rewards points
 * 
 * @param int $user_id
 */
function sync_user_reward_points_with_klaviyo( $user_id ) {
  
  $klaviyo_sync = new Klaviyo_Profile_Rewards_Sync();

  if ( $klaviyo_sync->is_ok() ) {

    $user = get_user( $user_id );

    if ( $user ) {
      $user_email = addslashes( $user->user_email );

      if ( $user_email ) {
        $klaviyo_profile_id = $klaviyo_sync->get_klaviyo_profile_id( $user_email );

        if ( $klaviyo_profile_id ) {
          $klaviyo_sync->update_user_klaviyo_id( $user_id, $klaviyo_profile_id );

          $current_points = get_user_meta( $user_id, '_reward_points', true );

          $klaviyo_sync->update_klaviyo_reward_points( $klaviyo_profile_id, $current_points );
        }
      }
    }
    

  }
}


/**
 * 
 */
function bulk_sync_profiles_for_klaviyo( $debug = false ) {

  $klaviyo_sync = new Klaviyo_Profile_Rewards_Sync();

  if ( $klaviyo_sync->is_ok() ) {

    $users = $klaviyo_sync->get_users_to_update_bulk( 10 );

    foreach ( $users['user_data'] as $user ) {
      $user_ids[] = $user['ID'];
    }

    $klaviyo_sync->log( 'Sending BULK profiles to update in Klaviyo, IDs: ' . $users['min_id'] . ' - ' . $users['max_id'] );


    if ( $klaviyo_sync->update_profiles_bulk( $users['user_data']) ) {
      
      if ( $debug ) {
        echo('<pre>' . print_r( $user_ids , 1 ) . '</pre>' );
      }
      $klaviyo_sync->mark_users_as_synced( $user_ids );   
    }

  }
  
}

/**
 * 
 */
function bulk_sync_profiles_for_klaviyo_again( $debug = false ) {

  $klaviyo_sync = new Klaviyo_Profile_Rewards_Sync();

  if ( $klaviyo_sync->is_ok() ) {

    $users = $klaviyo_sync->get_users_to_update_bulk( 9000, 'needs_sync' );

    foreach ( $users['user_data'] as $user ) {
      $user_ids[] = $user['ID'];
    }

    if ( $debug ) {
      echo('<pre>' . print_r( $user_ids , 1 ) . '</pre>' );
      die();
    }

    $klaviyo_sync->log( 'Sending (2) BULK profiles to update in Klaviyo, IDs: ' . $users['min_id'] . ' - ' . $users['max_id'] );


    if ( $klaviyo_sync->update_profiles_bulk( $users['user_data']) ) {
      
      
      $klaviyo_sync->mark_users_as_synced( $user_ids );   
    }

  }
  
}

/**
 * Downloads profiles from Klaviyo in bulk using API call "get_profiles"
 * and saves them to the database
 * 
 * Setting page cursor to download the next page on the next run
 * 
 * 
 * Makes the equivalent of this API call: 
 * 
 * curl --request GET       
 * --url 'https://a.klaviyo.com/api/profiles??additional-fields%5Bprofile%5D=subscriptions&fields%5Bprofile%5D=id,email&page%5Bsize%5D=100'    
 * --header 'Authorization: Klaviyo-API-Key ***************'       
 * --header 'accept: application/vnd.api+json'       
 * --header 'revision: 2025-01-15'
 *
 * 
 * Note that %5B is "[" and %5D is "]" in the curl request parameters. 
 */

 function bulk_download_profiles_for_klaviyo( $debug = false ) {
  $klaviyo_sync = new Klaviyo_Profile_Rewards_Sync();

  if (!$klaviyo_sync->is_ok()) {
    return;
  }

  global $wpdb;
  $table_name = $wpdb->prefix . 'users_klaviyo_data';

  // TODO
  // not a valid filter, need to manually filter users later
  // $profile_filter = 'equals(subscriptions.email.marketing.can_receive_email_marketing,true)';

  // Get the last processed page cursor from options
  $page_cursor = get_option( 'klaviyo_profiles_page_cursor', null );

  // Klaviyo API allows to download up to 100 profiles per request
  $page_size = 100; 

  try {
    
    $klaviyo_sync->log('Started processing' . $page_size . ' profiles from Klaviyo at ' . time() );
    
    // Get profiles from Klaviyo API
    $response = $klaviyo_sync->klaviyo->Profiles->getProfiles(
      array( 'subscriptions' ), // additional_fields_profile
      array( 'id', 'email' ), // fields to be gathered from profiles
      null, // $profile_filter
      $page_cursor,
      $page_size
    );

    if (!is_array($response) || !isset($response['data'])) {
      $klaviyo_sync->log('Invalid response from Klaviyo API');
      return;
    }

    $profiles = $response['data'];

    if ( isset( $response['links']['next'] ) ) {
      $next_page_cursor = extract_page_cursor_parameter($response['links']['next']);
    }

    if ( $debug ) {
      echo('<pre>' . print_r( $profiles , 1 ) . '</pre>' );
      die();
    }

    
    // Process each profile
    foreach ($profiles as $profile) {

      if ( isset($profile['id']) && isset($profile['attributes']['email']) ) {
        
        $klaviyo_id = $profile['id'];
        $email = $profile['attributes']['email'];

        $user_status = 'not_subscribed';

        $can_receive_email_marketing = $profile['attributes']['subscriptions']['email']['marketing'] ?? false;

        if ( $can_receive_email_marketing  ) {
          $user_status = 'subscribed';
        }

        // Find WordPress user by email
        $user = get_user_by( 'email', $email );
        if ( $user ) {

          // Insert or update the record in our custom table
          $wpdb->replace(
            $table_name,
            [
              'user_id'    => $user->ID,
              'status'     => $user_status,
              'klaviyo_id' => $klaviyo_id
            ],
            ['%d', '%s', '%s']
          );
        }
      }
    }

    // Save the next page cursor for the next run
    if ( $next_page_cursor ) {
      update_option( 'klaviyo_profiles_page_cursor', $next_page_cursor );
    } else {
      // If no more pages, reset the cursor to start over
      delete_option( 'klaviyo_profiles_page_cursor' );
    }

    $klaviyo_sync->log('Successfully processed ' . count($profiles) . ' profiles from Klaviyo, ' . $next_page_cursor);

  } catch (Exception $e) {
    $klaviyo_sync->log('Error downloading profiles from Klaviyo: ' . $e->getMessage());
  }
}

function extract_page_cursor_parameter( $cursor_link ) {

  $result = false;

  $query = parse_url($cursor_link, PHP_URL_QUERY);


  foreach ( explode('&', $query) as $chunk ) {
    $param = explode( "=", $chunk );
    if ( $param ) {
      $param_name = urldecode($param[0]);
      $param_value = urldecode($param[1]);
      
      if ( $param_name == 'page[cursor]') {
        $result = $param_value;
      }
      
    }
  }

  return $result;
}


function set_up_klaviyo_sync() {
  if ( function_exists('as_schedule_recurring_action') ) {
    // Schedule new portion of users to be processed every 10 minutes 
    if ( ! as_next_scheduled_action( 'bulk_download_profiles_for_klaviyo' ) ) {
      as_schedule_recurring_action( time(), 300, 'bulk_download_profiles_for_klaviyo' );
    }
  }
}

if ( isset( $_GET['test_klaviyo_downloads'] ) )  {
  bulk_download_profiles_for_klaviyo( $_GET['test_klaviyo_downloads'] );
  die();
}

if ( isset( $_GET['test_klaviyo_sync'] ) )  {
  bulk_sync_profiles_for_klaviyo( $_GET['test_klaviyo_sync'] );
  die();
}

if ( isset( $_GET['test_klaviyo_sync_again'] ) )  {

  $debug =  $_GET['test_klaviyo_sync_again'];
  bulk_sync_profiles_for_klaviyo_again( $debug );
  die();
}




add_action( 'bulk_download_profiles_for_klaviyo', 'bulk_download_profiles_for_klaviyo', 10, 0 );

// Add the action hook for processing user profiles
add_action( 'process_user_profile_for_klaviyo', 'sync_user_reward_points_with_klaviyo', 10, 1 );

add_action( 'init', 'set_up_klaviyo_sync', 10, 0 );
