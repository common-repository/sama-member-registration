<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
?>
<?php
$nonce = wp_create_nonce( 'member_registration_form_submit' );

?>
<div id="sama-registration-div">
<form onsubmit="return memmberRegistration(this);" id="sama-registration<?php echo esc_attr( $shortcode_id ); ?>"
	  enctype="multipart/form-data">
<section class=" ">
	<div class="container  ">
		<div class="row  ">
			<div class="col-12  ">
				<div class="card shadow-2-strong card-registration" style="border-radius: 15px;">
					<div class="card-body col-12">
						<h3 class="col-12">Registration Form</h3>


							<div class="row">
								<div class="col-12">

									<div class="form-outline">
										<label style="display:none;" class="form-label" for="username">User Name</label>
										<input placeholder="User name" type="text"  name="user_name" id="username" class="form-control form-control-lg" />
										<label style="display:none;" id="username_lbl"   class="wpsamaforms-error" for="username">This field is required.</label>
									</div>

								</div>
								<div class="col-12">

									<div class="form-outline">
										<label style="display:none;" class="form-label" for="first_name">First Name</label>
										<input placeholder="First Name" type="text"  name="first_name" id="first_name" class="form-control form-control-lg" />
										<label style="display:none;" id="firstName_lbl"   class="wpsamaforms-error" for="first_name">This field is required.</label>

									</div>

								</div>
								<div class="col-12">

									<div class="form-outline">
										<label style="display:none;" class="form-label" for="last_name">Last Name</label>
										<input placeholder="Last Name" type="text" name="last_name" id="last_name" class="form-control form-control-lg" />
										<label style="display:none;" id="lastName_lbl"   class="wpsamaforms-error" for="last_name">This field is required.</label>

									</div>

								</div>
							



							  
								<div class="col-12">

									<div class="form-outline">
										<label style="display:none;" class="form-label" for="email_address">Email</label>
										<input placeholder="Email address" type="email" name="email_address" id="email_address" class="form-control form-control-lg" />
										<label style="display:none;" id="emailAddress_lbl"   class="wpsamaforms-error" for="email_address">This field is required.</label>

									</div>

								</div>
								
								<div class="col-12">
									<div class="form-outline">
									<label style="display:none;"  class="form-label select-label">Select Gender</label>
									<select class="select col-12" name="gender_type" style="font-size: 18px; background-color:#fafafa; color: #6c757d">
										<option value="0">Gender</option>
										<option value="1">Male</option>
										<option value="2">Female</option>
										<option value="3">Prefer Not to Answer</option>
									</select>
									</div>


								</div>
							  
								<div class="col-12">

									<div class="form-outline">
										<label style="display:none;" class="form-label" for="phone_number">Phone Number</label>
										<input placeholder="Phone Number" type="tel" name="phone_number" id="phone_number" class="form-control form-control-lg" />
										<label style="display:none;" id="phoneNumber_lbl"   class="wpsamaforms-error" for="phone_number">This field is required.</label>

									</div>

								</div>
							  
							<div class="col-12">

									<div class="form-outline">
										<label style="display:none;" class="form-label" for="mobile_number">Mobile Number</label>
										<input placeholder="Mobile Number" type="tel" name="mobile_number" id="mobile_number" class="form-control form-control-lg" />
										<label style="display:none;" id="mobileNumber_lbl"   class="wpsamaforms-error" for="mobile_number">This field is required.</label>

									</div>

								</div>
							  

							<div class="col-12">

									<div class="form-outline">
										<label style="display:none;" class="form-label" for="address">Address </label>
										<textarea placeholder="Address" rows="5" name="address" style="font-size: 18px; background-color:#fafafa; color: #6c757d"></textarea>

									</div>

								</div>

								<div class="col-12">

									<div class="form-outline">
										<label style="display:none;" class="form-label" for="birth_date">Birth Date</label>
										<input placeholder="Birth date YYYY/MM/DD" type="text" name="birth_date" id="birth_date" class="form-control form-control-lg" />

									</div>

								</div>
							
							


<div class="col-12">
									<div class="form-outline">
									<label style="display:none;" class="form-label select-label">Choose subscription</label>
									<select class="select col-12" name="subscription_type" style="font-size: 18px; background-color:#fafafa; color: #6c757d">
										<option value="0">Select your subscription plan</option>
										<option value="2"> Annual Membership
</option>
										<option value="1">Life Membership
</option>
									</select>
									<label style="display:none;" id="memSub_lbl"   class="wpsamaforms-error" for="subscription_type">This field is required.</label>
									</div>
								</div>

							

							<div class="mt-4 pt-2">
								<input type="hidden" name="action" value="member_registration_form_submit">

								<input type="hidden" name="shortcode_id" value="<?php echo esc_attr( $shortcode_id ); ?>">
								<input name="nonce" type="hidden" value="<?php echo esc_attr( $nonce ); ?>">
								<input type="hidden" name="ok" value="1">
								<input id="regBtn" class="btn btn-primary btn-lg" type="button" value="Submit"  onclick="memmberRegistration(this.form);"/>
								<div class="loader" style="display:none;"></div>
							</div>
</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
</form>
</div>
