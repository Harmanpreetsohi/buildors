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

	// if(mysqli_num_rows($permission_qry)==1 || $role_data['role']=="Full Admin" || $user_type==1){
?>
<style>
table>thead>tr>th {
    text-align: center
}

table>tbody>tr>td {
    text-align: center
}
</style>
<script>
var customers = [];
</script>
<!-- ck editor--- -->


<div class="container">
    <div class="customPage_wrapper">
        <div class="row">
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12  ">
                <div class="card custom_sidebar">
                    <div class="card-body">
                        <!-- tab -->
                        <div class="custom_wrapper">
                            <ul class="nav nav-tabs" id="dynamicMenu" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                    <button class="nav-link active" id="text-tab" data-bs-toggle="tab"
                                        data-bs-target="#text" type="button" role="tab" aria-controls="text"
                                        aria-selected="true">Text</button>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="flexSwitchCheckDefault">
                                    </div>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                    <button class="nav-link" id="title-tab" data-bs-toggle="tab" data-bs-target="#title"
                                        type="button" role="tab" aria-controls="title"
                                        aria-selected="false">Title</button>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="flexSwitchCheckDefault">
                                    </div>
                                </li>
								<li class="nav-item" role="presentation">
								<i class="fa fa-bars" aria-hidden="true"></i>
                                        <button class="nav-link  btn" id="property-tab" data-bs-toggle="tab"
                                            data-bs-target="#property" type="button" role="tab" aria-controls="property"
                                            aria-selected="false">Add
                                            Property Overview</button>
											<div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="flexSwitchCheckDefault">
                                    </div>

                                </li>
								<li class="nav-item" role="presentation">
								<i class="fa fa-bars" aria-hidden="true"></i>
                                        <button class="nav-link  btn" id="Quote-tab" data-bs-toggle="tab"
                                            data-bs-target="#Quote" type="button" role="tab" aria-controls="Quote"
                                            aria-selected="false">
                                            Quote Details</button>
											<div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="flexSwitchCheckDefault">
                                    </div>

                                </li>

                                <li class="nav-item" role="presentation">
                                    <div class="add_page_btn_outer">
                                        <button class="nav-link add_page_btn btn" id="custom-tab" data-bs-toggle="tab"
                                            data-bs-target="#custom" type="button" role="tab" aria-controls="custom"
                                            aria-selected="false"> <i class="fa fa-plus me-1" aria-hidden="true"></i>
                                            Add Custom Page</button>
                                        <div>

                                </li>
								

                            </ul>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-lg-8 col-md-6 col-sm-12">
                <div class="tab-content" id="dynamicMenuContent">
                    <!-- text tab -->
                    <div class="tab-pane fade show active" id="text" role="tabpanel" aria-labelledby="text-tab">
                       
                         <?php include_once("elements/custom_text.php");?>
                    </div>
                    <!-- title tab -->
                    <div class="tab-pane fade" id="title" role="tabpanel" aria-labelledby="title-tab">
                        <?php include_once("elements/custom_title.php");?>
                    </div>

                    <!-- custom button tab -->
                    <div class="tab-pane fade" id="custom" role="tabpanel" aria-labelledby="custom-tab">
                            <?php include_once("elements/custom_options.php");?>     
                        
                    </div>
					<!-- Property overview tab -->
					<div class="tab-pane fade" id="property" role="tabpanel" aria-labelledby="property-tab">
						<?php include_once("elements/custom_picture.php");?>
                    </div>
					<!-- Quote details -->
					<div class="tab-pane fade" id="Quote" role="tabpanel" aria-labelledby="Quote-tab">
						<?php include_once("elements/quoteDetails.php");?>
                    </div>
					<!-- Property overview tab -->
					<div class="tab-pane fade" id="property" role="tabpanel" aria-labelledby="property-tab">
						<?php include_once("elements/custom_picture.php");?>
                    </div>
					<!-- Quote details -->
					<div class="tab-pane fade" id="Quote" role="tabpanel" aria-labelledby="Quote-tab">
						<?php include_once("elements/quoteDetails.php");?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
	// }else{
	    
	//     echo "<h3 style='height: 250px;' class='mt-4'>Access Denied</h3>";
	// }
?>

<?php include_once("footer.php"); ?>

<script>

$(document).on('submit','.menuForm',function(event){
        event.preventDefault();
    
        var form = $(this);
        
                console.log(form.serialize());
              
                $.ajax({
                    url: 'elements/add_custom_menu.php', 
                    type: 'POST',
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                    if (response.success) {
                        // Update the left menu
                        var menuItem = $('<div>').text(response.menuName);
                        $('#dynamicMenu').append(menuItem);
                    }
                    }
                });
            
        // });
    });



    </script>

