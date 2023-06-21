<?php
	include_once("header.php");
?>
	<div class="py-2">
		<h3>
		  Roles
			<a href="create_role.php" class="btn btn-primary" style="float: right">Create New Role</a>
		</h3>
	</div>
    <?php if($_SESSION['message']!='')echo $_SESSION['message'];unset($_SESSION['message']); ?>
	<div class="card">
		<div class="table-responsive py-4">
			<table class="table table-flush" id="datatable">
				<thead class="thead-light">
					<tr>
						<th>#</th>
						<th>Role</th>
						<th>Created</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
				<?php
                    $roles_data = mysqli_query($link,"select * from role_master");

                    if(mysqli_num_rows($roles_data)>0){
                        $i = 1;
                        while($row = mysqli_fetch_assoc($roles_data) ){
                ?>
                <tr>
                <td><?= $i++ ?></td>
                <td><?= $row['role'] ?></td>
                <td><?= $row['created_at'] ?></td>
                <td>
                    <a href="edit_role.php?role_id=<?= $row['role_id'] ?>" class="btn btn-primary">Edit</a>
                    <a href="server.php?cmd=delete_role&role_id=<?= $row['role_id'] ?>" onclick="return confirm('Are you sure you want to delete this record?')" class="btn btn-danger">Delete</a>
                </td>
                </tr>
                <?php }
                    }else{ ?>
					<tr>
						<td colspan="4">No role is created yet.</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
	
	
	 <div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Role</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <form>
                <label class="mb-0 mt-2">Role Name</label>
			    <input type="text" name="role_name" class="form-control" value="" placeholder="Role Name">
			    <span id="role_err"></span>
			  </form>
          </div>
          <div class="modal-footer">
                <button type="submit" class="btn btn-secondary btn_save_role">Save</button>
		    	<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
	
<?php include_once("footer.php");?>