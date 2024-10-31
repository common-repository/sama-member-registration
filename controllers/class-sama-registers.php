<?php

class Sama_Registrations {
	/**
	 * @var
	 */
	public static $shortcode_ids;

	/**
	 *
	 */
	public function __construct() {

		$this->admin_hooks();
		$this->sama_public_hooks();
		add_action( 'wp_ajax_nopriv_member_registration_form_submit', array( $this, 'sama_member_registration' ) );
		add_action( 'wp_ajax_member_registration_form_submit', array( $this, 'sama_member_registration' ) );

		add_action( 'wp_ajax_nopriv_member_accept_reject_user', array( $this, 'sama_accept_reject_user' ) );
		add_action( 'wp_ajax_member_accept_reject_user', array( $this, 'sama_accept_reject_user' ) );

	}

	/**
	 * @return void
	 */
	public function admin_hooks() {

		add_action( 'admin_enqueue_scripts', array( $this, 'sama_ajax_enqueuer' ) );

	}

	/**
	 * @return void
	 */
	function sama_accept_reject_user() {
		if ( ! empty( $_POST['ok'] ) && wp_verify_nonce( $_POST['nonceVal'], 'member_registration_form_submit' ) ) {
			$id      = intval( sanitize_text_field( ( isset( $_POST['id'] ) ) ? ( $_POST['id'] ) : ( 0 ) ) );
			$user_id = intval( sanitize_text_field( ( isset( $_POST['user_id'] ) ) ? ( $_POST['user_id'] ) : ( 0 ) ) );

			$_member                  = new SAMA_Member();
			$memberDataArr            = $_member->sama_update_accept_reject( $_POST );
			$memberData_to            = $_member->sama_get( $id );
			$_POST['membership_type'] = $memberData_to->membership_type;
			$subscription_data        = $_member->sama_add_to_subscription_accept_reject( $_POST );
			$subscription_id          = $subscription_data['lastid'];
			$_email                   = new Sama_Email();
			$email_template_subject   = 'sama_member_accept_email_subject';
			$email_template_body      = 'sama_member_accept_email_body';
			if ( $_POST['status'] == 3 ) {
				$email_template_subject = 'sama_member_reject_email_subject';
				$email_template_body    = 'sama_member_reject_email_body';
			}
			if ( $_POST['status'] == 5 ) {
				$email_template_subject = 'sama_member_suspend_email_subject';
				$email_template_body    = 'sama_member_suspend_email_body';
				$user_id                = $user_id;
				$u                      = new WP_User( $user_id );
				$u->remove_cap( 'member_summary' );
			}
			if ( $_POST['status'] == 4 ) {
				$email_template_subject = 'sama_member_subscription_email_subject';
				$email_template_body    = 'sama_member_subscription_email_body';
				$user_id                = $user_id;
				$u                      = new WP_User( $user_id );
				$u->remove_cap( 'member_summary' );
			}
			$options   = array(
				'emailSubject'    => $email_template_subject,
				'emailBody'       => $email_template_body,
				'subscription_id' => $subscription_id,
				'user_id'         => $user_id,
				'id'              => $id,
			);
			$dataEmail = $_email->sama_send_email( $options );

		}
		$dataEmail['subscription_data'] = $subscription_data;
		$dataEmail['post']              = $_POST;
		header( 'Content-Type: application/json; charset=utf-8' );
		echo json_encode( $dataEmail );
		die();
	}

	/**
	 * @return void
	 */
	function sama_member_registration() {

		if ( ! empty( $_POST['ok'] ) && wp_verify_nonce( $_POST['nonceVal'], 'member_registration_form_submit' ) ) {

			$_member    = new SAMA_Member();
			$memberData = $_member->sama_add_member( $_POST );

		}
		$welcome_web_message = wp_kses_post( get_option( 'sama_new_member_welcome_message_web' ) );
		if ( $memberData['already_exist'] > 0 ) {
			$welcome_web_message = wp_kses_post( get_option( 'sama_new_member_exist_message_web' ) );
		}
		$values                  = $this->sama_get_placeholder_values( $memberData['m_id'] );
		$welcome_web_message_txt = $this->sama_get_placeholder( $values, $welcome_web_message );

		$memberData['welcome_web_message'] = $welcome_web_message_txt;
		header( 'Content-Type: application/json; charset=utf-8' );
		echo json_encode( $memberData );
		die();
	}

	/**
	 * @param $id
	 *
	 * @return array
	 */
	public static function sama_get_placeholder_values( $id ) {
		$_member       = new SAMA_Member();
		$member        = $_member->sama_get( $id );
		$user_id       = $member->user_id;
		$first_name    = $member->first_name;
		$last_name     = $member->last_name;
		$register_date = $member->register_date;
		$accept_date   = $member->accept_date;
		$phone         = $member->phone;

		$placeholderValues = array(
			$user_id,
			$first_name,
			$last_name,
			$phone,
			$register_date,
			$accept_date,

		);

		return $placeholderValues;
	}

	/**
	 * @param $values
	 * @param $txt
	 *
	 * @return string
	 */
	public static function sama_get_placeholder( $values, $txt ) {
		$placeholder = array(
			'((user_id))',
			'((first_name))',
			'((last_name))',
			'((phone))',
			'((register_date))',
			'((accept_date))',

		);
		$newtxt = str_replace( $placeholder, $values, $txt );

		return wp_kses_post( nl2br( $newtxt ) );
	}


	/**
	 * @return void
	 */
	public function sama_public_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'sama_ajax_enqueuer' ) );
	}

	/**
	 * @return int|void
	 */
	static function sama_get_id() {
		if ( empty( self::$shortcode_ids ) ) {
			self::$shortcode_ids = array();
		}
		$current_id = count( self::$shortcode_ids );
		$current_id ++;
		self::$shortcode_ids[] = $current_id;

		return $current_id;
	}

	/**
	 * @param $att
	 *
	 * @return false|string
	 */
	public static function sama_registration_form( $att ) {
		global $wpdb, $post;

		if ( empty( $shortcode_id ) ) {
			$shortcode_id = self::sama_get_id();
		}
		ob_start();

		include SAMA_PATH . '/views/registration-form.html.php';

		$content = ob_get_clean();

		return $content;

	}

	/**
	 * @return void
	 */
	public function sama_ajax_enqueuer() {

		wp_register_style( 'sama-css', SAMA_URL_PATH . 'assets/css/style.css?v=1' );
		wp_enqueue_style( 'sama-css' );

		wp_enqueue_style( 'bootstrap', SAMA_URL_PATH . 'assets/css/bootstrap.min.css' );
		wp_enqueue_style( 'font-awesome', SAMA_URL_PATH . '/assets/font-awesome-4.7.0/css/font-awesome.min.css' );

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_style( 'jquery-ui' );

		// Namaste's own Javascript
		wp_register_script(
			'sama-common',
			SAMA_URL_PATH . 'assets/js/common.js',
			false,
			'0.1.0',
			false
		);
		wp_enqueue_script( 'sama-common' );

		// Here we create a javascript object variable called "youruniquejs_vars". We can access any variable in the array using youruniquejs_vars.name_of_sub_variable

		$id = sanitize_text_field( ( isset( $_GET['id'] ) ) ? ( $_GET['id'] ) : ( 0 ) );
		wp_localize_script(
			'sama-common',
			'sama_js_vars',
			array(
				// To use this variable in javascript use "youruniquejs_vars.ajaxurl"
				'ajaxurl'  => admin_url( 'admin-ajax.php' ),
				// To use this variable in javascript use "youruniquejs_vars.the_issue_key"
				'id'       => intval( $id ),
				'nonceVal' => wp_create_nonce( 'member_registration_form_submit' ),

			)
		);

		wp_enqueue_script( 'sama-common' );

	}

	/**
	 * @param $user
	 *
	 * @return void
	 */
	public static function sama_show_extra_profile_fields( $user ) {

		$phone       = get_the_author_meta( 'phone', $user->ID );
		$mobile      = get_the_author_meta( 'mobile', $user->ID );
		$address     = get_the_author_meta( 'address', $user->ID );
		$gender_type = get_the_author_meta( 'gender_type', $user->ID );
		$birth_date  = get_the_author_meta( 'birth_date', $user->ID );

		include SAMA_PATH . '/views/registration-extra-form.html.php';
	}

	/**
	 * @param $user_id
	 *
	 * @return false|void
	 */
	public static function sama_update_profile_fields( $user_id ) {
		$_member = new SAMA_Member();
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

		if ( ! empty( $_POST['gender_type'] ) ) {
			update_user_meta( $user_id, 'gender_type', sanitize_text_field( $_POST['gender_type'] ) );
		}
		if ( ! empty( $_POST['phone'] ) ) {
			update_user_meta( $user_id, 'phone', sanitize_text_field( $_POST['phone'] ) );
		}
		if ( ! empty( $_POST['mobile'] ) ) {
			update_user_meta( $user_id, 'mobile', sanitize_text_field( $_POST['mobile'] ) );
		}
		if ( ! empty( $_POST['address'] ) ) {
			update_user_meta( $user_id, 'address', sanitize_text_field( $_POST['address'] ) );
		}
		if ( ! empty( $_POST['birth_date'] ) ) {
			update_user_meta( $user_id, 'birth_date', sanitize_text_field( $_POST['birth_date'] ) );
		}
		$member_opiton['user_id']     = $user_id;
		$member_opiton['gender_type'] = sanitize_text_field( $_POST['gender_type'] );
		$member_opiton['phone']       = sanitize_text_field( $_POST['phone'] );
		$member_opiton['mobile']      = sanitize_text_field( $_POST['mobile'] );
		$member_opiton['address']     = sanitize_text_field( $_POST['address'] );
		$member_opiton['birth_date']  = sanitize_text_field( $_POST['birth_date'] );
		$_member->sama_update_member_data_from_wp( $member_opiton );
	}

}

new Sama_Registrations();
