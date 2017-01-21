<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


add_action( 'add_meta_boxes', 'webinarjam_add_order_metaboxes' );

function webinarjam_add_order_metaboxes()
{
        if( __webinarjam_order_has_webinars() ){

            add_meta_box(
                'woocommerce-order-webinarjam',
                __( 'WebinarJam registration results' ),
                'webinarjam_order_metabox_content',
                'shop_order',
                'side',
                'default'
            );
        }
}

function webinarjam_order_metabox_content()
{
    $reg_result=__webinarjam_get_webinar_registration_result_from_order();
    echo '<style> .wbj_wrapper{position: relative;} .wbj_row{display: block;}  .wbj_row label{display: block; font-weight: bolder;} .wbj_row input{width: 100%;}  </style>';
    echo '<div class="wbj_wrapper">';
    foreach($reg_result as $name=>$value){
     echo '
     <div class="wbj_row">
        <label for="reg_result_'.$name.'">'.$name.'</label>
        <input id="reg_result_'.$name.'" type="text"  value="'.$value.'">
     </div>';
    }
    echo '</div>';
}

