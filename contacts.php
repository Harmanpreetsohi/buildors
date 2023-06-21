<?php include_once("header.php"); 
    $user_id = $_SESSION['user_id'];
    // 	$role_id = $_SESSION['role_id'];
	$user_type = $_SESSION['user_type'];
	
	$user_qry = mysqli_query($link,"select role_id from users where id='$user_id'");
	$user_data = mysqli_fetch_assoc($user_qry);
	$role_id = $user_data['role_id'];
	
	$role_qry = mysqli_query($link,"select * from role_master where role_id='$role_id'");
	$role_data = mysqli_fetch_assoc($role_qry);
	
	$permission_qry = mysqli_query($link,"select * from permission where inner_user_id='$user_id' AND module_name='contacts' AND view_permission='1'");
	
	if(mysqli_num_rows($permission_qry)==1 || $role_data['role']=="Full Admin" || $user_type==1){
?>
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
			Contacts
			<input type="button" value="Send Broadcast" style="float: right; display: none;margin-left: 8px;" class="btn btn-primary" id="sendBroadcastButton" data-bs-toggle="modal" data-bs-target="#BCModal">
			<input type="button" value="Add New Contact" style="float: right;margin-left: 8px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addContactModal" />
			<a href="server.php?cmd=download_contact_sample_csv_file" style="float: right; margin-left: 5px;" class="btn btn-primary">Download Sample CSV</a>
			<input type="button" value="Upload Contacts" style="float: right;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadDCSub">
		</h3>
	</div>
	<?php if(isset($_SESSION['message']))echo $_SESSION['message'];unset($_SESSION['message']); ?>
	<div class="row" style="padding: 10px 0px">
		<div class="col-md-7"></div>
		<div class="col-md-2">
			<a href="contacts.php" class="btn btn-primary" style="float: right">Clear Filter</a>
		</div>
		<div class="col-md-3">
			<div class="input-group">
				<input type="text" id="searchKeyword" class="form-control" placeholder="Search" aria-label="Search" value="<?php  isset($_REQUEST['search']) ? $_REQUEST['search'] : '' ?>" >
				<span class="input-group-text" id="basic-addon2" style="cursor: pointer" onClick="searchKeyword()">
					<svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg> 
				</span>
			</div>
		</div>
		<div class="col-md-2 d-none">
			<div class="form-group">
				<select name="filter" class="form-control" onChange="filterContacts(this)">
					<option value="">- All Contacts -</option>
					<option <?php if($_REQUEST['filter']=='staff')echo 'selected="selected"';?> value="staff">Staff</option>
					<option <?php if($_REQUEST['filter']=='sub-con')echo 'selected="selected"';?> value="sub-con">DC Sub-Con</option>
					<option <?php if($_REQUEST['filter']=='customer')echo 'selected="selected"';?> value="customer">Customers</option>
				</select>
			</div>
		</div>
	</div>
	<div class="card">
		<div class="table-responsive">
			<table class="table table-flush">
				<thead class="thead-light">
					<tr>
						<th></th>
						<th>First name</th>
						<th>Last Name</th>
						<th>Company</th>
						<th>Phone</th>
						<th>Designation/Division</th>
						<th>Type</th>
						<th>Email</th>
						<th>Street address</th>
						<th>City</th>
						<th>State</th>
						<th>Zip Code</th>
						<th>KCG state</th>
						<th>Rating</th>
						<th>Created date</th>
						<th>Manage</th>
					</tr>
				</thead>
				<tbody>
				<?php
					if( isset($_REQUEST['filter']) && trim($_REQUEST['filter'])!=''){
						$sql = "select * from contacts where user_id='".$_SESSION['company_id']."' and type='".$_REQUEST['filter']."' order by id desc";
					}else if(isset($_REQUEST['search']) && trim($_REQUEST['search'])!=''){
						$sql = "select * from contacts where user_id='".$_SESSION['company_id']."' and 
									(first_name like '%".$_REQUEST['search']."%') or
									(last_name like '%".$_REQUEST['search']."%') or
									(company_name like '%".$_REQUEST['search']."%') or
									(phone like '%".$_REQUEST['search']."%') or
									(designation like '%".$_REQUEST['search']."%') or
									(email like '%".$_REQUEST['search']."%') or
									(street_address like '%".$_REQUEST['search']."%') or
									(city like '%".$_REQUEST['search']."%') or
									(state like '%".$_REQUEST['search']."%') or
									(zipcode like '%".$_REQUEST['search']."%') or
									(rating like '%".$_REQUEST['search']."%') order by id desc";
					}else{
						$sql = "select * from contacts where user_id='".$_SESSION['company_id']."' order by id desc";
					}
					if(isset($_GET['page']) && is_numeric($_GET['page']))
						$pageNum = $_GET['page'];
					else
						$pageNum = 1;
					$max_records_per_page = 20;
					$pageLink 		= "contacts.php?";
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
								<td style="text-align: center"><input type="checkbox" name="contact" value="<?php echo $row["phone"];?>" onChange="getSelectedCustomers(this,customers)"></td>
								<td><?php echo $row['first_name']?></td>
								<td><?php echo $row["last_name"];?></td>
								<td><?php echo $row["company_name"];?></td>
								<td><?php echo $row["phone"];?></td>
								<td><?php echo $row["designation"];?></td>
								<td><?php echo $row["type"];?></td>
								<td><?php echo $row["email"];?></td>
								<td><?php echo $row["street_address"];?></td>
								<td><?php echo $row["city"];?></td>
								<td><?php echo $row["state"];?></td>
								<td><?php echo $row["zipcode"];?></td>
								<td><?php echo $row["kcg_state"];?></td>
								<td><?php echo $row["rating"];?></td>
								<td><?php echo $row["created_date"];?></td>
								<td style="text-align: center">
									<a href="#"><i class="fa fa-edit" style="color: green"></i></a>&nbsp;&nbsp;
									<i class="fa fa-trash" style="cursor: pointer;color: red" onClick="confirDelete('<?php echo $row['id']?>')"></i>
								</td>
							</tr>
				<?php
						}
					}
					else{
				?>	
						<tr>
							<td colspan="6">No contact found.</td>
						</tr>
				<?php
					}
				?>	
					<tr><td colspan="7" style="text-align: -webkit-right"><?php echo $pages['pagingString'];?></td></tr>
				</tbody>
			</table>
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
						<label style="font-size: 12px;">Name = %name%</label>
						<textarea name="broadcast_sms" class="form-control" style="height: 150px;" placeholder="Write your message here" required></textarea><br>
						<label>Media</label>
						<input type="file" name="broadcast_media">
					</div>
					<div class="modal-footer">
						<input type="hidden" name="recipients" id="recipients">
						<input type="hidden" name="cmd" value="send_broadcast_to_the_customers">
						<input type="hidden" name="customers" id="customers" value="">
						<button type="submit" class="btn btn-secondary">Send now</button>
						<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="modal fade" id="uploadDCSub" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<form action="server.php" method="post" enctype="multipart/form-data">
					<div class="modal-header">
						<h2 class="h6 modal-title">Upload Contacts</h2>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
					    <?php 
            			    $permission_qry = mysqli_query($link,"select * from permission where inner_user_id='$user_id' AND module_name='contacts' AND insert_permission='1'");
                        	if(mysqli_num_rows($permission_qry)==1 || $role_data['role']=="Full Admin" || $user_type==1){
            			?>
						<label>Select CSV File</label>
						<input type="file" name="contacts" required>
					</div>
					<div class="modal-footer">
						<input type="hidden" name="cmd" value="upload_contacts">
						<input type="submit" value="Upload" class="btn btn-primary">
						<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>
						<?php }else{ echo 'Access denied'; } ?>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="modal fade" id="addContactModal" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<form action="server.php" method="post">
					<div class="modal-header">
						<h2 class="h6 modal-title">Add New Contact</h2>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
					    <?php 
                			    $permission_qry = mysqli_query($link,"select * from permission where inner_user_id='$user_id' AND module_name='contacts' AND insert_permission='1'");
                            	if(mysqli_num_rows($permission_qry)==1 || $role_data['role']=="Full Admin" || $user_type==1){
                		?>
						<label class="mb-0 mt-2">First Name</label>
						<input type="text" name="contact_fname" class="form-control" value="" placeholder="Contact First Name">
						<label class="mb-0 mt-2">Last Name</label>
						<input type="text" name="contact_lname" class="form-control" value="" placeholder="Contact Last Name">
						<label class="mb-0 mt-2">Email</label>
						<input type="text" name="contact_email" class="form-control" value="" placeholder="Email">
						<label class="mb-0 mt-2">Company Name</label>
						<input type="text" name="contact_company" class="form-control" value="" placeholder="Company Name">
						<label class="mb-0 mt-2">Phone Number</label>
						<input type="text" name="contact_phonenumber" class="form-control" value="" placeholder="Phone Number">
						<label class="mb-0 mt-2">Designation/Division</label>
						<input type="text" name="contact_designation" class="form-control" value="" placeholder="Designation/Division">
						<label class="mb-0 mt-2">Type</label>
						<input type="text" name="contact_type" class="form-control" value="" placeholder="Type">
						<label class="mb-0 mt-2">Address</label>
						<input type="text" name="contact_address" class="form-control" value="" placeholder="Address">
						<label class="mb-0 mt-2">City</label>
						<input type="text" name="contact_city" class="form-control" value="" placeholder="City">
						<label class="mb-0 mt-2">State</label>
						<input type="text" name="contact_state" class="form-control" value="" placeholder="State">
						<label class="mb-0 mt-2">Zip Code</label>
						<input type="text" name="contact_zipcode" class="form-control" value="" placeholder="Zip Code">
					</div>
					<div class="modal-footer">
						<input type="hidden" name="cmd" value="add_contact">
						<button type="submit" class="btn btn-secondary">Save</button>
						<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>
						<?php }else{ echo 'Access denied'; } ?>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php
	}else{
	    
	    echo "<h3 style='height: 250px;' class='mt-4'>Access Denied</h3>";
	}
?>	
<?php include_once("footer.php"); ?>
<script>
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
	function searchKeyword(){
		var searchKeyword = $("#searchKeyword").val();
		if($.trim(searchKeyword)=='')
			window.location = 'contacts.php';
		else
			window.location = 'contacts.php?search='+searchKeyword;
	}
	function filterContacts(obj){
		var type = $(obj).val();
		if($.trim(type)=='')
			window.location = 'contacts.php';
		else
			window.location = 'contacts.php?filter='+type;
	}
	function confirDelete(staffID){
		if(confirm("Are you sure you wanto to delete this contact?")){
			$(".overlay").show();
			$.post("server.php",{"cmd":"delete_staff",staffID:staffID},function(){
				window.location = 'contacts.php';
			});
		}
	}
</script>