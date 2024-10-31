<h3><?php esc_html_e( 'Personal Information', 'crf' ); ?></h3>

	<table class="form-table">
		<tr>
			<th><label for="gender_type">Gender</label></th>
			<td>
				<select class="" name="gender_type" style="">
						<option value="0">Gender</option>
						<option 
						<?php
						if ( $gender_type == 1 ) {
							echo 'selected'; }
						?>
						 value="1">Male</option>
						<option 
						<?php
						if ( $gender_type == 2 ) {
							echo 'selected'; }
						?>
						 value="2">Female</option>
						<option 
						<?php
						if ( $gender_type == 3 ) {
							echo 'selected'; }
						?>
						 value="3">Other</option>
					</select>
			</td>
		</tr>
		<tr>
		
		<tr>
		<th><label for="phone">Phone</label></th>
			<td>
				<input type="text"			       
				   id="phone"
				   name="phone"
				   value="<?php echo esc_attr( $phone ); ?>"
				   class="form-outline regular-text"
				/>
			</td>
		</tr>
		<tr>
		<th><label for="mobile">Mobile</label></th>
			<td>
				<input type="text"			       
				   id="mobile"
				   name="mobile"
				   value="<?php echo esc_attr( $mobile ); ?>"
				   class="regular-text"
				/>
			</td>
		</tr>
		<th><label for="address">Address</label></th>
			<td>
				<input type="text"			       
				   id="address"
				   name="address"
				   value="<?php echo esc_attr( $address ); ?>"
				   class="regular-text"
				/>
			</td>
		</tr>

		<tr>
			<th><label for="year_of_birth">Birth date</label></th>
			<td>
				<input type="text"			       
				   id="birth_date"
				   name="birth_date"
				   value="<?php echo esc_attr( $birth_date ); ?>"
				   class="regular-text"
				/>
			</td>
		</tr>
		<tr>
	</table>
