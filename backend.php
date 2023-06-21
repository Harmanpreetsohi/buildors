<?php

/** START SESSION */
session_start();
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

/** INCLUDE CONSTANTS / CONFIG */
require_once 'config.php';

/** SET DEFAULT TIMEZONE */
date_default_timezone_set( DEFAULT_TIMEZONE );

/** CONNECT TO DATABASE */
require_once 'database.php';

$cmd = $_REQUEST['cmd'];

switch($cmd){

    case "create_workflow":{
        // echo('<pre>');
        // print_r($_REQUEST);die;
        $userID = $_SESSION['user_id'];
        $name = $_REQUEST['name'];
        $triggers = json_encode($_REQUEST['triggers']);
        $actions = json_encode($_REQUEST['actionss']);
        $sql = "insert into workflow
                    (
                        user_id,
                        name,
                        triggers,
                        actions
                    )
                values
                    (
                        '".$userID."',
                        '".$name."',
                        $triggers,
                        $actions
                    )";
        $res = mysqli_query($link,$sql) or die(mysqli_error($link));
        if($res){
            $flowID = mysqli_insert_id($link);			
            $pageUrl    = "edit-workflow.php?flowid=".base64_encode($flowID);
            $message = 'Success! WorkFlow is Created successfully.';
            echo json_encode(['status'=>1,'message'=>$message,'pageUrl'=>$pageUrl]);
        }else{
            $message = 'Failed! something went wrong, please try again later.';
            echo json_encode(['status'=>2,'message'=>$message]);
        }
    } break;
    case "update_workflow":{
        // echo('<pre>');
        // print_r($_REQUEST);die;
        $workflow_id = $_REQUEST['workflow_id'];
        $name = $_REQUEST['name'];
        $triggers = json_encode($_REQUEST['triggers']);
        $actions = json_encode($_REQUEST['actionss']);
        $updatd_at = date('Y-m-d H:i:s');
        $sql = "update workflow set
                        name='".$name."',
                        triggers=$triggers,
                        actions=$actions,
                        updated_at='".$updatd_at."'
                        where

							id='".$workflow_id."'";
        $res = mysqli_query($link,$sql) or die(mysqli_error($link));
        if($res){
            $flowID = mysqli_insert_id($link);			
            $message = 'Success! WorkFlow is Updated successfully.';
            echo json_encode(['status'=>1,'message'=>$message]);
        }else{
            $message = 'Failed! something went wrong, please try again later.';
            echo json_encode(['status'=>2,'message'=>$message]);
        }
    } break;
    case "delete_workflow":{
        $sql = "delete from workflow where id='".base64_decode($_REQUEST['flowid'])."'";
        $res = mysqli_query($link,$sql);
        if($res){
            $_SESSION['message'] = '<div class="alert alert-success" role="alert">Success! Woekflow is deleted successfully.</div>';
        }else{
            $_SESSION['message'] = '<div class="alert alert-danger" role="alert">Failed! something went wrong, please try again later.</div>';
        }
        header("Location: ".$_SERVER['HTTP_REFERER']);
    } break;
    case "run_workflow":{
        include_once("functions.php");
        $bookingID = $_REQUEST['booking_id'];
        // $bookingID = mysqli_insert_id($link);
        runWorkFlow($bookingID);
    }break;
}

// $link->close();