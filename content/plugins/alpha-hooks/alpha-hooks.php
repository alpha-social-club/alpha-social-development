<?php
/*
Plugin Name: Alpha Hooks
Plugin URI:
Description: All filter and action hooks used on the site, gathered together as one happy family.
Version: 1.0.0
Author: Andrew Voyticky
Author URI:
*/

// Gravity forms custom validation filter hook.
add_filter( 'gform_validation_4', function($validation_result){

    $form = $validation_result["form"];

    foreach ( $form['fields'] as &$field ) {

        switch($field['id']) {

            // Username validation rules
            case 3:
                $is_username_validation_error = false;

                if ( $username = rgpost( "input_3" ) ) {

                    if (! preg_match('/^[a-z0-9\'_.-]+$/i', $username)) {

                        $is_username_validation_error = true;
                        $field['validation_message']  = "You may use only the following characters: letters (a-z), numbers (0-9), dashes (-), underscores (_), apostrophes ('), and periods (.). Try again please.";
                    }

                    // User exists? Show validation error
                    if ( username_exists($username) ) {

                        $is_username_validation_error = true;
                        $field['validation_message']  = "This nickname is already taken. Please choose another one.";
                    }


                }

                // Mark form validation as failed
                if ( $is_username_validation_error ) {
                    $validation_result['is_valid'] = false;
                    $field['failed_validation']    = true;
                }
            break;
                
            case 4:
                $password          = rgpost( "input_4" );
                $confirm_password  = rgpost( "input_4_2" );
                $password_strength = rgpost( "input_4_strength" );

                if ( $password != $confirm_password ) {
                    $field['validation_message']   = "The 2 passwords do not match. Please try again.";
                    $validation_result['is_valid'] = false;
                    $field['failed_validation']    = true;
                }

                if ( !$field['validation_message'] && $password_strength != "strong" ) {
                    $field['validation_message']   = "Your password must be strong. It's for your own protection.";
                    $validation_result['is_valid'] = false;
                    $field['failed_validation']    = true;
                }
            break;
        }
    }

    // Assign custom validation results back 
    $validation_result['form'] = $form;

    return $validation_result;
});

// Gravity forms custom validation filter hook.
add_filter( 'gform_validation_1', 'confirm_invitation_code');

/*
 * Read the user inputted invitation code and verify that it matches one of
 * the valid codes inside 'includes/invitation-codes.php'
 */
function confirm_invitation_code( $validation_result, $invitation_code ) {

    $form = $validation_result["form"];

    $current_page = rgpost( 'gform_source_page_number_' . $form['id'] ) ? 
            rgpost( 'gform_source_page_number_' . $form['id'] ) : 1;

    foreach ( $form['fields'] as &$field ) {

        if ( strpos( $field['cssClass'], 'invitation-code' ) === false ) {
            continue;
        }

        $field_page = $field['pageNumber'];

        if( $field_page != $current_page ) {
            continue;
        }

        $field_value = rgpost( "input_{$field['id']}" );

        $is_valid = is_the_code_correct( $field_value, $invitation_code );

        if ( $is_valid ) {
            continue;
        }

        $validation_result['is_valid'] = false;

        $field['failed_validation'] = true;
        $field['validation_message'] = 'The invitation code is not valid.';
    }

    $validation_result['form'] = $form;

    return $validation_result;
}

/*
 * This functions uses strcasecmp for case-insensitive comparison of the user
 * inputted string with the existing valid codes.
 */
function is_the_code_correct( $field_value, $invitation_code ) {

    // Get the current, valid invitation code from the file below.
    require 'includes/invitation-codes.php';
    
    $code_confirmed = false;
    
    // Loop through the array of the codes to find if any one is matching.
    foreach ( $invitation_code as $inviter => $code ) {

        // strcasecmp returns zero if two strings are case-insensitive equal.
        if ( strcasecmp( $field_value, $code ) == 0 ) {
            $code_confirmed = true;
            break;
        }
    }

    return $code_confirmed;
}