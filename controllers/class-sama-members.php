<?php

class SAMA_Members {
	public static function sama_manage() {
		$_member = new SAMA_Member();
		$user    = wp_get_current_user();

		$action = sanitize_text_field( ( isset( $_GET['action'] ) ) ? ( $_GET['action'] ) : ( 'list' ) );
		switch ( $action ) {
			case 'import-user':
				$member = $_member->sama_import_wp_user();
				break;
			case 'view':
				$id            = intval( sanitize_text_field( ( isset( $_GET['id'] ) ) ? ( $_GET['id'] ) : ( 0 ) ) );
				$member        = $_member->sama_get( $id );
				$_subscription = new SAMA_Subscription();
				$subscriptions = $_subscription->sama_find( $member->id );
				include SAMA_PATH . '/views/member.php';
				break;
			case 'list':
			default:
				$pagei     = sanitize_text_field( ( isset( $_GET['pagei'] ) ) ? ( $_GET['pagei'] ) : ( 0 ) );
				$pageCount = 5;
				if ( esc_attr( get_option( 'sama_no_of_rows' ) ) > 0 ) {

					$pageCount = esc_attr( get_option( 'sama_no_of_rows' ) );
				} else {
					$pageCount = 3;
				}
				if ( $pagei == '' || $pagei == 0 ) {
					$P          = 1;
					$offSetPage = 0;
				} else {
					$P          = $pagei;
					$offSetPage = $pagei - 1;
				}
				$rstart = $offSetPage * $pageCount;
				$rend   = $pageCount;

				$dir = sanitize_text_field( ( isset( $_GET['dir'] ) ) ? ( $_GET['dir'] ) : ( 'DESC' ) );
				if ( $dir != 'ASC' and $dir != 'DESC' ) {
					$dir = 'ASC';
				}
				$odir  = ( $dir == 'ASC' ) ? 'DESC' : 'ASC';
				$ob    = 'm.id';
				$obGet = sanitize_text_field( ( isset( $_GET['ob'] ) ) ? ( $_GET['ob'] ) : ( $ob ) );
				if ( ! empty( $obGet ) ) {

					if ( $obGet == 'phone' ) {
						$ob = 'm.phone';
					}
					if ( $obGet == 'status' ) {
						$ob = 'm.status';
					}
					if ( $obGet == 'register_date' ) {
						$ob = 'm.register_date';
					}
					if ( $obGet == 'accept_date' ) {
						$ob = 'm.accept_date';
					}
					if ( $obGet == 'first_name' ) {
						$ob = 'm.first_name';
					}
					if ( $obGet == 'last_name' ) {
						$ob = 'm.last_name';
					}
					if ( $obGet == 'user_nicename' ) {
						$ob = 'u.user_nicename';
					}
					if ( $obGet == 'membership_type' ) {
						$ob = 'm.membership_type';
					}
					if ( $obGet == 'ID' ) {
						$ob = 'u.ID';
					}
					$orderby = 'ORDER BY ' . sanitize_text_field( $ob ) . ' ' . $dir;
				}
				$sama_search     = '';
				$sama_search     = sanitize_text_field( ( isset( $_GET['sama_search'] ) ) ? ( $_GET['sama_search'] ) : ( $sama_search ) );
				$filters         = array(
					'rstart'      => $rstart,
					'rend'        => $rend,
					'ob'          => $ob,
					'dir'         => $dir,
					'getOb'       => $obGet,
					'sama_search' => $sama_search,
				);
				$members         = $_member->find( $filters );
				$totalLeadsCount = $_member->sama_get_total_count();

				include SAMA_PATH . '/views/members.php';
				break;
		}
	}
}
