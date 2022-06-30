<?php
/*
Plugin Name: Duplicate User
Plugin URI: http://www.inimist.com
Version: 0.1.0
Author: Arvind K.
Author URI: http://www.devarticles.in
Description: This plugin provides a way to delete duplicate users by ID, user_login or user_email while attributing 

An user-base cleaning script! In a case, thousands of users were entered by tempring with the basic wp_user table structure.. where ID field was changed to just int field with no PRIMARY KEY or INDEX associated, date field (user_registered) was converted to decimal and user_email field was changed to accept only 20 characters (varchar 20) .. etc.. and thus thousands of records were entered through a script with duplicate email addresses, user_logins and even with duplicate IDs!

Note: At the moment, this script takes care of ID, user_login and user_email fields only. One can easily extend it to check other fields as well. I will try to include an automatic field selection panel in the admin settings of this plugin.
*/

/*
Copyright (C) 2012-2013 Arvind Kumar, inimist.com (devarticles.in)
Original code by Arvind Kumar

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if(!class_exists("DelUserDuplicacy"))	{
	class DelUserDuplicacy {
		function printAdminPage()	{
			global $wpdb;		
		?>
		<div class=wrap>
		<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
		<h2>Delete Duplicate User</h2>
		<?php
		
		if(isset($_POST['confirm_DeleteDuplicateUsers']))	{
			$limit='';
			$userField = $_POST['deluduplicacy_type'];
			if($_POST['del_numberOfRecords']!="all")	{
				$limit = " limit 0, ".$_POST['del_numberOfRecords'];
			}				
			$query = "SELECT COUNT(ID) as duplicates, `ID`, `user_login`, `user_email` FROM `wp_users`  GROUP BY `$userField` ORDER BY COUNT(ID) DESC ". $limit;							
			$results = $wpdb->get_results($query);
			foreach($results as $result)	{
				if($result->duplicates > 1)	{
					$fieldValue = $result->{$userField};					
					$usersData = $wpdb->get_results("select `ID`, `$userField` from `wp_users` where `$userField`='$fieldValue'");
					$usersIds = array();
					foreach($usersData as $userRow)	{
						array_push($usersIds, $userRow->ID);
					}
					$postManyUsers = count_many_users_posts( $usersIds );
					$retainingUser = 1; //that is admin, by default
					arsort($postManyUsers, SORT_NUMERIC);
					foreach($postManyUsers as $k=>$v)	{
						$retainingUser = $k;
						unset($postManyUsers[$k]);
						break;
					}
					
					if(sizeof($postManyUsers)>0)	{
						foreach($postManyUsers as $id=>$numposts)	{							
							if ( current_user_can('delete_user', $id) )	{
								echo "User with ID: <strong>$id</strong> deleted and posts attributed to user ID: <strong>$retainingUser</strong><br />";
								wp_delete_user($id, $retainingUser);
							}
						}
					}
				}
			}			
		}
		
		if(isset($_POST['submit_DeleteDuplicateUsers']))	{
			$limit='';
			$userField = $_POST['deluduplicacy_type'];
			if($_POST['del_numberOfRecords']!="all")	{
				$limit = " limit 0, ".$_POST['del_numberOfRecords'];
			}				
			$query = "SELECT COUNT(ID) as duplicates, `ID`, `user_login`, `user_email` FROM `wp_users`  GROUP BY `$userField` ORDER BY COUNT(ID) DESC ". $limit;							
			$results = $wpdb->get_results($query);
			foreach($results as $result)	{
				if($result->duplicates > 1)	{						
					if(!isset($duplicatesFound))	{$duplicatesFound=true;echo "<p><strong>Listing duplicates below..</strong></p>";}						
					echo $result->{$userField} . " (Duplicates: {$result->duplicates})<br />";
				}
			}			
			if(isset($duplicatesFound))	{
?>
<input type="hidden" name="deluduplicacy_type" value="<?php echo $_POST['deluduplicacy_type']; ?>" />
<input type="hidden" name="del_numberOfRecords" value="<?php echo $_POST['del_numberOfRecords']; ?>" />
<div class="submit">

<p style="width:400px;"><strong>All above duplicates will be deleted except one of them from each set, who has highest number of posts. Posts of duplicates due for deletion, will be attributed to one with exception:</strong></p>

		<input type="submit" name="confirm_DeleteDuplicateUsers" value="<?php _e('Delete Duplicates Now!', 'delete-uduplicacy') ?>" /></div>
<?php
			}	else	{
				$noDuplicatesFound = true;
			}
		}		
		if(isset($noDuplicatesFound))	{
			echo '<p>'.__("No duplicates found for the selected field. Please try again").'</p>';	
		}
		
		if(!isset($duplicatesFound))	{
?>	
		<h4><?php _e("List duplicates by", "delete-uduplicacy"); ?></h4>
		<p><select name="deluduplicacy_type">
			<option value="ID"><?php _e("User ID", "delete-uduplicacy"); ?></option>
			<option value="user_email"><?php _e("User Email", "delete-uduplicacy"); ?></option>
			<option value="user_login"><?php _e("User Login", "delete-uduplicacy"); ?></option>
		</select></p>
		
		<h4><?php _e("How many to delete in a go", "delete-uduplicacy"); ?></h4>
		<p>
		
		<select name="del_numberOfRecords">
			<option value="30">30</option>
			<option value="50">50</option>
			<option value="100">100</option>
			<option value="200">200</option>
			<option value="300">300</option>
			<option value="500">500</option>
			<option value="1000">1000</option>
			<option value="all">All</option>
		</select><em><?php _e("Placed this dropdown for a situation where there are tens thousands of users in the wp_users table; actually for slow connections i.e. selecting \"All\" is not recommended", "delete-uduplicacy"); ?></em></p>

		<div class="submit">
		<input type="submit" name="submit_DeleteDuplicateUsers" value="<?php _e('List duplicates for deletion', 'delete-uduplicacy') ?>" /></div>
		
		<?php } ?>
		</form>
		 </div>
		<?php
		}
	}
}

if (class_exists("DelUserDuplicacy")) {
	$ak_deluduplicacy = new DelUserDuplicacy();
}

/*if (isset($ak_deluduplicacy)) {
	add_action('activate_delete-uduplicacy/delete-uduplicacy.php', array(&$ak_deluduplicacy, 'init'));
}*/

if(!function_exists('deluduplicacy_ap'))	{
	function deluduplicacy_ap()	{
		global $ak_deluduplicacy;
		if(!isset($ak_deluduplicacy))	{
			return;
		}
		if(function_exists("add_options_page"))	{
			add_options_page(__("Duplicate User"), __("Duplicate User"), 9, basename(__FILE__), array(&$ak_deluduplicacy, 'printAdminPage'));
		}
	}
}

add_action('admin_menu', 'deluduplicacy_ap');
?>