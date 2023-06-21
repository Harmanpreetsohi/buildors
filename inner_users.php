<?php
	include_once("header.php");
?>
	<div class="py-2">
		<h3>
		  Internal Users
			<a href="add_inner_user.php" class="btn btn-primary" style="float: right">Add Internal User</a>
		</h3>
	</div>
    <?php if($_SESSION['message']!='')echo $_SESSION['message'];unset($_SESSION['message']); ?>
	<div class="card">
		<div class="table-responsive py-4">
			<table class="table table-flush" id="datatable">
				<thead class="thead-light">
					<tr>
						<th>#</th>
						<th>Full Name</th>
						<th>Role</th>
						<th>Company Name</th>
						<th>Email</th>
						<th>Phone</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
				<?php
				    //$company_id = $_SESSION['user_id'];
				  
                    $internal_users = mysqli_query($link,"select * from users where business_name IS NULL");

                    if(mysqli_num_rows($internal_users)>0){
                        $i = 1;
                        while($row = mysqli_fetch_assoc($internal_users) ){
                        
                            $role_id = $row['role_id'];
                            
                            $getrole = mysqli_query($link,"select * from role_master where role_id='$role_id'");
                            $roledata = mysqli_fetch_assoc($getrole);
                      
                            $getcompany = mysqli_query($link,"select * from users where id=".$row['company_id']);
                           
                            $get_company = mysqli_fetch_assoc($getcompany);
                           
                            
                ?>
                <tr>
                <td><?= $i++ ?></td>
                <td><?= $row['first_name']." ".$row['last_name'] ?></td>
                <td><?= $roledata['role'] ?></td>
                <td><?= $get_company['business_name'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['phone_number'] ?></td>
                <td>
                    <a href="edit_internal_user.php?id=<?= $row['id'] ?>" class="btn btn-primary">Edit</a>
                    <a href="server.php?cmd=delete_internal_user&id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this record?')" class="btn btn-danger">Delete</a>
                    <a href="javascript:void(0)" data-bs-toggle="modal" onclick="get_permission(<?= $row['id'] ?>)" data-id="<?= $row['id'] ?>" data-bs-target="#permissions_Modal" class="btn btn-info">Permissions</a>
                </td>
                </tr>
                <?php }
                    }else{ ?>
					<tr>
						<td colspan="6" align="center">Data Not Found</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
	
	
	 <div class="modal fade" id="permissions_Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Permissions</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <input type="hidden" name="u_id" id="u_id">
              <div class="all_module_permissions">
                  
              </div>
          </div>
          <div class="modal-footer">
                <!--<button type="submit" class="btn btn-secondary btn_save_role">Save</button>-->
		    	<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
	
<?php include_once("footer.php");?>