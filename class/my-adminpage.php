<?php
/** 
 *	管理画面: WP Admin page
 */

class olbAdminPage {
	/** 
	 *	プラグインメニュー: Plugin menu
	 */
	public static function addAdminMenu(){
		add_menu_page(
			__('Online Lesson Booking system', OLBsystem::TEXTDOMAIN),
			__('OLBsystem', OLBsystem::TEXTDOMAIN),
			8,
			'olb-settings',
			array('olbAdminPage', 'settingPage')
			);
		add_submenu_page(
			'olb-settings',
			__('OLBsystem', OLBsystem::TEXTDOMAIN).' '.__('General', OLBsystem::TEXTDOMAIN),
			__('General', OLBsystem::TEXTDOMAIN),
			8,
			'olb-settings',
			array('olbAdminPage', 'settingPage')
			);
		add_submenu_page(
			'olb-settings',
			__('OLBsystem', OLBsystem::TEXTDOMAIN).' '.__('Special Pages', OLBsystem::TEXTDOMAIN),
			__('Special Pages', OLBsystem::TEXTDOMAIN),
			8,
			'olb-specialpages',
			array('olbAdminPage', 'settingPage')
			);
		add_submenu_page(
			'olb-settings',
			__('OLBsystem', OLBsystem::TEXTDOMAIN).' '.__('Mail', OLBsystem::TEXTDOMAIN),
			__('Mail', OLBsystem::TEXTDOMAIN),
			8,
			'olb-mail',
			array('olbAdminPage', 'settingPage')
			);
		add_submenu_page(
			'olb-settings',
			__('OLBsystem', OLBsystem::TEXTDOMAIN).' '.__('Reset', OLBsystem::TEXTDOMAIN),
			__('Reset', OLBsystem::TEXTDOMAIN),
			8,
			'olb-reset',
			array('olbAdminPage', 'settingPage')
			);

	}
	public static function settingPage($args = null){
		extract(
			wp_parse_args(
				$args,
				array(
					'title' => __('Online Lesson Booking system settings', OLBsystem::TEXTDOMAIN),
					'options_key' => OLBsystem::TEXTDOMAIN,
					)
				)
			);

		$options_group = 'settings';
		if(isset($_SERVER['QUERY_STRING'])){
			parse_str($_SERVER['QUERY_STRING'], $qs);
			if(isset($qs['page'])){
				$options_group = substr($qs['page'], strlen('olb-'));
			}
		}
		$message = '';
		// Profile page customize
		if(!isset($_POST['olb_options']['profile_customize'])){
			$olb_options['settings']['profile_customize'] = 0;
		}
		// Reset
		if(isset($_POST['olb_reset'])) {
			$default_option = OLBsystem::setDefaultOptions();
			$olb_options = get_option($options_key);
			if(in_array('general', $_POST['olb_reset'])) {
				$olb_options['settings'] = $default_option['settings'];
			}
			if(in_array('specialpages', $_POST['olb_reset'])) {
				$olb_options['specialpages'] = $default_option['specialpages'];
			}
			if(in_array('mail', $_POST['olb_reset'])) {
				$olb_options['mail'] = $default_option['mail'];
			}
			update_option($options_key, $olb_options);
			$message = __('Settings are reset', OLBsystem::TEXTDOMAIN);
		}
		// Update
		else if(isset($_POST['olb_options'])) {
			$olb_options = get_option($options_key);
			$olb_options[$options_group] = $_POST['olb_options'];
			if(isset($_POST['olb_options']['preserve_past'])) {
				$olb_options[$options_group]['preserve_past'] = floor(abs($_POST['olb_options']['preserve_past']));
			}
			update_option($options_key, $olb_options);
			$message = __('Settings are updated', OLBsystem::TEXTDOMAIN);
		}
		if($message){
			echo '<div id="message" class="updated fade"><p>'.$message.'</p></div>';
		}


		$olb_options = get_option($options_key);
		$options = $olb_options[$options_group];

		switch($options_group) {
			case 'settings':
			?>
<div id="<?php echo $options_key; ?>" class="wrap">
<?php screen_icon('options-general'); ?>
<h2><?php echo esc_html($title); ?></h2>
<div class="metabox-holder">
<div id="post-body">
<div id="post-body-content">
<div class="postbox">
<h3><span><?php _e('General', OLBsystem::TEXTDOMAIN); ?></span></h3>
<div class="inside">
<form method="post" action="<?php echo $_SERVER['PHP_SELF'].'?page=olb-settings'; ?>"> 

<table class="form-table">
<tr valign="top"><td colspan="2"><strong><?php _e('Timetable settings', OLBsystem::TEXTDOMAIN); ?></strong></td></tr>

<tr valign="top">
<th scope="row"><?php _e('Start time', OLBsystem::TEXTDOMAIN); ?></th>
<td>
<input type="text" name="olb_options[starttime]" id="" value="<?php echo $options['starttime']; ?>" size="5" /> ex. 10:00
<p class="description"></p>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('End time', OLBsystem::TEXTDOMAIN); ?></th>
<td>
<input type="text" name="olb_options[endtime]" id="" value="<?php echo $options['endtime']; ?>" size="5" /> ex. 22:00
<p class="description"></p>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Interval', OLBsystem::TEXTDOMAIN); ?></th>
<td>
<input type="text" name="olb_options[interval]" id="" value="<?php echo $options['interval']; ?>" size="5" /> 
<?php _e('minutes', OLBsystem::TEXTDOMAIN); ?>
<p class="description"></p>
</td>
</tr>

<tr><td colspan="2"><hr /></td></tr>
<tr valign="top"><td colspan="2"><strong><?php _e('Reservation settings', OLBsystem::TEXTDOMAIN); ?></strong></td></tr>

<tr valign="top">
<th scope="row"><?php _e('Time to close a reservation request ', OLBsystem::TEXTDOMAIN); ?></th>
<td>
<input type="text" name="olb_options[reserve_deadline]" id="" value="<?php echo $options['reserve_deadline']; ?>" size="5" /> 
<?php _e('minutes before', OLBsystem::TEXTDOMAIN); ?>
<p class="description"></p>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Time to close a cancellation request ', OLBsystem::TEXTDOMAIN); ?></th>
<td>
<input type="text" name="olb_options[cancel_deadline]" id="" value="<?php echo $options['cancel_deadline']; ?>" size="5" /> 
<?php _e('minutes before', OLBsystem::TEXTDOMAIN); ?>
<p class="description"></p>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('The limit of the reservation per day', OLBsystem::TEXTDOMAIN); ?></th>
<td>
<input type="text" name="olb_options[limit_per_day]" id="" value="<?php echo $options['limit_per_day']; ?>" size="5" />
<p class="description"></p>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('The limit of the reservation which is not charged', OLBsystem::TEXTDOMAIN); ?></th>
<td>
<input type="text" name="olb_options[free]" id="" value="<?php echo $options['free']; ?>" size="5" />
<p class="description"></p>
</td>
</tr>

<tr><td colspan="2"><hr /></td></tr>
<tr valign="top"><td colspan="2"><strong><?php _e('CRON settings', OLBsystem::TEXTDOMAIN); ?></strong></td></tr>

<tr valign="top">
<th scope="row"><?php _e('The days which do not delete the past schedule', OLBsystem::TEXTDOMAIN); ?></th>
<td>
<input type="text" name="olb_options[preserve_past]" id="" value="<?php echo $options['preserve_past']; ?>" size="5" />
<?php _e('days', OLBsystem::TEXTDOMAIN); ?>
<p class="description"></p>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('CRON interval', OLBsystem::TEXTDOMAIN); ?></th>
<td>
<select name="olb_options[cron_interval]">
<option value="halfhour" <?php echo ($options['cron_interval']=='halfhour') ? 'selected':''; ?>><?php _e('every harf hour', OLBsystem::TEXTDOMAIN); ?></option>
<option value="hourly" <?php echo ($options['cron_interval']=='hourly') ? 'selected':''; ?>><?php _e('hourly', OLBsystem::TEXTDOMAIN); ?></option>
<option value="daily" <?php echo ($options['cron_interval']=='daily') ? 'selected':''; ?>><?php _e('daily', OLBsystem::TEXTDOMAIN); ?></option>
<option value="10sec" <?php echo ($options['cron_interval']=='10sec') ? 'selected':''; ?>><?php _e('debug(every 10 seconds)', OLBsystem::TEXTDOMAIN); ?></option>
</select>
<p class="description"></p>
</td>
</tr>

<tr><td colspan="2"><hr /></td></tr>
<tr valign="top"><td colspan="2"><strong><?php _e('View settings', OLBsystem::TEXTDOMAIN); ?></strong></td></tr>

<tr valign="top">
<th scope="row"><?php _e('Show teachers per page', OLBsystem::TEXTDOMAIN); ?></th>
<td>
<input type="text" name="olb_options[room_per_page]" id="" value="<?php echo $options['room_per_page']; ?>" size="5" />
<p class="description"></p>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Customize user profile page', OLBsystem::TEXTDOMAIN); ?></th>
<td>
<label>
<input type="checkbox" name="olb_options[profile_customize]" id="" value="1" <?php echo ($options['profile_customize']) ? 'checked="checked"' : ''; ?> />
<?php _e('Yes', OLBsystem::TEXTDOMAIN); ?></label>
<p class="description"></p>
</td>
</tr>

<tr><td colspan="2"><hr /></td></tr>
<tr valign="top">
<th scope="row">&nbsp;</th>
<td>
<input type="hidden" name="olb_options[daymax]" id="" value="<?php echo $options['daymax']; ?>" />
<input type="hidden" name="olb_options[term]" id="" value="<?php echo $options['term']; ?>" />
<input type="submit" name="save" class="button-primary" value="<?php _e('Update', OLBsystem::TEXTDOMAIN);?>" class="large-text code" /></td>
</tr>
</table>

</form>
</div>
</div>
</div>
</div>
</div>
</div>
			<?php

				break;

			case 'specialpages':
			?>
<div id="<?php echo $options_key; ?>" class="wrap">
<?php screen_icon('options-general'); ?>
<h2><?php echo esc_html($title); ?></h2>
<div class="metabox-holder">
<div id="post-body">
<div id="post-body-content">
<div class="postbox">
<h3><span><?php _e('Special Pages', OLBsystem::TEXTDOMAIN); ?></span></h3>
<div class="inside">
<form method="post" action="<?php echo $_SERVER['PHP_SELF'].'?page=olb-specialpages'; ?>"> 

<table class="form-table">
<tr valign="top"><td colspan="2"><strong><?php _e('Special page slug', OLBsystem::TEXTDOMAIN); ?></strong></td></tr>

<tr valign="top">
<th scope="row"><?php _e('Daily schedule page', OLBsystem::TEXTDOMAIN); ?></th>
<td>
<input type="text" name="olb_options[daily_schedule_page]" id="" value="<?php echo $options['daily_schedule_page']; ?>" />
<p class="description">
<?php printf(__('The page which inserted the short code %s', OLBsystem::TEXTDOMAIN), '"<span style="color:#093">[olb_daily_schedule]</span>"'); ?>
</p>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Reservation form page', OLBsystem::TEXTDOMAIN); ?></th>
<td>
<input type="text" name="olb_options[reserve_form_page]" id="" value="<?php echo $options['reserve_form_page']; ?>" />
<p class="description">
<?php printf(__('The page which inserted the short code %s', OLBsystem::TEXTDOMAIN), '"<span style="color:#093">[olb_reserve_form]</span>"'); ?>
</p>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Cancel(by teacher) form page', OLBsystem::TEXTDOMAIN); ?></th>
<td>
<input type="text" name="olb_options[cancel_form_page]" id="" value="<?php echo $options['cancel_form_page']; ?>" />
<p class="description">
<?php printf(__('The page which inserted the short code %s', OLBsystem::TEXTDOMAIN), '"<span style="color:#093">[olb_cancel_form]</span>"'); ?>
</p>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Absent(by teacher) form page', OLBsystem::TEXTDOMAIN); ?></th>
<td>
<input type="text" name="olb_options[report_form_page]" id="" value="<?php echo $options['report_form_page']; ?>" />
<p class="description">
<?php printf(__('The page which inserted the short code %s', OLBsystem::TEXTDOMAIN), '"<span style="color:#093">[olb_report_form]</span>"'); ?>
</p>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Edit schedule(by teacher) page', OLBsystem::TEXTDOMAIN); ?></th>
<td>
<input type="text" name="olb_options[edit_schedule_page]" id="" value="<?php echo $options['edit_schedule_page']; ?>" />
<p class="description">
<?php printf(__('The page which inserted the short code %s', OLBsystem::TEXTDOMAIN), '"<span style="color:#093">[olb_edit_schedule]</span>"'); ?>
</p>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Refer members info(by teacher) page', OLBsystem::TEXTDOMAIN); ?></th>
<td>
<input type="text" name="olb_options[members_info_page]" id="" value="<?php echo $options['members_info_page']; ?>" />
<p class="description">
<?php printf(__('The page which inserted the short code %s', OLBsystem::TEXTDOMAIN), '"<span style="color:#093">[olb_refer_members_info]</span>"'); ?>
</p>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Members my page', OLBsystem::TEXTDOMAIN); ?></th>
<td>
<input type="text" name="olb_options[member_page]" id="" value="<?php echo $options['member_page']; ?>" size="20" />
<p class="description"><?php _e('After login, member is redirected to this Page', OLBsystem::TEXTDOMAIN); ?></p>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Login page', OLBsystem::TEXTDOMAIN); ?></th>
<td>
<input type="text" name="olb_options[login_page]" id="" value="<?php echo $options['login_page']; ?>" size="20" />
<p class="description">
<?php _e('A standard login page will be used if a "login page" is not specified.', OLBsystem::TEXTDOMAIN); ?><br>
<?php _e('If a login page is made separately, specify the name of that page.', OLBsystem::TEXTDOMAIN); ?>
</p>
</td>
</tr>

<tr><td colspan="2"><hr /></td></tr>
<tr valign="top">
<th scope="row">&nbsp;</th>
<td>
<input type="submit" name="save" class="button-primary" value="<?php _e('Update', OLBsystem::TEXTDOMAIN);?>" class="large-text code" /></td>
</tr>
</table>

</form>
</div>
</div>
</div>
</div>
</div>
</div>
			<?php

				break;

			case 'mail':
			?>
<style>
input[type="text"], textarea { width: 90%;}
</style>
<div id="<?php echo $options_key; ?>" class="wrap">
<?php screen_icon('options-general'); ?>
<h2><?php echo esc_html($title); ?></h2>
<div class="metabox-holder">
<div id="post-body">
<div id="post-body-content">
<div class="postbox">
<h3><span><?php _e('Mail', OLBsystem::TEXTDOMAIN); ?></span></h3>
<div class="inside">
<form method="post" action="<?php echo $_SERVER['PHP_SELF'].'?page=olb-mail'; ?>"> 

<table class="form-table">

<tr valign="top"><td colspan="2"><strong><?php _e('Email address', OLBsystem::TEXTDOMAIN); ?></strong></td></tr>

<tr valign="top">
<th scope="row"><?php _e('From email', OLBsystem::TEXTDOMAIN); ?></th>
<td>
<input type="text" name="olb_options[from_email]" id="" value="<?php echo $options['from_email']; ?>" />
<p class="description"></p>
</td>
</tr>

<tr><td colspan="2"><hr /></td></tr>
<tr valign="top"><td colspan="2"><strong><?php _e('Signature', OLBsystem::TEXTDOMAIN); ?></strong></td></tr>

<tr valign="top">
<th scope="row"><?php _e('Signature', OLBsystem::TEXTDOMAIN); ?></th>
<td>
<textarea name="olb_options[signature]" id=""  rows="10" cols="50" ><?php echo stripslashes($options['signature']); ?></textarea>
<p class="description"></p>
</td>
</tr>

<tr><td colspan="2"><hr /></td></tr>
<tr valign="top"><td colspan="2"><strong><?php _e('Notice of received reservation', OLBsystem::TEXTDOMAIN); ?></strong></td></tr>

<tr valign="top">
<th scope="row"><?php _e('Subject', OLBsystem::TEXTDOMAIN); ?></th>
<td>
<input type="text" name="olb_options[reservation_subject]" id="" value="<?php echo $options['reservation_subject']; ?>" size="50" />
<p class="description">
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Subject to teacher', OLBsystem::TEXTDOMAIN); ?></th>
<td>
<input type="text" name="olb_options[reservation_subject_to_teacher]" id="" value="<?php echo $options['reservation_subject_to_teacher']; ?>" size="50" />
<p class="description"></p>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Message', OLBsystem::TEXTDOMAIN); ?></th>
<td>
<textarea name="olb_options[reservation_message]" id=""  rows="10" cols="50" ><?php echo stripslashes($options['reservation_message']); ?></textarea>
<p class="description"></p>
</td>
</tr>

<tr><td colspan="2"><hr /></td></tr>
<tr valign="top"><td colspan="2"><strong><?php _e('Notice of received cancellation', OLBsystem::TEXTDOMAIN); ?></strong></td></tr>

<tr valign="top">
<th scope="row"><?php _e('Subject', OLBsystem::TEXTDOMAIN); ?></th>
<td>
<input type="text" name="olb_options[cancel_subject]" id="" value="<?php echo $options['cancel_subject']; ?>" size="50" />
<p class="description"></p>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Subject to teacher', OLBsystem::TEXTDOMAIN); ?></th>
<td>
<input type="text" name="olb_options[cancel_subject_to_teacher]" id="" value="<?php echo $options['cancel_subject_to_teacher']; ?>" size="50" />
<p class="description"></p>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Message', OLBsystem::TEXTDOMAIN); ?></th>
<td>
<textarea name="olb_options[cancel_message]" id=""  rows="10" cols="50" ><?php echo stripslashes($options['cancel_message']); ?></textarea>
<p class="description"></p>
</td>
</tr>

<tr><td colspan="2"><hr /></td></tr>
<tr valign="top"><td colspan="2"><strong><?php _e('Notice of cancellation by teacher', OLBsystem::TEXTDOMAIN); ?></strong></td></tr>

<tr valign="top">
<th scope="row"><?php _e('Subject', OLBsystem::TEXTDOMAIN); ?></th>
<td>
<input type="text" name="olb_options[cancel_subject_by_teacher]" id="" value="<?php echo $options['cancel_subject_by_teacher']; ?>" size="50" />
<p class="description"></p>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Subject to teacher', OLBsystem::TEXTDOMAIN); ?></th>
<td>
<input type="text" name="olb_options[cancel_subject_by_teacher_to_teacher]" id="" value="<?php echo $options['cancel_subject_by_teacher_to_teacher']; ?>" size="50" />
<p class="description"></p>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Message', OLBsystem::TEXTDOMAIN); ?></th>
<td>
<textarea name="olb_options[cancel_message_by_teacher]" id=""  rows="10" cols="50" ><?php echo $options['cancel_message_by_teacher']; ?></textarea>
<p class="description"></p>
</td>
</tr>


<tr><td colspan="2"><hr /></td></tr>
<tr valign="top">
<th scope="row">&nbsp;</th>
<td>
<input type="submit" name="save" class="button-primary" value="<?php _e('Update', OLBsystem::TEXTDOMAIN);?>" /></td>
</tr>
</table>

</form>
</div>
</div>
</div>
</div>
</div>
</div>
			<?php
				break;

			case 'reset':
			?>
<div id="<?php echo $options_key; ?>" class="wrap">
<?php screen_icon('options-general'); ?>
<h2><?php echo esc_html($title); ?></h2>
<div class="metabox-holder">
<div id="post-body">
<div id="post-body-content">
<div class="postbox">
<h3><span><?php _e('Reset', OLBsystem::TEXTDOMAIN); ?></span></h3>
<div class="inside">
<form method="post" action="<?php echo $_SERVER['PHP_SELF'].'?page=olb-reset'; ?>"> 
<table class="form-table">

<tr valign="top"><td colspan="2"><strong><?php _e('Reset plugin options', OLBsystem::TEXTDOMAIN); ?></strong></td></tr>

<tr valign="top">
<th scope="row"><?php _e('Target', OLBsystem::TEXTDOMAIN); ?></th>
<td>
<label><input type="checkbox" name="olb_reset[]" id="" value="general" />
<?php _e('General', OLBsystem::TEXTDOMAIN); ?></label><br>
<label><input type="checkbox" name="olb_reset[]" id="" value="specialpages" />
<?php _e('Special pages', OLBsystem::TEXTDOMAIN); ?></label><br>
<label><input type="checkbox" name="olb_reset[]" id="" value="mail" />
<?php _e('Mail', OLBsystem::TEXTDOMAIN); ?></label><br>
<p class="description"></p>
</td>
</tr>

<tr><td colspan="2"><hr /></td></tr>
<tr valign="top">
<th scope="row">&nbsp;</th>
<td>
<input type="submit" name="save" class="button-primary" value="<?php _e('Update', OLBsystem::TEXTDOMAIN);?>" /></td>
</tr>
</table>

</form>
</div>
</div>
</div>
</div>
</div>
</div>
			<?php
				break;
			default:
		}
	}


}
?>
