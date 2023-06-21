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
	
	$permission_qry = mysqli_query($link,"select * from permission where inner_user_id='$user_id' AND module_name='inbox' AND view_permission='1'");
	if(mysqli_num_rows($permission_qry)==1 || $role_data['role']=="Full Admin" || $user_type==1){
?>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>
	function getDate(){
		var msgInTime = new Date();
		var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
		var today = new Date();
		var d = today.getDate();
		var m = months[today.getMonth()];
		var hour = today.getHours();
		var min = today.getMinutes();	
		var msgTime = m+", "+d+" "+hour+":"+min;
		return msgTime;
	}	
	var pusher = new Pusher('65561534463c91979b12', {
		cluster: 'ap3'
	});
	var channel = pusher.subscribe('my-channel');
	var msgTime = getDate();
	channel.bind('new-incoming-sms', function(data){
		var chatStartedNumber = $("#to_number").val();
		if (window.location.href.split('=')[1] == 'fb') {
			if(chatStartedNumber == data.sender){
				$('#'+data.sender+'_container').find('#'+data.sender+'_message').html(data.text);
				$('#'+data.sender+'_message').css("font-weight","bold");
				var popMsg = `<div class="card border-0 shadow p-4 mb-4"><div class="d-flex justify-content-between align-items-center mb-2"><span class="font-small"><a href="javascript:void(0)">
				<svg height="800px" width="800px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 309.142 309.142" xml:space="preserve" style="width: 21px;height: 19px;">
												<g>
													<path style="fill:#005CB9;" d="M154.571,0C69.662,0,0.83,68.831,0.83,153.741c0,38.283,13.997,73.295,37.147,100.207
														c-6.953,19.986-19.807,37.209-36.479,49.581c10.592,3.619,21.938,5.613,33.757,5.613c20.214,0,39.082-5.751,55.067-15.698
														c19.551,9.007,41.312,14.039,64.249,14.039c84.909,0,153.741-68.833,153.741-153.742C308.313,68.831,239.48,0,154.571,0z"></path>
													<g>
														<g>
															<path style="fill:#FFFFFF;" d="M131.495,240.044h36.112c0,0,0-49.849,0-90.282h26.804l3.29-36.115h-28.7v-14.44
																c0-7.037,4.69-8.671,7.984-8.671c3.297,0,20.258,0,20.258,0V59.593l-27.895-0.113c-30.963,0-38.002,23.075-38.002,37.849v16.318
																H113.44v36.115h18.056C131.495,190.682,131.495,240.044,131.495,240.044z"></path>
														</g>
													</g>
												</g>
												</svg>
				<span class="fw-bold">`+data.from_name+`</span></a><span class="fw-normal ms-2">`+data.timestamp+`</span></span><div class="d-none d-sm-block"><svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg></div></div><p class="m-0">`+data.text+`</p></div>`;
				$("#chatContainer").append(popMsg);
				$('#'+data.sender+'_container').find('#'+data.sender+'_msgTime').text("Now");
				updateScroll();
			}
			return true;
		}
		var incomingNumber = data.from_number;
		var customerName = data.first_name+' '+data.last_name;
		var isChatStarted = '';
		if(chatStartedNumber == incomingNumber){
			isChatStarted = 'show_message';
		}
		
		if($.trim(isChatStarted)!=''){ // chat is started here
			var popMsg = `<div class="card border-0 shadow p-4 mb-4"><div class="d-flex justify-content-between align-items-center mb-2"><span class="font-small"><a href="javascript:void(0)"><img class="avatar-sm img-fluid rounded-circle me-2" src="../assets/img/team/profile-picture-1.jpg" alt="avatar"><span class="fw-bold">`+customerName+`</span></a><span class="fw-normal ms-2">`+msgTime+`</span></span><div class="d-none d-sm-block"><svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg></div></div><p class="m-0">`+data.message+`</p>`;
			if(data.from_media != 'no'){
				popMsg += `<div class="card border-0 shadow mediaContainer" style="padding: 7px;"><div class="card-body p-0"><img src="`+data.from_media+`" class="card-img-top mb-2 mb-lg-3" alt="media"></div></div></div>`;
			}else{
				popMsg += `</div>`;
			}
			$("#chatContainer").append(popMsg);
			//var idMaker = data.from_number.substring(2);
			var idMaker = data.from_number;
			$('#'+idMaker+'_container').find('#'+idMaker+'_message').html(data.message);
			$('#'+idMaker+'_message').css("font-weight","bold");
		}else{ // no chat selected yet.
			var idMaker = data.from_number;
			if($.trim(customerName)=='')
				customerName = data.from_number;
			
			$('#'+idMaker+'_container').find('a').text(customerName);
			$('#'+idMaker+'_container').find('#'+idMaker+'_msgTime').text("Now");
			$('#'+idMaker+'_container').find('#'+idMaker+'_message').html(data.message);
			$('#'+idMaker+'_message').css("font-weight","bold");
		}
		updateScroll();
	});
	
	channel.bind('new-incoming-number', function(data){
		var idMaker = data.from_number.substring(2);
		var customerName = data.first_name+' '+data.last_name;
		if($.trim(customerName)=='')
			customerName = data.from_number;
		$("#checkScroll").prepend(`<div id="`+idMaker+`_container" class="d-flex align-items-center justify-content-between border-bottom py-3"><div style="width: 100%"><div class="h6 mb-0 align-items-center"><i class="icon icon-xs me-2 fa fa-phone" style="color: darkorange"></i><a href="javascript:void(0)" onclick="getChats(this,`+data.from_number+`)">`+customerName+`</a><span id="`+idMaker+`_msgTime" style="font-size: 12px;float: right">Now</span></div><div id="`+idMaker+`_message" class="showMessage small card-stats">`+data.message+`</div></div></div>`);
	});
</script>
<style>
	.d-sm-block{
		display: none !important;
	}
	.mediaContainer{
		padding: 7px;
		width: 100px !important;
		height: 100px !important;
	}
	.chatMedia{
		height: 85px;
	}
	.previewImg{
		width: 150px;
		height: 87px;
	}
	.card-img, .card-img-top{
		border-radius: 0.4375rem;
	}
	.emojiOption{
		padding: 10px;
		cursor: pointer;
		font-size: 20px;
		width: 25px;
	}
	.popup {
		position: relative;
		display: inline-block;
		cursor: pointer;
		-webkit-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
	}
		/* The actual popup */
	.popuptext {
		visibility: hidden;
		width: 160px;
		background-color: #555;
		color: #fff;
		text-align: center;
		border-radius: 6px;
		padding: 8px 0;
		position: absolute;
		z-index: 1;
		bottom: 125%;
		left: 50%;
		margin-left: -80px;
	}
		/* Popup arrow */
	.popuptext::after {
		content: "";
		position: absolute;
		top: 100%;
		left: 50%;
		margin-left: -5px;
		border-width: 5px;
		border-style: solid;
		border-color: #555 transparent transparent transparent;
	}
		/* Toggle this class - hide and show the popup */
	.show {
		visibility: visible;
		-webkit-animation: fadeIn 1s;
		animation: fadeIn 1s;
	}
		/* Add animation (fade in the popup) */
	@-webkit-keyframes fadeIn {
		from {opacity: 0;} 
		to {opacity: 1;}
	}
	@keyframes fadeIn {
		from {opacity: 0;}
		to {opacity:1 ;}
	}
	.showMessage{
		white-space: nowrap; 
		width: 235px; 
		overflow: hidden;
		text-overflow: ellipsis;
	}
	.userMedia{
		width: 100%;
		height: 150px;
	}
	
	
</style>
	<div class="py-2">
		<h3>
			Inbox
			<?php if (!isset($_GET['chat'])): ?>
			<input type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#startNewChat" value="New chat" style="float: right">
			<input type="button" value="Add New Contact" style="float: right;margin-right: 8px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addContactModal" />
			<?php endif ?>
		</h3>
	</div>
	<?php if(isset($_SESSION['message']))echo $_SESSION['message'];unset($_SESSION['message']); ?>
	<div class="card">
		<ul class="nav nav-tabs">
		  <li class="nav-item" style="">
		    <a class="nav-link " aria-current="page" href="conversations.php?chat=calllogs" <?php if (isset($_GET['chat']) && $_GET['chat'] == 'calllogs'): ?>
		    	style="background: transparent;color: black;border: 1px solid #d9d6d6;border-bottom: 1px solid white;"
		    <?php endif ?> >
		    	<svg style="width: 30px;height: 20px;" fill="#000000" height="800px" width="800px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"  viewBox="0 0 473.806 473.806" xml:space="preserve"><g><g><path d="M374.456,293.506c-9.7-10.1-21.4-15.5-33.8-15.5c-12.3,0-24.1,5.3-34.2,15.4l-31.6,31.5c-2.6-1.4-5.2-2.7-7.7-4 c-3.6-1.8-7-3.5-9.9-5.3c-29.6-18.8-56.5-43.3-82.3-75c-12.5-15.8-20.9-29.1-27-42.6c8.2-7.5,15.8-15.3,23.2-22.8 c2.8-2.8,5.6-5.7,8.4-8.5c21-21,21-48.2,0-69.2l-27.3-27.3c-3.1-3.1-6.3-6.3-9.3-9.5c-6-6.2-12.3-12.6-18.8-18.6 c-9.7-9.6-21.3-14.7-33.5-14.7s-24,5.1-34,14.7c-0.1,0.1-0.1,0.1-0.2,0.2l-34,34.3c-12.8,12.8-20.1,28.4-21.7,46.5 c-2.4,29.2,6.2,56.4,12.8,74.2c16.2,43.7,40.4,84.2,76.5,127.6c43.8,52.3,96.5,93.6,156.7,122.7c23,10.9,53.7,23.8,88,26 c2.1,0.1,4.3,0.2,6.3,0.2c23.1,0,42.5-8.3,57.7-24.8c0.1-0.2,0.3-0.3,0.4-0.5c5.2-6.3,11.2-12,17.5-18.1c4.3-4.1,8.7-8.4,13-12.9 c9.9-10.3,15.1-22.3,15.1-34.6c0-12.4-5.3-24.3-15.4-34.3L374.456,293.506z M410.256,398.806 C410.156,398.806,410.156,398.906,410.256,398.806c-3.9,4.2-7.9,8-12.2,12.2c-6.5,6.2-13.1,12.7-19.3,20 c-10.1,10.8-22,15.9-37.6,15.9c-1.5,0-3.1,0-4.6-0.1c-29.7-1.9-57.3-13.5-78-23.4c-56.6-27.4-106.3-66.3-147.6-115.6 c-34.1-41.1-56.9-79.1-72-119.9c-9.3-24.9-12.7-44.3-11.2-62.6c1-11.7,5.5-21.4,13.8-29.7l34.1-34.1c4.9-4.6,10.1-7.1,15.2-7.1 c6.3,0,11.4,3.8,14.6,7c0.1,0.1,0.2,0.2,0.3,0.3c6.1,5.7,11.9,11.6,18,17.9c3.1,3.2,6.3,6.4,9.5,9.7l27.3,27.3 c10.6,10.6,10.6,20.4,0,31c-2.9,2.9-5.7,5.8-8.6,8.6c-8.4,8.6-16.4,16.6-25.1,24.4c-0.2,0.2-0.4,0.3-0.5,0.5 c-8.6,8.6-7,17-5.2,22.7c0.1,0.3,0.2,0.6,0.3,0.9c7.1,17.2,17.1,33.4,32.3,52.7l0.1,0.1c27.6,34,56.7,60.5,88.8,80.8 c4.1,2.6,8.3,4.7,12.3,6.7c3.6,1.8,7,3.5,9.9,5.3c0.4,0.2,0.8,0.5,1.2,0.7c3.4,1.7,6.6,2.5,9.9,2.5c8.3,0,13.5-5.2,15.2-6.9 l34.2-34.2c3.4-3.4,8.8-7.5,15.1-7.5c6.2,0,11.3,3.9,14.4,7.3c0.1,0.1,0.1,0.1,0.2,0.2l55.1,55.1 C420.456,377.706,420.456,388.206,410.256,398.806z"/><path d="M256.056,112.706c26.2,4.4,50,16.8,69,35.8s31.3,42.8,35.8,69c1.1,6.6,6.8,11.2,13.3,11.2c0.8,0,1.5-0.1,2.3-0.2 c7.4-1.2,12.3-8.2,11.1-15.6c-5.4-31.7-20.4-60.6-43.3-83.5s-51.8-37.9-83.5-43.3c-7.4-1.2-14.3,3.7-15.6,11 S248.656,111.506,256.056,112.706z"/><path d="M473.256,209.006c-8.9-52.2-33.5-99.7-71.3-137.5s-85.3-62.4-137.5-71.3c-7.3-1.3-14.2,3.7-15.5,11 c-1.2,7.4,3.7,14.3,11.1,15.6c46.6,7.9,89.1,30,122.9,63.7c33.8,33.8,55.8,76.3,63.7,122.9c1.1,6.6,6.8,11.2,13.3,11.2 c0.8,0,1.5-0.1,2.3-0.2C469.556,223.306,474.556,216.306,473.256,209.006z"/></g></g></svg>
		    	Call Logs</a>
		  </li>
		  <li class="nav-item" style="">
		    <a class="nav-link " aria-current="page" href="conversations.php" <?php if (!isset($_GET['chat'])): ?>
		    	style="background: transparent;color: black;border: 1px solid #d9d6d6;border-bottom: 1px solid white;"
		    <?php endif ?> >
		    	<svg  style="width: 30px;height: 20px;" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000" xml:space="preserve">
			<g><path d="M834.1,512.7c-7.5,11.6-18.3,20.7-32.4,27.1c-14.1,6.5-30,9.7-47.6,9.7c-22.3,0-41.1-3.3-56.2-9.8c-15.1-6.5-26.9-16.3-35.5-29.4c-8.6-13.1-13.1-27.9-13.6-44.4l30.8-2.7c1.5,12.4,4.9,22.5,10.2,30.4c5.3,7.9,13.6,14.3,24.8,19.2c11.2,4.9,23.9,7.3,37.9,7.3c12.5,0,23.5-1.9,33-5.6c9.5-3.7,16.6-8.8,21.3-15.2c4.7-6.5,7-13.5,7-21.1c0-7.7-2.2-14.5-6.7-20.3c-4.5-5.8-11.9-10.6-22.2-14.6c-6.6-2.6-21.3-6.6-44-12c-22.7-5.4-38.6-10.6-47.7-15.4c-11.8-6.2-20.6-13.8-26.4-23c-5.8-9.2-8.7-19.4-8.7-30.7c0-12.5,3.5-24.1,10.6-34.9c7.1-10.8,17.4-19.1,31-24.7c13.6-5.6,28.7-8.4,45.3-8.4c18.3,0,34.4,2.9,48.4,8.8c14,5.9,24.7,14.6,32.3,26c7.5,11.4,11.6,24.4,12.1,38.9l-31.3,2.4c-1.7-15.6-7.4-27.4-17.1-35.4c-9.7-8-24.1-12-43-12c-19.8,0-34.2,3.6-43.2,10.9c-9,7.2-13.6,16-13.6,26.2c0,8.9,3.2,16.2,9.6,21.9c6.3,5.7,22.7,11.6,49.3,17.6c26.6,6,44.8,11.3,54.7,15.7c14.4,6.6,25,15,31.8,25.2c6.8,10.2,10.3,21.9,10.3,35.1C845.4,488.7,841.6,501,834.1,512.7 M607.2,545.2h-31.5V338.6L504,545.2h-29.5l-71.4-210.2v210.2h-31.5V298.3h49.2l58.4,174.8c5.4,16.3,9.3,28.5,11.8,36.5c2.8-9,7.2-22.2,13.1-39.6l59.1-171.8h44V545.2L607.2,545.2z M316.7,512.7c-7.5,11.6-18.3,20.7-32.4,27.1c-14.1,6.5-30,9.7-47.6,9.7c-22.3,0-41.1-3.3-56.2-9.8c-15.1-6.5-26.9-16.3-35.5-29.4c-8.6-13.1-13.1-27.9-13.6-44.4l30.8-2.7c1.5,12.4,4.9,22.5,10.2,30.4c5.3,7.9,13.6,14.3,24.8,19.2c11.2,4.9,23.9,7.3,37.9,7.3c12.5,0,23.5-1.9,33-5.6c9.5-3.7,16.6-8.8,21.3-15.2c4.7-6.5,7-13.5,7-21.1c0-7.7-2.2-14.5-6.7-20.3c-4.5-5.8-11.9-10.6-22.2-14.6c-6.6-2.6-21.3-6.6-44-12c-22.7-5.4-38.6-10.6-47.7-15.4c-11.8-6.2-20.6-13.8-26.4-23c-5.8-9.2-8.7-19.4-8.7-30.7c0-12.5,3.5-24.1,10.6-34.9c7.1-10.8,17.4-19.1,31-24.7c13.6-5.6,28.7-8.4,45.3-8.4c18.3,0,34.4,2.9,48.4,8.8c14,5.9,24.7,14.6,32.3,26c7.5,11.4,11.6,24.4,12.1,38.9l-31.3,2.4c-1.7-15.6-7.4-27.4-17.1-35.4c-9.7-8-24.1-12-43-12c-19.8,0-34.2,3.6-43.2,10.9c-9,7.2-13.6,16-13.6,26.2c0,8.9,3.2,16.2,9.6,21.9c6.3,5.7,22.7,11.6,49.3,17.6c26.6,6,44.8,11.3,54.7,15.7c14.4,6.6,25,15,31.8,25.2c6.8,10.2,10.3,21.9,10.3,35.1C328,488.7,324.2,501,316.7,512.7 M499.9,35.3C229.5,35.3,10,208.1,10,421.1C10,538.9,76.7,644.2,182,715c35.5,23.9,28.3,120.7-81.2,249.8c208.9-28.3,283.6-166.5,329.1-161.4c23,2.5,46.3,3.8,70.1,3.8c270.6,0,490.1-172.6,490.1-386.1C990,208.1,770.5,35.3,499.9,35.3"/></g>
			</svg>Sms</a>
		  </li>
		  <li class="nav-item">
		    <a class="nav-link" href="conversations.php?chat=fb" <?php if (isset($_GET['chat']) && $_GET['chat'] == 'fb'): ?>
		    	style="background: transparent;color: black;border: 1px solid #d9d6d6;border-bottom: 1px solid white;"
		    <?php endif ?>>
		    	<svg height="800px" width="800px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 309.142 309.142" xml:space="preserve" style="width: 30px;height: 18px;">
				<g>
					<path style="fill:#005CB9;" d="M154.571,0C69.662,0,0.83,68.831,0.83,153.741c0,38.283,13.997,73.295,37.147,100.207
						c-6.953,19.986-19.807,37.209-36.479,49.581c10.592,3.619,21.938,5.613,33.757,5.613c20.214,0,39.082-5.751,55.067-15.698
						c19.551,9.007,41.312,14.039,64.249,14.039c84.909,0,153.741-68.833,153.741-153.742C308.313,68.831,239.48,0,154.571,0z"></path>
					<g>
						<g>
							<path style="fill:#FFFFFF;" d="M131.495,240.044h36.112c0,0,0-49.849,0-90.282h26.804l3.29-36.115h-28.7v-14.44
								c0-7.037,4.69-8.671,7.984-8.671c3.297,0,20.258,0,20.258,0V59.593l-27.895-0.113c-30.963,0-38.002,23.075-38.002,37.849v16.318
								H113.44v36.115h18.056C131.495,190.682,131.495,240.044,131.495,240.044z"></path>
						</g>
					</g>
				</g>
				</svg>Facebook</a>
		  </li>
		  <li class="nav-item">
		    <a class="nav-link" href="conversations.php?chat=insta" <?php if (isset($_GET['chat']) && $_GET['chat'] == 'insta'): ?>
		    	style="background: transparent;color: black;border: 1px solid #d9d6d6;border-bottom: 1px solid white;"
		    <?php endif ?>>
		    	<svg style="height: 24px;width: 18px;" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 48 48" width="48px" height="48px"><radialGradient id="yOrnnhliCrdS2gy~4tD8ma" cx="19.38" cy="42.035" r="44.899" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#fd5"/><stop offset=".328" stop-color="#ff543f"/><stop offset=".348" stop-color="#fc5245"/><stop offset=".504" stop-color="#e64771"/><stop offset=".643" stop-color="#d53e91"/><stop offset=".761" stop-color="#cc39a4"/><stop offset=".841" stop-color="#c837ab"/></radialGradient><path fill="url(#yOrnnhliCrdS2gy~4tD8ma)" d="M34.017,41.99l-20,0.019c-4.4,0.004-8.003-3.592-8.008-7.992l-0.019-20	c-0.004-4.4,3.592-8.003,7.992-8.008l20-0.019c4.4-0.004,8.003,3.592,8.008,7.992l0.019,20	C42.014,38.383,38.417,41.986,34.017,41.99z"/><radialGradient id="yOrnnhliCrdS2gy~4tD8mb" cx="11.786" cy="5.54" r="29.813" gradientTransform="matrix(1 0 0 .6663 0 1.849)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#4168c9"/><stop offset=".999" stop-color="#4168c9" stop-opacity="0"/></radialGradient><path fill="url(#yOrnnhliCrdS2gy~4tD8mb)" d="M34.017,41.99l-20,0.019c-4.4,0.004-8.003-3.592-8.008-7.992l-0.019-20	c-0.004-4.4,3.592-8.003,7.992-8.008l20-0.019c4.4-0.004,8.003,3.592,8.008,7.992l0.019,20	C42.014,38.383,38.417,41.986,34.017,41.99z"/><path fill="#fff" d="M24,31c-3.859,0-7-3.14-7-7s3.141-7,7-7s7,3.14,7,7S27.859,31,24,31z M24,19c-2.757,0-5,2.243-5,5	s2.243,5,5,5s5-2.243,5-5S26.757,19,24,19z"/><circle cx="31.5" cy="16.5" r="1.5" fill="#fff"/><path fill="#fff" d="M30,37H18c-3.859,0-7-3.14-7-7V18c0-3.86,3.141-7,7-7h12c3.859,0,7,3.14,7,7v12	C37,33.86,33.859,37,30,37z M18,13c-2.757,0-5,2.243-5,5v12c0,2.757,2.243,5,5,5h12c2.757,0,5-2.243,5-5V18c0-2.757-2.243-5-5-5H18z"/></svg>
		    	Instagram</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" href="conversations.php?chat=whatsapp" <?php if (isset($_GET['chat']) && $_GET['chat'] == 'whatsapp'): ?>
				style="background: transparent;color: black;border: 1px solid #d9d6d6;border-bottom: 1px solid white;"
			<?php endif ?>>
				<i class="fa fa-whatsapp"></i>
				WhatsApp</a>
			</li>
		</ul>
		<div class="row" style="padding: 15px;">
		<?php
			if(!isset($_GET['chat']) || (isset($_GET['chat']) && $_GET['chat'] != 'whatsapp')){
				?>
			<div class="col-md-3" style="padding: 0px;">
				<div class="card-body" style="max-height:600px;min-height:700px; overflow-y: auto" id="checkScroll">
					<input type="text" onKeyUp="searchInSideBar(this,event)" autocomplete="off" class="form-control" id="exampleInputIconLeft" placeholder="Search" aria-label="Search" style="margin-bottom: 10px" onBlur="searchInSideBar(this,event)">
					
					<div id="sideBarContacts">
					<?php
						$sql = "SELECT DISTINCT customer_number, MAX(created_date) 
								FROM conversations 
								where user_id='".$_SESSION['company_id']."'
								GROUP BY customer_number 
								ORDER BY MAX(created_date) DESC, customer_number";
						$res = mysqli_query($link,$sql);
						$allcount = mysqli_num_rows($res);
						if (isset($_GET['chat']) && ($_GET['chat'] == 'fb' || $_GET['chat'] == 'insta')) {
							$msg='';
							$msg = new FBChat('EABXfZCfLPDE4BAOLgnG8G4WJo6kfuVqXE6Cd6XqgZCqYpBJXBLBWMdiV4aQnEZAoIokFA4HcN7CWMvQdvNgr1mYDpThlpZB5otgwXTvbuACeTJhRRlgp351ypaZCGzok3ZAca1ZCWay68mDl17AIK52f9RJHbnudgAVHovyVnoAZBDtJpyHiNqfZCAHLDT9UQdgdqhlwYZBJ95aQ0XSOovw6mx20NYvzJO8doZD');
							// echo "string";
							// echo $msg->id;
							$sql = "SELECT DISTINCT sender,from_name,
							(SELECT timestamp from fb_msgs t2 WHERE t1.sender=t2.sender OR t1.sender=t2.recipient  ORDER BY id DESC LIMIT 1) AS timestamp,
							(SELECT text from fb_msgs t2 WHERE t1.sender=t2.sender  OR t1.sender=t2.recipient ORDER BY id DESC LIMIT 1) AS text
								FROM fb_msgs t1
								where user_id='".$_SESSION['company_id']."'
								AND sender !='".$msg->id."'
								AND platform = '".$_GET['chat']."'
								GROUP BY from_name 
								";
							$res = mysqli_query($link,$sql);
							$allcount = mysqli_num_rows($res);
							// print_r($msg);
							if ($allcount) { 
								while($row = mysqli_fetch_assoc($res)){
									?>
												<div id="<?php echo $row['sender']?>_container" class="d-flex align-items-center justify-content-between border-bottom py-3">
													<div style="width: 100%">
														<div class="h6 mb-0 align-items-center">
															<a href="javascript:void(0)" id="<?php echo $row['sender']?>_chatStarter" onClick="getChats(this,'<?php echo $row['sender']?>')" style="width: 140px;display: inline-block;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;"><?php echo $row['from_name']?></a>
															<span id="<?php echo $row['sender']?>_msgTime" style="font-size: 12px;float: right"><?php echo  date("d M, H:i a",((int)round($row['timestamp']/1000)))  ?></span>
														</div>
														<div id="<?php echo $row['sender']?>_message" class="showMessage small card-stats">
															<?php echo $row['text']  ?>
														</div>
													</div>
												</div>		
									<?php
								}
							} else{
								?>
								<div class="d-flex align-items-center justify-content-between border-bottom py-3">
								<div>
									<div class="h6 mb-0 d-flex align-items-center">
										No conversation found.
										</div>
									</div>
								</div>	
								<?php
							}
							
						}
						elseif(isset($_GET['chat']) && $_GET['chat'] == 'calllogs'){
							include_once 'templates/twilio_call_logs.php';
						}
						elseif($allcount){
							while($row = mysqli_fetch_assoc($res)){
								$customerInfo = getCustomerInfoByNumber($row['customer_number']);
								$lastMsg = getLatestMsgByNumber($row['customer_number']);
								$customerName = '';
								
								if($customerInfo)
									$customerName = $customerInfo['first_name'].' '.$customerInfo['last_name'];
								if(trim($customerName)==''){
									$customerName = $row['customer_number'];
								}
					?>
								<div id="<?php echo $row['customer_number']?>_container" class="d-flex align-items-center justify-content-between border-bottom py-3">
									<div style="width: 100%">
										<div class="h6 mb-0 align-items-center">
											<i class="icon icon-xs me-2 fa fa-phone" style="color: darkorange; cursor: pointer" onClick="showDialer(this,'<?php echo $row['customer_number']?>')"></i>
											<a href="javascript:void(0)" id="<?php echo $row['customer_number']?>_chatStarter" onClick="getChats(this,'<?php echo $row['customer_number']?>')" style="width: 140px;display: inline-block;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;"><?php echo $customerName?></a>
											<span id="<?php echo $row['customer_number']?>_msgTime" style="font-size: 12px;float: right"><?php echo date("d M, H:i a",strtotime($lastMsg['created_date']));?></span>
										</div>
										<div id="<?php echo $row['customer_number']?>_message" class="showMessage small card-stats">
											<?php echo $lastMsg['message'];?>
										</div>
									</div>
								</div>		
					<?php
							}
					?>
							<input type="hidden" id="start" value="0">
							<input type="hidden" id="rowperpage" value="<?= $rowperpage ?>">
							<input type="hidden" id="totalrecords" value="<?= $allcount ?>">
					<?php
						}
			   			else{
					?>
							<div class="d-flex align-items-center justify-content-between border-bottom py-3">
								<div>
									<div class="h6 mb-0 d-flex align-items-center">
										No conversation found.
									</div>
								</div>
							</div>	
					<?php
						}
					?>
					</div>
				</div>
			</div>
			<div class="col-md-9">
				<div class="row justify-content-center" style="min-height: 715px">
					<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top" id="chatInfo" style="display: none; background: #f2f4f6 !important">
						<div class="container-fluid">
							<div class="collapse navbar-collapse" id="navbarExample01">
								
								<div class="col-md-6 showName">
								<span></span>
									<!--
									<a data-toggle="tooltip" onclick="$('#input[name=contact_phone]').val($(this).attr('data-number')); getContact($(this).attr('data-number'));" data-number="" title="Save Contact!" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addContact" class="addContact"><i class="fa fa-users " style="color: darkorange;"></i></a>
									-->
									<a data-toggle="tooltip" title="Save Contact!" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addContact" class="addContact" onClick="getCustomerID()"><i class="fa fa-users " style="color: darkorange;"></i></a>
									<!--
									<a  title="Add to Pipeline!" data-number="" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addToPipeline" class="addToPipeline" ><i class="fa fa-download " style="color: lightgreen;"></i></a>
									-->
									<a data-toggle="tooltip" title="Update Contact!" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addContact" class="updateContact" onClick="getContact();"><i class="fa fa-users " style="color: green;"></i></a>
									
								</div>
								
								<!--<div class="col-md-1 showNumber"></div>-->
								<div class="col-md-2"></div>
								<div class="col-md-1"><img src="assets/img/loading.gif" id="loading" style="display: none"></div>
								<div class="col-md-3">
									<input type="text" name="search_in_chat" id="search_in_chat" class="form-control" placeholder="Search in chat" onKeyUp="searchInChat(this)">
								</div>
								<!--
								<ul class="navbar-nav me-auto mb-2 mb-lg-0">
									<li class="nav-item showName"></li>
									<li class="nav-item showNumber"></li>
									<li class="nav-item">
										<input type="text" name="search_in_chat" id="search_in_chat" class="form-control">
									</li>
								</ul>
								-->
							</div>
						</div>
					</nav>
					<div class="col-12" id="chatContainer" style="overflow-y:auto; height: 570px;background-color: beige">
						<div id="welcomeScreen" class="modal-dialog modal-info modal-dialog-centered" role="document" style="width: 100%; max-width: 100%; margin: 0px;">
							<div class="modal-content bg-gradient-secondary">
								<div class="modal-header"></div>
								<div class="modal-body text-white">
									<div class="py-3 text-center">
										<span class="modal-icon">
											<svg class="icon icon-xl text-gray-200" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M8.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 00-1.414-1.414L11 7.586V3a1 1 0 10-2 0v4.586l-.293-.293z"></path><path d="M3 5a2 2 0 012-2h1a1 1 0 010 2H5v7h2l1 2h4l1-2h2V5h-1a1 1 0 110-2h1a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"></path></svg>
										</span>
										<h2 class="h4 modal-title my-3">Select a Contact!</h2>
										<p>Please select a contact from left bar and start chat!</p>
									</div>
								</div>
								<div class="modal-footer">
									<!--<button type="button" class="btn btn-sm btn-white">Go to Inbox</button>-->
								</div>
							</div>
						</div>
					</div>
					
					<div class="sendingSection" style="margin-top: 10px;">
						<form action="#" id="chatFrom" class="chatForm" enctype="multipart/form-data" method="post" style="display: none">
							<div class="row">
								<div class="col-md-3" style="padding: 0px; display: flex">
									<span data-bs-toggle="modal" data-bs-target="#modal-default" style="cursor: pointer;padding: 0px 10px;font-size: 20px;">&#128512;</span>
									
									<i class="fa fa-folder" style="cursor: pointer;font-size: 30px; color: orange" onClick="showUserMedia(this)" data-bs-toggle="modal" data-bs-target="#showUserMedia"></i>&nbsp;&nbsp;
									
									<i class="fa fa-sticky-note" data-bs-toggle="modal" data-bs-target="#addNotes" style="cursor: pointer; font-size: 28px; color: green" onClick="getNotes(this)"></i>&nbsp;&nbsp;
									
									<i class="fa fa-calendar-check-o" data-bs-toggle="modal" data-bs-target="#todoList" style="cursor: pointer; font-size: 28px; color: blue" onClick="openTodoList(this)"></i>&nbsp;&nbsp;
									
									<div class="file-field">
										<!--
										<span class="popuptext" id="myPopup">
											<img src="" class="media_preview" style="display: none" />
										</span>
										-->
										<img src="" class="media_preview" style="display: none; width: 30px;" />
										<div class="d-flex justify-content-center">
											<div class="d-flex align-items-center">
												<svg class="icon icon-md text-gray-400 me-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" style="cursor: pointer;"><path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"></path></svg>
												<input type="file" name="chat_media" id="chat_media" style="width: 40px; max-width: 40px;padding-bottom: 0px;" onChange="showMediaPreview()">  
											</div>
										</div>
									</div>
									<!-- <div class="file-field">
										<div class="d-flex justify-content-center">
											<a href="?chat=fb">
												<svg height="800px" width="800px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 309.142 309.142" xml:space="preserve" style="width: 25px;height: 28px;">
												<g>
													<path style="fill:#005CB9;" d="M154.571,0C69.662,0,0.83,68.831,0.83,153.741c0,38.283,13.997,73.295,37.147,100.207
														c-6.953,19.986-19.807,37.209-36.479,49.581c10.592,3.619,21.938,5.613,33.757,5.613c20.214,0,39.082-5.751,55.067-15.698
														c19.551,9.007,41.312,14.039,64.249,14.039c84.909,0,153.741-68.833,153.741-153.742C308.313,68.831,239.48,0,154.571,0z"></path>
													<g>
														<g>
															<path style="fill:#FFFFFF;" d="M131.495,240.044h36.112c0,0,0-49.849,0-90.282h26.804l3.29-36.115h-28.7v-14.44
																c0-7.037,4.69-8.671,7.984-8.671c3.297,0,20.258,0,20.258,0V59.593l-27.895-0.113c-30.963,0-38.002,23.075-38.002,37.849v16.318
																H113.44v36.115h18.056C131.495,190.682,131.495,240.044,131.495,240.044z"></path>
														</g>
													</g>
												</g>
												</svg>
											</a>
										</div>
									</div> -->
								</div>
								<div class="col-md-8">
									<textarea class="form-control shadow mb-4" name="message" id="message" placeholder="Your Message" maxlength="1000" required onKeyPress="OnKeyPress(event)"></textarea>
								</div>
								<div class="col-md-1">
									<input type="hidden" name="to_number" id="to_number" value="">
									<button class="btn btn-icon-only btn-facebook d-inline-flex align-items-center" type="submit">
										<i class="fa fa-send-o"></i>
                                    </button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<?php	
		}elseif(isset($_GET['chat']) && $_GET['chat'] == 'whatsapp'){
			include_once 'templates/whatsapp_conversations.php';
		}
			?>
		</div>
	</div>
	<div class="modal fade" id="todoList" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="h6 modal-title" id="">To do List</h2>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="task-wrapper border bg-white shadow border-0 rounded">
						<div class="card hover-state border-bottom rounded-0 rounded-top py-3">
							<div class="card-body d-sm-flex align-items-center flex-wrap flex-lg-nowrap py-0">
								<div class="col-1 text-left text-sm-center mb-2 mb-sm-0">
									<div class="form-check check-lg inbox-check me-sm-2">
										<input class="form-check-input" type="checkbox" value="" id="mailCheck1" checked> 
										<label class="form-check-label" for="mailCheck1"></label>
									</div>
								</div>
								<div class="col-11 col-lg-8 px-0 mb-4 mb-md-0">
									<div class="mb-2">
										<h3 class="h5 line-through">Meeting with Ms.Bonnie from Themesberg LLC</h3>
										<div class="d-block d-sm-flex">
											<div>                                    
												<h4 class="h6 fw-normal text-gray mb-3 mb-sm-0">
													<svg class="icon icon-xxs text-gray-500 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
													10:00 AM
												</h4>
											</div>
											<div class="ms-sm-3"><span class="badge super-badge bg-success">Done</span></div>
										</div>
									</div>
									<div>
										<a href="./single-message.html" class="fw-bold text-dark">
											<span class="fw-normal text-gray line-through">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi pulvinar feugiat consequat. Duis lacus nibh, sagittis id varius vel, aliquet non augue.  </span> 
										</a>
									</div>
								</div>
								<div class="col-10 col-sm-2 col-lg-2 col-xl-2 d-none d-lg-block d-xl-inline-flex align-items-center ms-lg-auto text-right justify-content-end px-md-0">
									<div class="dropdown">
										<button class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"></path></svg>
											<span class="visually-hidden">Toggle Dropdown</span>
										</button>
										<div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1">
											<a class="dropdown-item d-flex align-items-center" href="#">
												<svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg>
												Edit
											</a>
											<a class="dropdown-item d-flex align-items-center" href="#">
												<svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
												Mark as Important
											</a>
											<a class="dropdown-item d-flex align-items-center" href="#">
												<svg class="dropdown-icon text-danger me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
												Delete
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card hover-state border-bottom rounded-0 py-3">
							<div class="card-body d-sm-flex align-items-center flex-wrap flex-lg-nowrap py-0">
								<div class="col-1 text-left text-sm-center mb-2 mb-sm-0">
									<div class="form-check check-lg inbox-check me-sm-2">
										<input class="form-check-input" type="checkbox" value="" id="mailCheck11"> 
										<label class="form-check-label" for="mailCheck11"></label>
									</div>
								</div>
								<div class="col-11 col-lg-8 px-0 mb-4 mb-md-0">
									<div class="mb-2">
										<h3 class="h5">Meeting with Ms.Bonnie from Themesberg LLC</h3>
										<div class="d-block d-sm-flex">
											<div>                                    
												<h4 class="h6 fw-normal text-gray d-flex align-items-center mb-3 mb-sm-0">
													<svg class="icon icon-xxs text-gray-500 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
													10:00 AM
												</h4>
											</div>
											<div class="ms-sm-3"><span class="badge super-badge bg-warning">In Progress</span></div>
										</div>
									</div>
									<div>
										<a href="./single-message.html" class="fw-bold text-dark">
											<span class="fw-normal text-gray">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi pulvinar feugiat consequat. Duis lacus nibh, sagittis id varius vel, aliquet non augue.  </span> 
										</a>
									</div>
								</div>
								<div class="col-10 col-sm-2 col-lg-2 col-xl-2 d-none d-lg-block d-xl-inline-flex align-items-center ms-lg-auto text-right justify-content-end px-md-0">
									<div class="dropdown">
										<button class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"></path></svg>
											<span class="visually-hidden">Toggle Dropdown</span>
										</button>
										<div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1">
											<a class="dropdown-item d-flex align-items-center" href="#">
												<svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg>
												Edit
											</a>
											<a class="dropdown-item d-flex align-items-center" href="#">
												<svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
												Mark as Important
											</a>
											<a class="dropdown-item d-flex align-items-center" href="#">
												<svg class="dropdown-icon text-danger me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
												Delete
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card hover-state border-bottom rounded-0 py-3">
							<div class="card-body d-sm-flex align-items-center flex-wrap flex-lg-nowrap py-0">
								<div class="col-1 text-left text-sm-center mb-2 mb-sm-0">
									<div class="form-check check-lg inbox-check me-sm-2">
										<input class="form-check-input" type="checkbox" value="" id="mailCheck2"> 
										<label class="form-check-label" for="mailCheck2"></label>
									</div>
								</div>
								<div class="col-11 col-lg-8 px-0 mb-4 mb-md-0">
									<div class="mb-2">
										<h3 class="h5">Meeting with Ms.Bonnie from Themesberg LLC</h3>
										<div class="d-block d-sm-flex">
											<div>                                    
												<h4 class="h6 fw-normal text-gray d-flex align-items-center mb-3 mb-sm-0">
													<svg class="icon icon-xxs text-gray-500 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
													10:00 AM
												</h4>
											</div>
											<div class="ms-sm-3"><span class="badge super-badge bg-warning">In Progress</span></div>
										</div>
									</div>
									<div>
										<a href="./single-message.html" class="fw-bold text-dark">
											<span class="fw-normal text-gray">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi pulvinar feugiat consequat. Duis lacus nibh, sagittis id varius vel, aliquet non augue.  </span> 
										</a>
									</div>
								</div>
								<div class="col-10 col-sm-2 col-lg-2 col-xl-2 d-none d-lg-block d-xl-inline-flex align-items-center ms-lg-auto text-right justify-content-end px-md-0">
									<div class="dropdown">
										<button class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"></path></svg>
											<span class="visually-hidden">Toggle Dropdown</span>
										</button>
										<div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1">
											<a class="dropdown-item d-flex align-items-center" href="#">
												<svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg>
												Edit
											</a>
											<a class="dropdown-item d-flex align-items-center" href="#">
												<svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
												Mark as Important
											</a>
											<a class="dropdown-item d-flex align-items-center" href="#">
												<svg class="dropdown-icon text-danger me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
												Delete
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card hover-state border-bottom rounded-0 py-3">
							<div class="card-body d-sm-flex align-items-center flex-wrap flex-lg-nowrap py-0">
								<div class="col-1 text-left text-sm-center mb-2 mb-sm-0">
									<div class="form-check check-lg inbox-check me-sm-2">
										<input class="form-check-input" type="checkbox" value="" id="mailCheck3"> 
										<label class="form-check-label" for="mailCheck3"></label>
									</div>
								</div>
								<div class="col-11 col-lg-8 px-0 mb-4 mb-md-0">
									<div class="mb-2">
										<h3 class="h5">Meeting with Ms.Bonnie from Themesberg LLC</h3>
										<div class="d-block d-sm-flex">
											<div>                                    
												<h4 class="h6 fw-normal text-gray d-flex align-items-center mb-3 mb-sm-0">
													<svg class="icon icon-xxs text-gray-500 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
													10:00 AM
												</h4>
											</div>
											<div class="ms-sm-3"><span class="badge super-badge bg-warning">In Progress</span></div>
										</div>
									</div>
									<div>
										<a href="./single-message.html" class="fw-bold text-dark">
											<span class="fw-normal text-gray">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi pulvinar feugiat consequat. Duis lacus nibh, sagittis id varius vel, aliquet non augue.  </span> 
										</a>
									</div>
								</div>
								<div class="col-10 col-sm-2 col-lg-2 col-xl-2 d-none d-lg-block d-xl-inline-flex align-items-center ms-lg-auto text-right justify-content-end px-md-0">
									<div class="dropdown">
										<button class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"></path></svg>
											<span class="visually-hidden">Toggle Dropdown</span>
										</button>
										<div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1">
											<a class="dropdown-item d-flex align-items-center" href="#">
												<svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg>
												Edit
											</a>
											<a class="dropdown-item d-flex align-items-center" href="#">
												<svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
												Mark as Important
											</a>
											<a class="dropdown-item d-flex align-items-center" href="#">
												<svg class="dropdown-icon text-danger me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
												Delete
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card hover-state border-bottom rounded-0 py-3">
							<div class="card-body d-sm-flex align-items-center flex-wrap flex-lg-nowrap py-0">
								<div class="col-1 text-left text-sm-center mb-2 mb-sm-0">
									<div class="form-check check-lg inbox-check me-sm-2">
										<input class="form-check-input" type="checkbox" value="" id="mailCheck4"> 
										<label class="form-check-label" for="mailCheck4"></label>
									</div>
								</div>
								<div class="col-11 col-lg-8 px-0 mb-4 mb-md-0">
									<div class="mb-2">
										<h3 class="h5">Meeting with Ms.Bonnie from Themesberg LLC</h3>
										<div class="d-block d-sm-flex">
											<div>                                    
												<h4 class="h6 fw-normal text-gray d-flex align-items-center mb-3 mb-sm-0">
													<svg class="icon icon-xxs text-gray-500 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
													10:00 AM
												</h4>
											</div>
											<div class="ms-sm-3"><span class="badge super-badge bg-purple">Waiting</span></div>
										</div>
									</div>
									<div>
										<a href="./single-message.html" class="fw-bold text-dark">
											<span class="fw-normal text-gray">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi pulvinar feugiat consequat. Duis lacus nibh, sagittis id varius vel, aliquet non augue.  </span> 
										</a>
									</div>
								</div>
								<div class="col-10 col-sm-2 col-lg-2 col-xl-2 d-none d-lg-block d-xl-inline-flex align-items-center ms-lg-auto text-right justify-content-end px-md-0">
									<div class="dropdown">
										<button class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"></path></svg>
											<span class="visually-hidden">Toggle Dropdown</span>
										</button>
										<div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1">
											<a class="dropdown-item d-flex align-items-center" href="#">
												<svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg>
												Edit
											</a>
											<a class="dropdown-item d-flex align-items-center" href="#">
												<svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
												Mark as Important
											</a>
											<a class="dropdown-item d-flex align-items-center" href="#">
												<svg class="dropdown-icon text-danger me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
												Delete
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card hover-state border-bottom rounded-0 py-3">
							<div class="card-body d-sm-flex align-items-center flex-wrap flex-lg-nowrap py-0">
								<div class="col-1 text-left text-sm-center mb-2 mb-sm-0">
									<div class="form-check check-lg inbox-check me-sm-2">
										<input class="form-check-input" type="checkbox" value="" id="mailCheck5"> 
										<label class="form-check-label" for="mailCheck5"></label>
									</div>
								</div>
								<div class="col-11 col-lg-8 px-0 mb-4 mb-md-0">
									<div class="mb-2">
										<h3 class="h5">Meeting with Ms.Bonnie from Themesberg LLC</h3>
										<div class="d-block d-sm-flex">
											<div>                                    
												<h4 class="h6 fw-normal text-gray d-flex align-items-center mb-3 mb-sm-0">
													<svg class="icon icon-xxs text-gray-500 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
													10:00 AM
												</h4>
											</div>
											<div class="ms-sm-3"><span class="badge super-badge bg-purple">Waiting</span></div>
										</div>
									</div>
									<div>
										<a href="./single-message.html" class="fw-bold text-dark">
											<span class="fw-normal text-gray">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi pulvinar feugiat consequat. Duis lacus nibh, sagittis id varius vel, aliquet non augue.  </span> 
										</a>
									</div>
								</div>
								<div class="col-10 col-sm-2 col-lg-2 col-xl-2 d-none d-lg-block d-xl-inline-flex align-items-center ms-lg-auto text-right justify-content-end px-md-0">
									<div class="dropdown">
										<button class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"></path></svg>
											<span class="visually-hidden">Toggle Dropdown</span>
										</button>
										<div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1">
											<a class="dropdown-item d-flex align-items-center" href="#">
												<svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg>
												Edit
											</a>
											<a class="dropdown-item d-flex align-items-center" href="#">
												<svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
												Mark as Important
											</a>
											<a class="dropdown-item d-flex align-items-center" href="#">
												<svg class="dropdown-icon text-danger me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
												Delete
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card hover-state border-bottom rounded-0 py-3">
							<div class="card-body d-sm-flex align-items-center flex-wrap flex-lg-nowrap py-0">
								<div class="col-1 text-left text-sm-center mb-2 mb-sm-0">
									<div class="form-check check-lg inbox-check me-sm-2">
										<input class="form-check-input" type="checkbox" value="" id="mailCheck6"> 
										<label class="form-check-label" for="mailCheck6"></label>
									</div>
								</div>
								<div class="col-11 col-lg-8 px-0 mb-4 mb-md-0">
									<div class="mb-2">
										<h3 class="h5">Meeting with Ms.Bonnie from Themesberg LLC</h3>
										<div class="d-block d-sm-flex">
											<div>                                    
												<h4 class="h6 fw-normal text-gray d-flex align-items-center mb-3 mb-sm-0">
													<svg class="icon icon-xxs text-gray-500 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
													10:00 AM
												</h4>
											</div>
											<div class="ms-sm-3"><span class="badge super-badge bg-purple">Waiting</span></div>
										</div>
									</div>
									<div>
										<a href="./single-message.html" class="fw-bold text-dark">
											<span class="fw-normal text-gray">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi pulvinar feugiat consequat. Duis lacus nibh, sagittis id varius vel, aliquet non augue.  </span> 
										</a>
									</div>
								</div>
								<div class="col-10 col-sm-2 col-lg-2 col-xl-2 d-none d-lg-block d-xl-inline-flex align-items-center ms-lg-auto text-right justify-content-end px-md-0">
									<div class="dropdown">
										<button class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"></path></svg>
											<span class="visually-hidden">Toggle Dropdown</span>
										</button>
										<div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1">
											<a class="dropdown-item d-flex align-items-center" href="#">
												<svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg>
												Edit
											</a>
											<a class="dropdown-item d-flex align-items-center" href="#">
												<svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
												Mark as Important
											</a>
											<a class="dropdown-item d-flex align-items-center" href="#">
												<svg class="dropdown-icon text-danger me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
												Delete
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row p-4">
							<div class="col-7 mt-1">
								Showing 1 - 20 of 289
							</div>
							<div class="col-5">
								<div class="btn-group float-end">
									<a href="#" class="btn btn-gray-100">
										<svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
									</a>
									<a href="#" class="btn btn-gray-800">
										<svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="cmd" value="save_notes">
					<button type="button" class="btn btn-secondary" onClick="alert('Under development')">Save</button>
					<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="modal-default" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content" style="display: block; padding: 15px;text-align: center">
				<div class="modal-header" style="margin-bottom: 10px;">
					<h2 class="h6 modal-title">Select an Emoji</h2>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<?php
					$emojis = emojis();
					foreach($emojis["smiley_faces"] as $key => $value){
						echo '<span id="'.$value.'" class="emojiOption">'.$value.'</span>';
					}
				?>
			</div>
		</div>
	</div>
	<div class="modal fade" id="addNotes" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="h6 modal-title" id="">Add Notes</h2>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<textarea name="notes" id="notes" class="form-control" style="height: 200px"></textarea>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="cmd" value="save_notes">
					<button type="button" class="btn btn-secondary" onClick="saveNotes(this)">Save</button>
					<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="addContact" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="h6 modal-title">Add Contact</h2>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="text-center d-none" id="loading_data">
						<p>Loading...</p>
					</div>
					<div class="" id="showforms">
						<nav>
							<div class="nav nav-tabs" id="nav-tab" role="tablist">
								<button class="nav-link nav-link-custom active" id="nav-opportunity-tab" data-bs-toggle="tab" data-bs-target="#nav-opportunity" type="button" role="tab" aria-controls="nav-opportunity" aria-selected="true">Opportunity</button>
								<button class="nav-link nav-link-custom" id="nav-book-ppointment-tab" data-bs-toggle="tab" data-bs-target="#nav-book-ppointment" type="button" role="tab" aria-controls="nav-book-ppointment" aria-selected="false">Book/Update Appointment</button>
								<button class="nav-link nav-link-custom" id="nav-tasks-tab" data-bs-toggle="tab" data-bs-target="#nav-tasks" type="button" role="tab" aria-controls="nav-tasks" aria-selected="false">Tasks</button>
								<button class="nav-link nav-link-custom" id="nav-notes-tab" data-bs-toggle="tab" data-bs-target="#nav-notes" type="button" role="tab" aria-controls="nav-notes" aria-selected="false">Notes</button>
								<button class="nav-link nav-link-custom" id="nav-edit-contact-id-tab" data-bs-toggle="tab" data-bs-target="#nav-edit-contact-id" type="button" role="tab" aria-controls="nav-edit-contact-id" aria-selected="false">Edit Contact ID</button>
							</div>
						</nav>
						<div class="tab-content mt-2" id="nav-tabContent">
							<div class="tab-pane fade show active" id="nav-opportunity" role="tabpanel" aria-labelledby="nav-opportunity-tab">
								<h5 class="mt-4">Contact Info</h5>
								<div class="row contactInfo">
									<div class="col-md-6">
										<label class="mb-0 mt-1">Contact Name</label>
										<input type="text" name="contact_name" class="form-control" value="" placeholder="Contact Name"/>
									</div>
									<div class="col-md-6">
										<label class="mb-0 mt-1">Email</label>
										<input type="text" name="contact_email" class="form-control" value="" placeholder="Email"/>
									</div>
									<div class="col-md-6">
										<label class="mb-0 mt-1">Phone</label>
										<input type="text" name="contact_phone" class="form-control" value="" placeholder="Phone" readonly/>
									</div>
									<!--
										<div class="col-md-6">
											<label class="mb-0 mt-1">Tags</label>
											<input type="text" name="contact_tags" class="form-control" value="" placeholder="Tags" />
										</div>
										-->
									<div class="col-md-6">
										<label class="mb-0 mt-1">Company Name</label>
										<input type="text" name="contact_companyname" class="form-control" value="" placeholder="Company Name"/>
									</div>
								</div>
								<h5 class="mt-4">Opportunity Info</h5>
								<label class="mb-0 mt-1">Opportunity Name</label>
								<input type="text" name="opportunity_name" class="form-control" value="" placeholder="Opportunity Name"/>
								<div class="row">
									<div class="col-md-6">
										<label class="mb-0 mt-1">Pipeline</label>
										<select name="opportunity_pipeline" class="form-control" onchange="getStages(this)">
											<option value="">-- select Pipeline --</option>
											<?php getPipelineOptions($_SESSION['company_id'])?>
										</select>
									</div>
									<div class="col-md-6">
										<label class="mb-0 mt-1">Stage</label>
										<select name="opportunity_stage" id="opportunity_stage" class="form-control"></select>
									</div>
									<div class="col-md-6">
										<label class="mb-0 mt-1">Lead Value</label>
										<input type="number" name="opportunity_leadvalue" class="form-control" value="" placeholder="Lead Value"/>
									</div>
									<div class="col-md-6">
										<label class="mb-0 mt-1">Opportunity Source</label>
										<input type="text" name="opportunity_source" class="form-control" value="" placeholder="Opportunity Source"/>
									</div>
									<div class="col-md-12" id="notesContainer">
										<label class="mb-0 mt-1"><h5>Notes</h5></label>
										<div class="notesContainer">
											<?php
											/*
											$sqlN = "select * from contact_notes where user_id='" . $_SESSION[ 'user_id' ] . "'";
											$resN = mysqli_query( $link, $sqlN );
											if ( mysqli_num_rows( $resN ) ) {
												while ( $rowN = mysqli_fetch_assoc( $resN ) ) {
													echo '<p>' . date( "d/m/Y h:ia", strtotime( $rowN[ 'created_date' ] ) ) . ' => ' . $rowN[ 'notes' ] . '</p>';
												}
											}
											*/
											?>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-md-12">
													<textarea name="opportunity_notes" id="opportunity_notes" class="form-control"></textarea>
												</div>
												<!--
												<div class="col-md-1 text-center">
													<i class="fa fa-plus" title="Save notes" onClick="saveNotes()" style="cursor: pointer"></i>
												</div>
												-->
											</div>
										</div>
									</div>
								</div>

								<div class="modal-footer">
									<input type="hidden" name="redirect_to" id="redirect_to" value="">
									<input type="hidden" name="contact_id" id="contact_id" value="">
									<input type="button" value="Update" class="btn btn-primary" onClick="updateOpportunityBox()">
									<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>
								</div>
							</div>

							<div class="tab-pane fade" id="nav-book-ppointment" role="tabpanel" aria-labelledby="nav-book-ppointment-tab">
								Add a calendar to start booking appointments.
								<div class="modal-footer">
									<input type="button" value="Update" class="btn btn-primary">
									<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>
								</div>
							</div>

							<div class="tab-pane fade" id="nav-tasks" role="tabpanel" aria-labelledby="nav-tasks-tab">
								<label class="mb-0 mt-1">Title</label>
								<input type="text" name="task_title" class="form-control" value="" placeholder="Title"/>
								<label class="mb-0 mt-1">Description</label>
								<textarea name="task_description" class="form-control" placeholder="Task Description"></textarea>
								<label class="mb-0 mt-1">Assign To</label>
								<select name="task_assignto" class="form-control">
									<option value="Not assigned">Not assigned</option>
									<option value="Betsy  WInters">Betsy WInters</option>
									<option value="James Johnson">James Johnson</option>
								</select>
								<label class="mb-0 mt-1">Due Date</label>
								<input type="date" name="task_due_date" class="form-control" value="" placeholder="Task Due Date"/>

								<div class="modal-footer">
									<input type="button" value="Update" class="btn btn-primary">
									<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>
								</div>
							</div>

							<div class="tab-pane fade" id="nav-notes" role="tabpanel" aria-labelledby="nav-notes-tab">
								<textarea name="notes" class="form-control" placeholder="Enter Note"></textarea>
								<div class="modal-footer">
									<input type="button" value="Update" class="btn btn-primary">
									<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>
								</div>
							</div>

							<div class="tab-pane fade" id="nav-edit-contact-id" role="tabpanel" aria-labelledby="nav-edit-contact-id-tab">
								edit contact record

								<div class="modal-footer">
									<input type="button" value="Update" class="btn btn-primary">
									<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--
	<div class="modal fade" id="addContact" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="h6 modal-title" id="">Add Contact</h2>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group" >
								<label>First Name</label>
								<input required type="text" class="form-control" name="first_name">
							</div>
							<div class="form-group" >
								<label>Last Name</label>
								<input required type="text" class="form-control" name="last_name">
							</div>
							<div class="form-group" >
								<label>Phone</label>
								<input readonly required type="text" class="form-control" name="phone">
							</div>
							<div class="form-group" >
								<label>Company Name</label>
								<input  required type="text" class="form-control" name="company_name">
							</div>
							<div class="form-group" >
								<label>Designation</label>
								<input  required type="text" class="form-control" name="designation">
							</div>
							<div class="form-group" >
								<label>Type</label>
								<input  required type="text" class="form-control" name="type">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group" >
								<label>Email</label>
								<input  required type="text" class="form-control" name="email">
							</div>
							<div class="form-group" >
								<label>Street Address</label>
								<input  required type="text" class="form-control" name="street_address">
							</div>
							<div class="form-group" >
								<label>City</label>
								<input  required type="text" class="form-control" name="city">
							</div>
							<div class="form-group" >
								<label>State</label>
								<input required type="text" class="form-control" name="state">
							</div>
							<div class="form-group" >
								<label>ZipCode</label>
								<input  required type="text" class="form-control" name="zipcode">
							</div>
							<div class="form-group" >
								<label>Kcg State</label>
								<input  required type="text" class="form-control" name="kcg_state">
							</div>
						</div>
					</div>
					
					<h5 class="mt-4">Opportunity Info</h5>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="mb-0 mt-1">Opportunity Name</label>
								<input type="text" name="opportunity_name" class="form-control" value="" placeholder="Opportunity Name" />		
							</div>
							<div class="form-group">
								<label class="mb-0 mt-1">Pipeline</label>
								<select name="opportunity_pipeline" class="form-control" onchange="getPipelineStages(this,'opportunity_stage')">
									<option value="">-- select Pipeline --</option>
									<?php getPipelineOptions($_SESSION['company_id'])?>
								</select>
							</div>
							<div class="form-group">
								<label class="mb-0 mt-1">Opportunity Source</label>
								<input type="text" name="opportunity_source" class="form-control" value="" placeholder="Opportunity Source" />
							</div>
							
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="mb-0 mt-1">Lead Value</label>
								<input type="number" name="opportunity_leadvalue" class="form-control" value="" placeholder="Lead Value" />
							</div>
							<div class="form-group">
								<label class="mb-0 mt-1">Stage</label>
								<select name="opportunity_stage" id="opportunity_stage" class="form-control">
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="cmd" value="save_notes">
					<button type="button" class="btn btn-secondary" onClick="saveContact(this)">Save</button>
					<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	-->
	<div class="modal fade" id="showUserMedia" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="h6 modal-title" id="">Media List</h2>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="col-md-12">
						<div class="row" id="showUserMediaSection" style="margin-bottom: 5px;"></div>
					</div>
				</div>
				<!--
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" onClick="saveNotes(this)">Save</button>
					<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>
				</div>
				-->
			</div>
		</div>
	</div>
	<div class="modal fade" id="startNewChat" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content" style="padding:15px;">
				<span id="showLoading" style="display: none;;margin-bottom: 10px;text-align: center">Searching...</span>
				<div class="input-group" style="margin-bottom: 10px;">
					<span class="input-group-text" id="basic-addon1">
						<svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>  
					</span>
					<input type="text" onKeyUp="getSearchWord(this,event)" autocomplete="off" class="form-control" id="exampleInputIconLeft" placeholder="Search" aria-label="Search">
				</div>
				<div id="contactsContainer">
				<?php
					$sql = "SELECT id,first_name,last_name,cell from customers where user_id='".$_SESSION['company_id']."' order by id desc limit 250";
					$res = mysqli_query($link,$sql);
					$allcount = mysqli_num_rows($res);
					if($allcount){
						while($row = mysqli_fetch_assoc($res)){
							$customerInfo = getCustomerInfoByNumber($row['cell']);
							$phoneNumber  = removeCountryCode($row['cell']);
							//$lastMsg = getLatestMsgByNumber($row['customer_number']);
							$customerName = $row['first_name'].' '.$row['last_name'];
				?>
							<div id="<?php echo $row['cell']?>_container" class="d-flex align-items-center justify-content-between border-bottom py-3">
								<div style="width: 100%">
									<div class="h6 mb-0 align-items-center">
										<i class="icon icon-xs me-2 fa fa-phone" style="color: darkorange;"></i>
										<a href="javascript:void(0)" id="<?php echo $phoneNumber?>_chatStarter" onClick="startNewChatUpdated(this,'<?php echo $phoneNumber?>','<?php echo $customerName?>')">
											<?php echo $customerName?>
										</a>
									</div>
								</div>
							</div>
				<?php
						}
					}
				?>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="videoPlayerbox" tabindex="-1" role="dialog" aria-labelledby="videoPlayerbox" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="h6 modal-title">Video player</h2>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body" id="videoPlayerContainer" style="text-align: center"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="addToPipeline" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
					<div class="modal-header">
						<h2 class="h6 modal-title">Add Contact to Pipeline</h2>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label>- Select Pipeline -</label>
							<select name="pipeline_id" class="form-control" onChange="getPipelineStages(this,'pipelineStages')">
								<option value="">- Select One -</option>
							<?php
								$sqlpp = "select id,title,stages from pipeline_list where user_id='".$_SESSION['company_id']."' order by id desc";
								$respp = mysqli_query($link,$sqlpp);
								if(mysqli_num_rows($respp)){
									while($rowpp = mysqli_fetch_assoc($respp)){
										echo '<option value="'.$rowpp['id'].'">'.$rowpp['title'].'</option>';
									}
								}else{
									echo '<option value="">No pipeline found</option>';
								}
							?>	
							</select>
						</div>
						<div class="form-group">
							<label>- Select Stage -</label>
							<select name="stage_id" id="pipelineStages" class="form-control">
								<option value="">- Select One -</option>
							</select>
						</div>
					</div>
					<div class="modal-footer">
						<input type="button" value="Add Now" class="btn btn-primary" onClick="addPipeline($('#to_number').val())">
						<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>
					</div>
			</div>
		</div>
	</div>
	<?php if (!isset($_GET['chat'])): ?>
	<div class="modal fade" id="addContactModal" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<form action="server.php" method="post">
					<div class="modal-header">
						<h2 class="h6 modal-title">Add New Contact</h2>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
					    <?php 
					        $permission_qry = mysqli_query($link,"select * from permission where inner_user_id='$user_id' AND module_name='inbox' AND insert_permission='1'");
                        	if(mysqli_num_rows($permission_qry)==1 || $role_data['role']=="Full Admin" || $user_type==1){
					    ?>
						<label class="mb-0 mt-2">First Name</label>
						<input type="text" name="contact_fname" class="form-control" value="" placeholder="Contact First Name">
						<label class="mb-0 mt-2">Last Name</label>
						<input type="text" name="contact_lname" class="form-control" value="" placeholder="Contact Last Name">
						<label class="mb-0 mt-2">Email</label>
						<input type="text" name="contact_email" class="form-control" value="" placeholder="Email">
						<label class="mb-0 mt-2">Company Name</label>
						<input type="text" name="contact_company" class="form-control" value="" placeholder="Company Name">
						<label class="mb-0 mt-2">Phone Number</label>
						<input type="text" name="contact_phonenumber" class="form-control" value="" placeholder="Phone Number">
						<label class="mb-0 mt-2">Designation/Division</label>
						<input type="text" name="contact_designation" class="form-control" value="" placeholder="Designation/Division">
						<label class="mb-0 mt-2">Type</label>
						<input type="text" name="contact_type" class="form-control" value="" placeholder="Type">
						<label class="mb-0 mt-2">Address</label>
						<input type="text" name="contact_address" class="form-control" value="" placeholder="Address">
						<label class="mb-0 mt-2">City</label>
						<input type="text" name="contact_city" class="form-control" value="" placeholder="City">
						<label class="mb-0 mt-2">State</label>
						<input type="text" name="contact_state" class="form-control" value="" placeholder="State">
						<label class="mb-0 mt-2">Zip Code</label>
						<input type="text" name="contact_zipcode" class="form-control" value="" placeholder="Zip Code">
					</div>
					<div class="modal-footer">
						<input type="hidden" name="cmd" value="add_contact">
						<button type="submit" class="btn btn-secondary">Save</button>
						<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>
						<?php }else{
						    echo 'Access denied';
						}?>
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php endif ?>
	
<?php
	}else{
	    
	    echo "<h3 style='height: 250px;' class='mt-4'>Access Denied</h3>";
	}
?>	
<?php include_once("footer.php");?>
<script>
	function getCustomerID(){
		$('input[name=contact_phone]').val($("#to_number").val());
	}
	function updateOpportunityBox(){
		$(".overlay").show();
		var firstName = $('input[name=contact_name]').val();
		var lastName  = $("#last_name").val();
		var email 	  = $('input[name=contact_email]').val();
		var phone 	  = $('input[name=contact_phone]').val();
		var companyName = $('input[name=contact_companyname]').val();
		var OpportunityName = $('input[name=opportunity_name]').val();
		var pipeLineID = $('select[name=opportunity_pipeline]').val();
		var pipeLineStage = $('select[name=opportunity_stage]').val();
		var leadValue = $('input[name=opportunity_leadvalue]').val();
		var opportunitySource = $('input[name=opportunity_source]').val();
		var contactID = $('#contact_id').val();
		var redirectTo = $("#redirect_to").val();
		var notes = $("#opportunity_notes").val();

		$.post("server.php",{"cmd":"add_opportunity",firstName:firstName,lastName:lastName,email:email,phone:phone,companyName:companyName,OpportunityName:OpportunityName,pipeLineID:pipeLineID,pipeLineStage:pipeLineStage,leadValue:leadValue,opportunitySource:opportunitySource,contactID:contactID,notes:notes},function(){
			if(redirectTo=='')
				window.location = 'conversations.php';
			else{
				window.location = 'conversations.php?chat=calllogs';
			}
		});
	}
	function getStages( e ) {
		var pipelinss_id = $( e ).val();
		$(".overlay").show();
		jQuery.ajax( {
			type: "post",
			dataType: "json",
			url: "server.php",
			data: {
				cmd: "get_pipeline_stages",
				pipeline_id: pipelinss_id
			},
			success: function ( response ) {
				//console.log( response );
				$(".overlay").hide();
				if ( response ) {
					$( '#opportunity_stage' ).html( response );
				}
			}
		} )
	}
	$(document).ready(function(){
		$('#startNewChatForm').on('submit',(function(e){
			e.preventDefault();
			var formData = new FormData(this);
			$(".overlay").show();
			$.ajax({
				type:'POST',
				url: 'server.php?cmd=start_new_chat',
				data:formData,
				cache:false,
				contentType: false,
				processData: false,
				success:function(data){
					$(".overlay").hide();
					$("#startNewChat").modal('toggle');
					var chatContact = `<div id="`+formData.get("phone_number")+`_container" class="d-flex align-items-center justify-content-between border-bottom py-3"><div style="width: 100%"><div class="h6 mb-0 align-items-center"><i class="icon icon-xs me-2 fa fa-phone" style="color: darkorange"></i><a href="javascript:void(0)" id="`+formData.get("phone_number")+`_chatStarter" onclick="getChats(this,`+formData.get("phone_number")+`)">`+formData.get("first_name")+` `+formData.get("last_name")+`</a><span id="`+formData.get("phone_number")+`_msgTime" style="font-size: 12px;float: right">Now</span></div><div id="`+formData.get("phone_number")+`_message" class="showMessage small card-stats">`+formData.get("message")+`</div></div></div>`;
					$("#checkScroll").prepend(chatContact);
					var eleID = $('#'+formData.get("phone_number")+'_chatStarter');
					getChats(eleID,formData.get("phone_number"));
				},
				error: function(data){
					$(".overlay").hide();
					alert("Error");
				}
			});
			return false;
		}));
		$(".emojiOption").on("click",function(){
			var emoji = $(this).attr("id");
			insertAtCaret("message", emoji);
		});
		$('.chatForm').on('submit',(function(e){
			$(".sendButton").prop("disabled",true);
			e.preventDefault();
			var formData = new FormData(this);
			$("#chatContainer").append(getMsgContent(formData.get('message'),formData.get('chat_media')));
			$.ajax({
				xhr:function(){
					var xhr = new window.XMLHttpRequest();
					xhr.upload.addEventListener("progress", function(evt){
						if(evt.lengthComputable){
							var percentComplete = evt.loaded / evt.total;
							percentComplete = parseInt(percentComplete * 100);
							//$(".progress").css('display', 'block');
							//$(".progress-bar").css('width', percentComplete+'%');
							$(".overlay").show();
							if(percentComplete === 100){
								$(".overlay").hide();
							}
						}
					}, false);
					return xhr;
				},
				type:'POST',
				url: 'server.php?cmd=send_chat_message<?= isset($_GET['chat']) ? '&chat=fb' :'' ?>',
				data:formData,
				cache:false,
				contentType: false,
				processData: false,
				success:function(data){
					$("#message").val('');
					$(".sendButton").prop("disabled",false);
					var obj = $.parseJSON(data);
					if(obj.error == 'yes'){
						alert(obj.message);
					}
				},
				error: function(data){
					alert("Something went wrong.");
				}
			});
			$("#message").val('');
			$("#chat_media").val('');
			$(".media_preview").attr('src','');
			$(".media_preview").hide();
			updateScroll();
			return false;
		}));
		<?php if (isset($_GET['start_chat'])) {
			?>
			startNewChatOnPageLoaded('<?= str_replace(' ', '', $_GET['start_chat']) ?>','<?= str_replace(' ', '', $_GET['start_chat']) ?>');
			<?php
		} ?>
	});
	function loadVideo(videoUrl){
		var html = `<video id="videoId" width="400" controls style="width:100%"><source src="`+videoUrl+`">Your browser does not support HTML video.</video>`;
		$("#videoPlayerContainer").html(html);
		$("#videoPlayerbox").on("hidden.bs.modal", function (){
			$('#videoId')[0].pause();
		});
		$("#videoPlayerbox").modal("show");
	}
	function searchInSideBar(obj,e){
		var searchWord = $(obj).val();
		if($.trim(searchWord)!=''){
			//if(window.event){ e = window.event; }
			//if(e.keyCode == 13){
			//if(searchWord.length >= 3){
				$("#showLoading").show();
				$.post("server.php",{"cmd":"search_in_sidebar_contacts",searchWord:searchWord},function(response){
					$("#showLoading").hide();
					$("#sideBarContacts").html(response);
				});
			//}
		}else{
			$.post("server.php",{"cmd":"load_default_chats"},function(response){
				$("#showLoading").hide();
				$("#sideBarContacts").html(response);
			});
		}
	}
	function getSearchWord(obj,e){
		var searchWord = $(obj).val();
		if($.trim(searchWord)!=''){
			//if(window.event){ e = window.event; }
			//if(e.keyCode == 13){
			if(searchWord.length >= 3){
				$("#showLoading").show();
				$.post("server.php",{"cmd":"search_contact_for_new_chat",searchWord:searchWord},function(response){
					$("#showLoading").hide();
					$("#contactsContainer").html(response);
				});
			}
		}
	}
	function startNewChatUpdated(obj,phoneNumber,customerName){
		$("#startNewChat").modal("toggle");
		var chatContact = `<div id="`+phoneNumber+`_container" class="d-flex align-items-center justify-content-between border-bottom py-3"><div style="width: 100%"><div class="h6 mb-0 align-items-center"><i class="icon icon-xs me-2 fa fa-phone" style="color: darkorange"></i><a href="javascript:void(0)" id="`+phoneNumber+`_chatStarter" onclick="getChats(this,'`+phoneNumber+`')">`+customerName+`</a><span id="`+phoneNumber+`_msgTime" style="font-size: 12px;float: right">Now</span></div><div id="`+phoneNumber+`_message" class="showMessage small card-stats"></div></div></div>`;
		$("#checkScroll").prepend(chatContact);
		var eleID = $('#'+phoneNumber+'_chatStarter');
		getChats(eleID,phoneNumber);
	}
	function startNewChatOnPageLoaded(phoneNumber,customerName){
		var chatContact = `<div id="`+phoneNumber+`_container" class="d-flex align-items-center justify-content-between border-bottom py-3"><div style="width: 100%"><div class="h6 mb-0 align-items-center"><i class="icon icon-xs me-2 fa fa-phone" style="color: darkorange"></i><a href="javascript:void(0)" id="`+phoneNumber+`_chatStarter" onclick="getChats(this,'`+phoneNumber+`')">`+customerName+`</a><span id="`+phoneNumber+`_msgTime" style="font-size: 12px;float: right">Now</span></div><div id="`+phoneNumber+`_message" class="showMessage small card-stats"></div></div></div>`;
		$("#checkScroll").prepend(chatContact);
		var eleID = $('#'+phoneNumber+'_chatStarter');
		getChats(eleID,phoneNumber);
	}
	function searchInChat(obj){
		var searchWord = $(obj).val();
		var customerNumber = $("#to_number").val();	
		//var totalChats = $("#chat_records").val();
		//if(($.trim(searchWord)!='') && (searchWord.length >= 3)){
			$("#loading").show();
			$.post("server.php",{cmd:"search_in_chat",customerNumber:customerNumber,searchWord:searchWord},function(data){
				$("#loading").hide();
				var data = $.parseJSON(data);
				//$("#welcomeScreen").hide();
				//$("#chatFrom").show();
				$("#chatContainer").html(data.chats);
				//$("#chatInfo").find(".showName").html(data.customer_name+' - '+data.customer_number);
				//$("#chatInfo").find(".showNumber").html(data.customer_number);
				//$("#chatInfo").show();
				//$(".overlay").hide();
				updateScroll();
				
			});
		//}
	}
	function showUserMedia(){
		var toNumber = $("#to_number").val();
		$("#showUserMediaSection").html("Loading...");
		$.post("server.php",{"cmd":"get_user_files",toNumber:toNumber},function(r){
			$("#showUserMediaSection").html(r);
		});
	}
	function saveNotes() {
		var notes = $( "#opportunity_notes" ).val();
		//var contactID = $( "#contact_id" ).val();
		$( ".overlay" ).show();
		$.post( "server.php", {
			"cmd": "save_contact_notes",
			notes: notes,
			contactID: contactID
		}, function () {
			$(".overlay").hide();
			var date = moment();
			var currentDate = date.format('DD/MM/YYYY');
			if ( $( ".notesContainer" ).find( "p" ).length > 0 ) {
				$( ".notesContainer" ).append( "<p>" + currentDate + " " + formatAMPM( new Date ) + " => " + notes + "</p>" );
			} else {
				$( ".notesContainer" ).html( "<p>" + currentDate + " " + formatAMPM( new Date ) + " => " + notes + "</p>" );
			}
			$( "#opportunity_notes" ).val( '' )
		} );
	}
	function formatAMPM( date ) {
		var hours = date.getHours();
		var minutes = date.getMinutes();
		var ampm = hours >= 12 ? 'pm' : 'am';
		hours = hours % 12;
		hours = hours ? hours : 12; // the hour '0' should be '12'
		minutes = minutes < 10 ? '0' + minutes : minutes;
		var strTime = hours + ':' + minutes + ' ' + ampm;
		return strTime;
	}
	function saveContact(){
		var first_name = $('#addContact input[name=first_name]').val();
		var last_name = $('#addContact input[name=last_name]').val();
		var phone = $('#addContact input[name=phone]').val();
		var company_name = $('#addContact input[name=company_name]').val();
		var designation = $('#addContact input[name=designation]').val();
		var type = $('#addContact input[name=type]').val();
		var email = $('#addContact input[name=email]').val();
		var street_address = $('#addContact input[name=street_address]').val();
		var city = $('#addContact input[name=city]').val();
		var state = $('#addContact input[name=state]').val();
		var zipcode = $('#addContact input[name=zipcode]').val();
		var kcg_state = $('#addContact input[name=kcg_state]').val();
		
		var opportunity_name = $('input[name=opportunity_name]').val();
		var opportunity_leadvalue = $('input[name=opportunity_leadvalue]').val();
		var opportunity_pipeline_id = $('select[name=opportunity_pipeline]').val();
		var opportunity_stage = $('select[name=opportunity_stage]').val();
		var opportunity_source = $('input[name=opportunity_source]').val();
		
		$(".overlay").show();
		$.post("server.php",{"cmd":"save_contact_customer", first_name: first_name ,last_name: last_name ,phone: phone, company_name: company_name, designation: designation, type: type, email: email, street_address: street_address, city: city, state: state, zipcode: zipcode, kcg_state: kcg_state, opportunity_name:opportunity_name,opportunity_leadvalue:opportunity_leadvalue,opportunity_pipeline_id:opportunity_pipeline_id,opportunity_stage:opportunity_stage,opportunity_source:opportunity_source},function(){
			$('#addContact').modal('toggle');
			$(".overlay").hide();
			/*
			swalWithBootstrapButtons.fire({
				icon: 'success',
				title: 'Contact save successfully.',
				text: 'Your contact number: '+toNumber+' has been saved',
				showConfirmButton: true,
				//timer: 2000
			});
			*/
			//window.location = 'conversations.php?chat=<?php echo $_REQUEST['chat']?>';
			location.reload();
		});
	}
	function addPipeline(num){
		$(".overlay").show();
		let pipeline_id = $('select[name=pipeline_id]').val();
		let stage_id = $('select[name=stage_id]').val();
		//alert(pipeline_id+' '+stage_id);
		//return false;
		$.post("server.php",{"cmd":"add_to_pipeline", num: num, pipeline_id: pipeline_id,stage_id: stage_id},function(){
			$(".overlay").hide();
			window.location = 'pipelines.php?pipeline_id='+pipeline_id;
		});
	}
	function getNotes(obj){
		var toNumber = $("#to_number").val();
		$("#notes").val("Loading...");
		$.post("server.php",{"cmd":"get_notes",toNumber:toNumber},function(r){
			$("#notes").val(r);
		});
	}
	function getContact(){
		var contactID = $("#contact_id").val();
		//var boking_contact_name = $(e).attr('data-name');
		//console.log( boking_id );
		$('input[name=booking_id]').val(contactID);
		$('#loading_data').removeClass('d-none');
		$('#showforms').addClass('d-none');
		$(".notesContainer").html('');
		jQuery.ajax({
			type: "post",
			dataType: "json",
			url: "server.php",
			data:{
				cmd: "get_bookings_oppert_details",
				booking_id: contactID
			},
			success: function(response){
				$('#loading_data').addClass('d-none');
				$('#showforms').removeClass('d-none');
				//console.log(response);
				var contactInfo = response;
				if(contactInfo.message == 'success'){
					$('input[name=contact_name]').val(contactInfo.first_name+' '+contactInfo.last_name);
					$("#last_name").val(contactInfo.last_name);
					$('input[name=contact_email]').val(contactInfo.email);
					$('input[name=contact_phone]').val(contactInfo.phone);
					$('input[name=contact_companyname]').val(contactInfo.company_name);
					$('input[name=opportunity_name]').val(contactInfo.opportunity_name);
					$('select[name=opportunity_pipeline]').val(contactInfo.pipeline_id);
					$('#opportunity_stage').val(contactInfo.pipeline_stage);
					$('input[name=opportunity_leadvalue]').val(contactInfo.lead_value);
					$('input[name=opportunity_source]').val(contactInfo.opportunity_source);
					$('#contact_id').val(contactInfo.id);
					$(".notesContainer").html(contactInfo.contact_notes);
					getStagess(contactInfo.pipeline_id, contactInfo.pipeline_stage);
				}else{
					$('input[name=contact_name]').val('');
					$("#last_name").val('');
					$('input[name=contact_email]').val('');
					$('input[name=contact_phone]').val('');
					$('input[name=contact_companyname]').val('');
					$('input[name=opportunity_name]').val('');
					$('select[name=opportunity_pipeline]').val('');
					$('select[name=opportunity_stage]').val('');
					$('input[name=opportunity_leadvalue]').val('');
					$('input[name=opportunity_source]').val('');
					$('#contact_id').val('');
					$(".notesContainer").html('');
				}
			}
		})
	}
	function getStagess(pipelinss_id=0, status=''){
		// console.log(pipelinss_id);
		jQuery.ajax({
			type: "post",
			dataType: "json",
			url: "server.php",
			data: {
				cmd: "get_pipeline_stages",
				pipeline_id: pipelinss_id
			},
			success: function ( response ) {
				//console.log( response );
				if ( response ) {
					console.log( status );
					$( '#opportunity_stage' ).html( response );
					$( 'select[name=opportunity_stage]' ).val( status );
				}
			}
		} )
	}
	function fetchData(){
		var start = Number($('#start').val());
		var allcount = Number($('#totalrecords').val());
		var rowperpage = Number($('#rowperpage').val());
		//var toNumber = $("#to_number").val();
		start = start + rowperpage;
		if(start <= allcount){
			$('#start').val(start);
			$(".overlay").show();
			$.ajax({
				url:"server.php?cmd=fetch_more_inbox_contacts",
				type: 'post',
				data: {start:start,rowperpage: rowperpage},
				success: function(response){
					//$("#checkScroll:last").after(response).show().fadeIn("slow"); // Add
					// Check if the page has enough content or not. If not then fetch records
					//checkWindowSize();
					$("#checkScroll").append(response);
					$(".overlay").hide();
				}
			});
		}
	}
	function myFunction() {
		var popup = document.getElementById("myPopup");
		popup.classList.toggle("show");
	}
	function getChats(obj,customerNumber){
		$(".overlay").show();
		$(".addContact").hide();
		$(".updateContact").hide();
		$("#chatInfo").find(".showName span").html("loading...");
		//$("#chatInfo").find(".showNumber").html("Loading...");
		var parentMsgEleID = customerNumber+'_container';
		$("#to_number").val(customerNumber);
		$.post("server.php<?= isset($_GET['chat']) ? ('?chat='.$_GET['chat']) :'' ?>",{"cmd":"get_chat",customerNumber:customerNumber},function(data){
			$(obj).parents("#"+parentMsgEleID).find("#"+customerNumber+'_message').css("font-weight","normal");
			var data = $.parseJSON(data);
			$("#contact_id").val(data.contact_id);
			$("#welcomeScreen").hide();
			$("#welcomeScreen").hide();
			$("#chatFrom").show();
			$("#chatContainer").html(data.chats);
			$("#chatInfo").find(".showName span").html(data.customer_name+' - '+data.customer_number);
			if(data.customer_name.length < 3) {
				$(".addContact").show();
			}else{
				$(".updateContact").show();
			}
			$(".addContact").attr('data-number',data.customer_number)
			$(".addToPipeline").attr('data-number',data.customer_number)
			//$("#chatInfo").find(".showNumber").html(data.customer_number);
			$("#chatInfo").show();
			$(".overlay").hide();
			updateScroll();
		});
	}
	function OnKeyPress(e){
		if (window.event) { e = window.event; }
		if (e.keyCode == 13){
			$(".chatForm").submit();
		}	
	}
	function validateTenDigitPhoneNumber(phoneNumber, elementID=""){
		if($.trim(elementID) != ''){
			$("#"+elementID).on("blur", function(){
				var mobNum = $(this).val();
				var filter = /^\d*(?:\.\d{1,2})?$/;
				if(filter.test(mobNum)){
					if(mobNum.length==10){
						alert("valid");
						return true;
					}else{
						alert('Please put 10  digit mobile number');
						return false;
					}
				}
				else{
					alert('Not a valid number');
					return false;
				}
			});
		}
		else{
			var mobNum = phoneNumber;
			if(!$.isNumeric(mobNum)){
				alert("Please enter phone number without country code");
				return false;
			}else{
				var filter = /^\d*(?:\.\d{1,2})?$/;
				if(filter.test(mobNum)){
					if(mobNum.length==10){
						alert("valid");
						return true;
					}else{
						alert('Please put 10  digit mobile number');
						return false;
					}
				}
				else{
					alert('Not a valid number');
					return false;
				}
			}
		}
	}
	function showMediaPreview(){
		var file = $("input[type=file]").get(0).files[0];
		if($.trim(file)!=''){
			if(file){
				var reader = new FileReader();
				reader.onload = function(){
					//$(".media_preview").attr("src",reader.result);
					$(".media_preview").attr("src",'<?php echo getServerUrl().'/assets/img/attachment.png'?>');
				}
				$(".media_preview").show();
			}
			reader.readAsDataURL(file);
		}
	}
	function getMsgContent(message,media){
		var msgTime = getDate();
		var html = `<div class="card text-black border-0 shadow p-4 ms-md-5 ms-lg-6 mb-4" style="background-color:#D9FDD3"><div class="d-flex justify-content-between align-items-center mb-2"><span class="font-small"><span class="fw-bold"><?php echo $_SESSION['first_name'].' '.$_SESSION['last_name']?></span><span class="fw-normal text-black-300 ms-2">`+msgTime+`</span></span><div class="d-none d-sm-block"><svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg></div></div><p class="text-black-300 m-0">`+message+`</p>`;
			<?php if(isset($_GET['chat'])){ ?>
				html = `<div class="card text-black border-0 shadow p-4 ms-md-5 ms-lg-6 mb-4" style="background-color:#D9FDD3"><div class="d-flex justify-content-between align-items-center mb-2"><span class="font-small"><span class="fw-bold"><?php echo $msg->name ?></span><span class="fw-normal text-black-300 ms-2">`+msgTime+`</span></span><div class="d-none d-sm-block"><svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg></div></div><p class="text-black-300 m-0">`+message+`</p>`;
			<?php } ?>
		var file = $("input[type=file]").get(0).files[0];
		if($.trim(file)!=''){
			if(file){
				var reader = new FileReader();
				reader.onload = function(){
					$(".previewImg").attr("src",reader.result);
				}
			}
			reader.readAsDataURL(file);
			html += `<div class="card border-0 shadow mediaContainer" style="padding: 7px;"><div class="card-body p-0"><img src="" class="previewImg card-img-top mb-2 mb-lg-3" alt="media"></div></div></div>`;
		}else{
			html += `</div>`;
		}
		return html;
	}
	function updateScroll(){
		var element = document.getElementById("chatContainer");
		element.scrollTop = element.scrollHeight;
	}
	function insertAtCaret(areaId, text) {
	  var txtarea = document.getElementById(areaId);
	  if (!txtarea) {
		return;
	  }
	  var scrollPos = txtarea.scrollTop;
	  var strPos = 0;
	  var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ?
		"ff" : (document.selection ? "ie" : false));
	  if (br == "ie") {
		txtarea.focus();
		var range = document.selection.createRange();
		range.moveStart('character', -txtarea.value.length);
		strPos = range.text.length;
	  } else if (br == "ff") {
		strPos = txtarea.selectionStart;
	  }
	  var front = (txtarea.value).substring(0, strPos);
	  var back = (txtarea.value).substring(strPos, txtarea.value.length);
	  txtarea.value = front + text + back;
	  strPos = strPos + text.length;
	  if (br == "ie") {
		txtarea.focus();
		var ieRange = document.selection.createRange();
		ieRange.moveStart('character', -txtarea.value.length);
		ieRange.moveStart('character', strPos);
		ieRange.moveEnd('character', 0);
		ieRange.select();
	  } else if (br == "ff") {
		txtarea.selectionStart = strPos;
		txtarea.selectionEnd = strPos;
		txtarea.focus();
	  }
	  txtarea.scrollTop = scrollPos;
	}
</script>
<?php if (isset($_GET['chat']) && $_GET['chat'] == 'calllogs'): ?>
	<style type="text/css">
		.sendingSection {
			display: none !important;
		}
	</style>
<?php endif ?>
<?php if (isset($GLOBALS['script'])): ?>
	<?php echo $GLOBALS['script']; ?>
<?php endif ?>