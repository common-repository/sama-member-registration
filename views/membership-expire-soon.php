<div class="col-12 expire-soon-summary summary-tbl">
 
	<table  style="width:100%; margin-top: 40px;">
		<caption style="padding-left: 20px; margin-bottom: 20px; font-size: 32px;background-color:#ffffff;color: #f73156;">
			Renewal and expiration
		</caption>
		<tr class="member-dboard" style="background: #f73156; color: white;">
			<th>Name</th>
			<th>Expire date</th>
			<th>Email</th>
			<th>Phone</th>
	</tr>
	<?php foreach ( $subscriptions as $subscription ) : ?>
		<tr>
			<td>
				<a href="admin.php?page=sama_members&action=view&id=<?php echo esc_attr( $subscription->id ); ?>"><?php echo esc_attr( $subscription->first_name ); ?> <?php echo esc_attr( $subscription->last_name ); ?></a>
			</td>
			 
			<td><?php echo nl2br( esc_attr( $subscription->subscription_date ) ); ?>
			</td>
			<td><?php echo nl2br( esc_attr( $subscription->user_email ) ); ?>
			</td>
			<td><?php echo nl2br( esc_attr( $subscription->mobile ) ); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
</div>
 
