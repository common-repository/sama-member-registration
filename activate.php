<?php
/**
 * Exit if accessed directly!
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'why though?' );
}

/**
 * create database table
 */
function sama_activate() {
    global $wpdb;

    $charset_coallate = $wpdb->get_charset_collate();

	$tablename_sama_members = $wpdb->prefix . 'sama_members';
	$sql1                = "CREATE TABLE $tablename_sama_members (
                            id int(11) NOT NULL AUTO_INCREMENT,
							  user_id int(11) NOT NULL,
							  membership_type int(11) NOT NULL,
							  status int(11) NOT NULL DEFAULT -1,
							  first_name varchar(255) NOT NULL,
							  last_name varchar(255) NOT NULL,
							  phone varchar(255) NOT NULL,
							  register_date datetime NOT NULL,
							  accept_date datetime DEFAULT NULL,
							  address varchar(255) NOT NULL,
							  mobile varchar(255) NOT NULL,
							  gender_type tinyint(1) NOT NULL,
							  birth_date date DEFAULT NULL,
                          PRIMARY KEY (id)
                        )  $charset_coallate";
	$tablename_sama_subscription = $wpdb->prefix . 'sama_members_subscription';
	$sql2                = "CREATE TABLE $tablename_sama_subscription (
                          id int(11) NOT NULL AUTO_INCREMENT,
						  user_id int(11) NOT NULL,
						  subscription_date datetime NOT NULL,
						  amount float NOT NULL,
						  is_send_email int(1) NOT NULL,
						  is_payment_received int(1) NOT NULL,
						  m_id int(11) NOT NULL,
						  reminder_type int(11) NOT NULL,
						  subscription_recurring_date datetime NOT NULL,
                          PRIMARY KEY (id)
                        )  $charset_coallate";


	// require WordPress dbDelta() function
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql1 );
	dbDelta( $sql2 );

	/* Create Member User Role */
	add_role(
		'member',
		__( 'Member' ),
		array(
			'read' => true,
			'edit_user' => true,

		)
	);


	$role = get_role( 'administrator' );
	$role->add_cap( 'member_setting' );  


 
}