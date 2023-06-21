<?php include_once("header.php"); ?>
<style>
	table > thead > tr > th{
		text-align: center
	}
	table > tbody > tr > td{
		text-align: center
	}
</style>
<script>
	var customers = [];
</script>
	<div class="py-2">
		<h3>
			Bookings
			<!-- <input type="button" value="Send Broadcast" style="float: right; display: none;margin-left: 8px;" class="btn btn-primary" id="sendBroadcastButton" data-bs-toggle="modal" data-bs-target="#BCModal">
			
			<a href="server.php?cmd=download_contact_sample_csv_file" style="float: right; margin-left: 5px;" class="btn btn-primary">Download Sample CSV</a>
			
			<input type="button" value="Upload Contacts" style="float: right;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadDCSub"> -->
		</h3>
	</div>

	<?php if($_SESSION['message']!='')echo $_SESSION['message'];unset($_SESSION['message']); ?>

	

	<div class="card">
		<div class="table-responsive">
			<table class="table table-flush">
				<thead class="thead-light">
					<tr>
						<th></th>
						<th>Booking #</th>
						<th>Email</th>
						<th>Name</th>
						<th>Phone</th>
						<th>Address</th>
						<th>City</th>
						<th>Service</th>
						<th>Booking Date</th>
						<th>Created</th>
						
					</tr>
				</thead>
				<tbody>
				<?php
					
					$res = mysqli_query($link,"SELECT * FROM `bookings` WHERE user_id=".$_SESSION['user_id']);
					if(mysqli_num_rows($res)){
						// $index = $countPaging;
						while($row = mysqli_fetch_assoc($res)){
				?>
							<tr>
								<td style="text-align: center"><input type="checkbox" name="contact" value="<?php echo $row["phone"];?>" onChange="getSelectedCustomers(this,customers)"></td>
								<td><?php echo $row['number']?></td>
								<td><?php echo $row["email"];?></td>
								<td><?php echo $row["firstName"];?></td>
								<td><?php echo $row["phone"];?></td>
								<td><?php echo $row["addressLine"];?></td>
								<td><?php echo $row["city"];?></td>
								<td><?php echo $row["service"];?></td>
								<td><?php echo $row["time"];?></td>
								<td><?php echo $row["created_at"];?></td>
								
							</tr>
				<?php
					
					} }
				?>	
					<tr><td colspan="7" style="text-align: -webkit-right"></td></tr>
				</tbody>
			</table>
		</div>
	</div>
	
	
<?php include_once("footer.php"); ?>
