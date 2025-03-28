<?php
/**
 * Plugin Name: Klaviyo - Sync user reward points
 * Plugin URI:
 * Description: Updates user's rewards points via Klaviyo API.
 * Author: Artem
 * Version: 0.1
 * Author URI:
 */

require 'vendor/autoload.php';
require 'inc.php';

include_once(ABSPATH . 'wp-includes/pluggable.php');


function test_klaviyo() {
  if ( isset($_GET['test_klaviyo']) ) {

    $user_id = $_GET['test_klaviyo']; // 124663; 
    sync_user_reward_points_with_klaviyo( $user_id );
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
        }
      }
    }
    
    // TODO send points to Klaviyo
    $current_points = get_user_meta($user_id, '_reward_points', true);

  }
}


// Schedule actions for each user profile
function schedule_user_profile_actions() {

  $klaviyo_sync = new Klaviyo_Profile_Rewards_Sync();
  
  $user_ids = $klaviyo_sync->get_users_without_klaviyo_id();
  foreach ( $user_ids as $user_id ) {
      // Schedule a single action for each user profile
      as_schedule_single_action(time(), 'process_user_profile_for_klaviyo', array( 'user_id' => $user_id ) );
  }
}

// Hook the scheduling function to an appropriate action
add_action( 'init', 'schedule_user_profile_actions' );

// Add the action hook for processing user profiles
add_action( 'process_user_profile_for_klaviyo', 'sync_user_reward_points_with_klaviyo', 10, 1 );

add_action( 'init', 'test_klaviyo' );