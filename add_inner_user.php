<?php
	include_once("header.php");
?>
<?php if($_SESSION['user_id']==1){ ?>
	<div class="py-2">
		<h3>Add User
			<a href="inner_users.php" class="btn btn-primary" style="float: right">Back</a>
		</h3>
	</div>
	
	<?php if($_SESSION['message']!='')echo $_SESSION['message'];unset($_SESSION['message']); ?>

	<div class="card">
	    
		<div class="card-body">
			<div class="col-lg-12 col-sm-12">
				<form action="server.php" method="post" enctype="multipart/form-data">
				    <h3 class="m-1">Overview</h3>
            	    <hr class="mt-1">
            	    <div class="row g-3">
            	        <div class="col-sm-12 col-md-6 col-lg-6 mb-3">
            	            <label>Select Company</label>
                	        <select name="company" class="form-select">
                    	    <?php 
                    	    $select_business = mysqli_query($link,"SELECT `id`, `business_name` FROM `users` where business_name IS NOT NULL");
                    	    if(mysqli_num_rows($select_business)>0){
                    	        while($company = mysqli_fetch_assoc($select_business)){
                    	            echo '<option value="' . $company['id'] . '">' . $company['business_name'] . '</option>';
                        	    }
                    	    }
                    	    ?>
                	        </select>
                	    </div>
                	    <div class="col-9 col-md-4 col-lg-4 mb-3">
                	        <label>Select user role</label>
                	        <select name="role" class="form-select">
                    	    <?php 
                    	    $select_business = mysqli_query($link,"SELECT `role_id`, `role` FROM `role_master`");
                    	    if(mysqli_num_rows($select_business)>0){
                    	        while($company = mysqli_fetch_assoc($select_business)){
                    	            echo '<option value="' . $company['role_id'] . '">' . $company['role'] . '</option>';
                        	    }
                    	    }
                    	    ?>
                	        </select>
                	    </div>
                	    <div class="col-3 col-md-2 col-lg-2 mb-3">
                	        <label class="w-100"> </label>
                	        <a href="roles.php" class="text-decoration-underline text-info">Manage roles</a>
                	    </div>
            	    </div>
				    <div class="row g-3">
                      <div class="col-sm-12 col-md-6 col-lg-6 mb-3">
						<label>First name</label>
						<input type="text" name="first_name" class="form-control" placeholder="Enter first name" required>
                      </div>
                      <div class="col-sm-12 col-md-6 col-lg-6 mb-3">
						<label>Last name</label>
						<input type="text" name="last_name" class="form-control" placeholder="Enter last name" required>
                      </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-12 col-md-6 col-lg-6 mb-3">
    						<label>Email</label>
    						<input type="text" name="email" class="form-control" placeholder="Enter email" required>
    					</div>
                        <div class="col-sm-12 col-md-6 col-lg-6 mb-3">
    						<label>Phone</label>
    						<input type="text" name="phone" class="form-control" placeholder="Enter phone">
    					</div>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-12 col-md-6 col-lg-6 mb-3">
    						<label>Password</label>
    						<input type="password" name="password" class="form-control" placeholder="Enter Password" required>
    					</div>
                        <div class="col-sm-12 col-md-6 col-lg-6 mb-3">
    						<label>Confirm Password</label>
    						<input type="password" name="cpassword" class="form-control" placeholder="Enter Confirm Password" required>
    					</div>
                    </div>
                    <!--<hr>-->
                    <h3 class="m-1">Permissions</h3>
                    <hr class="mt-1">
                    <div class="overflow-auto">
					<table class="table table-borderless">
                      <thead>
                        <tr>
                          <th scope="col"></th>
                          <th scope="col" class="text-center">Create</th>
                          <th scope="col" class="text-center">update</th>
                          <th scope="col" class="text-center">delete</th>
                          <th scope="col" class="text-center">view</th>
                        </tr>
                      </thead>
                      <tbody>
                          <tr class="border">
                          <th scope="row">About <input type="hidden" name="module_name[]" value="about"></th>
                          <td><input type="checkbox" name="about[]" class="form-check mx-auto" value="ins"></td>
                          <td><input type="checkbox" name="about[]" class="form-check mx-auto" value="upd"></td>
                          <td><input type="checkbox" name="about[]" class="form-check mx-auto" value="del"></td>
                          <td><input type="checkbox" name="about[]" class="form-check mx-auto" value="view"></td>
                        </tr>
                        <tr class="border">
                          <th scope="row">Inbox <input type="hidden" name="module_name[]" value="inbox"></th>
                          <td><input type="checkbox" name="inbox[]" class="form-check mx-auto" value="ins"></td>
                          <td><input type="checkbox" name="inbox[]" class="form-check mx-auto" value="upd"></td>
                          <td><input type="checkbox" name="inbox[]" class="form-check mx-auto" value="del"></td>
                          <td><input type="checkbox" name="inbox[]" class="form-check mx-auto" value="view"></td>
                        </tr>
                        <tr class="border">
                          <th scope="row">IVR <input type="hidden" name="module_name[]" value="ivr"></th>
                          <td><input type="checkbox" name="ivr[]" class="form-check mx-auto" value="ins"></td>
                          <td><input type="checkbox" name="ivr[]" class="form-check mx-auto" value="upd"></td>
                          <td><input type="checkbox" name="ivr[]" class="form-check mx-auto" value="del"></td>
                          <td><input type="checkbox" name="ivr[]" class="form-check mx-auto" value="view"></td>
                        </tr>
                        <tr class="border">
                          <th scope="row">SMS Scheduler <input type="hidden" name="module_name[]" value="sms"></th>
                          <td><input type="checkbox" name="sms[]" class="form-check mx-auto" value="ins"></td>
                          <td><input type="checkbox" name="sms[]" class="form-check mx-auto" value="upd"></td>
                          <td><input type="checkbox" name="sms[]" class="form-check mx-auto" value="del"></td>
                          <td><input type="checkbox" name="sms[]" class="form-check mx-auto" value="view"></td>
                        </tr>
                        <tr class="border">
                          <th scope="row">Scheduler <input type="hidden" name="module_name[]" value="scheduler"></th>
                          <td><input type="checkbox" name="scheduler[]" class="form-check mx-auto" value="ins"></td>
                          <td><input type="checkbox" name="scheduler[]" class="form-check mx-auto" value="upd"></td>
                          <td><input type="checkbox" name="scheduler[]" class="form-check mx-auto" value="del"></td>
                          <td><input type="checkbox" name="scheduler[]" class="form-check mx-auto" value="view"></td>
                        </tr>
                        <tr class="border">
                          <th scope="row">Calendar <input type="hidden" name="module_name[]" value="calendar"></th>
                          <td><input type="checkbox" name="calendar[]" class="form-check mx-auto" value="ins"></td>
                          <td><input type="checkbox" name="calendar[]" class="form-check mx-auto" value="upd"></td>
                          <td><input type="checkbox" name="calendar[]" class="form-check mx-auto" value="del"></td>
                          <td><input type="checkbox" name="calendar[]" class="form-check mx-auto" value="view"></td>
                        </tr>
                        <tr class="border">
                          <th scope="row">Bulk SMS <input type="hidden" name="module_name[]" value="bulksms"></th>
                          <td><input type="checkbox" name="bulksms[]" class="form-check mx-auto" value="ins"></td>
                          <td><input type="checkbox" name="bulksms[]" class="form-check mx-auto" value="upd"></td>
                          <td><input type="checkbox" name="bulksms[]" class="form-check mx-auto" value="del"></td>
                          <td><input type="checkbox" name="bulksms[]" class="form-check mx-auto" value="view"></td>
                        </tr>
                        <tr class="border">
                          <th scope="row">Contacts <input type="hidden" name="module_name[]" value="contacts"></th>
                          <td><input type="checkbox" name="contacts[]" class="form-check mx-auto" value="ins"></td>
                          <td><input type="checkbox" name="contacts[]" class="form-check mx-auto" value="upd"></td>
                          <td><input type="checkbox" name="contacts[]" class="form-check mx-auto" value="del"></td>
                          <td><input type="checkbox" name="contacts[]" class="form-check mx-auto" value="view"></td>
                        </tr>
                        <tr class="border">
                          <th scope="row">Pipelines <input type="hidden" name="module_name[]" value="pipelines"></th>
                          <td><input type="checkbox" name="pipelines[]" class="form-check mx-auto" value="ins"></td>
                          <td><input type="checkbox" name="pipelines[]" class="form-check mx-auto" value="upd"></td>
                          <td><input type="checkbox" name="pipelines[]" class="form-check mx-auto" value="del"></td>
                          <td><input type="checkbox" name="pipelines[]" class="form-check mx-auto" value="view"></td>
                        </tr>
                        <tr class="border">
                          <th scope="row">Workflows <input type="hidden" name="module_name[]" value="workflows"></th>
                          <td><input type="checkbox" name="workflows[]" class="form-check mx-auto" value="ins"></td>
                          <td><input type="checkbox" name="workflows[]" class="form-check mx-auto" value="upd"></td>
                          <td><input type="checkbox" name="workflows[]" class="form-check mx-auto" value="del"></td>
                          <td><input type="checkbox" name="workflows[]" class="form-check mx-auto" value="view"></td>
                        </tr>
                      </tbody>
                    </table>
                    </div>
					<hr class="mt-1">
					
					<!--<div class="mb-4">-->
					<!--	<label>Cell</label>-->
					<!--	<input type="text" name="cell" class="form-control" placeholder="Enter cell" required>-->
					<!--</div>-->
					<!--<div class="mb-4">-->
					<!--	<label>Address</label>-->
					<!--	<input type="text" name="address" class="form-control" placeholder="Enter address" required>-->
					<!--</div>-->
					<!--<div class="mb-4">-->
					<!--	<label>City</label>-->
					<!--	<input type="text" name="city" class="form-control" placeholder="Enter city" required>-->
					<!--</div>-->
					<!--<div class="mb-4">-->
					<!--	<label>State</label>-->
					<!--	<input type="text" name="state" class="form-control" placeholder="Enter state" required>-->
					<!--</div>-->
					<!--<div class="mb-4">-->
					<!--	<label>Zip</label>-->
					<!--	<input type="text" name="zipcode" class="form-control" placeholder="Enter zip" required>-->
					<!--</div>-->
					<!--<div class="mb-4">-->
					<!--	<label>Sales Manager Name</label>-->
					<!--	<input type="text" name="sale_manager_name" class="form-control" >-->
					<!--</div>-->
					<!--<div class="mb-4">-->
					<!--	<label>Sales Manager Number</label>-->
					<!--	<input type="text" name="sale_manager_number" class="form-control" >-->
					<!--</div>-->
					<!--<div class="mb-4">-->
					<!--	<label>Project manager name</label>-->
					<!--	<input type="text" name="project_manager_name" class="form-control" >-->
					<!--</div>-->
					<!--<div class="mb-4">-->
					<!--	<label>Project manager number</label>-->
					<!--	<input type="text" name="project_manager_number" class="form-control" >-->
					<!--</div>-->
					<!--<div class="mb-4">-->
					<!--	<label>Mannagement name</label>-->
					<!--	<input type="text" name="management_name" class="form-control" >-->
					<!--</div>-->
					<!--<div class="mb-4">-->
					<!--	<label>Mannagement number</label>-->
					<!--	<input type="text" name="management_number" class="form-control" >-->
					<!--</div>-->
					<!--<div class="mb-4">-->
					<!--	<label>Tag workflow</label>-->
					<!--	<input type="text" name="tagworkflow" class="form-control" >-->
					<!--</div>-->
					<div class="mb-4">
						<input type="hidden" name="cmd" value="add_inner_user">
						<input type="submit" value="Add User" class="btn btn-gray-800 d-inline-flex align-items-center me-2">
					</div>
				</form>
			</div>
		</div>
	</div>
<?php }else{
    echo 'Access denied';
}?>
<?php include_once("footer.php");?>