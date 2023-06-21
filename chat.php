<?php
	include_once("header.php");
?>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>
	// Enable pusher logging - don't include this in production
	//Pusher.logToConsole = true;

	var pusher = new Pusher('65561534463c91979b12', {
		cluster: 'ap3'
	});

	var channel = pusher.subscribe('my-channel');
		channel.bind('my-event', function(data) {
		alert(JSON.stringify(data));
	});
</script>
<style>
	.d-sm-block{
		display: none !important;
	}
</style>
	<div class="py-2">
		<h3>Conversations</h3>
	</div>

	<div class="row justify-content-center mt-3">
		<div class="col-12">
			<div class="card border-0 shadow p-4 mb-4">
				<div class="d-flex justify-content-between align-items-center mb-2">
					<span class="font-small">
						<a href="#">
							<img class="avatar-sm img-fluid rounded-circle me-2" src="../assets/img/team/profile-picture-1.jpg" alt="avatar">
							<span class="fw-bold">Neil Sims</span>
						</a>
						<span class="fw-normal ms-2">March 26, 19:25</span>
					</span>
					<div class="d-none d-sm-block">
						<svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
					</div>
				</div>
				<p class="m-0">
					Hi Chris! Thanks a lot for this very useful template. Saved me a lot of time and searches on the internet.
				</p>
			</div>
			<div class="card bg-gray-800 text-white border-0 shadow p-4 ms-md-5 ms-lg-6 mb-4">
				<div class="d-flex justify-content-between align-items-center mb-2">
					<span class="font-small">
					<span class="fw-bold">Your Answer</span>
					<span class="fw-normal text-gray-300 ms-2">March 26, 19:25</span>
					</span>
					<div class="d-none d-sm-block">
						<svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>                            
					</div>
				</div>
				<p class="text-gray-300 m-0">
					Hi Neil, thanks for sharing your thoughts regarding Spaces. Hi Neil, thanks for sharing your thoughts regarding Spaces.
				</p>
			</div>
			<div class="card border-0 shadow p-4 mb-4">
				<div class="d-flex justify-content-between align-items-center mb-2">
					<span class="font-small">
						<a href="#">
							<img class="avatar-sm img-fluid rounded-circle me-2"
								src="../assets/img/team/profile-picture-1.jpg" alt="avatar">
							<span class="fw-bold">Neil Sims</span>
					</a>
					<span class="ms-2">March 26, 19:25</span>
					</span>
					<div class="d-none d-sm-block">
						<svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>                            
					</div>
				</div>
				<p class="m-0">
					Hi Chris! Thanks a lot for this very useful template. Saved me a lot of time and searches on the internet.
				</p>
			</div>
			<div class="card border-0 shadow p-4 mb-4">
				<div class="d-flex justify-content-between align-items-center mb-2">
					<span class="font-small">
						<a href="#">
							<img class="avatar-sm img-fluid rounded-circle me-2"
								src="../assets/img/team/profile-picture-1.jpg" alt="avatar">
							<span class="fw-bold">Neil Sims</span>
					</a>
					<span class="ms-2">March 26, 19:25</span>
					</span>
					<div class="d-none d-sm-block">
						<div class="d-none d-sm-block">
							<svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>                            
						</div>
					</div>
				</div>
				<p class="m-0">
					Hi Chris! Thanks a lot for this very useful template. Saved me a lot of time and searches on the internet.
				</p>
			</div>
			<div class="card bg-gray-800 text-white border-0 shadow p-4 ms-md-5 ms-lg-6 mb-4">
				<div class="d-flex justify-content-between align-items-center mb-2">
					<span class="font-small">
					<span class="fw-bold">Your Answer</span>
					<span class="ms-2">March 26, 19:25</span>
					</span>
					<div class="d-none d-sm-block">
						<div class="d-none d-sm-block">
							<svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>                            
						</div>
					</div>
				</div>
				<p class="text-gray-300 m-0">
					Hi Neil, thanks for sharing your thoughts regarding Spaces. Hi Neil, thanks for sharing your thoughts regarding Spaces.
				</p>
			</div>
			
			
			<form action="#" class="mt-4 mb-5 chatForm chatOptions" enctype="multipart/form-data">
				<textarea class="form-control border-0 shadow mb-4" name="message" id="message" placeholder="Your Message" rows="3" maxlength="1000" required></textarea>
				
				<div class="d-flex justify-content-between align-items-center mt-3">
					<div class="file-field">
						<div class="d-flex justify-content-center">
							<div class="d-flex align-items-center">
								<svg class="icon icon-md text-gray-400 me-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"></path></svg>
								<input type="file"> 
								<div class="d-block text-left">
									<div class="fw-normal text-dark mb-lg-1">Attach File</div> 
									<div class="text-gray small pe-3 pe-lg-11 d-none d-md-inline">Supported files are: jpg, jpeg, png, gif.
									</div>
								</div>                                           
							</div>
						</div>
					</div>
					<div>
						<button type="submit" class="btn btn-secondary d-inline-flex align-items-center text-dark chatOptions">
							<svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.707 3.293a1 1 0 010 1.414L5.414 7H11a7 7 0 017 7v2a1 1 0 11-2 0v-2a5 5 0 00-5-5H5.414l2.293 2.293a1 1 0 11-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
							Reply
						</button>
					</div>
				</div>
			</form>
			
		</div>
	</div>	
<?php include_once("footer.php");?>
<script>
	$(document).ready(function(){
		$('.chatForm').on('submit',(function(e){
			$(".chatOptions").prop("disabled",true);
			e.preventDefault();
			var formData = new FormData(this);
			$.ajax({
				xhr:function(){
					var xhr = new window.XMLHttpRequest();
					xhr.upload.addEventListener("progress", function(evt) {
					  if (evt.lengthComputable) {
						var percentComplete = evt.loaded / evt.total;
						percentComplete = parseInt(percentComplete * 100);
						$(".progress").css('display', 'block');
						$(".progress-bar").css('width', percentComplete+'%');
						if (percentComplete === 100){
						}
					  }
					}, false);
					return xhr;
				},
				type:'POST',
				url: 'server.php?cmd=send_chat_message',
				data:formData,
				cache:false,
				contentType: false,
				processData: false,
				success:function(data){
					alert("success");
					$(".chatOptions").prop("disabled",false);
				},
				error: function(data){
					alert("Error");
				}
			});
			return false;
		}));
	});
</script>