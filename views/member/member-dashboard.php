<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly

?>
<div class="fsms_div">
	<table width="100%" style="">
		<caption>
			Member details

		</caption>
		<thead>
		 </thead>
		 <tbody>
			 <table>
	<tr>
		<td>Nick name</td>
		<td><?php echo esc_attr( $member->user_nicename ); ?></td>
	</tr>
	<tr>
		<td>First name</td>
		<td><?php echo esc_attr( $member->first_name ); ?></td>
	</tr>
	<tr>
		<td>Last name</td>
		<td><?php echo esc_attr( $member->last_name ); ?></td>
	</tr>
	<?php
	$gender_type = esc_attr( $member->gender_type );
	if ( $gender_type == 1 ) {
		$member_gender = 'Male';
	} elseif ( $gender_type == 2 ) {
		$member_gender = 'Female';
	} else {
		$member_gender = 'Other';
	}
	?>
	<tr>
		<td>Gender</td>
		<td><?php echo esc_attr( $member_gender ); ?></td>
	</tr>
	<tr>
		<td>Status</td>
		<td style="" id="accept_reject_span_<?php echo esc_attr( $member->ID ); ?>">
		<?php
		$status_txt = 'Submitted';
		if ( $member->status == -1 ) {
			$status_txt = 'Submitted';
		}
		if ( $member->status == 1 ) {
			$status_txt = 'Accepted';
		}
		if ( $member->status == 3 ) {
			$status_txt = 'Rejected';
		}
		if ( $member->status == 4 ) {
			$status_txt = 'Expired';
		}
		if ( $member->status == 5 ) {
			$status_txt = 'Suspend';
		}
		if ( $member->status == 10 ) {
			$status_txt = 'Member';
		}
		echo esc_attr( $status_txt );
		?>

	</td>
	</tr>
	<tr>
		<td>Email</td>
		<td><?php echo esc_attr( $member->user_email ); ?></td>
	</tr>
	<tr>
		<td>Phone</td>
		<td><?php echo esc_attr( $member->phone ); ?></td>
	</tr>
	<tr>
		<td>Moblie</td>
		<td><?php echo esc_attr( $member->mobile ); ?></td>
	</tr>
	<tr>
		<td>Registered date</td>
		<td>
		<?php
		$register_date = date( 'Y-m-d', strtotime( $member->register_date ) );
		echo esc_attr( $register_date );
		?>
		</td>
	</tr>
	<tr>
		<td>Accepted date</td>
		<td>
		<?php
		$accept_date = date( 'Y-m-d', strtotime( $member->accept_date ) );
		echo esc_attr( $accept_date );
		?>
		</td>
	</tr>
	<tr>
		<td>Expire date</td>
		<td>
		<?php
		$accept_date = date( 'Y-m-d', strtotime( '+1 year', strtotime( $member->accept_date ) ) );

		echo esc_attr( $accept_date );
		?>
		</td>
	</tr>
	<tr>
		<td>Subscription type</td>
		<td>
		<?php

		if ( $member->membership_type == 1 ) {
			echo esc_attr( 'Life time' );
		} else {
			echo esc_attr( 'Annual' );
		}
		?>
		</td>
	</tr>
	<tr>
		<td>Address</td>
		<td><?php echo esc_attr( $member->address ); ?></td>
	</tr>
	<tr>
		<td>Birth date</td>
		<td><?php echo esc_attr( $member->birth_date ); ?></td>
	</tr>
		 </tbody>
		  
	</table>
</div>

