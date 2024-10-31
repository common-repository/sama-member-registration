<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
?>
<div class="fsms_div">
<div class="wrap">
	<div>
 
 

	<form action="options.php" method="post">


		<?php

		settings_fields( 'sama-settings' );

		do_settings_sections( 'sama-settings' );

		?>
		<div style="font-size:16px; padding: 15px;background-color: #40ccf4; color: #fff;">
			<div>Welcome to SAMA member registration</div>
	<p>If you have further development of this plugin, please write to me <a href="mailto:wapnishantha@gmail.com?subject=Feedback sama">Feedback (wapnishantha@gmail.com)</a>. Do you like to this plugin, please write a review on <a href="https://wordpress.org/plugins/sama-member-registration" target="_blank">wordpress</a></p>
	
			<p>You can use following placehoders</p>
			<p>
			((user_id))
			((first_name))
			((last_name))
			((phone))
			((mobile))            
			((register_date))
			((accept_date))
			((payment_page))
			((user_name))
			((password))
			((member_login_url))
			((member_password_reset_url))
			</p>
		</div>
		</div>
		<table>
			<caption>
				<div style="padding-left:10px;">Settings</div>
			</caption>
			<tr>
				<td>No of rows</td>
				<td><input style="width:100px;" type="text" placeholder="25" name="sama_no_of_rows"
						   value="<?php echo esc_attr( get_option( 'sama_no_of_rows' ) ); ?>"/></td>

			</tr>
			<tr>
				<td>Paypal email</td>
				<td><input style="width:350px;" type="text" placeholder="paypal@paypal.com" name="sama_paypal_email"
						   value="<?php echo esc_attr( get_option( 'sama_paypal_email' ) ); ?>"/></td>

			</tr>
			<tr>
				<td>Paypal payment page <div style="font-size:12px;">(Ex <?php echo site_url(); ?>/<?php echo esc_attr( get_option( 'sama_paypal_payment_page' ) ); ?>/?id=XXX&subscription_id=XX) <br>
					Create page for paypal subscription and add shortcode [sama-paypal] 
				</div></td>
				<td><input style="width:350px;" type="text" placeholder="paypal payment page" name="sama_paypal_payment_page"
						   value="<?php echo esc_attr( get_option( 'sama_paypal_payment_page' ) ); ?>"/></td>

			</tr>
			<tr>
				<td>Paypal notify url</td>
				<td><?php echo site_url(); ?>/wp-json/sama/v1/notify/
					<input style="width:350px;display: none;" type="text" placeholder="" name="sama_notify_url"
						   value="<?php echo esc_attr( get_option( 'sama_notify_url' ) ); ?>"/></td>

			</tr>
			<tr>
				<td>Paypal cancel return url</td>
				<td><input style="width:350px;" type="text" placeholder="" name="sama_cancel_return_url"
						   value="<?php echo esc_attr( get_option( 'sama_cancel_return_url' ) ); ?>"/></td>

			</tr>
			<tr>
				<td>Paypal return url</td>
				<td><input style="width:350px;" type="text" placeholder="" name="sama_return_url"
						   value="<?php echo esc_attr( get_option( 'sama_return_url' ) ); ?>"/></td>

			</tr>
			<tr>
				<td>Paypal live?</td>
				<td>
					<select style="width:150px;"  name="sama_paypal_is_live">
						<option 
						<?php
						if ( esc_attr( get_option( 'sama_paypal_is_live' ) ) == 0 ) {
							echo 'selected';
						};
						?>
						 value="0">Test</option>
						<option 
						<?php
						if ( esc_attr( get_option( 'sama_paypal_is_live' ) ) == 1 ) {
							echo 'selected';
						};
						?>
						 value="1">Live</option>
					</select>
				</td>

			</tr>
			<tr>
				<td>Yearly subscription amount</td>
				<td><input style="width:150px;" type="text" placeholder="1000" name="sama_yearly_subscription_amount"
						   value="<?php echo esc_attr( get_option( 'sama_yearly_subscription_amount' ) ); ?>"/></td>

			</tr>
			<tr>
				<td>Life time subscription amount</td>
				<td><input style="width:150px;" type="text" placeholder="100" name="sama_monthly_subscription_amount"
						   value="<?php echo esc_attr( get_option( 'sama_monthly_subscription_amount' ) ); ?>"/></td>

			</tr>
			   
			<tr>
				<td>Currency symbol</td>
				<td><input style="width:50px;" type="text" placeholder="&pound;" name="sama_currency_symbol"
						   value="<?php echo esc_attr( get_option( 'sama_currency_symbol' ) ); ?>"/></td>

			</tr>
			
			<tr>
				<td>New member registration cc email (comma separated)</td>
				<td><input type="text" placeholder="cc@cc.com,cc2@cc.com" name="sama_new_member_registration_cc_email_address"
						   value="<?php echo esc_attr( get_option( 'sama_new_member_registration_cc_email_address' ) ); ?>"/>

				</td>

			</tr>
			
						<tr>
				<td>Email from name</td>
				<td><input type="text" placeholder="from name" name="sama_from_name"
						   value="<?php echo esc_attr( get_option( 'sama_from_name' ) ); ?>"/>

				</td>

			</tr>
			<tr>
				<td>Email from address</td>
				<td><input type="text" placeholder="from@yourcompany.com" name="sama_from_email_address"
						   value="<?php echo esc_attr( get_option( 'sama_from_email_address' ) ); ?>"/>

				</td>

			</tr>

			<tr>
				<td style="background: #ffffff;"><div style="margin-top: 80px;"><!-- --></div></td>
			</tr>		
			<tr>
				<td style="background: #cccccc;">
					<h5>New member ADMIN - welcome email subject</h5>
				</td>
				<td style="background: #cccccc;"><input type="text" placeholder="Welcome" name="sama_new_member_admin_welcome_email_subject"
						   value="<?php echo esc_attr( get_option( 'sama_new_member_admin_welcome_email_subject' ) ); ?>"/>

				</td>

			</tr>
			<tr>
				<td style="background: #ffffff;">New member ADMIN - welcome Email body
					
				</td>
				<td style="background: #ffffff;"> <?php wp_editor( stripslashes( wp_kses_post( ( get_option( 'sama_new_member_admin_welcome_email_body' ) ) ) ), 'sama_new_member_admin_welcome_email_body' ); ?>

				</td>

			</tr>
			
			<tr>
				<td style="background: #ffffff;"><div style="margin-top: 80px;"><!-- --></div></td>
			</tr>

			<tr>
				<td style="background: #cccccc;"><h5>New member welcome message in registration form</h5>

				</td>
				<td style="background: #cccccc;">
				</td>

			</tr>
			<tr>
				<td style="background: #ffffff;">New member welcome message in registration form
					
				</td>
				<td style="background: #ffffff;"> <?php wp_editor( stripslashes( wp_kses_post( ( get_option( 'sama_new_member_welcome_message_web' ) ) ) ), 'sama_new_member_welcome_message_web' ); ?>

				</td>

			</tr>
			
			 <tr>
				<td style="background: #ffffff;"><div style="margin-top: 80px;"><!-- --></div></td>
			</tr>
			
			<tr>
				<td style="background: #cccccc;"><h5>New member welcome email subject</h5>

				</td>
				<td style="background: #cccccc;"><input type="text" placeholder="Welcome" name="sama_new_member_welcome_email_subject"
						   value="<?php echo esc_attr( get_option( 'sama_new_member_welcome_email_subject' ) ); ?>"/>

				</td>

			</tr>
			<tr>
				<td style="background: #ffffff;">New member welcome Email body
					
				</td>
				<td style="background: #ffffff;"> <?php wp_editor( stripslashes( wp_kses_post( ( get_option( 'sama_new_member_welcome_email_body' ) ) ) ), 'sama_new_member_welcome_email_body' ); ?>

				</td>

			</tr>
			
			 <tr>
				<td style="background: #ffffff;"><div style="margin-top: 80px;"><!-- --></div></td>
			</tr>
			
			
			<tr>
				<td style="background: #cccccc;"><h5>New member already exist message in registration form</h5>

				</td>
				<td style="background: #cccccc;">
				</td>

			</tr>
			

			<tr>
				<td style="background: #ffffff;">New member already exist message in registration form
					
				</td>
				<td style="background: #ffffff;"><?php wp_editor( stripslashes( wp_kses_post( ( get_option( 'sama_new_member_exist_message_web' ) ) ) ), 'sama_new_member_exist_message_web' ); ?>

				</td>

			</tr>
			
			 <tr>
				<td style="background: #ffffff;"><div style="margin-top: 80px;"><!-- --></div></td>
			</tr>
			
			<tr>
				<td style="background: #cccccc;"><h5>New member already exist email subject</h5>

				</td>
				<td style="background: #cccccc;"><input type="text" placeholder="Welcome" name="sama_exist_member_welcome_email_subject"
						   value="<?php echo esc_attr( get_option( 'sama_exist_member_welcome_email_subject' ) ); ?>"/>

				</td>

			</tr>
			<tr>
				<td style="background: #ffffff;">New member already exist email body
					
				</td>
				<td style="background: #ffffff;"><?php wp_editor( stripslashes( wp_kses_post( ( get_option( 'sama_exist_member_welcome_email_body' ) ) ) ), 'sama_exist_member_welcome_email_body' ); ?>

				</td>

			</tr>
			
			 <tr>
				<td style="background: #ffffff;"><div style="margin-top: 80px;"><!-- --></div></td>
			</tr>
			
			<tr>
				<td style="background: #cccccc;"><h5>Member accept Email subject</h5>

				</td>
				<td style="background: #cccccc;"><input type="text" placeholder="Thanks you" name="sama_member_accept_email_subject"
						   value="<?php echo esc_attr( get_option( 'sama_member_accept_email_subject' ) ); ?>"/>

				</td>

			</tr>
			<tr>
				<td style="background: #ffffff;">Member accept Email body
					
				</td>
				<td style="background: #ffffff;"><?php wp_editor( stripslashes( wp_kses_post( ( get_option( 'sama_member_accept_email_body' ) ) ) ), 'sama_member_accept_email_body' ); ?>

				</td>

			</tr>

			 <tr>
				<td style="background: #ffffff;"><div style="margin-top: 80px;"><!-- --></div></td>
			</tr>
			
			
			<tr>
				<td style="background: #cccccc;"><h5>Paypal success - Email subject</h5>

				</td>
				<td style="background: #cccccc;"><input type="text" placeholder="Welcome" name="sama_paypal_paid_email_subject"
						   value="<?php echo esc_attr( get_option( 'sama_paypal_paid_email_subject' ) ); ?>"/>

				</td>

			</tr>
			<tr>
				<td style="background: #ffffff;">Paypal success - Email body
					
				</td>
				<td style="background: #ffffff;"> <?php wp_editor( stripslashes( wp_kses_post( ( get_option( 'sama_paypal_paid_email_body' ) ) ) ), 'sama_paypal_paid_email_body' ); ?>

				</td>

			</tr>		
			
			
			<tr>
				<td style="background: #ffffff;"><div style="margin-top: 80px;"><!-- --></div></td>
			</tr>

			<tr>
				<td style="background: #cccccc;"><h5>Member Expiration days (Yearly 365)</h5></td>
				<td style="background: #cccccc;"><input style="width:100px;" type="text" placeholder="365" name="sama_member_expiration_days"
						   value="<?php echo esc_attr( get_option( 'sama_member_expiration_days' ) ); ?>"/></td>

			</tr>

			<tr>
				<td style="background: #cccccc;"><h5>Member Expiration Email subject</h5>

				</td>
				<td style="background: #cccccc;"><input type="text" placeholder="Thanks you" name="sama_member_subscription_email_subject"
						   value="<?php echo esc_attr( get_option( 'sama_member_subscription_email_subject' ) ); ?>"/>

				</td>

			</tr>
			<tr >
				<td style="background: #ffffff;">Member Expiration Email body
					
				</td>
				<td style="background: #ffffff;"><?php wp_editor( stripslashes( wp_kses_post( ( get_option( 'sama_member_subscription_email_body' ) ) ) ), 'sama_member_subscription_email_body' ); ?>

				</td>

			</tr>
			

			 <tr>
				<td style="background: #ffffff;"><div style="margin-top: 80px;"><!-- --></div></td>
			</tr>

			<tr>
				<td style="background: #cccccc;"><h5>Early reminder 1st (Days from registration)</h5></td>
				<td style="background: #cccccc;"><input style="width:100px;" type="text" placeholder="21" name="sama_early_reminder_1"
						   value="<?php echo esc_attr( get_option( 'sama_early_reminder_1' ) ); ?>"/></td>

			</tr>
			<tr>
				<td style="background: #cccccc;"><h5>Early reminder 1st - Email subject</h5>

				</td>
				<td style="background: #cccccc;"><input type="text" placeholder="Early reminder first - Email subject" name="sama_early_reminder_1_email_subject"
						   value="<?php echo esc_attr( get_option( 'sama_early_reminder_1_email_subject' ) ); ?>"/>

				</td>

			</tr>
			<tr>
				<td style="background: #ffffff;">Early reminder first - Email body
					
				</td>
				<td style="background: #ffffff;"><?php wp_editor( stripslashes( wp_kses_post( ( get_option( 'sama_early_reminder_1_email_body' ) ) ) ), 'sama_early_reminder_1_email_body' ); ?>

				</td>

			</tr>
			
			 <tr>
				<td style="background: #ffffff;"><div style="margin-top: 80px;"><!-- --></div></td>
			</tr>
			
			<tr>
				<td style="background: #cccccc;"><h5>Early reminder 2nd (Days from registration)</h5></td>
				<td style="background: #cccccc;"><input style="width:100px;" type="text" placeholder="14" name="sama_early_reminder_2"
						   value="<?php echo esc_attr( get_option( 'sama_early_reminder_2' ) ); ?>"/></td>

			</tr>

			<tr>
				<td style="background: #cccccc;"><h5>Early reminder 2nd - Email subject</h5>

				</td>
				<td style="background: #cccccc;"><input type="text" placeholder="Early reminder second - Email subject" name="sama_early_reminder_2_email_subject"
						   value="<?php echo esc_attr( get_option( 'sama_early_reminder_2_email_subject' ) ); ?>"/>

				</td>

			</tr>
			<tr>
				<td style="background: #ffffff;">Early reminder second - Email body
					
				</td>
				<td style="background: #ffffff;"><?php wp_editor( stripslashes( wp_kses_post( ( get_option( 'sama_early_reminder_2_email_body' ) ) ) ), 'sama_early_reminder_2_email_body' ); ?>

				</td>

			</tr>
			
			 <tr>
				<td style="background: #ffffff;"><div style="margin-top: 80px;"><!-- --></div></td>
			</tr>
			
			<tr>
				<td style="background: #cccccc;"><h5>Early reminder 3rd (Days from registration)</h5></td>
				<td style="background: #cccccc;"><input style="width:100px;" type="text" placeholder="7" name="sama_early_reminder_3"
						   value="<?php echo esc_attr( get_option( 'sama_early_reminder_3' ) ); ?>"/></td>

			</tr>

			<tr>
				<td style="background: #cccccc;"><h5>Early reminder 3rd - Email subject</h5>

				</td>
				<td style="background: #cccccc;"><input type="text" placeholder="Early reminder third - Email subject" name="sama_early_reminder_3_email_subject"
						   value="<?php echo esc_attr( get_option( 'sama_early_reminder_3_email_subject' ) ); ?>"/>

				</td>

			</tr>
			<tr>
				<td style="background: #ffffff;">Early reminder third - Email body
					
				</td>
				<td style="background: #ffffff;"><?php wp_editor( stripslashes( wp_kses_post( ( get_option( 'sama_early_reminder_3_email_body' ) ) ) ), 'sama_early_reminder_3_email_body' ); ?>

				</td>

			</tr>

			 <tr>
				<td style="background: #ffffff;"><div style="margin-top: 80px;"></div></td>
			</tr>

			<tr>
				<td style="background: #cccccc;"><h5>Late reminder - first (days after)</h5></td>
				<td style="background: #cccccc;"><input style="width:100px;" type="text" placeholder="1" name="sama_early_reminder_4"
						   value="<?php // echo esc_attr( get_option( 'sama_early_reminder_4' ) ); ?>"/></td>

			</tr>
			
			<tr>
				<td style="background: #cccccc;"><h5>Late reminder first - Email subject</h5>

				</td>
				<td style="background: #cccccc;"><input type="text" placeholder="Late reminder first - Email subject" name="sama_early_reminder_4_email_subject"
						   value="<?php echo esc_attr( get_option( 'sama_early_reminder_4_email_subject' ) ); ?>"/>

				</td>

			</tr>
			<tr>
				<td style="background: #ffffff;">Late reminder third - Email body
					
				</td>
				<td style="background: #ffffff;"><?php wp_editor( stripslashes( wp_kses_post( ( get_option( 'sama_early_reminder_4_email_body' ) ) ) ), 'sama_early_reminder_4_email_body' ); ?>

				</td>

			</tr>
			
			 <tr>
				<td style="background: #ffffff;"><div style="margin-top: 80px;"></div></td>	
		</tr>
			
			<tr>
				<td style="background: #cccccc;"><h5>Late reminder - second (days after)</h5></td>
				<td style="background: #cccccc;"><input style="width:100px;" type="text" placeholder="7" name="sama_early_reminder_5"
						   value="<?php echo esc_attr( get_option( 'sama_early_reminder_5' ) ); ?>"/></td>

			</tr>

			<tr>
				<td style="background: #cccccc;"><h5>Late reminder second - Email subject</h5>

				</td>
				<td style="background: #cccccc;"><input type="text" placeholder="Late reminder second - Email subject" name="sama_early_reminder_5_email_subject"
						   value="<?php echo esc_attr( get_option( 'sama_early_reminder_5_email_subject' ) ); ?>"/>

				</td>

			</tr>
			<tr>
				<td style="background: #ffffff;">Late reminder second - Email body
					
				</td>
				<td style="background: #ffffff;"><?php wp_editor( stripslashes( wp_kses_post( ( get_option( 'sama_early_reminder_5_email_body' ) ) ) ), 'sama_early_reminder_5_email_body' ); ?>

				</td>

			</tr>
			
			
			<tr>
				<td style="background: #ffffff;"><div style="margin-top: 80px;"><!-- --></div></td>
			</tr>
			
			<tr>
				<td style="background: #cccccc;"><h5>Member suspend Email subject</h5>

				</td>
				<td style="background: #cccccc;"><input type="text" placeholder="Thanks you" name="sama_member_suspend_email_subject"
						   value="<?php echo esc_attr( get_option( 'sama_member_suspend_email_subject' ) ); ?>"/>

				</td>

			</tr>
			<tr>
				<td style="background: #ffffff;">Member suspend Email body
					
				</td>
				<td style="background: #ffffff;"><?php wp_editor( stripslashes( wp_kses_post( ( get_option( 'sama_member_suspend_email_body' ) ) ) ), 'sama_member_suspend_email_body' ); ?>

				</td>

			</tr>


			 <tr>
				<td style="background: #ffffff;"><div style="margin-top: 80px;"><!-- --></div></td>
			</tr>

			<tr>
				<td style="background: #cccccc;"><h5>Member reject email subject</h5>

				</td>
				<td style="background: #cccccc;"><input type="text" placeholder="Thanks you" name="sama_member_reject_email_subject"
						   value="<?php echo esc_attr( get_option( 'sama_member_reject_email_subject' ) ); ?>"/>

				</td>

			</tr>
			<tr>
				<td style="background: #ffffff;">Member reject email body
					
				</td>
				<td style="background: #ffffff;"><?php wp_editor( stripslashes( wp_kses_post( ( get_option( 'sama_member_reject_email_body' ) ) ) ), 'sama_member_reject_email_body' ); ?>

				</td>

			</tr>
			  
			  
				
			<tr>
				<td style="background: #ffffff;"></td>
				<td style="background: #ffffff;"><?php submit_button(); ?></td>
			</tr>
		</table>
	</form>
</div>
</div>
