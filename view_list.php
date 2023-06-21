<?php
	include_once("header.php");
	$listID = $_REQUEST['lid'];
	$listInfo = getListInfo($listID);
?>
<style>
	table > thead > tr > th{
		text-align: center
	}
	table > tbody > tr > td{
		text-align: center
	}
</style>
	<div class="py-2">
		<h3>
			<?php echo $listInfo["title"]?> Details
			<a href="bulksms.php" class="btn btn-primary" style="float: right">Back</a>
		</h3>
	</div>

	<?php if($_SESSION['message']!='')echo $_SESSION['message'];unset($_SESSION['message']); ?>

	<div class="card">
		<div class="table-responsive py-4">
			<table class="table table-flush" id="datatable">
				<thead class="thead-light">
					<tr>
						<th>Sr#</th>
						<th>First name</th>
						<th>Last name</th>
						<th>Phone number</th>
						<!--<th>Status</th>-->
						<th>Manage</th>
					</tr>
				</thead>
				<tbody>
				<?php
					//echo $sql = "select c.first_name, c.last_name, c.cell, c.status from customers c, lists l, list_assignment la where la.list_id='".$listID."' and la.customer_id=c.id";
					$sql = "select customer_id from list_assignment where list_id='".$listID."'";
					$res = mysqli_query($link,$sql);
					if(mysqli_num_rows($res)){
						$index = 1;
						while($row = mysqli_fetch_assoc($res)){
							//$sel = "select id,first_name, last_name, cell, status from customers where id='".$row['customer_id']."'";
							$sel = "select * from contacts where id='".$row['customer_id']."'";
							$exe = mysqli_query($link,$sel);
							while($rec = mysqli_fetch_assoc($exe)){
				?>
								<tr>
								<td><?php echo $index++;?></td>
								<td><?php echo $rec['first_name']?></td>
								<td><?php echo $rec['last_name']?></td>
								<td><?php echo $rec['phone']?></td>
								<!--	
								<td>
									<?php 
										if($rec['status'] == '1'){
											echo '<span class="badge bg-success">Active</span>';
										}else{
											echo '<span class="badge bg-danger">Opt-out</span>';
										}
									?>
								</td>
								-->
								<td><i class="fa fa-trash" style="color: red; cursor: pointer" title="Remove contact" onClick="deleteCustomer('<?php echo $rec['id']?>','<?php echo $listID?>')"></i></td>
							</tr>
				<?php
							}
						}
					}else{
				?>	
							<tr>
								<td colspan="5">No customers found in this list.</td>
							</tr>
				<?php
					}
				?>	
				</tbody>
			</table>
		</div>		
	</div>
<?php include_once("footer.php");?>
<script>
	function deleteCustomer(contactID,listID){
		if(confirm("Are you sure you want to delete this contact?")){
			$(".overlay").show();
			$.post("server.php",{"cmd":"delete_conatact",contactID:contactID,listID:listID},function(){
				window.location = 'view_list.php?lid=<?php echo $listID?>';
			});
		}
	}
</script>