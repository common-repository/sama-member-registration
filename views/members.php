<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly

?>
<div class="fsms_div">
	<form action="admin.php?page=sama_members">
		<div>
			<input type="text" name="sama_search" value="<?php echo esc_attr( $sama_search ); ?>">
			<input type="hidden" name="page" value="sama_members">
			<input type="submit" name="sama_search_btn" value="Search">
		</div>
	</form>

	<table width="100%" style="">
		<caption>
			Member list | <a href="admin.php?page=sama_members&action=import-user">Import from user</a>

		</caption>
		<thead>
		<tr>
			<th><a href='admin.php?page=sama_members&dir=<?php echo esc_attr( $odir ); ?>&ob=id'>ID</a></th>


			<th><a href='admin.php?page=sama_members&dir=<?php echo esc_attr( $odir ); ?>&ob=first_name'>First name</a>
			</th>
			<th><a href='admin.php?page=sama_members&dir=<?php echo esc_attr( $odir ); ?>&ob=last_name'>Last name</a>
			</th>

			<th><a href='admin.php?page=sama_members&dir=<?php echo esc_attr( $odir ); ?>&ob=register_date'>Reg. date</a>
			</th>
			<th><a href='admin.php?page=sama_members&dir=<?php echo esc_attr( $odir ); ?>&ob=accept_date'>Accept date</a>
			</th>
			<th><a href='admin.php?page=sama_members&dir=<?php echo esc_attr( $odir ); ?>&ob=membership_type'>Type</a>
			</th>
			<th><a href='admin.php?page=sama_members&dir=<?php echo esc_attr( $odir ); ?>&ob=phone'>Phone</a></th>
			<th><a href='admin.php?page=sama_members&dir=<?php echo esc_attr( $odir ); ?>&ob=mobile'>Mobile</a></th>

			<th><a href='admin.php?page=sama_members&dir=<?php echo esc_attr( $odir ); ?>&ob=user_email'>Email</a></th>


			<th style="text-align:center;"><a
						href='admin.php?page=sama_members&dir=<?php echo esc_attr( $odir ); ?>&ob=gender_type'>Gender</a>
			</th>
			<th style="text-align:center;display: none;"><a href=''>Paid</a></th>
			<th style="text-align:center;"><a
						href='admin.php?page=sama_members&dir=<?php echo esc_attr( $odir ); ?>&ob=status'>Status</a></th>
			<th colspan="2" style="text-align:center; background-color: #d8d8dd">Option</th>

		</tr>
		</thead>
		<tbody>
		<?php
		/*
		echo "<pre>";
		print_r($members);*/
		?>

		<?php
		$_member = new SAMA_Member();
		foreach ( $members as $member ) :

			$paid_member = $_member->sama_is_paid_member( $member );
			/*
			echo "<pre>";
			print_r($paid_member);
			echo "</pre>";*/
			?>
			<tr>
				<td>
					<a href="admin.php?page=sama_members&action=view&id=<?php echo esc_attr( $member->id ); ?>"><?php echo esc_attr( $member->id ); ?></a>
				</td>

				<td>
					<?php echo esc_attr( $member->first_name ); ?>
				</td>
				<td>
					<?php echo esc_attr( $member->last_name ); ?>
				</td>
				<td>
					<?php echo esc_attr( date( 'Y-m-d', strtotime( $member->register_date ) ) ); ?>
				</td>
				<td>
					<?php
					if ( $member->status > 0 ) {
						echo esc_attr( date( 'Y-m-d', strtotime( $member->accept_date ) ) );
					}
					?>
				</td>
				<td>
					<?php
					$membership_type = esc_attr( $member->membership_type );
					if ( $membership_type == 2 ) {
						echo 'Annual';
					} else {
						echo 'Life time';
					}
					?>
				</td>
				<td>
					<a href="tel:<?php echo esc_attr( $member->phone ); ?>"><?php echo esc_attr( $member->phone ); ?></a>
				</td>
				<td>
					<a href="tel:<?php echo esc_attr( $member->mobile ); ?>"><?php echo esc_attr( $member->mobile ); ?></a>
				</td>

				<td>
					<?php echo esc_attr( $member->user_email ); ?>
				</td>
				<td>
					<?php
					$gender_type = esc_attr( $member->gender_type );
					if ( $gender_type == 1 ) {
						echo 'Male';
					} elseif ( $gender_type == 2 ) {
						echo 'Female';
					} else {
						echo 'Prefer Not to Answer';
					}
					?>

				</td>
				<td style="text-align:center;display: none;">
					<?php
					$is_payment_received_txt = '<span style="color:red;"  class="fa fa-times"></span>';
					if ( isset( $paid_member->is_payment_received ) ) {
						$is_payment_received = $paid_member->is_payment_received;
						if ( $is_payment_received == 1 ) {
							$is_payment_received_txt = '<span style="color:green;" span class="fa fa-check"></span>';
						}
					}
					echo esc_attr( $is_payment_received_txt );
					?>
				</td>
				<td style="text-align:center;" id="accept_reject_span_<?php echo esc_attr( $member->ID ); ?>">
					<?php
					$status_icon = '<span style="color:orange;" class="fa fa-question"></span>';
					if ( $member->status == 3 ) {
						$status_icon = '<span style="color:red;"  class="fa fa-times"></span>';

					} elseif ( esc_attr( $member->status ) == 1 ) {
						$status_icon = '<span style="color:green;" span class="fa fa-check"></span>';
					}
					$status_txt    = 'Submitted';
					$allow_accept  = 'disabled';
					$allow_reject  = 'disabled';
					$allow_suspend = 'disabled';
					if ( esc_attr( $member->status ) == - 1 ) {
						$status_txt    = 'Submitted';
						$allow_accept  = '';
						$allow_reject  = '';
						$allow_suspend = 'disabled';

					}
					if ( esc_attr( $member->status ) == 1 ) {
						$status_txt    = 'Accepted';
						$allow_accept  = 'disabled';
						$allow_reject  = 'disabled';
						$allow_suspend = 'disabled';
					}
					if ( esc_attr( $member->status ) == 3 ) {
						$status_txt    = 'Rejected';
						$allow_accept  = 'disabled';
						$allow_reject  = 'disabled';
						$allow_suspend = 'disabled';
					}
					if ( esc_attr( $member->status ) == 4 ) {
						$status_txt = 'Expired';
					}
					if ( esc_attr( $member->status ) == 5 ) {
						$status_txt    = 'Suspended';
						$allow_accept  = 'disabled';
						$allow_reject  = 'disabled';
						$allow_suspend = 'disabled';
					}
					if ( $member->status == 10 ) {
						$status_txt    = 'Member';
						$allow_accept  = 'disabled';
						$allow_reject  = 'disabled';
						$allow_suspend = '';

					}
					$allow_accept  = '';
					$allow_reject  = '';
					$allow_suspend = '';
					$allow_expired = '';

					echo esc_attr( $status_txt );


					?>

				</td>
				<td style="text-align:left; padding-left: 10px;">
					<?php
					if ( 1 == 2 ) {
						?>
						<span style="color:green;" class="cursorPointer"
							  id="accept_span_<?php echo esc_attr( $member->ID ); ?>"
							  onclick="acceptOrReject(<?php echo esc_attr( $member->id ); ?>,1,<?php echo esc_attr( $member->ID ); ?>)">Accept</span>
					<?php } ?>

				</td>
				<td>
					<?php
					if ( 1 == 2 ) {
						?>
						<span id="reject_span_<?php echo esc_attr( $member->ID ); ?>" style="color:red;"
							  class="cursorPointer"
							  onclick="acceptOrReject(<?php echo esc_attr( $member->id ); ?>,3,<?php echo esc_attr( $member->ID ); ?>)">Reject</span>
						<?php
					}
					?>
					<select id="member_status" name="member_status"
							onchange="acceptOrReject(<?php echo esc_attr( $member->id ); ?>,this.value,<?php echo esc_attr( $member->ID ); ?>);">
						<option value="-2">Please select</option>
						<option 
						<?php
						if ( esc_attr( $member->status ) == 1 ) {
							echo 'selected';
						}
						?>
						 value="1" <?php echo esc_attr( $allow_accept ); ?>>Accept
						</option>
						<option 
						<?php
						if ( esc_attr( $member->status ) == 3 ) {
							echo 'selected';
						}
						?>
						 value="3" <?php echo esc_attr( $allow_reject ); ?>>Reject
						</option>
						<option 
						<?php
						if ( esc_attr( $member->status ) == 5 ) {
							echo 'selected';
						}
						?>
						 value="5" <?php echo esc_attr( $allow_suspend ); ?>>Suspend
						</option>
						<option 
						<?php
						if ( esc_attr( $member->status ) == 4 ) {
							echo 'selected';
						}
						?>
						 value="4" <?php echo esc_attr( $allow_expired ); ?>>Expired
						</option>
					</select>
				</td>

			</tr>
		<?php endforeach; ?>

		<?php

		$leadPagi = '';
		$pageText = 'Page:';

		if ( $totalLeadsCount > $pageCount ) {
			$pageText = 'Page:';
		}
		$leadPagi .= '<tr>';
		$leadPagi .= "<td colspan='16' class='rgt'>&nbsp;" . $pageText;

		// echo $acPageCount;
		$acPageCount    = ceil( ( $totalLeadsCount / $pageCount ) );
		$totalPageCount = $acPageCount;
		$page           = (int) ( $P );


		if ( ( $page - 9 ) > 0 ) {
			$startpage = $page - 9;
		} else {
			$startpage = 1;
		}


		if ( ( $page + 9 ) < $totalPageCount ) {
			$endpage = $page + 9;
		} else {
			$endpage = $totalPageCount;
		}


		if ( $startpage > 1 ) {
			if ( $page == 1 ) {
				$leadPagi .= "<span class='cPointer'><a href='admin.php?page=sama_members&dir=" . $filters['dir'] . '&ob=' . $filters['getOb'] . "'>1</a></span>";
			} else {
				$leadPagi .= "<span class='cPointer'><a href='admin.php?page=sama_members&dir=" . $filters['dir'] . '&ob=' . $filters['getOb'] . "'>1</a></span>";
			}


			$leadPagi .= '...&nbsp;';
		}

		for ( $i = $startpage; $i <= $endpage; $i ++ ) {
			if ( $page == $i ) {
				$leadPagi .= "<span class='cPointer' style='font-weight: bold;' ><a href='admin.php?page=sama_members&pagei=" . $i . '&dir=' . $filters['dir'] . '&ob=' . $filters['getOb'] . "'>" . $i . '</a></span>';
			} else {
				$leadPagi .= "<span class='cPointer' ><a href='admin.php?page=sama_members&pagei=" . $i . '&dir=' . $filters['dir'] . '&ob=' . $filters['getOb'] . "'>" . $i . '</a></span>';
			}
		}
		if ( $endpage < $totalPageCount ) {
			$leadPagi .= '...&nbsp;';
			if ( $page == $totalPageCount ) {
				$leadPagi .= '&nbsp;' . $totalPageCount . '</span>';
			} else {
				$leadPagi .= "&nbsp;<span class='cPointer'><a href='admin.php?page=sama_members&pagei=" . $i . '&dir=' . $filters['dir'] . '&ob=' . $filters['getOb'] . "'>" . $totalPageCount . '</a></span>&nbsp;';
			}
		}

		$leadPagi .= '</td>';
		$leadPagi .= '</tr>';
		echo wp_kses_post( $leadPagi );
		?>
		</tbody>
	</table>
</div>

