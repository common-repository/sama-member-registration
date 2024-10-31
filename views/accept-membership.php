<div class="col-12 accept-summary summary-tbl">
	<table  style="width:100%;">
		<caption>
			Accept members
		</caption>
		<tr>
			<th>Name</th>			 
			<th>Registered date</th>
			<th>Accept date</th>
	</tr>
	<?php foreach ( $accept_members as $member ) : ?>
		<tr>
			<td>
				<a href="admin.php?page=sama_members&action=view&id=<?php echo esc_attr( $member->id ); ?>"><?php echo esc_attr( $member->first_name ); ?> <?php echo esc_attr( $member->last_name ); ?></a>
			</td>
			<td><?php echo nl2br( esc_attr( $member->register_date ) ); ?>
			</td>
			<td><?php echo nl2br( esc_attr( $member->accept_date ) ); ?>
			</td>
			 
		</tr>
	<?php endforeach; ?>
	</table>
</div>
