<?php

class Sama_Email {
	public function __construct() {

	}

	public static function sama_password_reset_link( $user_id ) {

		if ( $user_id > 0 ) {
			$user       = new WP_User( intval( $user_id ) );
			$reset_key  = get_password_reset_key( $user );
			$user_login = $user->user_login;
			$locale     = get_user_locale( $user );

			$rp_link = network_site_url(
				"wp-login.php?action=rp&key=$reset_key&login=" . rawurlencode( $user_login ),
				'login'
			) . '&wp_lang=' . $locale . '';

		}

		return $rp_link;
	}

	public static function sama_send_email( $options ) {
		global $wpdb;
		$_member         = new SAMA_Member();
		$id              = $options['id'];
		$subscription_id = $options['subscription_id'];

		$password  = $options['password'];
		$user_name = $options['user_name'];

		$email_subject      = $options['emailSubject'];
		$email_body         = $options['emailBody'];
		$sama_email_subject = wp_kses_post( get_option( $email_subject ) );
		$sama_email_body    = wp_kses_post( get_option( $email_body ) );
		$member             = $_member->sama_get( $id );
		$to                 = sanitize_email( $member->user_email );

		$from_email                                    = sanitize_email( get_option( 'sama_from_email_address' ) );
		$from_name                                     = sanitize_email( get_option( 'sama_from_name' ) );
		$sama_new_member_registration_cc_email_address = sanitize_text_field( get_option( 'sama_new_member_registration_cc_email_address' ) );

		$wp_email = 'wordpress@' . preg_replace( '#^www\.#', '', wp_parse_url( network_home_url(), PHP_URL_HOST ) );

		if ( $from_name == '' ) {
			$from_name = ( '' !== get_site_option( 'site_name' ) ) ? esc_html( get_site_option( 'site_name' ) ) : 'WordPress';
		}
		if ( $from_email == '' ) {
			$from_email = 'wordpress@' . preg_replace(
				'#^www\.#',
				'',
				wp_parse_url( network_home_url(), PHP_URL_HOST )
			);
		}

		$message_headers = "From: \"{$from_name}\" <{$from_email}>\n" . 'Content-Type: text/plain; charset="' . get_option( 'blog_charset' ) . "\"\n";
		// $message_headers .= 'cc: '.$sama_new_member_registration_cc_email_address."\r\n";

		$placeholder = self::sama_get_placeholder_values( $id, $subscription_id, $user_name, $password );

		$subject      = self::sama_get_email_placeholder( $placeholder, $sama_email_subject );
		$message_body = self::sama_get_email_placeholder( $placeholder, $sama_email_body );

		$result = wp_mail( $to, $subject, ( $message_body ), $message_headers );

		$status = $result ? 'OK' : 'Error: ' . $GLOBALS['phpmailer']->ErrorInfo;

		$email_result['status']  = $status;
		$email_result['to']      = 'to -' . $to;
		$email_result['subject'] = 'subject' . $subject;

		$email_result['message_body'] = 'message_body' . $message_body;

		return $email_result;

	}

	public static function sama_send_payapal_paid_email( $options ) {
		global $wpdb;
		$_member         = new SAMA_Member();
		$id              = $options['id'];
		$subscription_id = $options['subscription_id'];

		$password  = $options['password'];
		$user_name = $options['user_name'];

		$email_subject      = $options['emailSubject'];
		$email_body         = $options['emailBody'];
		$sama_email_subject = wp_kses_post( get_option( $email_subject ) );
		$sama_email_body    = wp_kses_post( get_option( $email_body ) );
		$member             = $_member->sama_get( $id );
		$to                 = sanitize_email( $member->user_email );

		$from_email                                    = sanitize_email( get_option( 'sama_from_email_address' ) );
		$from_name                                     = sanitize_email( get_option( 'sama_from_name' ) );
		$sama_new_member_registration_cc_email_address = sanitize_text_field( get_option( 'sama_new_member_registration_cc_email_address' ) );

		$wp_email = 'wordpress@' . preg_replace( '#^www\.#', '', wp_parse_url( network_home_url(), PHP_URL_HOST ) );

		if ( $from_name == '' ) {
			$from_name = ( '' !== get_site_option( 'site_name' ) ) ? esc_html( get_site_option( 'site_name' ) ) : 'WordPress';
		}
		if ( $from_email == '' ) {
			$from_email = 'wordpress@' . preg_replace(
				'#^www\.#',
				'',
				wp_parse_url( network_home_url(), PHP_URL_HOST )
			);
		}

		$message_headers = "From: \"{$from_name}\" <{$from_email}>\n" . 'Content-Type: text/plain; charset="' . get_option( 'blog_charset' ) . "\"\n";
		// $message_headers .= 'cc: '.$sama_new_member_registration_cc_email_address."\r\n";

		$placeholder = self::sama_get_placeholder_values( $id, $subscription_id, $user_name, $password );

		$subject      = self::sama_get_email_placeholder( $placeholder, $sama_email_subject );
		$message_body = self::sama_get_email_placeholder( $placeholder, $sama_email_body );

		$result = wp_mail( $to, $subject, ( $message_body ), $message_headers );

		$status = $result ? 'OK' : 'Error: ' . $GLOBALS['phpmailer']->ErrorInfo;

		$email_result['status']  = $status;
		$email_result['to']      = 'to -' . $to;
		$email_result['subject'] = 'subject' . $subject;

		$email_result['message_body'] = 'message_body' . $message_body;

		return $email_result;

	}

	public static function sama_send_admin_email( $options ) {
		global $wpdb;
		$_member         = new SAMA_Member();
		$id              = $options['id'];
		$subscription_id = $options['subscription_id'];
		$password        = $options['password'];
		$user_name       = $options['user_name'];

		$email_subject      = $options['emailSubject'];
		$email_body         = $options['emailBody'];
		$sama_email_subject = wp_kses_post( get_option( $email_subject ) );
		$sama_email_body    = wp_kses_post( get_option( $email_body ) );
		$member             = $_member->sama_get( $id );
		// $to                 = sanitize_email( $member->user_email );

		$from_email = sanitize_email( get_option( 'sama_from_email_address' ) );
		$from_name  = sanitize_email( get_option( 'sama_from_name' ) );
		$to         = sanitize_text_field( get_option( 'sama_new_member_registration_cc_email_address' ) );

		$wp_email = 'wordpress@' . preg_replace( '#^www\.#', '', wp_parse_url( network_home_url(), PHP_URL_HOST ) );

		if ( $from_name == '' ) {
			$from_name = ( '' !== get_site_option( 'site_name' ) ) ? esc_html( get_site_option( 'site_name' ) ) : 'WordPress';
		}
		if ( $from_email == '' ) {
			$from_email = 'wordpress@' . preg_replace(
				'#^www\.#',
				'',
				wp_parse_url( network_home_url(), PHP_URL_HOST )
			);
		}

		$message_headers = "From: \"{$from_name}\" <{$from_email}>\n" . 'Content-Type: text/plain; charset="' . get_option( 'blog_charset' ) . "\"\n";
		// $message_headers .= 'cc: '.$sama_new_member_registration_cc_email_address."\r\n";

		$placeholder = self::sama_get_placeholder_values( $id, $subscription_id, $user_name, $password );

		$subject      = self::sama_get_email_placeholder( $placeholder, $sama_email_subject );
		$message_body = self::sama_get_email_placeholder( $placeholder, $sama_email_body );

		$result = wp_mail( $to, $subject, ( $message_body ), $message_headers );

		$status = $result ? 'OK' : 'Error: ' . $GLOBALS['phpmailer']->ErrorInfo;

		$email_result['status']  = $status;
		$email_result['to']      = 'to -' . $to;
		$email_result['subject'] = 'subject' . $subject;

		$email_result['message_body'] = 'message_body' . $message_body;

		return $email_result;

	}

	public static function sama_get_placeholder_values( $id, $subscription_id, $user_name, $password ) {
		$_member       = new SAMA_Member();
		$member        = $_member->sama_get( $id );
		$user_id       = $member->user_id;
		$first_name    = $member->first_name;
		$last_name     = $member->last_name;
		$register_date = $member->register_date;
		$accept_date   = $member->accept_date;
		$phone         = $member->phone;
		$mobile        = $member->mobile;

		$sama_paypal_payment_page = esc_attr( get_option( 'sama_paypal_payment_page' ) );
		if ( $sama_paypal_payment_page == '' ) {
			$sama_paypal_payment_page = 'paypal';
		}

		$payment_page              = site_url() . '/' . $sama_paypal_payment_page . '/?id=' . $id . '&subscription_id=' . $subscription_id;
		$member_login_url          = site_url() . '/wp-login.php';
		$member_password_reset_url = self::sama_password_reset_link( $user_id );

		$placeholder_values = array(
			$user_id,
			$first_name,
			$last_name,
			$phone,
			$mobile,
			$register_date,
			$accept_date,
			$payment_page,
			$user_name,
			$password,
			$member_login_url,
			$member_password_reset_url,
		);

		return $placeholder_values;
	}

	public static function sama_get_email_placeholder( $values, $txt ) {
		$placeholder = array(
			'((user_id))',
			'((first_name))',
			'((last_name))',
			'((phone))',
			'((mobile))',
			'((register_date))',
			'((accept_date))',
			'((payment_page))',
			'((user_name))',
			'((password))',
			'((member_login_url))',
			'((member_password_reset_url))',
		);
		$newtxt      = str_replace( $placeholder, $values, $txt );

		return ( nl2br( $newtxt ) );
	}

}
