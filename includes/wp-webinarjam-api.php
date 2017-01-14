<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

function __webinarjam_list_webinars($api_key){
    $response=wp_remote_post('https://app.webinarjam.com/api/v2/webinars',
    array(
        'method'=>'POST',
        'body'=>array('api_key'=>$api_key)
    ));
    if ( is_wp_error( $response ) ) {
        return $response;// return wp_error as is to debug
    } else {
        $body = $response['body'];
        if('Unauthorized'===$body) return new WP_Error( 'Unauthorized','Unauthorized while listing webinars' );
        else $result= json_decode( $body );
        return isset($result->webinars)?$result->webinars:new WP_Error( 'wrong_response','wrong response from server while listing webinars, '.isset($result->status)?$result->message:' ' );
    }
}

function __webinarjam_get_webinar_data($api_key,$webinar_id){
    $response=wp_remote_post('https://app.webinarjam.com/api/v2/webinar',
        array(
            'method'=>'POST',
            'body'=>array('api_key'=>$api_key,'webinar_id'=>$webinar_id)
        ));
    if ( is_wp_error( $response ) ) {
        return $response;
    } else {
        $body = $response['body'];
        if('Unauthorized'===$body) return  new WP_Error( 'Unauthorized','Unauthorized while gettings single webinar details' );
        else $result= json_decode( $body );
        return isset($result->webinar)?$result->webinar:new WP_Error( 'wrong_response','wrong response from server while getting single webinar details, '.isset($result->status)?$result->message:' ' );
    }
}

function __webinarjam_register_user_to_webinar($api_key,$webinar_id,$user,$schedule=0){
    $name=''; $email='';
    if(is_numeric($user)){
        $user=get_userdata($user);
    }
    if($user instanceof WP_User && $user->ID>0){
        $email=$user->user_email;
        $name=(!empty($user->user_firstname))&& (!empty($user->user_lastname))?$user->user_firstname.' '.$user->user_lastname: $user->display_name;
        $response=wp_remote_post('https://app.webinarjam.com/api/v2/register',
            array(
                'method'=>'POST',
                'body'=>array(
                    'api_key'=>$api_key,
                    'webinar_id'=>$webinar_id,
                    'name'=>$name,
                    'email'=>$email,
                    'schedule'=>$schedule
                )
        ));
        if ( is_wp_error( $response ) ) {
            return $response; // return WP_Error as is to debug.
        } else {
            $body = $response['body'];
            if('Unauthorized'===$body) return new WP_Error( 'Unauthorized','Unauthorized while registering user to webinar');
            else $result = json_decode( $body );
            return isset($result->user)?$result->user:new WP_Error( 'wrong_response', isset($result->status)?$result->message: 'wrong response from server while registering user to webinar' );
        }
    }
    return new WP_Error( 'nouser','wrong user id or email supplied' ); // if no right user or user id supplied
}