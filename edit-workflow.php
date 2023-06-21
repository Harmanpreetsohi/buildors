<?php

include_once("header.php");


if(!isset($_REQUEST['flowid'])){
    die('404');
}

$workflow_data = mysqli_query($link,"select * from workflow where id='".base64_decode($_REQUEST['flowid'])."'");
if(mysqli_num_rows($workflow_data)>0){
    $workflow_data = mysqli_fetch_assoc($workflow_data);
}else{
    die('404');
}
?>
<!-- <!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Workflow</title> -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous"> -->
    <!-- <link href="css/bootstrap.min.css" rel="stylesheet" /> -->
    <!-- Font Awesome CSS -->
    <!-- <link rel="stylesheet" href="css/font-awesome.min.css" />
    <link rel="stylesheet" href="css/workflow.css" />
  </head>
  <body> -->
    <link rel="stylesheet" href="css/workflow.css" />
    <nav class="navbar bg-dark" data-bs-theme="dark">
        <div class="container-fluid justify-content-between">
            <div class="form-group">
                <label>WorkFlow Name:</label>
                <input class="form-control" type="text" name="workflow_title"
                    value="<?= $workflow_data['name'] ?>" placeholder="Write WorkFlow Name...">
            </div>
            <button class="btn btn-success" id="save_workflow"><i class="fa fa-save"></i> Save</button>
        </div>
    </nav>
    <div class="card text-center pb-5">
        <div class="p-3 mt-4 d-inline-block rounded trigger_section m-auto">
            <span id="created_trigger"></span>
            <button type="button" class="btn btn-light d-block mt-2 trigger_btn m-auto" data-bs-toggle="modal" data-bs-target="#addworkflowModal">Add New Workflow Trigger</button>
        </div>
        <button type="button" class="btn btn-light d-block mt-4 m-auto add_plus_action" onclick="acction_post(0)" data-id="0" data-bs-toggle="modal" data-bs-target="#addactionModal"><i class="fa fa-plus"></i></button>
        <button type="button" class="btn btn-light d-block mt-4 m-auto add_action" onclick="acction_post(0)" data-id="0" id="toggle_actionbtn" data-bs-toggle="modal" data-bs-target="#addactionModal">Add your frist Action</button>
        <span id="append_action"></span>
        <div class="m-auto" id="default_flag" style="width:3rem;height:3rem;margin-top: 19px !important;">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--file-icons w-full h-full" width="100%" height="100%" preserveAspectRatio="xMidYMid meet" viewBox="0 0 512 512" data-icon="file-icons:donejs" data-inline="false"><path d="M306.062 212.468l-18.418 67.977h-76.013l18.083-67.977h76.348zM512 58.098l-83.045 310.08H65.967l-58.6 85.724l-7.367-.67L106.15 58.098H512zm-280.947 62.954h206.273l8.037-31.812H239.424l-8.371 31.812zm-58.6-.335c8.371 0 17.078-7.032 19.422-15.404c2.343-8.706-2.68-15.403-11.386-15.403c-19.75 1.108-29.4 29.227-8.036 30.807zm-41.188 0c21.987-1.247 27.145-30.698 8.037-30.807c-8.372 0-17.078 7.032-19.422 15.403c-2.01 8.372 3.013 15.404 11.385 15.404zm315.437 91.751l15.739-59.27h-64.293l-16.073 59.27h-76.013l15.738-59.27h-76.013l-16.073 59.27h-76.348l15.738-59.27h-64.293l-15.738 59.27h64.293l-18.083 67.977H70.99l-17.413 64.293h64.294l17.412-64.293h76.348l-17.413 64.293h76.348l17.078-64.293h76.348l-17.413 64.293h64.628l17.413-64.293h-64.628l18.083-67.977h64.627z" fill="currentColor"></path></svg>
        </div>
    </div>
    <?php include('trigger_modals.php') ?>
    <?php include('action_modals.php') ?>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script> -->
    <script src="js/bootstrap.bundle.min.js" ></script>
    <script src="js/jquery-3.6.3.min.js" ></script>
    <!-- <link rel="stylesheet" href="css/fastselect.min.css">
    <script src="js/fastselect.standalone.js"></script> -->
    <link rel="stylesheet" href="css/bootstrap-tagsinput.css">
    <script src="js/bootstrap-tagsinput.min.js"></script>
    <script>
        var triggers = JSON.parse('<?= $workflow_data['triggers'] ?>');
        var actionss = JSON.parse('<?= $workflow_data['actions'] ?>');
        var action_position = 0;
        var parent_action_position = 0;
        var branch_id = -1;
        var editaction_position = -1;
        var workflow_ids = <?= $workflow_data['id'] ?>;
        var c = 1;
        let trigger_blocks = [];
        let action_blocks = [];
    </script>
    <script src="js/funcations.js" ></script>
    <script>
        renderTriggers();
        renderActions();
        // $('.tagsInput').fastselect();
        $('#save_workflow').click(function(){
            let workflow_title = $('input[name=workflow_title]').val();
            if(workflow_title){
                var data = new FormData();
                data.append("cmd","update_workflow");
                data.append("workflow_id",workflow_ids);
                data.append("name",workflow_title);
                data.append("triggers",JSON.stringify(triggers));
                data.append("actionss",JSON.stringify(actionss));
                $('#save_workflow').attr('disabled',true);
                $.ajax({
                    url: "backend.php",
                    data: data,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (result) {
                        let reslt = JSON.parse(result);
                        // console.log(reslt);
                        $('#save_workflow').removeAttr('disabled');
                        if (reslt.status == 1) {
                            // success
                            alert(reslt.message);
                        }else{
                            alert(reslt.message);
                        }
                    }
                });
            }else{
                alert("WorkFLow Name Field is required.");
            }
        });
    </script>
    <script src="js/workflow.js" ></script>
  </body>
</html>