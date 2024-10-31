<div class="col-12 new-member-summary summary-tbl">
	<table  style="width:100%;">
		<caption style="padding-left: 20px; margin-bottom: 20px; font-size: 32px;background-color:#ffffff;color: #fdb270;">
			Pending members list - Not accepted
		</caption>
		<tr class="member-dboard" style="background:#fdb270; color: white;">
			<th>Name</th>			 
			<th>Applyed date</th>
			<th>Email</th>
			<th>Phon no</th>
	</tr>
	<?php foreach ( $new_members as $member ) : ?>
		<tr>
			<td>
				<a href="admin.php?page=sama_members&action=view&id=<?php echo esc_attr( $member->id ); ?>"><?php echo esc_attr( $member->first_name ); ?> <?php echo esc_attr( $member->last_name ); ?></a>
			</td>
			<td><?php echo nl2br( esc_attr( $member->register_date ) ); ?>
			</td>
			
			<td><?php echo nl2br( esc_attr( $member->user_email ) ); ?>
			</td>
			<td><?php echo nl2br( esc_attr( $member->mobile ) ); ?>
			</td>
			 
		</tr>
	<?php endforeach; ?>
	</table>
</div>
