<?php 
ini_set('display_errors', 1);
error_reporting(1);

require_once( dirname( __FILE__ ) . '/../wp-load.php' );
require_once( dirname( __FILE__ ) . '/../wp-admin/includes/image.php' );


global $wpdb;

require_once "ru_class.php";
 require_once "sync.php";
$rentalsUnited = new rentalsUnited();

$owner = $rentalsUnited->getOwners();
$ownderId = 0;

if($owner)
{
	if($owner->Status=="Success")
	foreach($owner->Owners->Owner as $Ownr)
	{
		$att = $Ownr->attributes();
		$ownderId = $att['OwnerID'];
		break;
	}
}

if($ownderId)
{
	$properties = $rentalsUnited->getOwnerProperties($ownderId);	
	if($properties)
	{
		$i=1;
		if($properties->Status=="Success")
		foreach($properties->Properties->Property as $property)
		{
			$pid = (string)$property->ID;
			#$sql = "INSERT INTO `ru_all_list` (`ru_id`,`status`) values ('".$pid."','0')";
        	$sql = "INSERT INTO `ru_all_list` (`ru_id`,`status`) SELECT '".$pid."', '0' FROM DUAL WHERE NOT EXISTS( SELECT 1 FROM `ru_all_list` WHERE `ru_id` = '".$pid."' ) LIMIT 1";
			$wpdb->query($sql);
		}
	}
}

//wp-content\themes\wprentals-child-po added code
//table created in the database


$sql = "UPDATE `ru_all_list` SET `status` = '0' WHERE 1";
$wpdb->query($sql);

$link = admin_url()."index.php";

header("Location:".$link);
die;


