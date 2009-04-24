<?php
/*
Plugin Name: SoJ User Time Zone
Version: 1.0
Author: Jeff Johnson
Description: Allow users to set their time zone; the times of posts and comments made by them are then adjusted accordingly.
*/

/**
 * Generate admin panel for plugin
 */
function soj_set_user_meta_subpanel()
{
	// Get current user info
	global $current_user;
	get_currentuserinfo();

	// Handle setting settings
	if(isset($_POST['action']))
	{
		switch($_POST['action'])
		{
			case 'setUserMeta':

				// Make sure we've got something
				if(!isset($_POST['offset']))
				{
					$message = 'Please specify an offset.';
					break;
				}
				
				// Make sure we've got an appropriate integer
				$tz = intval($_POST['offset']);
				if($tz<-12 || $tz>12)
				{
					$message = 'The offset: '.$tz.' is invalid. Please specify something between -12 and 12.';
					break;
				}
				
				// Set it
				update_usermeta($current_user->ID, 'user_timezone', $tz);
				update_usermeta($current_user->ID, 'user_begin_dst', $_POST['beginDST']);
				update_usermeta($current_user->ID, 'user_end_dst', $_POST['endDST']);

				$message = 'Time zone set.';
				break;
			
			default:
				$message = 'No action specified.';
		}
		if(isset($message))
		{
		?>
		<div id="message" class="updated fade">
			<p><?php _e($message); ?></p>
		</div>
		<?php
		}
	}
	?>
    
	<div class="wrap">
		<h2>Set Your Time Zone</h2>
		<form method="post" action="" enctype="multipart/form-data">
		<table cellpadding="0" cellspacing="8" border="0">
		<tbody>
			<tr>
				<td>Type in your GMT offset (e.g., -5): </td>
				<td>
					<input type="hidden" name="action" value="setUserMeta" />
					<input type="text" name="offset" size="3" value="<?php echo get_usermeta($current_user->ID, 'user_timezone'); ?>" />
				</td>
			</tr>
			<tr>
				<td colspan="2"><i>**DST currently disabled**</i></td>
			</tr>
			<tr>
				<td>Begin daylight savings YYYY-MM-DD HH:MM:SS (GMT)<br>(leave blank if not applicable): </td>
				<td><input type="text" name="beginDST" value="<?php echo get_usermeta($current_user->ID, 'user_begin_dst'); ?>" />
			</tr>
			<tr>
				<td>End daylight savings YYYY-MM-DD HH:MM:SS (GMT)<br>(leave blank if not applicable): </td>
				<td><input type="text" name="endDST" value="<?php echo get_usermeta($current_user->ID, 'user_end_dst'); ?>" />
			</tr>
			<tr>
				<td colspan="2"><input type="submit" value="Submit" /></td>
			</tr>
		</tbody>
		</table>
		</form>
	</div>
	<?php
}

/**
 * Add admin panel for plugin
 */
function soj_set_user_meta_panel()
{
    if (function_exists('add_options_page')) {
		add_options_page('SoJ User Time Zone', 'SoJ User Time Zone', 'read', __FILE__, 'soj_set_user_meta_subpanel');
    }
 }
 add_action('admin_menu', 'soj_set_user_meta_panel');

/**
 * Add time filters
 */
function soj_user_time_zone_filter($time_string, $time_format)
{
	global $post;

	// Get user time zone offset
	$tz = get_usermeta($post->post_author, 'user_timezone');
	if(!is_numeric($tz))
		return $time_string;
	$tz = intval($tz);
	if($tz<-12 || $tz>12)
		return $time_string;

	// Daylight savings?
	/*
	$b_dst = get_usermeta($post->post_author, 'user_begin_dst');
	$e_dst = get_usermeta($post->post_author, 'user_end_dst');
	if(!empty($b_dst) && !empty($e_dst))
		if(strcmp($post->post_date_gmt,$e_dst)<0 && strcmp($post->post_date_gmt,$b_dst)>0)
			$tz++;
	*/

	// Adjust time
	$time = strtotime($post->post_date_gmt) + ($tz * 3600);

	// Recreate time stamp
	return date($time_format, $time).' (GMT '.$tz.')';
}

add_filter('get_the_time', 'soj_user_time_zone_filter', 1, 2);
?>