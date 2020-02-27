<?php
/**
 * WebinarJam API wrappers.
 *
 * @package woo-webinarjam;
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get webinars data as array.
 *
 * @param string $api_key  API Key used for connection.
 * @return array|WP_Error
 */
function webinarjam_list_webinars( $api_key ) {

	$response = wp_remote_post(
		'https://webinarjam.genndi.com/api/webinars',
		array(
			'method' => 'POST',
			'body'   => array( 'api_key' => $api_key ),
		)
	);

	if ( is_wp_error( $response ) ) {
		return $response; // return wp_error as is to debug.
	} else {
		$body = $response['body'];
		if ( 'Unauthorized' === $body ) {
			return new WP_Error( 'Unauthorized', 'Unauthorized while listing webinars' );
		} else {
			$result = json_decode( $body );
		}

		return isset( $result->webinars ) ? $result->webinars : new WP_Error( 'wrong_response', 'wrong response from server while listing webinars, ' . isset( $result->status ) ? $result->message : ' ' );
	}
}

/**
 * Get single webinar data by webinar_id.
 *
 * @param string $api_key     Api key string.
 * @param string $webinar_id  Webinar id to get data for.
 * @return array|WP_Error
 */
function webinarjam_get_webinar_data( $api_key, $webinar_id ) {

	$response = wp_remote_post(
		'https://webinarjam.genndi.com/api/webinar',
		array(
			'method' => 'POST',
			'body'   => array(
				'api_key'    => $api_key,
				'webinar_id' => $webinar_id,
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		return $response;
	} else {

		$body = $response['body'];
		if ( 'Unauthorized' === $body ) {
			return new WP_Error( 'Unauthorized', 'Unauthorized while gettings single webinar details' );
		} else {
			$result = json_decode( $body );
		}

		return isset( $result->webinar ) ? $result->webinar : new WP_Error( 'wrong_response', 'wrong response from server while getting single webinar details, ' . isset( $result->status ) ? $result->message : ' ' );
	}
}

/**
 * Registers WP_User to webinar, it utilized user_email and other user's data from WP_User object.
 *
 * @param string      $api_key      Api key to connect to.
 * @param string      $webinar_id   WebinarId to register user to.
 * @param WP_User|int $user         User object or user id to register to webinar.
 * @param int         $schedule     Schedule param for webinar (wasn't used yet by webinarjam docs).
 * @return array|WP_Error
 */
function webinarjam_register_user_to_webinar( $api_key, $webinar_id, $user, $schedule = 0 ) {

	$name  = '';
	$email = '';
	if ( is_numeric( $user ) ) {
		$user = get_userdata( $user );
	}
	if ( $user instanceof WP_User && $user->ID > 0 ) {
		$email      = $user->user_email;
		$first_name = ! empty( $user->user_firstname ) ? $user->user_firstname : $user->display_name;
		$last_name  = ! empty( $user->user_lastname ) ? $user->last_name : '';

		$response = wp_remote_post(
			'https://webinarjam.genndi.com/api/register',
			array(
				'method' => 'POST',
				'body'   => array(
					'api_key'    => $api_key,
					'webinar_id' => $webinar_id,
					'first_name' => $first_name,
					'last_name'  => $last_name,
					'email'      => $email,
					'schedule'   => $schedule,
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response; // return WP_Error as is to debug.
		} else {
			$body = $response['body'];
			if ( 'Unauthorized' === $body ) {
				return new WP_Error( 'Unauthorized', 'Unauthorized while registering user to webinar' );
			} else {
				$result = json_decode( $body );
			}
			return isset( $result->user ) ? $result->user : new WP_Error( 'wrong_response', isset( $result->status ) ? $result->message : 'wrong response from server while registering user to webinar' );
		}
	}
	return new WP_Error( 'nouser', 'wrong user id or email supplied' ); // if no right user or user id supplied.
}
