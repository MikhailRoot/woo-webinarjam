<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Shortcode to echo webinar registration result data for particular order_id if none specified it gets last order with webinar for current user
 *
 * @param $atts  default param to echo default is live_room_url there are also:
 *
 *              webinar_id, user_id, name, email, schedule, date, timezone, live_room_url , replay_room_url, thank_you_url
 *
 *              all those are webinarjam's data for example user_id and webinar_id are id's in webinarjam system not your Wordpress|Woocommerce
 *
 *              You can also specify css classes passed in as class attribute as string
 *
 *              You can specify order_id too
 *
 *              Also it supports wrapping some other content for example image or text for link, to place inside of it.
 *
 * @param string $content
 * @return string
 */
function webinarjam_registration_result_link_and_content_shortcode($atts, $content=''){

    $defaults=array(
        'param'=>'live_room_url',
        'order_id'=>null,
        'class'=>'webinarjam_reg_result'
    );

    $atts=wp_parse_args($atts,$defaults);

    // add default wrapper class for shortcode
    $atts['class'] =  $atts['class']!=='webinarjam_reg_result' ?
            'webinarjam_reg_result '.$atts['param'].' '.$atts['class'] :
            $atts['param'].' '.$atts['class'];

    // if order_id =null lets get curren't users last order and echo it's last webinar reg data.
    if( is_null($atts['order_id']) ){
        $last_webinar_order_id=__webinarjam_get_current_user_last_order_id_with_webinarjam_webinar();

        if( is_null($last_webinar_order_id) ) return '';

        $atts['order_id']=$last_webinar_order_id;
    }

    $reg_result=__webinarjam_get_webinar_registration_result_from_order($atts['order_id']);

    $param_to_echo=$reg_result->{$atts['param']};

    $result='';

    if($param_to_echo){
        // url and span
        if(strpos($atts['param'],'_url')===false){

            $result= '<div class="'.$atts['class'].'"><span class="webinarjam_value">'.$param_to_echo.'</span>'.$content.'</div>';

        }else{

            $result= '<a class="'.$atts['class'].'" href="'.$param_to_echo.'" >';
            $result.= strlen($content)>0?$content:$param_to_echo;
            $result.='</a>';
        }
    }

    return $result;
}

add_shortcode('webinarjam','webinarjam_registration_result_link_and_content_shortcode');