<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}


add_action('add_meta_boxes', 'webinarjam_add_order_metaboxes');

function webinarjam_add_order_metaboxes()
{
    if (webinarjam_order_has_webinars()) {

        add_meta_box(
            'woocommerce-order-webinarjam',
            __('WebinarJam registration results'),
            'webinarjam_order_metabox_content',
            'shop_order',
            'side',
            'default'
        );
    }
}

function webinarjam_order_metabox_content()
{
    $reg_results = webinarjam_get_webinar_registration_results_from_order();
    if ( is_array($reg_results) ) {
        echo '<style> 
            .wbj_wrapper{ position: relative; margin: 0 -12px; counter-reset: webinarjamcount;}  
            .wbj_webinar_item{position: relative; padding: 12px; } 
            .wbj_webinar_item:before{
                counter-increment: webinarjamcount;
                content: counter(webinarjamcount) " Webinar in order"; 
                display: block; 
                text-align: center; 
                font-weight: 700; 
                padding-bottom: 1em;
            }
            .wbj_webinar_item:first-child:last-child:before{
                display: none;
            } 
            .wbj_webinar_item + .wbj_webinar_item{ padding-top: 3em;}  
            .wbj_webinar_item:nth-child(odd){background: white;} 
            .wbj_webinar_item:nth-child(even){background: rgba(0, 115, 170, 0.1);} 
            .wbj_row{display: block;}  
            .wbj_row label{display: block; font-weight: bolder;} 
            .wbj_row input{width: 100%;}  
            </style>';

        echo '<div class="wbj_wrapper">';
        foreach ($reg_results as $reg_result) {
            echo '<div class="wbj_webinar_item">';
            foreach ($reg_result as $name => $value) {
                echo '
     <div class="wbj_row">
        <label for="reg_result_' . $name . '">' . $name . '</label>
        <input id="reg_result_' . $name . '" type="text"  value="' . $value . '">
     </div>';
            }
            echo '</div>';
        }
        echo '</div>';
    }
}

