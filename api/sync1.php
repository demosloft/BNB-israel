<?php 

error_reporting(1);

require_once( dirname( __FILE__ ) . '/../wp-load.php' );
require_once( dirname( __FILE__ ) . '/../wp-admin/includes/image.php' );

global $wpdb;

require_once "ru_class.php";
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
			$sql = "INSERT INTO `ru_all_list` (`ru_id`,`status`) values ('".$pid."','0')";
			$wpdb->query($sql);
		}
	}
}
