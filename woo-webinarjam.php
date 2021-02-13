<?php
/**
 * Plugin Name: WooCommerce WebinarJam
 * Description: Sell access to your webinars with WooCommerce.
 * Version: 0.7.2
 * Author: Mikhail Durnev
 * Author URI: https://mikhailroot.ru
 * Copyright: (c) 2019 Mikhail Durnev (email : mikhailD.101@gmail.com; skype: mikhail.root)
 *
 * @package woo-webinarjam;
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// include http api methods.
require_once __DIR__ . '/includes/wp-webinarjam-api.php';

/**
 * Links Plugin Settings page.
 */
function webinarjam_admin_settings() {

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_attr__( 'You do not have sufficient permissions to access this page.' ) );
	}
	require_once plugin_dir_path( __FILE__ ) . 'includes/settings.php';
}

/**
 * Register settings admin page.
 */
function webinarjam_admin_page() {
	add_options_page( 'WebinarJam Settings', 'WebinarJam Settings', 'manage_options', 'webinarjam-admin-settings', 'webinarjam_admin_settings' );
}
add_action( 'admin_menu', 'webinarjam_admin_page' );

/**
 * Register the custom product type after init
 */
function register_webinarjam_product_type() {

    if ( class_exists('WC_Product') ) {
	    require_once plugin_dir_path( __FILE__ ) . 'includes/wc-product-webinarjam.php';

	    // functions to work with orders, extract data from webinars etc.

	    require_once plugin_dir_path( __FILE__ ) . 'includes/utilities.php';

	    // add metabox to Order's page.
	    require_once plugin_dir_path( __FILE__ ) . 'includes/order-webinarjam-metabox.php';

	    require_once plugin_dir_path( __FILE__ ) . 'includes/webinarjam-shortcodes.php';
    }
}
add_action( 'init', 'register_webinarjam_product_type' );


function add_webinarjam_product( $types = array() ) {

	// Key should be exactly the same as in the class product_type parameter.
	$types['webinarjam'] = __( 'WebinarJam' );

	return $types;

}
add_filter( 'product_type_selector', 'add_webinarjam_product' );

/**
 * Adjust product's tab
 *
 * @param array $tabs Array of tabs for product type.
 * @return array
 */
function webinarjam_product_tabs( $tabs ) {
	// first hide unneeded ones.
	// Other default values for 'attribute' are; general, inventory, shipping, linked_product, variations, advanced.
	$tabs['attribute']['class'][]  = 'hide_if_webinarjam ';
	$tabs['inventory']['class'][]  = 'hide_if_webinarjam ';
	$tabs['shipping']['class'][]   = 'hide_if_webinarjam ';
	$tabs['variations']['class'][] = 'hide_if_webinarjam ';
	$tabs['advanced']['class'][]   = 'hide_if_webinarjam ';

	// create our own tab.
	$mytab = array(
		'webinarjam' => array(
			'label'  => __( 'Select Webinar', 'woocommerce' ),
			'target' => 'webinarjam_options',
			'class'  => array( 'show_if_webinarjam', 'hide_if_simple', 'hide_if_variable', 'hide_if_grouped', 'hide_if_external' ),
		),
	);

	return $mytab + $tabs;
}
add_filter( 'woocommerce_product_data_tabs', 'webinarjam_product_tabs' );

/**
 * Contents of webinarjam select webinar product tab.
 */
function webinarjam_select_webinar_product_tab_content() {
	global $post, $thepostid;

	// lets get webinarlist and select one in dropdown! // simplest) .
	$webinarjam_api_key = get_option( 'webinarjam_api_key', '' );
	$webinarlist        = webinarjam_list_webinars( $webinarjam_api_key );
	$webinars           = array();
	if ( is_array( $webinarlist ) && ! is_wp_error( $webinarlist ) ) {
		foreach ( $webinarlist as $webinar ) {
			$webinars[ $webinar->webinar_id ] = $webinar->name;
		}
	}

	?>
	<div id='webinarjam_options' class='panel woocommerce_options_panel'>
	<div class='options_group show_if_webinarjam'>
	<?php
	if ( empty( $webinarjam_api_key ) ) {
		?>
			<h2>You need to specify Webinar Jam API key first</h2>
				<p><a href="/wp-admin/options-general.php?page=webinarjam-admin-settings">click here to set API key</a> then go to webinarjam and create Webinars to sell</p>
				<p>then select here in dropdown list needed webinar to sell.</p>
		<?php
	} elseif ( is_wp_error( $webinarlist ) ) {
		?>
				<h2>Error loading webinars</h2>
				<p>Possible wrong API key</p>
				<p><p><a href="/wp-admin/options-general.php?page=webinarjam-admin-settings">click here to set API key</a></p>
			<?php
	} elseif ( count( $webinars ) < 1 ) {
		?>
					<h2>No webinars loaded from Webinar Jam</h2>
					<p>Create new webinar on webinarjam admin panel and try again.</p>
				<?php
	} else {
		woocommerce_wp_select(
			array(
				'id'          => 'webinarjam_id',
				'name'        => 'webinarjam_id',
				'label'       => __( 'Webinar to sell' ),
				'desc_tip'    => 'true',
				'description' => __( 'Select Webinar to sell, they are sorted by creation date, latest first', 'woocommerce' ),
				'options'     => $webinars,
			)
		);
	}

	?>
	</div>
	</div>
	<?php

}
add_action( 'woocommerce_product_data_panels', 'webinarjam_select_webinar_product_tab_content' );

/**
 * Save the custom fields for product.
 *
 * @param int $post_id  Product's post_id we work with.
 */
function webinarjam_option_field( $post_id ) {

	if ( isset( $_POST['webinarjam_id'] ) ) {
		update_post_meta( $post_id, 'webinarjam_id', sanitize_text_field( $_POST['webinarjam_id'] ) );
	}
	update_post_meta( $post_id, '_virtual', 'yes' );
	update_post_meta( $post_id, '_sold_individually', 'yes' );
	update_post_meta( $post_id, '_manage_stock', 'no' );
	update_post_meta( $post_id, '_backorders', 'no' );
}
add_action( 'woocommerce_process_product_meta_webinarjam', 'webinarjam_option_field' );

/**
 * Sets our custom switch class for product type in admin UI.
 */
function webinarjam_product_type_selector_js() {
	if ( 'product' !== get_post_type() ) :
		return;
	endif;
	?>
	<script type='text/javascript' id="webinarjam-product-pricing-tab-enabler">
		jQuery( '.options_group.pricing' ).addClass( 'show_if_webinarjam' );
	</script>
	<?php
}

add_action( 'admin_footer', 'webinarjam_product_type_selector_js' );

/**
 * Autocomplete webinarjam orders as they are virtual products.
 *
 * @param string $order_status Current Order status.
 * @param int    $order_id     Order id we process.
 * @return string
 */
function webinarjam_autocomplete_orders( $order_status, $order_id ) {

	$order = new WC_Order( $order_id );
	if ( 'processing' === $order_status && ( 'on-hold' === $order->status || 'pending' === $order->status || 'failed' === $order->status ) ) {
		$virtual_order = null;
		if ( count( $order->get_items() ) > 0 ) {
			foreach ( $order->get_items() as $item ) {
				if ( 'line_item' === $item['type'] ) {
					$_product = $item->get_product();// $order->get_product_from_item($item);
					if ( 'webinarjam' === $_product->product_type ) {
						// send email here:) .
						// email is sent by woocommerce in other hook.
						return 'completed';
					}
				}
			}
		}
	}
	return $order_status;
}

add_filter( 'woocommerce_payment_complete_order_status', 'webinarjam_autocomplete_orders', 5, 2 );

/**
 * Sends webinar link for paid order to customer.
 *
 * @param int $order_id  Order id to process.
 */
function webinarjam_send_webinar_link_to_paid_client( $order_id ) {

	$user_email      = '';
	$user_first_name = '';
	$user_last_name  = '';

	$order = new WC_Order( $order_id );
	$user  = $order->get_user();

	if ( $user instanceof WP_User && $user->ID > 0 ) {
		$user_email      = $user->user_email;
		$user_first_name = ! empty( $user->user_firstname ) ? $user->user_firstname : $user->display_name;
		$user_last_name  = ! empty( $user->user_lastname ) ? $user->last_name : '';
	} else {
		// get user data from order. Idea from Sam Krieg to support guests orders.
		$user_email      = $order->get_billing_email();
		$user_first_name = $order->get_billing_first_name();
		$user_last_name  = $order->get_billing_last_name();
	}

	$registration_results = array(); // array to store webinar registration results - in case we have multiple webinars bought in one order.

	$old_registration_results = json_decode( get_post_meta( $order->get_id(), 'webinarjam_registration_result', true ) );

	if ( ! empty( $old_registration_results ) ) {
		return; // do nothing as otherwise we'll reregister user again.
	}

	if ( count( $order->get_items() ) > 0 ) {
		foreach ( $order->get_items() as $item ) {
			if ( ! is_object( $item ) ) {
				continue;
			}
			if ( $item->is_type( 'line_item' ) ) {
				$_product = $item->get_product();

				if ( 'webinarjam' === $_product->product_type ) {
					// lets register user for webinar and send him access link.
					$admin_email        = get_option( 'admin_email', '' );
					$webinarjam_api_key = get_option( 'webinarjam_api_key', '' );
					$webinarjam_id      = get_post_meta( $_product->get_id(), 'webinarjam_id', true );

					// get whole webinar object to access it's friendly name to show.
					$webinar_obj  = webinarjam_get_webinar_data( $webinarjam_api_key, $webinarjam_id );
					$webinar_name = isset( $webinar_obj->name ) ? $webinar_obj->name : $_product->get_title();
					// lets extract first schedule id - so register to webinar start working.
					$schedule = isset( $webinar_obj->schedules[0]->schedule ) ? $webinar_obj->schedules[0]->schedule : 0;
					// REGISTER user to webinar!
					$webinar_registration = webinarjam_register_user_to_webinar( $webinarjam_api_key, $webinarjam_id, $user_email, $user_first_name, $user_last_name, $schedule );

					if ( is_wp_error( $webinar_registration ) ) {
						// email to admin registration error!
						$error_email_template = file_get_contents( plugin_dir_path( __FILE__ ) . 'includes/error_email_template.php' );
						$user_name            = $user_first_name . ' ' . $user_last_name;
						$error                = new WP_Error();

						if ( is_wp_error( $webinar_obj ) ) {
							 $error->add( $webinar_obj->get_error_code(), $webinar_obj->get_error_message() );
						}

						$error->add( $webinar_registration->get_error_code(), $webinar_registration->get_error_message() );

						$error_messages = '<ul>';
						foreach ( $error->get_error_messages() as $message ) {
							$error_messages .= '<li>' . $message . '</li>';
						}
						$error_messages .= '</ul>';
						$error_data      = array(
							'webinar_name' => $webinar_name,
							'user_email'   => $user_email,
							'user_name'    => $user_name,
							'order_id'     => $order->get_id(),
							'product_id'   => $_product->id,
							'product_name' => $_product->get_title(),
							'date'         => gmdate( 'Y-m-d H:i:s' ),
							'errors'       => $error_messages,
						);
						foreach ( $error_data as $item => $value ) {
							$error_email_template = str_replace( '{' . $item . '}', $value, $error_email_template );
						}
						wc_mail( $admin_email, 'Webinar registration Error!', $error_email_template );

					} else {
						// lets store successful registration to webinar to Order post meta.
						$webinar_registration->{'webinar_name'} = $webinar_name; // extend stored data with webinar_name.
						$registration_results[]                 = $webinar_registration; // push it to array of registration results
						// send email to client and admin notification here:) .
						$default_email_template       = file_get_contents( plugin_dir_path( __FILE__ ) . 'includes/email-templates/default.php' );
						$default_admin_email_template = file_get_contents( plugin_dir_path( __FILE__ ) . 'includes/email-templates/default-admin.php' );

						$subject          = get_option( 'webinarjam_paid_successfully_email_subject', 'Successfull webinar registration' );
						$email_body       = get_option( 'webinarjam_paid_successfully_email_template', $default_email_template );
						$admin_email_body = get_option( 'webinarjam_paid_successfully_admin_email_template', $default_admin_email_template );

						$email_body       = str_replace( '{webinar_name}', $webinar_name, $email_body );
						$subject          = str_replace( '{webinar_name}', $webinar_name, $subject );
						$admin_email_body = str_replace( '{webinar_name}', $webinar_name, $admin_email_body );

						$substitutes = (array) $webinar_registration;

						foreach ( $substitutes as $item => $value ) {
							$email_body       = str_replace( '{' . $item . '}', $value, $email_body );
							$subject          = str_replace( '{' . $item . '}', $value, $subject );
							$admin_email_body = str_replace( '{' . $item . '}', $value, $admin_email_body );
						}

						if ( 'on' === get_option( 'webinarjam_notify_client_on_successfull_registration', false ) ) {
							// lets send prepared email to user:) .
							wc_mail( $user_email, $subject, $email_body );
						}

						if ( 'on' === get_option( 'webinarjam_notify_admin_on_successfull_registration', false ) ) {
							wc_mail( $admin_email, 'New Webinar applicant!', $admin_email_body );
						}
					}
				}
			}
		}
	}

	if ( count( $registration_results ) ) {
		update_post_meta( $order->get_id(), 'webinarjam_registration_result', wp_json_encode( $registration_results ) );
	}
}
add_action( 'woocommerce_order_status_completed', 'webinarjam_send_webinar_link_to_paid_client', 50, 1 );

/**
 * Crazy hook to show button to buy item!!!
 */
function woocommerce_webinarjam_add_to_cart() {
	wc_get_template( 'single-product/add-to-cart/simple.php' );
}
add_action( 'woocommerce_webinarjam_add_to_cart', 'woocommerce_webinarjam_add_to_cart' );

