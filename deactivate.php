<?php
/**
 * Exit if accessed directly!
 */
if (!defined( 'ABSPATH' ) )
	die( 'why though?' );

function sama_deactivate() {
	global $wpdb;

	$role = get_role( 'administrator' );
	$role->remove_cap( 'member_setting' );

	$role = get_role( 'member' );
	$role->remove_cap( 'member_summary' );

	remove_role( 'member' );

	$tablename_sama_members = $wpdb->prefix . 'sama_members';
	//$wpdb->query( "DROP TABLE IF EXISTS {$tablename_sama_members}" );

	$tablename_sama_subscription = $wpdb->prefix . 'sama_members_subscription';
	//$wpdb->query( "DROP TABLE IF EXISTS {$tablename_sama_subscription}" );

	 
}