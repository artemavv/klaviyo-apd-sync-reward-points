<?php
/**
 * Plugin Name: Klaviyo - Sync user reward points
 * Plugin URI:
 * Description: Updates user's rewards points via Klaviyo API.
 * Author: Artem
 * Version: 0.3
 * Author URI:
 */

require 'vendor/autoload.php';
require 'inc.php';

include_once( ABSPATH . 'wp-includes/pluggable.php' );


function test_klaviyo() {

  if ( isset($_GET['test_klaviyo232323']) ) {

    $klaviyo_sync = new Klaviyo_Profile_Rewards_Sync();

    if ( $klaviyo_sync->is_ok() ) {

      $users = $klaviyo_sync->get_users_to_update_bulk( 4 );

      foreach ( $users['user_data'] as $user ) {
        $user_ids[] = $user['ID'];
      }

      $klaviyo_sync->log( 'Sendind BULK profiles to update in Klaviyo, IDs: ' . $users['min_id'] . ' - ' . $users['max_id'] );

      if ( $klaviyo_sync->update_profiles_bulk( $users['user_data']) ) {
        $klaviyo_sync->mark_users_as_synced( $user_ids );   
      }

      die();
    }
    
  }
}

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


// Schedule actions for each user profile
function schedule_user_profiles_for_klaviyo_sync() {

  $klaviyo_sync = new Klaviyo_Profile_Rewards_Sync();

  $user_ids = $klaviyo_sync->get_users_without_klaviyo_id();
  foreach ( $user_ids as $user_id ) {
      // Schedule a single action for each user profile
      as_schedule_single_action( time() + 60, 'process_user_profile_for_klaviyo', array( 'user_id' => $user_id ) );
  }
}

if ( function_exists('as_schedule_recurring_action') ) {
  // Schedule new portion of users to be processed every 10 minutes 
  if ( ! as_next_scheduled_action( 'new_user_profiles_for_klaviyo_sync' ) ) {
    as_schedule_recurring_action( time(), 600, 'new_user_profiles_for_klaviyo_sync' );
  }
}

add_action( 'new_user_profiles_for_klaviyo_sync', 'schedule_user_profiles_for_klaviyo_sync', 10, 0 );

// Add the action hook for processing user profiles
add_action( 'process_user_profile_for_klaviyo', 'sync_user_reward_points_with_klaviyo', 10, 1 );

add_action( 'init', 'test_klaviyo' );