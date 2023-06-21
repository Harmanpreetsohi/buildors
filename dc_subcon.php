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
			DC Sub-Contractors 
			<input type="button" value="Upload CSV" style="float: right;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadDCSub">
			
			<a href="server.php?cmd=download_subcon_sample_csv_file" style="float: right; margin-right: 5px;" class="btn btn-primary">Download Sample CSV</a>
			
			<input type="button" value="Send Broadcast" style="float: right; display: none; margin-right: 5px;" class="btn btn-primary" id="sendBroadcastButton" data-bs-toggle="modal" data-bs-target="#BCModal">
		</h3>
	</div>

	<?php 
		$sendable = true;
		if($_SESSION['message']!='')echo $_SESSION['message'];unset($_SESSION['message']); 
		if(count($twilioNumbers) < 1){
			echo '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> You have no twilio number to send message.</div>';
			$sendable = false;
		}
	?>

	<div class="card">
		<div class="table-responsive py-4">
			<table class="table table-flush" id="datatable">
				<thead class="thead-light">
					<tr>
						<th></th>
						<th>#</th>
						<th>Company</th>
						<th>Division</th>
						<th>Primary Contact</th>
						<th>Cell</th>
						<th>Phone</th>
						<th>Email</th>
						<th>City</th>
						<th>Street Address</th>
						<th>Zip</th>
						<th>KCG State</th>
						<th>Rating</th>
						<th>State</th>
					</tr>
				</thead>
				<tbody>
				<?php
					$sql = "select * from dc_subcon where user_id='".$_SESSION['user_id']."' order by id desc";
					$res = mysqli_query($link,$sql);
					if(mysqli_num_rows($res)){
						$index = 1;
						while($row = mysqli_fetch_assoc($res)){
				?>
							<tr>
								<td>
									<div class="form-check">
                                        <input class="customers form-check-input" name="customers" type="checkbox" value="<?php echo $row["cell"];?>" onChange="getSelectedCustomers(this,customers)">
                                    </div>
								</td>
								<td><?php echo $index++ ?></td>
								<td><?php echo $row['company']?></td>
								<td><?php echo $row['division']?></td>
								<td><?php echo $row["primary_contact"];?></td>
								<td><?php echo $row["cell"];?></td>
								<td><?php echo $row["phone"];?></td>
								<td><?php echo $row["email"];?></td>
								<td><?php echo $row["city"];?></td>
								<td><?php echo $row["street_address"];?></td>
								<td><?php echo $row["zipcode"];?></td>
								<td><?php echo $row["kcg_state"];?></td>
								<td><?php echo $row["rating"];?></td>
								<td><?php echo $row["state"];?></td>
							</tr>
				<?php
						}
					}else{
				?>	
						<tr>
							<td colspan="6">No dc subcontractor found.</td>
						</tr>
				<?php
					}
				?>	
				</tbody>
			</table>
		</div>
	</div>
	<div class="modal fade" id="uploadDCSub" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<form action="server.php" method="post" enctype="multipart/form-data">
					<div class="modal-header">
						<h2 class="h6 modal-title">Upload DC Sub-contractor</h2>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<label>Select CSV File</label>
						<input type="file" name="dc_subcon" required>
					</div>
					<div class="modal-footer">
						<input type="hidden" name="cmd" value="upload_dc_subcontractor">
						<input type="submit" value="Upload" class="btn btn-primary">
						<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="modal fade" id="BCModal" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<form action="server.php" method="post" onSubmit="return makeSure(this)" enctype="multipart/form-data">
					<div class="modal-header">
						<h2 class="h6 modal-title">Send Broadcast</h2>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<label>Message</label>
						<label style="font-size: 12px;">Name = %name% ,Address = %address%</label>
						<textarea name="broadcast_sms" class="form-control" style="height: 150px;" placeholder="Write your message here" required></textarea><br>
						<label>Media</label>
						<input type="file" name="broadcast_media">
					</div>
					<div class="modal-footer">
						<input type="hidden" name="recipients" id="recipients">
						<input type="hidden" name="cmd" value="send_broadcast_to_the_customers">
						<input type="hidden" name="coming_from" value="dc_subcontractors">
						<?php if($sendable){ ?>
						<button type="submit" class="btn btn-secondary">Send now</button>
						<?php } ?>
						<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php include_once("footer.php"); ?>
<script>
	function makeSure(obj){
		if(confirm("Are you sure you want to send broadcast now?")){
			$(".overlay").show();
			return true;
		}else{
			return false;
		}
	}
	function getSelectedCustomers(obj,customers){
		var contact = $(obj).val();
		if($(obj).is(":checked") == true){
			customers.push(contact);
		}else{
			var found = customers.indexOf(contact);
			if(found > -1){
				customers.splice(found, 1);
			}
		}
		var totalRecipients =  customers.length;
		if(totalRecipients > 0){
			$("#sendBroadcastButton").show();	
		}else{
			$("#sendBroadcastButton").hide();
		}
		var json = JSON.stringify(customers);
		$("#recipients").val(json);
	}
</script>