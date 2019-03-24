<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
$default_email_template=file_get_contents( plugin_dir_path(__FILE__). 'default_email_template.php');
$default_admin_email_template=file_get_contents( plugin_dir_path(__FILE__). 'default_admin_email_template.php');
?>
<style>
    input[type=text] {width:100%;}
    textarea{width:100%; min-height: 150px}
</style>
<div class="wrap">
    <h2>Webinar Jam sell webinars with woocommerse settings</h2>
    <form method="post" action="options.php">
        <?php wp_nonce_field('update-options'); ?>

        <table class="form-table" style="width:100%">
            <tr valign="top">
                <th scope="row">WebinarJam API key</th>
                <td><input type="text" name="webinarjam_api_key" value="<?php echo get_option('webinarjam_api_key',''); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row">Notify Client via email on successfull webinar  user registration?</th>
                <td><input type="checkbox" name="webinarjam_notify_client_on_successfull_registration" <?php echo get_option('webinarjam_notify_client_on_successfull_registration',false)==='on'?'checked':''; ?> /></td>
            </tr>
            <tr valign="top">
                <th scope="row">Email subject for successfully paid client notification </th>
                <td><input type="text" name="webinarjam_paid_successfully_email_subject" value="<?php echo get_option('webinarjam_paid_successfully_email_subject','Successfull webinar registration'); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row">Email template for client purchased webinar with links to participate</th>
                <td>
                    <?php  wp_editor(get_option('webinarjam_paid_successfully_email_template',$default_email_template),'webinarjam_paid_successfully_email_template',array('textarea_name'=>'webinarjam_paid_successfully_email_template')); ?>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Notify admin via email on successfull webinar  user registration?</th>
                <td><input type="checkbox" name="webinarjam_notify_admin_on_successfull_registration" <?php echo get_option('webinarjam_notify_admin_on_successfull_registration',false)==='on'?'checked':''; ?> /></td>
            </tr>
            <tr valign="top">
                <th scope="row">Admin notification Email template</th>
                <td>
                    <?php  wp_editor(get_option('webinarjam_paid_successfully_admin_email_template',$default_admin_email_template),'webinarjam_paid_successfully_admin_email_template',array('textarea_name'=>'webinarjam_paid_successfully_admin_email_template')); ?>
                </td>
            </tr>
        </table>
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options"  value="webinarjam_api_key,webinarjam_paid_successfully_email_template,webinarjam_paid_successfully_email_subject,webinarjam_notify_admin_on_successfull_registration,webinarjam_paid_successfully_admin_email_template" />
        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
        </p>

    </form>
</div>