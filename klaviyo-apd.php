<?php
/**
 * Plugin Name: Sync user reward points with Klaviyo 
 * Plugin URI:
 * Description: Updates user's rewards points via Klaviyo API.
 * Author: Artem
 * Version: 0.1
 * Author URI:
 */

require 'vendor/autoload.php';

use KlaviyoAPI\KlaviyoAPI;

// This is proof-of-concept code to request Klavio API, get a profile ID, and update that profile.

if ( false ) {
  
    // TODO get the actual API Key from site settings
  
    $api_key = ' ';


    $klaviyo = new KlaviyoAPI( $api_key );

    try {
        
        $response = $klaviyo->Profiles->getProfiles(
            null, // $additional_fields_profile
            null, // $fields_profile
            'equals(email,"some.email@test.com")'
        );
        
        // TODO extract and save Klaviyo profile in wordpress user profile


        $klaviyo_profile_id = '01GDDKASAP8TKDDA2GRZDSVP4H'; // test ID 
        
        $profile_partial_update_query = [
            "data" => [ 
                "type"         => "profile", 
                "id"           => $klaviyo_profile_id, 
                "attributes"   => [ 
                    "properties" => [ "reward_points" => "123" ]
                ]
            ]
        ];

        // TODO add response handling
        $update_response = $klaviyo->Profiles->updateProfile($id, $profile_partial_update_query);
    }
    catch ( Exception $e) {
    
        echo('<pre>' . print_r( $e, 1) . '</pre>' );
    }

    echo('RESPONSE <pre>' . print_r( $response, 1) . '</pre>' );

    die();
}