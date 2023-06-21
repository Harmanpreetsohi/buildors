<?php include_once("header.php"); ?>
<style>
	table > thead > tr > th{
		text-align: center
	}
	table > tbody > tr > td{
		text-align: center
	}
</style>
	<div class="py-2">
		<h3>Appointments</h3>
	</div>

	<div class="card">
		<div class="table-responsive py-4">
			<table class="table table-flush" id="datatable">
				<thead class="thead-light">
					<tr>
						<th>Sr#</th>
						<th>Phone number</th>
						<th>Online booking</th>
						<th>Virtual online</th>
						<th>Onsite</th>
						<th>Booking date</th>
					</tr>
				</thead>
				<tbody>
				<?php
					$sql = "select * from appointments order by id desc";
					$res = mysqli_query($link,$sql);
					if(mysqli_num_rows($res)){
						$index = 1;
						while($row = mysqli_fetch_assoc($res)){
				?>
							<tr>
								<td><?php echo $index++ ?></td>
								<td><?php echo $row['phone_number']?></td>
								<td>
									<?php 
										if($row['book_online']=='1'){
											echo '<span class="badge badge-sm bg-success badge-pill notification-count">Yes</span>';
										}else{
											echo '<span class="badge badge-sm bg-danger badge-pill notification-count">No</span>';
										}
									?>
								</td>
								<td>
									<?php 
										if($row['virtual_online']=='1'){
											echo '<span class="badge badge-sm bg-success badge-pill notification-count">yes</span>';
										}else{
											echo '<span class="badge badge-sm bg-danger badge-pill notification-count">No</span>';
										}
									?>
								</td>
								<td>
									<?php 
										if($row['onsite']=='1'){
											echo '<span class="badge badge-sm bg-success badge-pill notification-count">yes</span>';
										}else{
											echo '<span class="badge badge-sm bg-danger badge-pill notification-count">No</span>';
										}
									?>
								</td>
								<td><?php echo $row["created_date"];?></td>
							</tr>
				<?php
						}
					}else{
				?>	
						<tr>
							<td colspan="6">No appointment created yet.</td>
						</tr>
				<?php
					}
				?>	
				</tbody>
			</table>
		</div>
	</div>
<?php include_once("footer.php"); ?>