<?php
	session_start();
	/*
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	*/
	if($_SESSION['user_id']==''){
		header("location: index.php");
		die("Session expired, please login again.");
	}
	include_once("database.php");
	include_once("functions.php");
	include_once("clsss/class-fb.php");
	date_default_timezone_set("US/Eastern");
	$pageName = getCurrentPageName();
	$twilioNumbers = getRandomTwilioNumbers($_SESSION['user_id']);
	$clientID = '527250546278-1pbvlc9ogrbmeabb1e7v14nu87s2e6pk.apps.googleusercontent.com';
	$clientSecret = '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';
	$redirectUrl = getServerUrl().'/server.php?cmd=get_google_calendar_code';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Karma IVR - Dashboard</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="apple-touch-icon" sizes="120x120" href="./assets/img/favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="./assets/img/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="./assets/img/favicon/favicon-16x16.png">
	<link rel="manifest" href="./assets/img/favicon/site.webmanifest">
	<link rel="mask-icon" href="./assets/img/favicon/safari-pinned-tab.svg" color="#ffffff">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="theme-color" content="#ffffff">
	<link type="text/css" href="./vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
	<link type="text/css" href="./vendor/notyf/notyf.min.css" rel="stylesheet">
	<link type="text/css" href="./vendor/fullcalendar/main.min.css" rel="stylesheet">
	<link type="text/css" href="./vendor/apexcharts/dist/apexcharts.css" rel="stylesheet">
	<link type="text/css" href="./vendor/dropzone/dist/min/dropzone.min.css" rel="stylesheet">
	<link type="text/css" href="./vendor/choices.js/public/assets/styles/choices.min.css" rel="stylesheet">
	<link type="text/css" href="./vendor/leaflet/dist/leaflet.css" rel="stylesheet">
	<link type="text/css" href="./css/volt.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
	
	<style>
		/** dialer **/
		 #dialer_table {
			width: 100%;
			font-size: 1.5em;
		}

		#dialer_table tr td {
			text-align: center;
			height: 50px;
			width: 33%;
		}

		#dialer_table #dialer_input_td {
			border-bottom: 1px solid #fafafa;
		}

		#dialer_table #dialer_input_td input {
			width: 100%;
			border: none;
			font-size: 1.6em;
		}

		/* Remove arrows from type number input : Chrome, Safari, Edge, Opera */
		#dialer_table #dialer_input_td input::-webkit-outer-spin-button,
		#dialer_table #dialer_input_td input::-webkit-inner-spin-button {
			-webkit-appearance: none;
			margin: 0;
		}

		/* Remove arrows from type number input : Firefox */
		#dialer_table #dialer_input_td input[type=number] {
			-moz-appearance: textfield;
		}

		#dialer_table #dialer_input_td input::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
			color: #cccccc;
			opacity: 1; /* Firefox */
		}

		#dialer_table #dialer_input_td input:-ms-input-placeholder { /* Internet Explorer 10-11 */
			color: #cccccc;
		}

		#dialer_table #dialer_input_td input::-ms-input-placeholder { /* Microsoft Edge */
			color: #cccccc;
		}

		#dialer_table #dialer_call_btn_td {
			color: #ffffff;
			background-color: green;
			border: none;
			cursor: pointer;
			width: 100%;
			text-decoration: none;
			padding: 5px 32px;
			text-align: center;
			display: inline-block;
			margin: 10px 2px 4px 2px;
			transition: all 300ms ease;
			-moz-transition: all 300ms ease;
			--webkit-transition: all 300ms ease;
		}

		#dialer_table #dialer_call_btn_td:hover {
			background-color: #009d00;
		}

		#dialer_table .dialer_num_tr td {
			-webkit-touch-callout: none; /* iOS Safari */
			-webkit-user-select: none; /* Safari */
			-khtml-user-select: none; /* Konqueror HTML */
			-moz-user-select: none; /* Old versions of Firefox */
			-ms-user-select: none; /* Internet Explorer/Edge */
			user-select: none; /* Non-prefixed version, currently supported by Chrome, Edge, Opera and Firefox */
		}

		#dialer_table .dialer_num_tr td:nth-child(1) {
			border-right: 1px solid #fafafa;
		}

		#dialer_table .dialer_num_tr td:nth-child(3) {
			border-left: 1px solid #fafafa;
		}

		#dialer_table .dialer_num_tr:nth-child(1) td,
		#dialer_table .dialer_num_tr:nth-child(2) td,
		#dialer_table .dialer_num_tr:nth-child(3) td,
		#dialer_table .dialer_num_tr:nth-child(4) td {
			border-bottom: 1px solid #fafafa;
		}

		#dialer_table .dialer_num_tr .dialer_num {
			color: #0B559F;
			cursor: pointer;
		}

		#dialer_table .dialer_num_tr .dialer_num:hover {
			background-color: #fafafa;
		}

		#dialer_table .dialer_num_tr:nth-child(0) td {
			border-top: 1px solid #fafafa;
		}

		#dialer_table .dialer_del_td img {
			cursor: pointer;
		}
		/** end**/
		.overlay {
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			position: fixed;
			background: #222;
			opacity: 0.2;
			display: none;
			z-index: 9999
		}

		.overlay__inner {
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			position: absolute;
		}

		.overlay__content {
			left: 50%;
			position: absolute;
			top: 50%;
			transform: translate(-50%, -50%);
		}

		.spinner {
			width: 75px;
			height: 75px;
			display: inline-block;
			border-width: 2px;
			border-color: rgba(255, 255, 255, 0.05);
			border-top-color: #fff;
			animation: spin 1s infinite linear;
			border-radius: 100%;
			border-style: solid;
		}

		@keyframes spin {
		  100% {
			transform: rotate(360deg);
		  }
		}
	</style>
</head>

<body>
	<div class="overlay">
		<div class="overlay__inner">
			<div class="overlay__content"><span class="spinner"></span></div>
		</div>
	</div>
	<?php include_once("sidebar.php");?>
	<main class="content">
		<?php include_once("top_nav.php")?>