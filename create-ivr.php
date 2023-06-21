<?php
	include_once("header.php");
?>
    <link rel="stylesheet" href="css/ivr_workflow.css" />
    <div class="py-2">
		<h3>Create IVR Response</h3>
	</div>
    <nav class="navbar bg-dark" data-bs-theme="dark">
        <div class="container-fluid justify-content-between">
            <div style="display: flex;" >
                <div class="form-group">
                <label>Response Name:</label>
                <input class="form-control" type="text" name="ivr_title" placeholder="Write Name...">
                </div>
                <div class="form-group" style="min-width: 228px;margin-left: 14px;" >
                    <label>Assign Number:</label>
                    <select class="form-control form-select" type="text" name="assigned_number" required >
                        <option value="" >Select Number</option>
                        <?php
                        $workflow_data = mysqli_query($link,"SELECT twilio_numbers.phone_number FROM `twilio_numbers`  LEFT JOIN ivr_rsponses on ivr_rsponses.assigned_number = twilio_numbers.phone_number WHERE ivr_rsponses.assigned_number IS NULL AND twilio_numbers.user_id=".$_SESSION['user_id']);
                        while($row = mysqli_fetch_assoc($workflow_data) ){
                        ?>
                        <option value="<?= $row['phone_number'] ?>" ><?= $row['phone_number'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <button class="btn btn-success" id="save_ivr"><i class="fa fa-save"></i> Save</button>
        </div>
    </nav>
    <div class="card text-center pb-5">
        <svg id="drawlinesvg"></svg>
        <div class="p-3 mt-4 d-inline-block rounded trigger_section m-auto">
            <span id="created_trigger"></span>
            <button type="button" class="btn btn-light d-block mt-2 trigger_btn m-auto" >Incomming IVR Call</button>
        </div>
        <button type="button" class="btn btn-light d-block mt-4 m-auto add_plus_action add_plus_actiond" onclick="acction_post(0)" data-id="0" data-bs-toggle="modal" data-bs-target="#addactionModal"><i class="fa fa-plus"></i></button>
        <button type="button" class="btn btn-light d-block mt-4 m-auto add_action add_plus_actiond d-none" onclick="acction_post(0)" data-id="0" id="toggle_actionbtn" data-bs-toggle="modal" data-bs-target="#addactionModal">Add your frist Action</button>
        <span id="append_action"></span>
        <div class="m-auto" style="width:3rem;height:3rem;margin-top: 19px !important;">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--file-icons w-full h-full" width="100%" height="100%" preserveAspectRatio="xMidYMid meet" viewBox="0 0 512 512" data-icon="file-icons:donejs" data-inline="false"><path d="M306.062 212.468l-18.418 67.977h-76.013l18.083-67.977h76.348zM512 58.098l-83.045 310.08H65.967l-58.6 85.724l-7.367-.67L106.15 58.098H512zm-280.947 62.954h206.273l8.037-31.812H239.424l-8.371 31.812zm-58.6-.335c8.371 0 17.078-7.032 19.422-15.404c2.343-8.706-2.68-15.403-11.386-15.403c-19.75 1.108-29.4 29.227-8.036 30.807zm-41.188 0c21.987-1.247 27.145-30.698 8.037-30.807c-8.372 0-17.078 7.032-19.422 15.403c-2.01 8.372 3.013 15.404 11.385 15.404zm315.437 91.751l15.739-59.27h-64.293l-16.073 59.27h-76.013l15.738-59.27h-76.013l-16.073 59.27h-76.348l15.738-59.27h-64.293l-15.738 59.27h64.293l-18.083 67.977H70.99l-17.413 64.293h64.294l17.412-64.293h76.348l-17.413 64.293h76.348l17.078-64.293h76.348l-17.413 64.293h64.628l17.413-64.293h-64.628l18.083-67.977h64.627z" fill="currentColor"></path></svg>
        </div>
    </div>
    <?php include('ivr_action_modals.php') ?>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script> -->
    <script src="js/bootstrap.bundle.min.js" ></script>
    <script src="js/jquery-3.6.3.min.js" ></script>
    <script src="js/ivr_funcations.js?v=<?= strtotime('now') ?>" ></script>
    
    <script>
        var actionss = [];
        var treeData = [];
        var action_position = 0;
        var parent_action_position = -1;
        var branch_id = -1;
        var count_actions = 0;
        var is_create_condition = -1;
        var editaction_position = -1;
        var c = 0;
        var menu = 1;
        var menu_id = false;

        renderActions();

        let action_blocks = [];
        
        $('#save_ivr').click(function () {
            let ivr_title = $('input[name=ivr_title]').val();
            let assigned_number = $('select[name=assigned_number]').val();

            if (assigned_number == '') {
                alert('Please Assign Number!');
                return false;
            }

            if (ivr_title) {
                var data = new FormData();
                data.append("cmd", "new_ivr_response");
                data.append("name", ivr_title);
                data.append("assigned_number", assigned_number);
                data.append("treeData", JSON.stringify(treeData));
                data.append("actionss", JSON.stringify(actionss));
                $('#save_ivr').attr('disabled', true);
                $.ajax({
                    url: "server.php",
                    data: data,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (result) {
                        let reslt = JSON.parse(result);
                        // console.log(reslt);
                        $('#save_ivr').removeAttr('disabled');
                        if (reslt.status == 1) {
                            // success
                            alert(reslt.message);
                            window.location = reslt.pageUrl;
                        } else {
                            alert(reslt.message);
                        }
                    }
                });
            } else {
                alert("Response Name Field is required.");
            }
        });
    </script>
    <script src="js/ivr_model.js?v=<?= strtotime('now') ?>" ></script>
  </body>
</html>