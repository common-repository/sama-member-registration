<hr style="height:1px;">
<div class="sama-dashboard fsms_div">

	<div class="container">
	<div class="">
		<!--<div class="summary-txt" style="font-size:18px;font-weight: bold;">Summary</div>-->
		<div class="row summary-div"  >
			<!--<div class="col-2 summary-tile">
				<div class=" txt">NO of Members</div>
				<div class="  value"><?php /*echo (esc_attr( $summary['total_count'])); */ ?></div>

			</div>-->
			<div class="col-2 summary-tile" style="background-color: #fdb270; padding: 20px;margin: 5px;color: white;text-align: center;">
				<div class=" txt" style="font-size: 22px;">Approval Pending</div>
				<div class=" value" style="font-size: 68px;"><?php echo ( esc_attr( $summary['total_approval_pending'] ) ); ?></div>
			</div>
			<div class="col-2 summary-tile" style="background-color: #f78e31; padding: 20px;margin: 5px;color: white;text-align: center;">
				<div class=" txt" style="font-size: 22px;">Payment Pending</div>
				<div class=" value" style="font-size: 68px;"><?php echo ( esc_attr( $summary['total_payment_pending'] ) ); ?></div>
			</div>
			<div class="col-2 summary-tile" style="background-color:black;padding: 20px;margin: 5px;color: white;text-align: center;">
				<div class=" txt" style="font-size: 22px;">Rejected Members</div>
				<div class="value" style="font-size: 68px;"><?php echo ( esc_attr( $summary['total_rejected'] ) ); ?></div>
			</div>
			<div class="col-2 summary-tile" style="background-color: #40ccf4; padding: 20px;margin: 5px;color: white;text-align: center;">
				<div class=" txt" style="font-size: 22px;">Life Time Members</div>
				<div class=" value" style="font-size: 68px;"><?php echo ( esc_attr( $summary['total_life'] ) ); ?> </div>
			</div>
			<div class="col-2 summary-tile" style="background-color: #43d10b; padding: 20px;margin: 5px;color: white;text-align: center;">
				<div class=" txt" style="font-size: 22px;">Annual Members</div>
				<div class=" value" style="font-size: 68px;"><?php echo ( esc_attr( $summary['total_annual'] ) ); ?></div>
			</div>
			<div class="col-2 summary-tile" style="background-color: #f73156; padding: 20px;margin: 5px;color: white;text-align: center;">
				<div class=" txt" style="font-size: 22px;">Renewal and expiration </div>
				<div class=" value" style="font-size: 68px;"><?php echo ( esc_attr( $summary['total_renewal_and_expiration'] ) ); ?></div>
			</div>
			<div class="col-2 summary-tile" style="background-color: #f73156; padding: 20px;margin: 5px;color: white;text-align: center;">
				<div class=" txt" style="font-size: 22px;">Suspended members </div>
				<div class=" value" style="font-size: 68px;"><?php echo ( esc_attr( $summary['total_suspended'] ) ); ?></div>
			</div>
		</div>
				 
	</div>
</div>
<hr style="height:1px;">
	<div class="row">
		<div class="col-12">
			<?php require 'new-membership.php'; ?>	 
		</div>
		<div class="col-12">
			<?php require 'payment-pending-membership.php'; ?>	 
		</div>
	</div>
<hr style="height:1px;">
	<div class="row">	
		<div class="col-12">
			<?php require 'membership-expire-soon.php'; ?>	 
		</div>
		<div class="col-12">
			<?php require 'reject-membership.php'; ?>	 
		</div>
		<div class="col-12">
			<?php require 'suspend-membership.php'; ?>	 
		</div>
	</div>
</div>
