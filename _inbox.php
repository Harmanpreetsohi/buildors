<?php include_once("header.php");?>
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
	
	//Pusher.logToConsole = true;
	
	var pusher = new Pusher('65561534463c91979b12', {
		cluster: 'ap3'
	});
	var channel = pusher.subscribe('my-channel');
	
	channel.bind('new-incoming-sms', function(data){
		//alert(JSON.stringify(data));
		var msgTime = getDate();
		var chatStartedNumber = $("#to_number").val();
		var incomingNumber = data.from_number;
		var isChatStarted = '';
		if(chatStartedNumber == incomingNumber){
			isChatStarted = 'show_message';
		}
		if($.trim(isChatStarted)!=''){
			var popMsg = `<div class="card border-0 shadow p-4 mb-4"><div class="d-flex justify-content-between align-items-center mb-2"><span class="font-small"><a href="javascript:void(0)"><img class="avatar-sm img-fluid rounded-circle me-2" src="../assets/img/team/profile-picture-1.jpg" alt="avatar"><span class="fw-bold"></span></a><span class="fw-normal ms-2">`+msgTime+`</span></span><div class="d-none d-sm-block"><svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg></div></div><p class="m-0">`+data.message+`</p>`;
			if(data.from_media != 'no'){
				popMsg += `<div class="card border-0 shadow mediaContainer" style="padding: 7px;"><div class="card-body p-0"><img src="`+data.from_media+`" class="card-img-top mb-2 mb-lg-3" alt="media"></div></div></div>`;
			}else{
				popMsg += `</div>`;
			}
			$("#chatContainer").append(popMsg);
			var idMaker = data.from_number.substring(2);
			$('#'+idMaker+'_container').find('#'+idMaker+'_message').html(data.message);
			$('#'+idMaker+'_message').css("font-weight","bold");
		}else{
			var idMaker = data.from_number.substring(2);
			$('#'+idMaker+'_container').find('#'+idMaker+'_message').html(data.message);
			$('#'+idMaker+'_message').css("font-weight","bold");
		}
		updateScroll();
	});
	
	channel.bind('new-incoming-number', function(data){
		//alert(JSON.stringify(data));
		var idMaker = data.from_number.substring(2);
		//$('#'+idMaker+'_container').find('#'+idMaker+'_message').html(data.message);
		$("#checkScroll").prepend(`<div id="`+idMaker+`_container" class="d-flex align-items-center justify-content-between border-bottom py-3">
									<div style="width: 100%">
										<div class="h6 mb-0 align-items-center">
											<i class="icon icon-xs me-2 fa fa-phone" style="color: darkorange"></i>
											<a href="javascript:void(0)" onclick="getChats(`+data.from_number+`)">`+data.from_number+`</a>
											<span id="`+idMaker+`_msgTime" style="font-size: 12px;float: right">Now</span>
										</div>
										<div id="`+idMaker+`_message" class="showMessage small card-stats">`+data.message+`</div>
									</div>
								</div>`);
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
</style>
<style>
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
</style>
	<div class="py-2">
		<h3>Inbox </h3>
	</div>

	<div class="card">
		<div class="row" style="padding: 15px;">
			<div class="col-md-3" style="padding: 0px;">
				<div class="card-body" style="max-height: 450px; overflow-y: auto" id="checkScroll">
					<?php
						//$sql = "select from_number,message,created_date from broadcast_history where direction='in' group by from_number order by MAX(created_date) DESC";
						//$sql = "select from_number from broadcast_history where direction='in' group by from_number order by MAX(created_date) DESC";
						//$sql = "select from_number from broadcast_history group by from_number order by MAX(created_date) DESC";
						$rowperpage = 250;
						/*
						$sql = "SELECT DISTINCT from_number, MAX(created_date) 
								FROM broadcast_history 
								GROUP BY from_number 
								ORDER BY MAX(created_date) DESC, from_number limit 0,$rowperpage";
						*/
						$sql = "select from_number from broadcast_history order by id desc";
						$res = mysqli_query($link,$sql);
						$allcount = mysqli_num_rows($res);
						if($allcount){
							while($row = mysqli_fetch_assoc($res)){
								$customerInfo = getCustomerInfoByNumber($row['from_number']);
								$lastMsg = getLatestMsgByNumber($row['from_number']);
								
								$idMaker = substr($row['from_number'],2);
					?>
								<div id="<?php echo $idMaker?>_container" class="d-flex align-items-center justify-content-between border-bottom py-3">
									<div style="width: 100%">
										<div class="h6 mb-0 align-items-center">
											<i class="icon icon-xs me-2 fa fa-phone" style="color: darkorange"></i>
											<a href="javascript:void(0)" onClick="getChats('<?php echo $row['from_number']?>')"><?php echo $row['from_number']?></a>
											<span id="<?php echo $idMaker?>_msgTime" style="font-size: 12px;float: right"><?php echo date("H:i a",strtotime($lastMsg['created_date']));?></span>
										</div>
										<div id="<?php echo $idMaker?>_message" class="showMessage small card-stats">
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
						}else{
					?>
							<div class="d-flex align-items-center justify-content-between border-bottom py-3">
								<div>
									<div class="h6 mb-0 d-flex align-items-center">
										No reply found.
									</div>
								</div>
							</div>	
					<?php
						}
					?>
				</div>
			</div>
			<div class="col-md-9">
				<div class="row justify-content-center mt-3">
					<div class="col-12" id="chatContainer" style="overflow-y:auto; height: 450px;background-color: beige">
						<div id="welcomeScreen" class="modal-dialog modal-info modal-dialog-centered" role="document" style="width: 100%; max-width: 100%; margin: 0px;">
							<div class="modal-content bg-gradient-secondary">
								<div class="modal-header">
									
								</div>
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
					
					<div class="sendingSection" style="margin-top: 15px;">
						<form action="#" id="chatFrom" class="chatForm" enctype="multipart/form-data" method="post" style="display: none">
							<div class="row">
								<div class="col-md-2" style="padding: 0px; display: flex">
									<span data-bs-toggle="modal" data-bs-target="#modal-default" style="cursor: pointer;padding: 0px 10px;font-size: 20px;">&#128512;</span>
									
									<i class="fa fa-folder" style="cursor: pointer;font-size: 30px; color: orange"></i>&nbsp;&nbsp;
									<i class="fa fa-sticky-note" style="cursor: pointer; font-size: 28px; color: green"></i>&nbsp;&nbsp;
									
									<div class="file-field">
										<!--
										<span class="popuptext" id="myPopup">
											<img src="" class="media_preview" style="display: none" />
										</span>
										-->
										<img src="" class="media_preview" style="display: none" />
										<div class="d-flex justify-content-center">
											<div class="d-flex align-items-center">
												<svg class="icon icon-md text-gray-400 me-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" style="cursor: pointer;"><path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"></path></svg>
												<input type="file" name="chat_media" id="chat_media" style="width: 40px; max-width: 40px;padding-bottom: 0px;" onChange="showMediaPreview()">  
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-9">
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
<?php include_once("footer.php");?>
<script>
	/*
	jQuery(function($) {
		$('#checkScroll').on('scroll', function() {
			if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight){
				//alert('Loading more contacts!');
				//alert($(this).innerHeight());
				fetchData();
			}
		});
	});
	*/
	/*
	$("#checkScroll").scroll(function(){
		if($("#checkScroll").scrollTop() + $("#checkScroll").height() == $("#checkScroll").height()) {
			alert($("#checkScroll").height());
			alert("bottom!");
		}
	});
	*/
	//checkWindowSize();
	// Check if the page has enough content or not. If not then fetch records
	/*
	function checkWindowSize(){
		if($(window).height() >= $(document).height()){
			// Fetch records
			fetchData();
			alert("checking");
		}
	}
	*/
	// Fetch records
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
	/*
	$(document).on('touchmove', onScroll); // for mobile

	function onScroll(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100){
			fetchData(); 
		}
	}

	$(window).scroll(function(){
		var position = $(window).scrollTop();
		var bottom = $(document).height() - $(window).height();
		if( position == bottom ){
			fetchData(); 
		}
	});
	*/
</script>
<script>
	function myFunction() {
		var popup = document.getElementById("myPopup");
		popup.classList.toggle("show");
	}
	function getChats(customerNumber){
		$(".overlay").show();
		$("#to_number").val(customerNumber);
		$.post("server.php",{"cmd":"get_chat",customerNumber:customerNumber},function(data){
			var data = $.parseJSON(data);
			$("#welcomeScreen").hide();
			$("#chatFrom").show();
			$("#chatContainer").html(data.chats);
			//$(".sendingElement").prop("disabled",false);
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
	$(document).ready(function(){
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
				type:'POST',
				url: 'server.php?cmd=send_chat_message',
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
	});
	function showMediaPreview(){
		var file = $("input[type=file]").get(0).files[0];
		if($.trim(file)!=''){
			if(file){
				var reader = new FileReader();
				reader.onload = function(){
					$(".media_preview").attr("src",reader.result);
				}
				$(".media_preview").show();
			}
			reader.readAsDataURL(file);
		}
	}
	function getMsgContent(message,media){
		var msgTime = getDate();
		var html = `<div class="card text-black border-0 shadow p-4 ms-md-5 ms-lg-6 mb-4" style="background-color:#D9FDD3"><div class="d-flex justify-content-between align-items-center mb-2"><span class="font-small"><span class="fw-bold">Your Answer</span><span class="fw-normal text-black-300 ms-2">`+msgTime+`</span></span><div class="d-none d-sm-block"><svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg></div></div><p class="text-black-300 m-0">`+message+`</p>`;
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