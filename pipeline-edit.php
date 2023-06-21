<?php
	include_once("header.php");
	
	$user_id = $_SESSION['user_id'];
	//$role_id = $_SESSION['role_id'];
	$user_type = $_SESSION['user_type'];
	
	$user_qry = mysqli_query($link,"select role_id from users where id='$user_id'");
	$user_data = mysqli_fetch_assoc($user_qry);
	$role_id = $user_data['role_id'];
	
	
	$role_qry = mysqli_query($link,"select * from role_master where role_id='$role_id'");
	$role_data = mysqli_fetch_assoc($role_qry);
	
	
	
	$permission_qry = mysqli_query($link,"select * from permission where inner_user_id='$user_id' AND module_name='pipelines' AND update_permission='1'");
	

	
	if(mysqli_num_rows($permission_qry)==1 || $role_data['role']=="Full Admin" || $user_type==1){
	    
    $pipeline_list = mysqli_query($link,"select * from pipeline_list where id=".$_REQUEST['pipelineid']);

    if(mysqli_num_rows($pipeline_list) == 0){
        die('404 not found');
    }
    $pipeline_list_data = mysqli_fetch_assoc($pipeline_list)
?>
	<div class="py-2">
		<h3>Edit Pipeline</h3>
	</div>
	
	<?php if(isset($_SESSION['message']))echo $_SESSION['message'];unset($_SESSION['message']); ?>

	<div class="card">
		<div class="card-body">
			<div class="col-lg-12 col-sm-12">
				<form action="server.php" method="post">
					<div class="mb-4">
						<label>Title</label>
						<input type="text" name="title" value="<?= $pipeline_list_data['title'] ?>" class="form-control" placeholder="Enter list title" required>
					</div>
                    <div id="add_stages">
                    <?php
                        foreach (json_decode($pipeline_list_data['stages']) as $stages){
                    ?>
                    <div class="row my-3 remove_stage">
                        <div class="col-md-10">
                            <input type="text" name="stages[]" value="<?= $stages ?>" class="form-control" placeholder="Enter stage title" />
                        </div>
                        <div class="col-md-2 text-center">
                            <button type="button" class="btn btn-danger remove_field">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <?php
                        }
                    ?>
                    </div>
					<div class="mb-4 mt-2 text-end">
						<button type="button" id="add_stage" class="btn btn-primary">Add Stage</button> 
					</div>
					<div class="mb-4">
						<input type="hidden" name="cmd" value="update_pipeline">
						<input type="hidden" name="id" value="<?= $_REQUEST['pipelineid'] ?>">
						<input type="submit" value="Update Pipeline" class="btn btn-gray-800 d-inline-flex align-items-center me-2">
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
<?php include_once("footer.php");?>
<script>
    $('#add_stage').click(function(){
        $('#add_stages').append(
            `<div class="row my-3 remove_stage">
                <div class="col-md-10">
                    <input type="text" name="stages[]" class="form-control" placeholder="Enter stage title" />
                </div>
                <div class="col-md-2 text-center">
                    <button type="button" class="btn btn-danger remove_field">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>`
        );
    });
    $("#add_stages").on("click", ".remove_field", function (e) {
        e.preventDefault();
        $(this).parents('.remove_stage').remove();
    });
</script>