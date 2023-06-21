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
			Customers 
			<input type="button" value="Send Broadcast" style="float: right; display: none" class="btn btn-primary" id="sendBroadcastButton" data-bs-toggle="modal" data-bs-target="#BCModal">
			<a href="add_customer.php" class="btn btn-primary" style="float: right; margin-right: 5px">Add Customer</a>
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
		<table class="table table-flush">
			<thead class="thead-light">
				<tr>
					<th></th>
					<th>#</th>
					<th>First Name</th>
					<th>Last Name</th>
					<th>Cell</th>
					<th>City</th>
					<th>State</th>
					<th>Zip code</th>
					<th>Date</th>
					<th>Manage</th>
				</tr>
			</thead>
			<tbody>
			<?php
				$sql = "select * from customers where user_id='".$_SESSION['user_id']."' order by id desc";
				if(is_numeric($_GET['page']))
					$pageNum = $_GET['page'];
				else
					$pageNum = 1;
				$max_records_per_page = 20;
				$pageLink 		= "customers.php?";
				$pages 		= generatePaging($sql,$pageLink,$pageNum,$max_records_per_page);
				$limit 		= $pages['limit'];
				$sql 	   .= $limit;
				if($pageNum==1)
					$countPaging=1;
				else
					$countPaging=(($pageNum*$max_records_per_page)-$max_records_per_page)+1;

				if($_SESSION['TOTAL_RECORDS'] <= $max_records_per_page){
					$maxLimit = $_SESSION['TOTAL_RECORDS'];	
				}else{
					$maxLimit = (((int)$countPaging+(int)$max_records_per_page)-1);
				}
				if($maxLimit >= $_SESSION['TOTAL_RECORDS']){
					$maxLimit = $_SESSION['TOTAL_RECORDS'];	
				}
				$res = mysqli_query($link,$sql);
				if(mysqli_num_rows($res)){
					$index = $countPaging;
					while($row = mysqli_fetch_assoc($res)){
			?>
						<tr>
							<td>
								<div class="form-check">
									<input class="customers form-check-input" name="customers" type="checkbox" value="<?php echo $row["cell"];?>" onChange="getSelectedCustomers(this,customers)">
								</div>
							</td>
							<td><?php echo $index++ ?></td>
							<td><?php echo $row['first_name']?></td>
							<td><?php echo $row['last_name']?></td>
							<td><?php echo $row["cell"];?></td>
							<td><?php echo $row["city"];?></td>
							<td><?php echo $row["state"];?></td>
							<td><?php echo $row["zipcode"];?></td>
							<td><?php echo $row["created_date"];?></td>
							<td style="text-align: center">
								<a href="edit_customer.php?id=<?php echo $row['id']?>" style="margin-right: 10px;"> 
									<i class="fa fa-edit" style="color: green"></i>
								</a>
								<i class="fa fa-trash" style="color: red;cursor: pointer" onClick="deleteCustomer('<?php echo $row['id']?>')"></i>
							</td>
						</tr>
			<?php
					}
				}else{
			?>	
					<tr>
						<td colspan="9">No customer found.</td>
					</tr>
			<?php
				}
			?>	
				<tr><td colspan="9" style="text-align: -webkit-right"><?php echo $pages['pagingString'];?></td></tr>
			</tbody>
		</table>
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
						<input type="hidden" name="customers" id="customers" value="">
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
	function deleteCustomer(customerID){
		if(confirm("Are you sure you want to delete this customer?")){
			$(".overlay").show();
			$.post("server.php",{"cmd":"delete_custsomer",customerID:customerID},function(r){
				window.location = 'customers.php';
			});
		}
	}
	function makeSure(obj){
		if(confirm("Are you sure you want to send broadcast to this group?")){
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