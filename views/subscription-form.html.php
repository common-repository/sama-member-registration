<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly

?>
<?php

// Set variables for paypal form
$sama_paypal_is_live = sanitize_email( get_option( 'sama_paypal_is_live' ) );
$paypal_url          = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
// $paypal_url = 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';
if ( $sama_paypal_is_live == 1 ) {
	$paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
}
// Test PayPal API URL
$paypal_email = sanitize_email( get_option( 'sama_paypal_email' ) );
$notify_url   = site_url() . '/wp-json/sama/v1/notify/';

$cancel_return_url = site_url() . esc_attr( get_option( 'sama_cancel_return_url' ) );
$return_url        = site_url() . esc_attr( get_option( 'sama_return_url' ) );

// echo $notify_url;
?>

 <form action="<?php echo esc_attr( $paypal_url ); ?>" method="post">
<table>
	
	<tr>
		<td>First name</td>
		<td><?php echo esc_attr( $member->first_name ); ?></td>
	</tr>
	<tr>
		<td>Last name</td>
		<td><?php echo esc_attr( $member->last_name ); ?></td>
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
		<td>Registor date</td>
		<td><?php echo esc_attr( $member->register_date ); ?></td>
	</tr>
	<tr>
		<td>Subscription type</td>
		<td>
		<?php

		if ( esc_attr( $member->membership_type ) == 1 ) {
			echo 'Life';
		} else {
			echo 'Yearly';
		}
		?>
		</td>
	</tr>
	<tr>
		<td>Subscription amount</td>
		<td><?php echo esc_attr( $subscription->amount ); ?></td>
	</tr>
	  

	<tr>
		<td colspan="2" valign="top">
<input type="submit" name="submit" value="Pay now" >
	<input type="hidden" name="invoice" value="<?php echo esc_attr( $subscription->id ); ?>" />
						
<input type="hidden" name="business" value="<?php echo esc_attr( $paypal_email ); ?>">
<!-- Buy Now button. -->
<input type="hidden" name="cmd" value="_xclick">
 <!-- Details about the item that buyers will purchase. -->
<input type="hidden" name="item_name" value="monthly subscription">
<input type="hidden" name="item_number" value="1">
<input type="hidden" name="amount" value="<?php echo esc_attr( $subscription->amount ); ?>">
<input type="hidden" name="currency_code" value="USD">
<!-- URLs -->
<input type='hidden' name='cancel_return' value='<?php echo esc_attr( $cancel_return_url ); ?>'>
<input type='hidden' name='return' value='<?php echo esc_attr( $return_url ); ?>'>
<input type='hidden' name='notify_url'
	   value='<?php echo esc_attr( $notify_url ); ?>'>
<!-- payment button. -->

 
		</td>
	</tr>

</table>




</form>
					
