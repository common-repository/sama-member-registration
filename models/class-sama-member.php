<?php

class SAMA_Member {

	public function sama_import_wp_user() {
		global $wpdb;
		$tablename_users        = $wpdb->prefix . 'users';
		$tablename_members      = $wpdb->prefix . 'sama_members';
		$tablename_usermeta     = $wpdb->prefix . 'usermeta';
		$tablename_subscription = $wpdb->prefix . 'sama_members_subscription';

		$users = $wpdb->get_results( ( 'SELECT * FROM ' . $tablename_users . ' as u' ) );

		foreach ( $users as $user ) :

			$user_nicename   = $user->user_nicename;
			$user_id         = $user->ID;
			$register_date   = $user->user_registered;
			$membership_type = 2;
			$accept_date     = date( 'Y-m-01' );
			$member_data     = $this->sama_member_exists( $user_id );
			$m_id            = 0;
			if ( ! empty( $member_data ) && property_exists( $member_data, 'id' ) ) {
				$m_id = $member_data->id;
			}
			if ( $m_id > 0 ) {

			} else {

				$u = new WP_User( $user_id );

				$u->remove_role( 'subscriber' );

				$u->add_role( 'member' );

				$result     = $wpdb->query(
					$wpdb->prepare(
						'INSERT INTO ' . $tablename_members . ' SET  
			first_name=%s,   user_id=%d,register_date=%s,membership_type=%d , accept_date=%s, status=%d',
						$user_nicename,
						$user_id,
						$register_date,
						$membership_type,
						$accept_date,
						10
					)
				);
				$sql_insert = $wpdb->last_query;

				$m_id   = $wpdb->insert_id;
				$amount = 100;
				$result = $wpdb->query(
					$wpdb->prepare(
						'INSERT INTO ' . $tablename_subscription . ' SET is_payment_received=%d, m_id=%d,is_send_email=%d,user_id=%d, subscription_date=%s, amount=%d',
						1,
						$m_id,
						1,
						$user_id,
						date( 'Y-m-d H:i:s' ),
						$amount
					)
				);
			}

		endforeach;
		echo '<div>Import all users</div>';
	}

	public static function sama_update_member_data_from_wp( $member_opiton ) {
		global $wpdb;
		$user_id     = $member_opiton['user_id'];
		$gender_type = $member_opiton['gender_type'];
		$phone       = $member_opiton['phone'];
		$mobile      = $member_opiton['mobile'];
		$address     = $member_opiton['address'];
		$birth_date  = $member_opiton['birth_date'];

		$tablename_members = $wpdb->prefix . 'sama_members';
		$result            = $wpdb->query(
			$wpdb->prepare(
				'UPDATE ' . $tablename_members . ' SET gender_type =%d, phone=%s, mobile=%s, address=%s, birth_date=%s WHERE user_id=%d',
				$gender_type,
				$phone,
				$mobile,
				$address,
				$birth_date,
				$user_id
			)
		);
	}

	public function sama_member_exists( $user_id = null ) {
		global $wpdb;
		$tablename_members = $wpdb->prefix . 'sama_members';
		$member            = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT * FROM ' . $tablename_members . ' as m WHERE m.user_id=%d',
				$user_id
			)
		);

		if ( count( $member ) === 0 ) {
			return false;
		} else {
			return $member[0];
		}

	}

	public static function sama_add_next_subscription_by_cron( $subscription ) {
		global $wpdb;

		$current_year           = date( 'Y' );
		$tablename_subscription = $wpdb->prefix . 'sama_members_subscription';
		$m_id                   = $subscription->id;
		$user_id                = $subscription->user_id;
		$subscription_date      = $subscription->accept_date;
		$membership_type        = $subscription->membership_type;
		if ( $membership_type == 1 ) {
			$amount = esc_attr( get_option( 'sama_monthly_subscription_amount' ) );
		} else {
			$amount = esc_attr( get_option( 'sama_yearly_subscription_amount' ) );
		}
		$sub_date      = strtotime( $subscription_date );
		$accepted_year = date( 'Y', $sub_date );
		$add_years     = ( (int) $current_year - (int) $accepted_year ) + 1;

		$new_subscription_date = date( 'Y-m-d', strtotime( '+' . $add_years . ' year', $sub_date ) );

		$total_count = $wpdb->get_var(
			$wpdb->prepare(
				'SELECT COUNT(*) FROM ' . $tablename_subscription . ' WHERE m_id=%d AND subscription_date=%s',
				$m_id,
				$new_subscription_date
			)
		);

		if ( $total_count == 0 ) {
			$result = $wpdb->query(
				$wpdb->prepare(
					'INSERT INTO ' . $tablename_subscription . ' SET reminder_type=%d,m_id=%d,user_id=%d, subscription_date=%s, amount=%d',
					- 999,
					$m_id,
					$user_id,
					$new_subscription_date,
					$amount
				)
			);
			$txt    = $wpdb->last_query;
		}

		return $total_count;

	}

	public static function sama_dashboard_stats() {
		global $wpdb;
		$tablename_users        = $wpdb->prefix . 'users';
		$tablename_members      = $wpdb->prefix . 'sama_members';
		$tablename_usermeta     = $wpdb->prefix . 'usermeta';
		$tablename_subscription = $wpdb->prefix . 'sama_members_subscription';
		// $subscriptions          = $wpdb->get_results( $wpdb->prepare( "SELECT u.user_email,m.mobile, m.accept_date,m.first_name,m.last_name,DATE_FORMAT(DATE_ADD(s.subscription_date, INTERVAL 1 YEAR), '%%Y-%%m-%%d') as subscription_date,m.accept_date,m.id,m.user_id,m.membership_type  FROM " . $tablename_subscription . " as s LEFT JOIN " . $tablename_members . " as m ON s.user_id=m.user_id LEFT JOIN " . $tablename_usermeta . " as me ON me.user_id=m.user_id LEFT JOIN " . $tablename_users . " as u ON u.ID=m.user_id WHERE  s.is_payment_received=1 AND m.status=1 AND me.meta_key ='wp_capabilities' AND me.meta_value LIKE '%member%' AND m.membership_type=2 group by m.user_id ORDER BY  s.subscription_date ASC") );
		$no_of_days_old = 300;
		$subscriptions  = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT  u.user_email,m.mobile, m.accept_date,m.first_name,m.last_name,DATE_FORMAT(DATE_ADD(s.subscription_date, INTERVAL 1 YEAR), '%%Y-%%m-%%d') as subscription_date,m.accept_date,m.id,m.user_id,m.membership_type FROM " . $tablename_subscription . ' as s LEFT JOIN ' . $tablename_members . ' as m ON s.user_id=m.user_id   LEFT JOIN ' . $tablename_users . " as u ON u.ID=m.user_id  WHERE s.reminder_type=%d AND DATE_FORMAT(s.subscription_date,'%%Y-%%m-%%d') < DATE_FORMAT((NOW() + INTERVAL " . $no_of_days_old . " DAY),'%%Y-%%m-%%d')",
				- 999
			)
		);

		// echo $wpdb->last_query;
		return $subscriptions;
	}

	public static function sama_dashboard_summary_stats() {
		global $wpdb;
		$tablename_users        = $wpdb->prefix . 'users';
		$tablename_members      = $wpdb->prefix . 'sama_members';
		$tablename_usermeta     = $wpdb->prefix . 'usermeta';
		$tablename_subscription = $wpdb->prefix . 'sama_members_subscription';
		$total_count            = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $tablename_members );
		$summary['total_count'] = $total_count;

		$total_accepted            = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $tablename_members . ' WHERE status=1' );
		$summary['total_accepted'] = $total_accepted;

		$total_rejected            = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $tablename_members . ' WHERE status=3' );
		$summary['total_rejected'] = $total_rejected;
		$total_life                = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $tablename_members . ' WHERE membership_type=1 AND status=10' );
		$summary['total_life']     = $total_life;

		$total_annual            = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $tablename_members . ' WHERE membership_type=2 AND status=10 ' );
		$summary['total_annual'] = $total_annual;

		$total_annual                     = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $tablename_members . ' WHERE status=1 ' );
		$summary['total_payment_pending'] = $total_annual;

		$total_annual                      = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $tablename_members . ' WHERE  status=-1 ' );
		$summary['total_approval_pending'] = $total_annual;

		$no_of_days_old                          = 300;
		$total_renewal_and_expiration            = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $tablename_subscription . " as s WHERE s.reminder_type=-999 AND DATE_FORMAT(s.subscription_date,'%Y-%m-%d') < DATE_FORMAT((NOW() + INTERVAL " . $no_of_days_old . " DAY),'%Y-%m-%d')" );
		$summary['total_renewal_and_expiration'] = $total_renewal_and_expiration;
		// echo $wpdb->last_query;

		$total_suspended            = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $tablename_members . ' WHERE status=5' );
		$summary['total_suspended'] = $total_suspended;

		return $summary;
	}

	public static function sama_dashboard_summary_new_member() {

		global $wpdb;
		$tablename_users        = $wpdb->prefix . 'users';
		$tablename_members      = $wpdb->prefix . 'sama_members';
		$tablename_usermeta     = $wpdb->prefix . 'usermeta';
		$tablename_subscription = $wpdb->prefix . 'sama_members_subscription';
		$new_members            = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT * FROM ' . $tablename_members . ' as m LEFT JOIN ' . $tablename_users . ' as u ON u.ID=m.user_id   WHERE status=%d ORDER BY  m.id DESC LIMIT 0,50',
				- 1
			)
		);

		// echo $wpdb->last_query;
		return $new_members;

	}

	public static function sama_dashboard_summary_accept_member() {

		global $wpdb;
		$tablename_users        = $wpdb->prefix . 'users';
		$tablename_members      = $wpdb->prefix . 'sama_members';
		$tablename_usermeta     = $wpdb->prefix . 'usermeta';
		$tablename_subscription = $wpdb->prefix . 'sama_members_subscription';
		$accept_members         = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT * FROM ' . $tablename_members . ' as  m LEFT JOIN ' . $tablename_users . ' as u ON u.ID=m.user_id WHERE status=%d ORDER BY  accept_date DESC LIMIT 0,50',
				1
			)
		);

		// echo $wpdb->last_query;
		return $accept_members;

	}

	public static function sama_dashboard_summary_suspend_member() {

		global $wpdb;
		$tablename_users        = $wpdb->prefix . 'users';
		$tablename_members      = $wpdb->prefix . 'sama_members';
		$tablename_usermeta     = $wpdb->prefix . 'usermeta';
		$tablename_subscription = $wpdb->prefix . 'sama_members_subscription';
		$suspend_members        = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT * FROM ' . $tablename_members . ' as  m LEFT JOIN ' . $tablename_users . ' as u ON u.ID=m.user_id WHERE status=%d ORDER BY  accept_date DESC LIMIT 0,50',
				5
			)
		);

		// echo $wpdb->last_query;
		return $suspend_members;

	}

	public static function sama_dashboard_summary_reject_member() {

		global $wpdb;
		$tablename_users        = $wpdb->prefix . 'users';
		$tablename_members      = $wpdb->prefix . 'sama_members';
		$tablename_usermeta     = $wpdb->prefix . 'usermeta';
		$tablename_subscription = $wpdb->prefix . 'sama_members_subscription';
		$reject_members         = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT * FROM ' . $tablename_members . ' as m LEFT JOIN ' . $tablename_users . ' as u ON u.ID=m.user_id WHERE status=%d ORDER BY  accept_date DESC LIMIT 0,50',
				3
			)
		);

		// echo $wpdb->last_query;
		return $reject_members;

	}

	public static function sama_dashboard_summary_payment_pending_member() {

		global $wpdb;
		$tablename_users        = $wpdb->prefix . 'users';
		$tablename_members      = $wpdb->prefix . 'sama_members';
		$tablename_usermeta     = $wpdb->prefix . 'usermeta';
		$tablename_subscription = $wpdb->prefix . 'sama_members_subscription';
		$pending_members        = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT * FROM ' . $tablename_subscription . ' as s LEFT JOIN ' . $tablename_users . ' as u ON u.ID=s.user_id LEFT JOIN ' . $tablename_members . ' as m ON u.ID=m.user_id WHERE m.status=%d ORDER BY  accept_date DESC LIMIT 0,50',
				1
			)
		);

		// echo $wpdb->last_query;
		return $pending_members;

	}

	public function find( $filters = null ) {
		global $wpdb;

		$rstart      = $filters['rstart'];
		$rend        = $filters['rend'];
		$dir         = $filters['dir'];
		$ob          = $filters['ob'];
		$sama_search = $filters['sama_search'];

		$tablename_users    = $wpdb->prefix . 'users';
		$tablename_members  = $wpdb->prefix . 'sama_members';
		$tablename_usermeta = $wpdb->prefix . 'usermeta';

		$capabilities_slug = $wpdb->prefix . 'capabilities';

		$key_txt = '';
		if ( $sama_search != '' ) {
			$key_txt  = " AND ((m.first_name like '%" . $sama_search . "%' OR m.last_name like '%" . $sama_search . "%' OR m.phone like '%" . $sama_search . "%' OR m.address like '%" . $sama_search . "%'  OR m.mobile like '%" . $sama_search . "%')";
			$key_txt .= " OR ( u.user_nicename like '%" . $sama_search . "%') )";

		}

		$members = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT *  FROM ' . $tablename_users . ' as u LEFT JOIN ' . $tablename_members . ' as m ON u.ID=m.user_id LEFT JOIN ' . $tablename_usermeta . ' as me ON me.user_id=u.ID WHERE m.id>0 ' . $key_txt . ' AND me.meta_key = %s AND me.meta_value LIKE %s ORDER BY '.$ob.' '.$dir.'  LIMIT %d, %d',
				$capabilities_slug,
				'%member%',
				
				
				$rstart,
				$rend
			)
		);
		 
		return $members;
	}

	public function sama_get_total_count( $filters = null ) {
		global $wpdb;
		$tablename_users    = $wpdb->prefix . 'users';
		$tablename_members  = $wpdb->prefix . 'sama_members';
		$tablename_usermeta = $wpdb->prefix . 'usermeta';
		$capabilities_slug = $wpdb->prefix . 'capabilities';

		$totalLeadsCount = $wpdb->get_var( ( 'SELECT count(*)  FROM ' . $tablename_users . ' as u LEFT JOIN ' . $tablename_members . ' as m ON u.ID=m.user_id LEFT JOIN ' . $tablename_usermeta . ' as me ON me.user_id=u.ID WHERE me.meta_key = "'.$capabilities_slug.'" AND me.meta_value LIKE "%member%" ' ) );

		return $totalLeadsCount;

	}


	public function sama_add_member( array $vars ) {
		global $wpdb;
		$this->sama_prepare_vars( $vars );
		$user_name                = $vars['user_name'];
		$first_name               = $vars['first_name'];
		$last_name                = $vars['last_name'];
		$phone_number             = $vars['phone_number'];
		$mobile_number            = $vars['mobile_number'];
		$subscription_type        = $vars['subscription_type'];
		$password                 = $vars['password'];
		$user_email               = $vars['email_address'];
		$gender_type              = $vars['gender_type'];
		$address                  = $vars['address'];
		$birth_date               = $vars['birth_date'];
		$user_id                  = email_exists( $user_email );
		$user_data['user_id']     = $user_id;
		$user_data['user_status'] = 1;
		$members_tablename        = $wpdb->prefix . 'sama_members';
		$password                 = wp_generate_password( $length = 12, $include_standard_special_chars = false );

		$member_data = $this->sama_member_exists( $user_id );
		if ( $member_data->id > 0 && $user_id != false ) {
			$user_data['user_id']     = $user_id;
			$user_data['user_status'] = 1;
			$user_data['m_id']        = $member_data->id;
			$_email                   = new Sama_Email();
			wp_set_password( $password, $user_id );

			$options                    = array(
				'emailSubject'    => 'sama_exist_member_welcome_email_subject',
				'emailBody'       => 'sama_exist_member_welcome_email_body',
				'subscription_id' => 0,
				'user_id'         => $user_id,
				'id'              => $member_data->id,
				'password'        => $password,
				'user_name'       => $user_name,
			);
			$user_data['already_exist'] = (int) $member_data->id;
			$_email->sama_send_email( $options );

		} else {

			if ( ! $user_id and email_exists( $user_email ) == false ) {

				$user_id                       = wp_create_user( $user_name, $random_password, $user_email );
				$user_data['user_id']          = $user_id;
				$user_data['already_exist_wp'] = 'yes1';

			} else {
				// wp_set_password( $password, $user_id );
				$user_id                       = email_exists( $user_email );
				$user_data['user_id']          = $user_id;
				$user_data['already_exist_wp'] = 'yes2';
			}
			$result     = $wpdb->query(
				$wpdb->prepare(
					'INSERT INTO ' . $members_tablename . ' SET address=%s,gender_type=%s,birth_date=%s,
			first_name=%s, last_name=%s, phone=%s, mobile=%s, user_id=%d,register_date=%s,membership_type=%d',
					$address,
					$gender_type,
					$birth_date,
					$first_name,
					$last_name,
					$phone_number,
					$mobile_number,
					$user_id,
					date( 'Y-m-d H:i:s' ),
					$subscription_type
				)
			);
			$sql_insert = $wpdb->last_query;
			$m_id       = $wpdb->insert_id;
			$u          = new WP_User( $user_id );
			// Remove role
			$u->remove_role( 'subscriber' );
			// Add role
			$u->add_role( 'member' );
			$user_data['m_id']        = $m_id;
			$user_data['user_status'] = 0;
			$user_data['sql_insert']  = $sql_insert;

			$_email  = new Sama_Email();
			$options = array(
				'emailSubject'    => 'sama_new_member_welcome_email_subject',
				'emailBody'       => 'sama_new_member_welcome_email_body',
				'subscription_id' => 0,
				'user_id'         => $user_id,
				'password'        => $password,
				'user_name'       => $user_name,
				'id'              => $m_id,
			);
			$_email->sama_send_email( $options );

			$options_admin = array(
				'emailSubject'    => 'sama_new_member_admin_welcome_email_subject',
				'emailBody'       => 'sama_new_member_admin_welcome_email_body',
				'subscription_id' => 0,
				'user_id'         => $user_id,
				'id'              => $m_id,
			);
			$_email->sama_send_admin_email( $options_admin );

		}

		if ( ! empty( $first_name ) ) {
			update_user_meta( $user_id, 'first_name', sanitize_text_field( $first_name ) );
		}
		if ( ! empty( $last_name ) ) {
			update_user_meta( $user_id, 'last_name', sanitize_text_field( $last_name ) );
		}
		if ( ! empty( $gender_type ) ) {
			update_user_meta( $user_id, 'gender_type', sanitize_text_field( $gender_type ) );
		}
		if ( ! empty( $phone_number ) ) {
			update_user_meta( $user_id, 'phone', sanitize_text_field( $phone_number ) );
		}
		if ( ! empty( $mobile_number ) ) {
			update_user_meta( $user_id, 'mobile', sanitize_text_field( $mobile_number ) );
		}
		if ( ! empty( $address ) ) {
			update_user_meta( $user_id, 'address', sanitize_text_field( $address ) );
		}
		if ( ! empty( $birth_date ) ) {
			update_user_meta( $user_id, 'birth_date', sanitize_text_field( $birth_date ) );
		}

		return $user_data;
	}

	public function sama_prepare_vars( &$vars ) {
		$vars['id']                = sanitize_text_field( $vars['id'] );
		$vars['user_id']           = sanitize_text_field( $vars['user_id'] );
		$vars['status']            = sanitize_text_field( $vars['status'] );
		$vars['user_name']         = sanitize_text_field( $vars['user_name'] );
		$vars['first_name']        = sanitize_text_field( $vars['first_name'] );
		$vars['last_lame']         = sanitize_text_field( $vars['last_name'] );
		$vars['email_address']     = sanitize_text_field( $vars['email_address'] );
		$vars['phone_number']      = sanitize_text_field( $vars['phone_number'] );
		$vars['mobile_number']     = sanitize_text_field( $vars['mobile_number'] );
		$vars['subscription_type'] = sanitize_text_field( $vars['subscription_type'] );
		$vars['gender_type']       = sanitize_text_field( $vars['gender_type'] );
		$vars['address']           = sanitize_text_field( $vars['address'] );
		$vars['birth_date']        = sanitize_text_field( $vars['birth_date'] );
		$vars['membership_type']   = sanitize_text_field( $vars['membership_type'] );
		$vars['reminder_type']     = sanitize_text_field( $vars['reminder_type'] );
		$vars['accept_date']       = sanitize_text_field( $vars['accept_date'] );

	}

	// return specific stage details
	public function sama_get( $id ) {
		global $wpdb;
		$id                 = intval( $id );
		$tablename_users    = $wpdb->prefix . 'users';
		$tablename_members  = $wpdb->prefix . 'sama_members';
		$tablename_usermeta = $wpdb->prefix . 'usermeta';
		$capabilities_slug = $wpdb->prefix . 'capabilities';
		$member             = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT *  FROM ' . $tablename_users . ' as u LEFT JOIN ' . $tablename_members . ' as m ON u.ID=m.user_id LEFT JOIN ' . $tablename_usermeta . ' as me ON me.user_id=u.ID WHERE me.meta_key = "'.$capabilities_slug.'" AND me.meta_value LIKE "%member%" AND m.id =%d',
				$id
			)
		);

		if ( count( $member ) === 0 ) {
			return false;
		} else {
			return $member[0];
		}
	}

	public static function sama_get_from_user_id( $id ) {
		global $wpdb;
		$id                 = intval( $id );
		$tablename_users    = $wpdb->prefix . 'users';
		$tablename_members  = $wpdb->prefix . 'sama_members';
		$tablename_usermeta = $wpdb->prefix . 'usermeta';
		$capabilities_slug = $wpdb->prefix . 'capabilities';
		$member             = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT *  FROM ' . $tablename_users . ' as u LEFT JOIN ' . $tablename_members . ' as m ON u.ID=m.user_id LEFT JOIN ' . $tablename_usermeta . ' as me ON me.user_id=u.ID WHERE me.meta_key = "'.$capabilities_slug.'" AND me.meta_value LIKE "%member%" AND m.user_id =%d',
				$id
			)
		);

		if ( count( $member ) === 0 ) {
			return false;
		} else {
			return $member[0];
		}

	}

	public static function sama_get_from_invoice_id( $id ) {
		global $wpdb;
		$id = intval( $id );

		$tablename_subscription = $wpdb->prefix . 'sama_members_subscription';
		$member                 = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT *  FROM ' . $tablename_subscription . ' as s WHERE s.id =%d',
				$id
			)
		);
		$txt                    = $wpdb->last_query;

		if ( count( $member ) === 0 ) {
			return false;
		} else {
			return $member[0];
		}

	}

	public function sama_update_accept_reject( array $vars ) {
		global $wpdb;
		$this->sama_prepare_vars( $vars );

		$tablename_members = $wpdb->prefix . 'sama_members';

		$status = $vars['status'];
		$id     = $vars['id'];
		$result = $wpdb->query(
			$wpdb->prepare(
				'UPDATE ' . $tablename_members . ' SET accept_date =%s, status=%d WHERE id=%d',
				date( 'Y-m-d H:i:s' ),
				$status,
				$id
			)
		);

	}

	public static function sama_update_subscription( $user_id ) {
		global $wpdb;
		$tablename_subscription = $wpdb->prefix . 'sama_members_subscription';

		$result = $wpdb->query(
			$wpdb->prepare(
				'UPDATE ' . $tablename_subscription . ' SET is_send_email =%s WHERE user_id=%d',
				1,
				$user_id
			)
		);

		return $wpdb->last_query;
	}

	public static function sama_update_subscription_by_cron( $user_id, $subscription_id, $m_id ) {
		global $wpdb;
		$tablename_subscription = $wpdb->prefix . 'sama_members_subscription';

		$result = $wpdb->query(
			$wpdb->prepare(
				'UPDATE ' . $tablename_subscription . ' SET m_id=%d, is_send_email =%s WHERE id=%d',
				$m_id,
				1,
				$subscription_id
			)
		);

		return $wpdb->last_query;
	}

	public static function sama_update_paypal_subscription( $invoice, $m_id ) {
		global $wpdb;

		$tablename_subscription = $wpdb->prefix . 'sama_members_subscription';

		$result = $wpdb->query(
			$wpdb->prepare(
				'UPDATE ' . $tablename_subscription . ' SET is_payment_received =%s WHERE id=%d',
				1,
				$invoice
			)
		);

		$tablename_members = $wpdb->prefix . 'sama_members';

		$result = $wpdb->query(
			$wpdb->prepare(
				'UPDATE ' . $tablename_members . ' SET status =%s WHERE id=%d',
				10,
				$m_id
			)
		);

		return $wpdb->last_query;

	}


	public static function sama_is_paid_member( $m_data ) {
		global $wpdb;
		$m_id = $m_data->id;

		$tablename_subscription = $wpdb->prefix . 'sama_members_subscription';

		$paid_member = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT * from ' . $tablename_subscription . ' WHERE m_id=%d AND is_payment_received = 1 AND YEAR(subscription_date) = YEAR(CURDATE())',
				$m_id
			)
		);

		if ( count( $paid_member ) === 0 ) {
			return false;
		} else {
			return $paid_member[0];
		}

	}

	public function sama_add_to_subscription_by_cron( array $vars ) {
		global $wpdb;
		$this->sama_prepare_vars( $vars );
		$tablename_subscription      = $wpdb->prefix . 'sama_members_subscription';
		$membership_type             = $vars['membership_type'];
		$reminder_type               = $vars['reminder_type'];
		$subscription_recurring_date = $vars['accept_date'];
		if ( $membership_type == 1 ) {
			$amount = esc_attr( get_option( 'sama_monthly_subscription_amount' ) );
		} else {
			$amount = esc_attr( get_option( 'sama_yearly_subscription_amount' ) );
		}

		$user_id = $vars['user_id'];

		if ( (int) $user_id > 0 && (float) $amount > 0 ) {
			$result = $wpdb->query(
				$wpdb->prepare(
					'INSERT INTO ' . $tablename_subscription . ' SET subscription_recurring_date=%s,reminder_type=%d,
			user_id=%d, subscription_date=%s, amount=%d',
					$subscription_recurring_date,
					$reminder_type,
					$user_id,
					date( 'Y-m-d H:i:s' ),
					$amount
				)
			);
		}
		$txt = $wpdb->last_query;

		$lastid = $wpdb->insert_id;

		return $lastid;

	}

	public function sama_add_to_subscription_accept_reject( array $vars ) {
		global $wpdb;
		$this->sama_prepare_vars( $vars );
		$tablename_subscription = $wpdb->prefix . 'sama_members_subscription';
		$membership_type        = $vars['membership_type'];
		if ( $membership_type == 1 ) {
			$amount = esc_attr( get_option( 'sama_monthly_subscription_amount' ) );
		} else {
			$amount = esc_attr( get_option( 'sama_yearly_subscription_amount' ) );
		}

		$user_id = $vars['user_id'];
		$m_id    = $vars['id'];

		if ( (float) $user_id > 0 && (float) $amount > 0 ) {
			$result = $wpdb->query(
				$wpdb->prepare(
					'INSERT INTO ' . $tablename_subscription . ' SET m_id=%d,is_send_email=%d,user_id=%d, subscription_date=%s, amount=%d',
					$m_id,
					1,
					$user_id,
					date( 'Y-m-d H:i:s' ),
					$amount
				)
			);
		}
		$txt = $wpdb->last_query;

		$lastid         = $wpdb->insert_id;
		$data['lastid'] = $lastid;
		$data['sql']    = $txt;

		return $data;

	}
}
