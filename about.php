<?php
	include_once("header.php");

   	$user_id = $_SESSION['user_id'];
// 	$role_id = $_SESSION['role_id'];
	$user_type = $_SESSION['user_type'];
	
	
	$user_qry = mysqli_query($link,"select role_id from users where id='$user_id'");
	$user_data = mysqli_fetch_assoc($user_qry);
	$role_id = $user_data['role_id'];
	
	
	$role_qry = mysqli_query($link,"select * from role_master where role_id='$role_id'");
	$role_data = mysqli_fetch_assoc($role_qry);
	
	
	
	$permission_qry = mysqli_query($link,"select * from permission where inner_user_id='$user_id' AND module_name='about' AND view_permission='1'");
	

	
	if(mysqli_num_rows($permission_qry)==1 || $role_data['role']=="Full Admin" || $user_type==1){
?>
	<div class="py-2">
		<h3>About <?php echo $_SESSION['business_name']?></h3>
	</div>

	<?php if($_SESSION['message']!='')echo $_SESSION['message'];unset($_SESSION['message']);?>

	<div class="card">
		<div class="table-responsive py-4">
			<table class="table table-flush" id="datatable">
				<thead class="thead-light">
					<tr>
						<th>#</th>
						<th>Phone Number</th>
						<th>Used for IVR</th>
						<th>Manage</th>
					</tr>
				</thead>
				<tbody>
				<?php
					$sql = "select * from twilio_numbers where user_id='".$_SESSION['company_id']."' order by id desc";
					$res = mysqli_query($link,$sql);
					if(mysqli_num_rows($res)){
						$index = 1;
						while($row = mysqli_fetch_assoc($res)){
				?>
							<tr>
								<td><?php echo $index++; ?></td>
								<td><?php echo $row['phone_number'];?></td>
								<td>
									<?php 
										if($row['ivr_number']=='1'){
											echo "yes";
										}else{
											echo "No";
										}
									?>
								</td>
								<td>
									<i class="fa fa-plus" title="Use for ivr" style="color: green;cursor: pointer" onClick="assignNumberToIvr(this,'<?php echo $row['sid']?>','<?php echo $row['phone_number']?>')"></i>
								</td>
							</tr>
				<?php
						}
					}
				?>	
				</tbody>
			</table>
		</div>
	</div>
	<?php
	}else{
	    
	    echo "<h3 style='height: 250px;' class='mt-4'>Access Denied</h3>";
	}
?>
<?php include_once("footer.php");?>
<script>
	function assignNumberToIvr(obj,sid,number){
		if(confirm("Are you sure you want to use this "+number+" number for ivr?")){
			$.post("server.php",{"cmd":"assign_phone_number_to_ivr",sid:sid,number:number},function(r){
				window.location = 'about.php';
			});
		}
	}
</script>