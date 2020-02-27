<?php
/**
 * Admin email template as html.
 *
 * @package woo-webinarjam;
 */

defined( 'ABSPATH' ) || exit;
?>
<h2>New user paid and successfully registered to webinar {webinar_name}</h2>
<table border="1">
	<tr>
		<td>User email</td>
		<td>{email}</td>
	</tr>
	<tr>
		<td>User Name</td>
		<td>{name}</td>
	</tr>
	<tr>
		<td>Date</td>
		<td>{date}</td>
	</tr>
	<tr>
		<td>Timezone</td>
		<td>{timezone}</td>
	</tr>
	<tr>
		<td>User access link </td>
		<td><a href="{live_room_url}">{live_room_url}</a></td>
	</tr>
	<tr>
		<td>User Webinar replay link</td>
		<td><a href="{replay_room_url}">{replay_room_url}</a></td>
	</tr>
	<tr>
		<td>Than you link</td>
		<td><a href="{thank_you_url}">{thank_you_url}</a></td>
	</tr>
</table>
