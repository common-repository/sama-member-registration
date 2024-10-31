<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly

?>
<div class="fsms_div">
<table width="100%" style="">
		<caption>
			Subscription list

		</caption>
	<tr>

				<th>Subscription send date</th>
				<th>Reminder type</th>
				<th>Amount</th>
				<th>Payment</th>

		</tr>
	<?php foreach ( $subscriptions as $subscription ) : ?>
		<tr>
				 
				<td><?php echo esc_attr( $subscription->subscription_date ); ?></td>
				<td>
				<?php
				$reminder_type     = esc_attr( $subscription->reminder_type );
				$reminder_type_txt = 'Welcome';
				if ( $reminder_type == 7 ) {
					$reminder_type_txt = 'One week before';
				}
				if ( $reminder_type == 14 ) {
					$reminder_type_txt = 'Two week before';
				}
				if ( $reminder_type == 21 ) {
					$reminder_type_txt = 'Three week before';
				}
				if ( $reminder_type == -1 ) {
					$reminder_type_txt = 'One day after';
				}
				if ( $reminder_type == -7 ) {
					$reminder_type_txt = 'Two week after';
				}
				if ( $reminder_type == -999 ) {
					$reminder_type_txt = 'Next payment date';
				}
				?>
		<?php echo esc_attr( $reminder_type_txt ); ?>     
				 </td>                
				<td><?php echo esc_attr( $subscription->amount ); ?></td>
				<td>
				<?php
				$is_payment_received     = esc_attr( $subscription->is_payment_received );
				$is_payment_received_txt = 'N/A';
				if ( $is_payment_received == 1 ) {
					$is_payment_received_txt = 'Recived';
				}
				?>
		<?php echo esc_attr( $is_payment_received_txt ); ?>     
				 </td>
		</tr>
	<?php endforeach; ?>
	</table>
</div>
