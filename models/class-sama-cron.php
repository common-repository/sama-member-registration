<?php
new Sama_Cron();

class Sama_Cron {

	function __construct() {

		add_filter( 'cron_schedules', array( $this, 'sama_add_schedule_one_week_before' ) );

		if ( ! wp_next_scheduled( 'sama_add_schedule_one_week_before' ) ) {
			wp_schedule_event( time(), 'one_week_before', 'sama_add_schedule_one_week_before' );
		}

		add_action( 'sama_add_schedule_one_week_before', array( $this, 'sama_handle_two_week_before' ) );

		add_action( 'sama_add_schedule_one_week_before', array( $this, 'sama_handle_one_week_before' ) );

		add_action( 'sama_add_schedule_one_week_before', array( $this, 'sama_handle_one_day_before' ) );

		add_action( 'sama_add_schedule_one_week_before', array( $this, 'sama_handle_zero_day_before' ) );

		add_action( 'sama_add_schedule_one_week_before', array( $this, 'sama_handle_one_day_after' ) );

		add_action( 'sama_add_schedule_one_week_before', array( $this, 'sama_handle_one_week_after' ) );

		add_action( 'sama_add_schedule_one_week_before', array( $this, 'sama_handle_next_payment_date' ) );

	}

	function sama_handle_next_payment_date() {
		global $wpdb;
		$_member                = new SAMA_Member();
		$tablename_users        = $wpdb->prefix . 'users';
		$tablename_members      = $wpdb->prefix . 'sama_members';
		$tablename_usermeta     = $wpdb->prefix . 'usermeta';
		$tablename_subscription = $wpdb->prefix . 'sama_members_subscription';

		$next_subscriptions = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT m.accept_date,m.id,m.user_id,m.membership_type  FROM ' . $tablename_subscription . ' as s LEFT JOIN ' . $tablename_members . ' as m ON s.m_id=m.id WHERE s.reminder_type=%d AND m.membership_type=%d',
				0,
				2
			)
		);
		$txt                = $wpdb->last_query;

		foreach ( $next_subscriptions as $subscription ) :

			$t = $_member->sama_add_next_subscription_by_cron( $subscription );

		endforeach;

	}

	function sama_handle_two_week_before() {
		$sama_early_reminder_1 = 0;
		if ( esc_attr( get_option( 'sama_early_reminder_1' ) ) > 0 ) {

			$sama_early_reminder_1 = esc_attr( get_option( 'sama_early_reminder_1' ) );
		}
		$no_of_days    = $sama_early_reminder_1;
		$reminder_type = 21;
		if ( $sama_early_reminder_1 > 0 ) {
			$this->sama_handle_common_week_before( $no_of_days, $reminder_type );
		}

	}

	function sama_handle_one_week_before() {
		$sama_early_reminder_2 = 0;
		if ( esc_attr( get_option( 'sama_early_reminder_2' ) ) > 0 ) {

			$sama_early_reminder_2 = esc_attr( get_option( 'sama_early_reminder_2' ) );
		}
		$no_of_days    = $sama_early_reminder_2;
		$reminder_type = 7;
		if ( $sama_early_reminder_2 > 0 ) {
			$this->sama_handle_common_week_before( $no_of_days, $reminder_type );
		}

	}

	function sama_handle_zero_day_before() {
		$sama_member_expiration_days = 365;
		if ( esc_attr( get_option( 'sama_member_expiration_days' ) ) > 0 ) {

			$sama_member_expiration_days = esc_attr( get_option( 'sama_member_expiration_days' ) );
		}
		$no_of_days    = $sama_member_expiration_days;
		$reminder_type = 365;
		$this->sama_handle_common_week_before( $no_of_days, $reminder_type );
	}

	function sama_handle_one_day_before() {
		$sama_early_reminder_3 = 0;
		if ( esc_attr( get_option( 'sama_early_reminder_3' ) ) > 0 ) {

			$sama_early_reminder_3 = esc_attr( get_option( 'sama_early_reminder_3' ) );
		}
		$no_of_days    = $sama_early_reminder_3;
		$reminder_type = 1;
		if ( $sama_early_reminder_3 > 0 ) {
			$this->sama_handle_common_week_before( $no_of_days, $reminder_type );
		}

	}

	function sama_handle_one_day_after() {
		$sama_early_reminder_4 = 0;
		if ( esc_attr( get_option( 'sama_early_reminder_4' ) ) > 0 ) {

			$sama_early_reminder_4 = esc_attr( get_option( 'sama_early_reminder_4' ) );
		}
		$no_of_days    = $sama_early_reminder_4;
		$reminder_type = - 1;
		if ( $sama_early_reminder_4 > 0 ) {
			$this->sama_handle_common_week_before( $no_of_days * - 1, $reminder_type );
		}

	}

	function sama_handle_one_week_after() {
		$sama_early_reminder_5 = 0;
		if ( esc_attr( get_option( 'sama_early_reminder_5' ) ) > 0 ) {

			$sama_early_reminder_5 = esc_attr( get_option( 'sama_early_reminder_5' ) );
		}
		$no_of_days    = $sama_early_reminder_5;
		$reminder_type = - 7;
		if ( $sama_early_reminder_5 > 0 ) {
			$this->sama_handle_common_week_before( $no_of_days * - 1, $reminder_type );
		}

	}

	function sama_add_schedule_one_week_before( $schedules ) {
		$schedules['one_week_before'] = array(
			'interval' => 86400,
			'display'  => esc_html__( 'one week before' ),
		);

		return $schedules;
	}

	function sama_handle_common_week_before( $no_of_days, $reminder_type ) {
		/*
		$no_of_days = 3;
		$reminder_type = 1;*/
		$_member       = new SAMA_Member();
		$_email        = new Sama_Email();
		$email_subject = 'sama_member_subscription_email_subject';
		$email_body    = 'sama_member_subscription_email_body';
		if ( $reminder_type == 21 ) {
			$email_subject = 'sama_early_reminder_1_email_subject';
			$email_body    = 'sama_early_reminder_1_email_body';
		}
		if ( $reminder_type == 7 ) {
			$email_subject = 'sama_early_reminder_2_email_subject';
			$email_body    = 'sama_early_reminder_2_email_body';
		}
		if ( $reminder_type == 1 ) {
			$email_subject = 'sama_early_reminder_3_email_subject';
			$email_body    = 'sama_early_reminder_3_email_body';
		}
		if ( $reminder_type == - 1 ) {
			$email_subject = 'sama_early_reminder_4_email_subject';
			$email_body    = 'sama_early_reminder_4_email_body';
		}
		if ( $reminder_type == - 7 ) {
			$email_subject = 'sama_early_reminder_5_email_subject';
			$email_body    = 'sama_early_reminder_5_email_body';
		}

		$subscriptions = $this->sama_subscriptions_lists( $no_of_days );
		foreach ( $subscriptions as $subscription ) :

			$membership_type = $subscription->membership_type;
			$user_id         = $subscription->user_id;
			$m_id            = $subscription->id;

			$subscriptionOption['user_id']         = $user_id;
			$subscriptionOption['reminder_type']   = $reminder_type;
			$subscriptionOption['membership_type'] = $membership_type;
			$subscriptionOption['accept_date']     = $subscription->accept_date;

			$subscription_id = $_member->sama_add_to_subscription_by_cron( $subscriptionOption );

			$_email    = new Sama_Email();
			$options   = array(
				'emailSubject'    => $email_subject,
				'emailBody'       => $email_body,
				'subscription_id' => $subscription->s_id,
				'user_id'         => $subscription->user_id,
				'id'              => $subscription->id,
				's_id'            => $subscription->s_id,
				'reminder_type'   => $subscription->$reminder_type,
			);
			$emailData = $_email->sama_send_email( $options );

			$re = $_member->sama_update_subscription_by_cron( $user_id, $subscription_id, $m_id );

		endforeach;
	}


	function sama_subscriptions_lists( $no_of_days ) {
		global $wpdb;
		$_member                = new SAMA_Member();
		$tablename_users        = $wpdb->prefix . 'users';
		$tablename_members      = $wpdb->prefix . 'sama_members';
		$tablename_usermeta     = $wpdb->prefix . 'usermeta';
		$tablename_subscription = $wpdb->prefix . 'sama_members_subscription';
		$capabilities_slug = $wpdb->prefix . 'capabilities';
		$subscriptions          = $wpdb->get_results( $wpdb->prepare( 'SELECT s.id as s_id, m.accept_date,m.id,m.user_id,m.membership_type  FROM ' . $tablename_subscription . ' as s LEFT JOIN ' . $tablename_members . ' as m ON s.user_id=m.user_id LEFT JOIN ' . $tablename_usermeta . " as me ON me.user_id=m.user_id WHERE s.reminder_type=-999 AND DATE_FORMAT(s.subscription_date,'%%Y-%%m-%%d') = DATE_FORMAT((NOW() + INTERVAL " . $no_of_days . " DAY),'%%Y-%%m-%%d') AND  me.meta_key ='".$capabilities_slug."' AND me.meta_value LIKE '%member%' GROUP by s.user_id" ) );

		$txt = $wpdb->last_query;

		return $subscriptions;
	}


}




