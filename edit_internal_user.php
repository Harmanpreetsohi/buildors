<?php
	include_once("header.php");
?>

<?php if($_SESSION['user_id']==1){ 
    
    $internal_user_id =$_GET['id'];
    
    $sql = mysqli_query($link,"SELECT * FROM users WHERE id='$internal_user_id'");
    
    if(mysqli_num_rows($sql)==1){
        $user_data = mysqli_fetch_assoc($sql);
?>
	<div class="py-2">
		<h3>Update User
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
                    	            if($company['id']==$user_data['company_id']){
                    	                $selected="selected";
                    	            }
                    	            else{
                    	               $selected=""; 
                    	            }
                    	            echo '<option value="' . $company['id'] . '"'.$selected.'>' . $company['business_name'] . '</option>';
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
                    	            if($user_data['role_id']==$company['role_id']){
                        	            echo '<option value="' . $company['role_id'] . '" selected>' . $company['role'] . '</option>';
                    	            }else{
                    	                echo '<option value="' . $company['role_id'] . '">' . $company['role'] . '</option>';
                    	            }
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
						<input type="text" name="first_name" class="form-control" placeholder="Enter first name" value="<?= $user_data['first_name'] ?>" required>
                      </div>
                      <div class="col-sm-12 col-md-6 col-lg-6 mb-3">
						<label>Last name</label>
						<input type="text" name="last_name" class="form-control" placeholder="Enter last name" value="<?= $user_data['last_name'] ?>" required>
                      </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-12 col-md-6 col-lg-6 mb-3">
    						<label>Email</label>
    						<input type="text" name="email" class="form-control" placeholder="Enter email" value="<?= $user_data['email'] ?>" required>
    					</div>
                        <div class="col-sm-12 col-md-6 col-lg-6 mb-3">
    						<label>Phone</label>
    						<input type="text" name="phone" class="form-control" placeholder="Enter phone" value="<?= $user_data['phone_number'] ?>">
    					</div>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-12 col-md-6 col-lg-6 mb-3">
    						<label>Password</label>
    						<input type="text" name="password" class="form-control" placeholder="Enter Password" >
    					</div>
                        <div class="col-sm-12 col-md-6 col-lg-6 mb-3">
    						<label>Confirm Password</label>
    						<input type="text" name="cpassword" class="form-control" placeholder="Enter Confirm Password" >
    					</div>
                    </div>
     <!--               <h3 class="m-1">Permissions</h3>-->
     <!--               <hr class="mt-1">-->
     <!--               <div class="overflow-auto">-->
					<!--<table class="table table-borderless">-->
     <!--                 <thead>-->
     <!--                   <tr>-->
     <!--                     <th scope="col"></th>-->
     <!--                     <th scope="col" class="text-center">Create</th>-->
     <!--                     <th scope="col" class="text-center">update</th>-->
     <!--                     <th scope="col" class="text-center">delete</th>-->
     <!--                     <th scope="col" class="text-center">view</th>-->
     <!--                   </tr>-->
     <!--                 </thead>-->
     <!--                 <tbody>-->
     <!--                     <tr class="border">-->
     <!--                     <th scope="row">About <input type="hidden" name="module_name[]" value="about"></th>-->
     <!--                     <td><input type="checkbox" name="about[]" class="form-check mx-auto" value="ins"></td>-->
     <!--                     <td><input type="checkbox" name="about[]" class="form-check mx-auto" value="upd"></td>-->
     <!--                     <td><input type="checkbox" name="about[]" class="form-check mx-auto" value="del"></td>-->
     <!--                     <td><input type="checkbox" name="about[]" class="form-check mx-auto" value="view"></td>-->
     <!--                   </tr>-->
     <!--                   <tr class="border">-->
     <!--                     <th scope="row">Inbox <input type="hidden" name="module_name[]" value="inbox"></th>-->
     <!--                     <td><input type="checkbox" name="inbox[]" class="form-check mx-auto" value="ins"></td>-->
     <!--                     <td><input type="checkbox" name="inbox[]" class="form-check mx-auto" value="upd"></td>-->
     <!--                     <td><input type="checkbox" name="inbox[]" class="form-check mx-auto" value="del"></td>-->
     <!--                     <td><input type="checkbox" name="inbox[]" class="form-check mx-auto" value="view"></td>-->
     <!--                   </tr>-->
     <!--                   <tr class="border">-->
     <!--                     <th scope="row">IVR <input type="hidden" name="module_name[]" value="ivr"></th>-->
     <!--                     <td><input type="checkbox" name="ivr[]" class="form-check mx-auto" value="ins"></td>-->
     <!--                     <td><input type="checkbox" name="ivr[]" class="form-check mx-auto" value="upd"></td>-->
     <!--                     <td><input type="checkbox" name="ivr[]" class="form-check mx-auto" value="del"></td>-->
     <!--                     <td><input type="checkbox" name="ivr[]" class="form-check mx-auto" value="view"></td>-->
     <!--                   </tr>-->
     <!--                   <tr class="border">-->
     <!--                     <th scope="row">SMS Scheduler <input type="hidden" name="module_name[]" value="sms"></th>-->
     <!--                     <td><input type="checkbox" name="sms[]" class="form-check mx-auto" value="ins"></td>-->
     <!--                     <td><input type="checkbox" name="sms[]" class="form-check mx-auto" value="upd"></td>-->
     <!--                     <td><input type="checkbox" name="sms[]" class="form-check mx-auto" value="del"></td>-->
     <!--                     <td><input type="checkbox" name="sms[]" class="form-check mx-auto" value="view"></td>-->
     <!--                   </tr>-->
     <!--                   <tr class="border">-->
     <!--                     <th scope="row">Calendar <input type="hidden" name="module_name[]" value="calendar"></th>-->
     <!--                     <td><input type="checkbox" name="calendar[]" class="form-check mx-auto" value="ins"></td>-->
     <!--                     <td><input type="checkbox" name="calendar[]" class="form-check mx-auto" value="upd"></td>-->
     <!--                     <td><input type="checkbox" name="calendar[]" class="form-check mx-auto" value="del"></td>-->
     <!--                     <td><input type="checkbox" name="calendar[]" class="form-check mx-auto" value="view"></td>-->
     <!--                   </tr>-->
     <!--                   <tr class="border">-->
     <!--                     <th scope="row">Bulk SMS <input type="hidden" name="module_name[]" value="bulksms"></th>-->
     <!--                     <td><input type="checkbox" name="bulksms[]" class="form-check mx-auto" value="ins"></td>-->
     <!--                     <td><input type="checkbox" name="bulksms[]" class="form-check mx-auto" value="upd"></td>-->
     <!--                     <td><input type="checkbox" name="bulksms[]" class="form-check mx-auto" value="del"></td>-->
     <!--                     <td><input type="checkbox" name="bulksms[]" class="form-check mx-auto" value="view"></td>-->
     <!--                   </tr>-->
     <!--                   <tr class="border">-->
     <!--                     <th scope="row">Contacts <input type="hidden" name="module_name[]" value="contacts"></th>-->
     <!--                     <td><input type="checkbox" name="contacts[]" class="form-check mx-auto" value="ins"></td>-->
     <!--                     <td><input type="checkbox" name="contacts[]" class="form-check mx-auto" value="upd"></td>-->
     <!--                     <td><input type="checkbox" name="contacts[]" class="form-check mx-auto" value="del"></td>-->
     <!--                     <td><input type="checkbox" name="contacts[]" class="form-check mx-auto" value="view"></td>-->
     <!--                   </tr>-->
     <!--                   <tr class="border">-->
     <!--                     <th scope="row">Pipelines <input type="hidden" name="module_name[]" value="pipelines"></th>-->
     <!--                     <td><input type="checkbox" name="pipelines[]" class="form-check mx-auto" value="ins"></td>-->
     <!--                     <td><input type="checkbox" name="pipelines[]" class="form-check mx-auto" value="upd"></td>-->
     <!--                     <td><input type="checkbox" name="pipelines[]" class="form-check mx-auto" value="del"></td>-->
     <!--                     <td><input type="checkbox" name="pipelines[]" class="form-check mx-auto" value="view"></td>-->
     <!--                   </tr>-->
     <!--                   <tr class="border">-->
     <!--                     <th scope="row">Workflows <input type="hidden" name="module_name[]" value="workflows"></th>-->
     <!--                     <td><input type="checkbox" name="workflows[]" class="form-check mx-auto" value="ins"></td>-->
     <!--                     <td><input type="checkbox" name="workflows[]" class="form-check mx-auto" value="upd"></td>-->
     <!--                     <td><input type="checkbox" name="workflows[]" class="form-check mx-auto" value="del"></td>-->
     <!--                     <td><input type="checkbox" name="workflows[]" class="form-check mx-auto" value="view"></td>-->
     <!--                   </tr>-->
     <!--                 </tbody>-->
     <!--               </table>-->
     <!--               </div>-->
					<hr class="mt-1">
					<div class="mb-4">
						<input type="hidden" name="cmd" value="update_internal_user">
						<input type="hidden" name="user_id" value="<?= $user_data['id'] ?>">
						<input type="submit" value="Update User" class="btn btn-gray-800 d-inline-flex align-items-center me-2">
					</div>
				</form>
			</div>
		</div>
	</div>
<?php

    }else{
        echo 'Data Not Found.';
    }
}else{
    echo 'Access denied';
}?>
<?php include_once("footer.php");?>