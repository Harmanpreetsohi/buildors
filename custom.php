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
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
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
                                            aria-selected="false"> <i class="fa fa-plus me-1" aria-hidden="true"></i>Add
                                            Custom Page</button>
                                        <div>

                                </li>
								

                            </ul>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-lg-8 col-md-6 col-sm-12">
                <div class="tab-content" id="myTabContent">
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
                </div>
            </div>
        </div>
    </div>
</div>
<?php
	}else{
	    
	    echo "<h3 style='height: 250px;' class='mt-4'>Access Denied</h3>";
	}
?>

<?php include_once("footer.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
    integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
    integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous">
</script>



<script>
function getSelectedCustomers(obj, customers) {
    var contact = $(obj).val();
    if ($(obj).is(":checked") == true) {
        customers.push(contact);
    } else {
        var found = customers.indexOf(contact);
        if (found > -1) {
            customers.splice(found, 1);
        }
    }
    var totalRecipients = customers.length;
    if (totalRecipients > 0) {
        $("#sendBroadcastButton").show();
    } else {
        $("#sendBroadcastButton").hide();
    }
    var json = JSON.stringify(customers);
    $("#recipients").val(json);
}

function searchKeyword() {
    var searchKeyword = $("#searchKeyword").val();
    if ($.trim(searchKeyword) == '')
        window.location = 'contacts.php';
    else
        window.location = 'contacts.php?search=' + searchKeyword;
}

function filterContacts(obj) {
    var type = $(obj).val();
    if ($.trim(type) == '')
        window.location = 'contacts.php';
    else
        window.location = 'contacts.php?filter=' + type;
}

function confirDelete(staffID) {
    if (confirm("Are you sure you wanto to delete this contact?")) {
        $(".overlay").show();
        $.post("server.php", {
            "cmd": "delete_staff",
            staffID: staffID
        }, function() {
            window.location = 'contacts.php';
        });
    }
}
</script>