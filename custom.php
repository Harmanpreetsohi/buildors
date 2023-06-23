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
            <div class="col-lg-4">
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
            <div class="col-lg-8">
                <div class="tab-content" id="myTabContent">
                    <!-- text tab -->
                    <div class="tab-pane fade show active" id="text" role="tabpanel" aria-labelledby="text-tab">
                        <div class="page_content_container">
                            <div class="page_content_outer">
                                <h6>Page content</h6>
                                <h5>Introduction</h5>
                            </div>
                            <div class="btn_outer">
                                <button class="btn btn-primary ">View Page</button>
                            </div>
                        </div>
                        <div class="text_container">

                            <textarea name="editor" id="editor"></textarea>

                        </div>

                    </div>
                    <!-- title tab -->
                    <div class="tab-pane fade" id="title" role="tabpanel" aria-labelledby="title-tab">
                        <div class="page_content_container">
                            <div class="page_content_outer">
                                <h6>Page content</h6>
                                <h5>Title</h5>
                            </div>
                            <div class="btn_outer">
                                <button class="btn btn-primary">View Page</button>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <form>
                                    <div>
                                        <label>Report Type</label>
                                        <input type="text" class="form-control" />
                                    </div>
                                    <div>
                                        <label>Date</label>
                                        <input type="date" class="form-control" />
                                    </div>
                                    <div>
                                        <h5>Primary Image<h5>
                                                <div class="primaryImg_outer">
                                                    <div class="primary_img_inner_content">
                                                        <i class="fa fa-upload" aria-hidden="true"></i>
                                                        <label for="primaryimg">Upload</label>
                                                        <input type="file" id="primaryimg" class="form-control"
                                                            name="upload" />
                                                    </div>
                                                </div>
                                    </div>
                                    <div>
                                        <label>Certification/Secondary Logo</label>
                                        <img src="assets/img/cartoonImg/cartoon.jpg" />
                                    </div>
                                    <div>
                                        <label>First name</label>
                                        <input type="text" class="form-control" />
                                    </div>
                                    <div>
                                        <label>Last name</label>
                                        <input type="text" class="form-control" />
                                    </div>
                                    <div>
                                        <label>Address</label>
                                        <input type="text" class="form-control" />
                                    </div>
                                    <div>
                                        <label>City</label>
                                        <input type="text" class="form-control" />
                                    </div>
                                    <div>
                                        <label>State/Provience</label>
                                        <input type="text" class="form-control" />
                                    </div>
                                    <div>
                                        <label>Zip code/Postal code</label>
                                        <input type="number" class="form-control" />
                                    </div>
                                    <form>
                            </div>
                        </div>
                    </div>

                    <!-- custom button tab -->
                    <div class="tab-pane fade" id="custom" role="tabpanel" aria-labelledby="custom-tab">
                        <div class="page_content_container">
                            <div class="page_content_outer">
                                <h6>Page content</h6>
                                <h3>Custom Page</h3>
                            </div>
                            <div class="btn_outer">
                                <button class=" btn btn-primary delete_btn me-2">Delete</button>
                                <button class="btn btn-primary ">View Page</button>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="text_wrapper">
                                    <div>
                                        <input type="radio" class="form-check-input" name="radio" />
                                        <label>Text</label>
                                    </div>
                                    <button class="btn btn-primary">Add</button>
                                </div>
                                <div class="text_wrapper">
                                    <div>
                                        <input type="radio" class="form-check-input" name="radio" />
                                        <label>Title</label>

                                    </div>
                                    <button class="btn btn-primary">Add</button>
                                </div>
                                <div class="text_wrapper">
                                    <div>
                                        <input type="radio" class="form-check-input" name="radio" />
                                        <label>Picture</label>
                                    </div>
                                    <button class="btn btn-primary">Add</button>
                                </div>
                                <div class="text_wrapper">
                                    <div>
                                        <input type="radio" class="form-check-input" name="radio" />
                                        <label>Pdf</label>

                                    </div>
                                    <button class="btn btn-primary">Add</button>
                                </div>
                                <div class="text_wrapper">
                                    <div>
                                        <input type="radio" class="form-check-input" name="radio" />
                                        <label>Authorization</label>

                                    </div>
                                    <button class="btn btn-primary">Add</button>
                                </div>
                            </div>
                        </div>
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
<script src="ckeditor/ckeditor.js"></script>
<script>
window.onload = function() {
    CKEDITOR.replace("editor");
    var editor = CKEDITOR.instances.editor;
    editor.setData("<h1>Custom Default Content</h1>");
};
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