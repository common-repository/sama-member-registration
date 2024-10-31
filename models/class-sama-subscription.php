<?php

class SAMA_Subscription {

	public function sama_get( $id ) {
		global $wpdb;
		$id                     = intval( $id );
		$tablename_users        = $wpdb->prefix . 'users';
		$tablename_members      = $wpdb->prefix . 'sama_members';
		$tablename_usermeta     = $wpdb->prefix . 'usermeta';
		$tablename_subscription = $wpdb->prefix . 'sama_members_subscription';
		$subscription           = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT *  FROM ' . $tablename_subscription . ' WHERE id =%d',
				$id
			)
		);

		return $subscription[0];
	}

	public function sama_find( $m_id ) {
		global $wpdb;

		$tablename_subscription = $wpdb->prefix . 'sama_members_subscription';
		$tablename_users        = $wpdb->prefix . 'users';
		$tablename_members      = $wpdb->prefix . 'sama_members';

		$subscriptions = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT *  FROM ' . $tablename_subscription . ' as s LEFT JOIN ' . $tablename_users . ' as u ON u.ID=s.user_id LEFT JOIN ' . $tablename_members . ' as m ON m.user_id=u.ID WHERE s.m_id =%d',
				$m_id
			)
		);

		return $subscriptions;
	}

}
