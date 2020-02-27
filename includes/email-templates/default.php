<?php
/**
 * Default email template for success as html.
 *
 * @package woo-webinarjam;
 */

defined( 'ABSPATH' ) || exit;
?>
<h2>Thank you!</h2>
<table>
	<tr><td>
			Now you can take part in {webinar_name} webinar.
		</td>
	</tr>
	<tr>
		<td>
			It is appointed to {date} in {timezone} timezone
		</td>
	</tr>
	<tr>
		<td>
			To participate click on  <a href="{live_room_url}">{live_room_url}</a>
		</td>
	</tr>
	<tr>
		<td>
			Webinar replay available on  <a href="{replay_room_url}">{replay_room_url}</a>
		</td>
	</tr>
	<tr>
		<td>
			<a href="{thank_you_url}">Thank you!</a>
		</td>
	</tr>
</table>
