<?php

	include_once("header.php");

	$userID = $_SESSION['user_id'];

	if(isset($_REQUEST['pipeline_id']) && $_REQUEST['pipeline_id']!=''){
		$res = mysqli_query($link,"SELECT * FROM `pipeline_list` WHERE id=".$_REQUEST['pipeline_id']);
	}else{
		$res = mysqli_query($link,"SELECT * FROM `pipeline_list` WHERE user_id=".$userID." ORDER BY id ASC LIMIT 1");
	}

	$pipelineres = mysqli_query($link,"SELECT * FROM `pipeline_list` WHERE user_id=".$userID." ORDER BY id ASC");

	if(mysqli_num_rows($res)){
		$pipeline_row = mysqli_fetch_assoc($res);
		$stages = json_decode($pipeline_row['stages']);

	}else{
		$pipeline_row['title'] = '';
		$pipeline_row['id'] = 0;
		$stages = [];
	}

?>

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

<style>

	.add-task-container {

	  display: -webkit-box;

	  display: -ms-flexbox;

	  display: flex;

	  width: 20rem;

	  height: 5.3rem;

	  margin: auto;

	  background: #a8a8a8;

	  border: #000013 0.2rem solid;

	  border-radius: 0.2rem;

	  padding: 0.4rem;

	}

	.main-container {

	  display: -webkit-box;

	  display: -ms-flexbox;

	  display: flex;

	}

	.columns {

		display: -webkit-box;

		display: -ms-flexbox;

		display: flex;

		-webkit-box-align: start;

		-ms-flex-align: start;

		align-items: flex-start;

		/*margin: 1.6rem auto;*/

	}

	.column {

		width: 200px;

		margin: 0 0.6rem;

		background: #eeeeee;

		border: #e3e3e3 1px solid;

		border-radius: 5px;

		padding: 1px;

	}

	.column-header {

	  padding: 0.1rem;

	  border-bottom: #000013 0.2rem solid;

	}

	

	

	.to-do-column .column-header {

		background: #ff872f;

		border-radius: 5px

	}

	.doing-column .column-header {

	  background: #13a4d9;

	}

	.done-column .column-header {

	  background: #15d072;

	}

	.trash-column .column-header {

	  background: #ff4444;

	}

	

	.columnHeader{

		background: black;

		border-radius: 5px;

		padding: 0.1rem;

	}

	.columnHeader h4 {

		text-align: center;

		color: white;

		font-size: 22px;

	}

	

	.task-list {

	  min-height: 3rem;

	}

	ul {

	  list-style-type: none;

	  margin: 0;

	  padding: 0;

	}



	li{

	  list-style-type: none;

	}

	.column-button {

	  text-align: center;

	  padding: 0.1rem;

	}

	.button {

	  font-family: "Arimo", sans-serif;

	  font-weight: 700;

	  border: #000013 0.14rem solid;

	  border-radius: 0.2rem;

	  color: #000013;

	  padding: 0.6rem 1rem;

	  margin-bottom: 0.3rem;

	  cursor: pointer;

	}

	.delete-button {

	  background-color: #ff4444;

	  margin: 0.1rem auto 0.6rem auto;

	}

	.delete-button:hover {

	  background-color: #fa7070;

	}

	.add-button {

	  background-color: #ffcb1e;

	  padding: 0 1rem;

	  height: 2.8rem;

	  width: 10rem;

	  margin-top: 0.6rem;

	}

	.add-button:hover {

	  background-color: #ffdd6e;

	}

	.task {

	  display: -webkit-box;

	  display: -ms-flexbox;

	  display: flex;

	  -webkit-box-pack: center;

	  -ms-flex-pack: center;

	  justify-content: center;

	  vertical-align: middle;

	  list-style-type: none;

	  background: #fff;

	  -webkit-transition: all 0.3s;

	  transition: all 0.3s;

	  margin: 0.4rem;

	  height: 50px;

	  border: #000013 0.15rem solid;

	  border-radius: 0.2rem;

	  cursor: move;

	  text-align: center;

	  vertical-align: middle;

	}

	#taskText {

	  background: #fff;

	  border: #000013 0.15rem solid;

	  border-radius: 0.2rem;

	  text-align: center;

	  font-family: "Roboto Slab", serif;

	  height: 4rem;

	  width: 7rem;

	  margin: auto 0.8rem auto 0.1rem;

	}

	.task p {

	  margin: auto;

	}

	/* Dragula CSS Release 3.2.0 from: https://github.com/bevacqua/dragula */

	.gu-mirror {

	  position: fixed !important;

	  margin: 0 !important;

	  z-index: 9999 !important;

	  opacity: 0.8;

	  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=80)";

	  filter: alpha(opacity=80);

	}

	.gu-hide {

	  display: none !important;

	}

	.gu-unselectable {

	  -webkit-user-select: none !important;

	  -moz-user-select: none !important;

	  -ms-user-select: none !important;

	  user-select: none !important;

	}

	.gu-transit {

	  opacity: 0.2;

	  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=20)";

	  filter: alpha(opacity=20);

	}





	/* general styles */



	body {

	font-family: 'Helvetica Neue',Arial,Helvetica,sans-serif;

	}



	.wrapper {

	padding: 1rem;

	}



	/* column styles */



	.column__list {

	display: grid;

	grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));

	grid-gap: .5rem;

	align-items: start;

	/* uncomment these lines if you want to have the standard Trello behavior instead of the column wrapping */

	/*   grid-auto-flow: column;

		grid-auto-columns: minmax(260px, 1fr); */

	}



	.column__item {

	border-radius: .2rem;

	background-color: #dfe3e6;

	padding: .5rem;

	}



	.column__title--wrapper {

	display: grid;

	grid-template-columns: repeat(2, 1fr);

	padding: .25rem;

	align-items: center;

	}



	.column__title--wrapper h2 {

	color: #17394d;

	font-weight: 700;

	font-size: .9rem;

	}



	.column__title--wrapper i {

	text-align: right;

	color: #798d99;

	}



	.column__item--cta {

	padding: .25rem;

	display: flex;

	color: #798d99;

	}



	.column__item--cta i {

	margin-right: .25rem;

	}



	/* card styles */



	.card__list {

	display: grid;

	grid-template-rows: auto;

	grid-gap: .5rem;

	margin: .5rem 0;

	}



	.card__item {

	background-color: white;

	border-radius: .25rem;

	box-shadow: 0 1px 0 rgba(9,45,66,.25);

	padding: .5rem;

	}



	.card__tag {

	font-size: .75rem;

	padding: .1rem .5rem;

	border-radius: .25rem;

	font-weight: 700;

	color: white;

	margin-bottom: .75rem;

	display: inline-block;

	}



	.card__image {

	width: 100%;

	margin-bottom: .25rem;

	}



	/* sticker colors */



	.card__tag--design {

	background-color: #61bd4f;

	}



	.card__tag--browser {

	background-color: #c377e0;

	}



	.card__tag--mobile {

	background-color: #f2d600;

	}



	.card__tag--high {

	background-color: #eb5a46;

	}



	.card__tag--low {

	background-color: #00c2e0;

	}



	.card__title {

	color: #17394d;

	margin-bottom: .75rem;

	}



	/* card actions */



	.card__actions {

	display: flex;

	align-items: center;

	}



	.card__actions--wrapper i {

	color: #798d99;

	margin-right: .5rem;

	}



	.card__actions--text {

	color: #798d99;

	font-size: .8rem;

	margin-left: -.25rem;

	margin-right: .5rem;

	}



	.card__avatars {

	display: flex;

	flex: 1;

	justify-content: flex-end;

	}



	.card__avatars--item {

	margin-left: .25rem;

	width: 28px;

	height: 28px;

	}



	.avatar__image {  

	border-radius: 50%;

	width: 100%;

	height: 100%;

	object-fit: cover;

	}

	.card__item {

		cursor: pointer;

	}

</style>

	<div class="py-2">

		<h3><?= $pipeline_row['title'] ?>
			<select name="pipeliness" class="form-control" onchange="getPipelinelist(this)" style="display: inline-block;width: auto;">
				<option value="">-- select Pipeline ---</option>
				<?php while($rowdatt = mysqli_fetch_assoc($pipelineres)){ ?>
				<option value="<?= $rowdatt['id'] ?>" <?= $pipeline_row['id']==$rowdatt['id']? 'selected':'' ?>><?= $rowdatt['title'] ?></option>
				<?php } ?>
			</select>
			<a href="pipelineslist.php" class="btn btn-primary" style="float: right">All Piplines</a>
		</h3>
		<!--

		<div class="add-task-container">

			<input type="text" maxlength="12" id="taskText" placeholder="New Task..." onkeydown="if (event.keyCode == 13)document.getElementById('add').click()">

			<button id="add" class="button add-button" onclick="addTask()">Add New Task</button>

		</div>

		-->
		<?php if(isset($_SESSION['message']) && $_SESSION['message']!='')echo $_SESSION['message'];unset($_SESSION['message']); ?>

	</div>



	<div class="card">

		<div class="row statuses" style="display: flex;padding: 5px;flex-direction: row;" >
			<?php foreach($stages as $stage){ ?>
			<div class="col-md-3 col-lg-3 col-sm-12 mt-3" >

				<li class="column__item">

			    <div class="column__title--wrapper">

			      	<h2><?= $stage ?></h2>

					<i class="fas fa-ellipsis-h" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false"></i>
					
					<ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
						<li><a class="dropdown-item" href="select_template.php">Create Workflow</a></li>
						<li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#assign_workflow" onclick="assinged_stauts(this)" data-status="<?= $stage ?>">Assign Workflow</a></li>
					</ul>
			    </div>

			    <ul class="card__list dragula_card" id="<?= $stage ?>">

			    </ul>

			  </li>

			</div>
			<?php } ?>
		</div>

			

		

	</div>

	<div class="modal fade" id="modal-default" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">

		<div class="modal-dialog modal-dialog-centered" role="document">

			<div class="modal-content">

				<form action="server.php" method="post" enctype="multipart/form-data">

					<div class="modal-header">

						<h2 class="h6 modal-title" id="">Update Settings</h2>

						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

					</div>

					<div class="modal-body">

						<h6>Under dev</h6>

					</div>

					<div class="modal-footer">

						<input type="hidden" name="cmd" value="send_broadcast">

						<input type="hidden" name="list_id" id="list_id" value="">

						<button type="button" class="btn btn-secondary">Save</button>

						<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>

					</div>

				</form>

			</div>

		</div>

	</div>
	<div class="modal fade" id="assign_workflow" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">

		<div class="modal-dialog modal-dialog-centered" role="document">

			<div class="modal-content">

				<form action="server.php" method="post">

					<div class="modal-header">

						<h2 class="h6 modal-title">Assign Workflow</h2>

						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

					</div>

					<div class="modal-body">

						<label>Select Workflow</label>

						<select class="form-control" name="workflow" required>
							<option value="">-- select workflow --</option>
							<?php 
							$workflow_data = mysqli_query($link,"select * from workflow where user_id=".$_SESSION['user_id']);

							if(mysqli_num_rows($workflow_data)>0){
			
								while($row = mysqli_fetch_assoc($workflow_data)){
							?>
							<option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
							<?php }
							} ?>
						</select>

					</div>

					<div class="modal-footer">

						<input type="hidden" name="cmd" value="assign_workflow_to_booked">
						<input type="hidden" name="booking_status" value="booked">

						<input type="submit" value="Save" class="btn btn-primary">

						<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>

					</div>

				</form>

			</div>

		</div>

	</div>
	<div class="modal fade" id="assign_opportunity" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">

		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">

			<div class="modal-content">

				<form action="server.php" method="post">

					<div class="modal-header">

						<h2 class="h6 modal-title">Edit Opportunity</h2>

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
									<h6>Contact Info</h6>
									<label class="mb-0 mt-1">Contact Name</label>
									<input type="text" name="contact_name" class="form-control" value="" placeholder="Contact Name" />
									<div class="row">
										<div class="col-md-6">
											<label class="mb-0 mt-1">Email</label>
											<input type="text" name="contact_email" class="form-control" value="" placeholder="Email" />
										</div>
										<div class="col-md-6">
											<label class="mb-0 mt-1">Phone</label>
											<input type="text" name="contact_phone" class="form-control" value="" placeholder="Phone" />
										</div>
										<div class="col-md-6">
											<label class="mb-0 mt-1">Tags</label>
											<input type="text" name="contact_tags" class="form-control" value="" placeholder="Tags" />
										</div>
										<div class="col-md-6">
											<label class="mb-0 mt-1">Company Name</label>
											<input type="text" name="contact_companyname" class="form-control" value="" placeholder="Company Name" />
										</div>
									</div>
									<h6>Opportunity Info</h6>
									<label class="mb-0 mt-1">Opportunity Name</label>
									<input type="text" name="opportunity_name" class="form-control" value="" placeholder="Opportunity Name" />
									<div class="row">
										<div class="col-md-6">
											<label class="mb-0 mt-1">Pipeline</label>
											<select name="opportunity_pipeline" class="form-control">
												<option value="Open Projects">Open Projects</option>
												<option value="Partner Recruiting">Partner Recruiting</option>
												<option value="Solar Leads">Solar Leads</option>
											</select>
										</div>
										<div class="col-md-6">
											<label class="mb-0 mt-1">Stage</label>
											<select name="opportunity_stage" class="form-control">
												<option value="Contract Signed">Contract Signed</option>
												<option value="Site Survey Scheduling">Site Survey Scheduling</option>
												<option value="Site Survey Scheduled">Site Survey Scheduled</option>
												<option value="Design Stage">Design Stage</option>
											</select>
										</div>
										<div class="col-md-6">
											<label class="mb-0 mt-1">Status</label>
											<select name="opportunity_status" class="form-control">
												<option value="Open">Open</option>
												<option value="Won">Won</option>
												<option value="Lost">Lost</option>
												<option value="Abandoned">Abandoned</option>
											</select>
										</div>
										<div class="col-md-6">
											<label class="mb-0 mt-1">Lead Value</label>
											<input type="number" name="opportunity_leadvalue" class="form-control" value="" placeholder="Lead Value" />
										</div>
										<div class="col-md-6">
											<label class="mb-0 mt-1">Status</label>
											<select name="opportunity_status" class="form-control">
												<option value="Betsy  WInters">Betsy  WInters</option>
												<option value="James Johnson">James Johnson</option>
												<option value="Jordon Wanamaker">Jordon Wanamaker</option>
												<option value="Joshua Davis">Joshua Davis</option>
											</select>
										</div>
										<div class="col-md-6">
											<label class="mb-0 mt-1">Opportunity Source</label>
											<input type="text" name="opportunity_source" class="form-control" value="" placeholder="Opportunity Source" />
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="nav-book-ppointment" role="tabpanel" aria-labelledby="nav-book-ppointment-tab">
									Add a calendar to start booking appointments.
								</div>
								<div class="tab-pane fade" id="nav-tasks" role="tabpanel" aria-labelledby="nav-tasks-tab">
									<label class="mb-0 mt-1">Title</label>
									<input type="text" name="task_title" class="form-control" value="" placeholder="Title" />
									<label class="mb-0 mt-1">Description</label>
									<textarea name="task_description" class="form-control" placeholder="Task Description"></textarea>
									<label class="mb-0 mt-1">Assign To</label>
									<select name="task_assignto" class="form-control">
										<option value="Not assigned">Not assigned</option>
										<option value="Betsy  WInters">Betsy  WInters</option>
										<option value="James Johnson">James Johnson</option>
									</select>
									<label class="mb-0 mt-1">Due Date</label>
									<input type="date" name="task_due_date" class="form-control" value="" placeholder="Task Due Date" />
								</div>
								<div class="tab-pane fade" id="nav-notes" role="tabpanel" aria-labelledby="nav-notes-tab">
									<textarea name="notes" class="form-control" placeholder="Enter Note"></textarea>
								</div>
								<div class="tab-pane fade" id="nav-edit-contact-id" role="tabpanel" aria-labelledby="nav-edit-contact-id-tab">
									edit contact record
								</div>
							</div>
						</div>
					</div>

					<div class="modal-footer">

						<input type="hidden" name="cmd" value="update_opportunity">
						<input type="hidden" name="booking_id" value="0">
						<input type="hidden" name="opper_booking_id" value="0">

						<input type="submit" value="Update" class="btn btn-primary">

						<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>

					</div>

				</form>

			</div>

		</div>

	</div>

<?php include_once("footer.php");?>

<script src="./js/dragula.js"></script>

<script>
	function getPipelinelist(obj){
		var pipelineID = $(obj).val();
		$(".overlay").show();
		window.location = "pipelines.php?pipeline_id="+pipelineID;
	}
	dragula([
		<?php foreach($stages as $stage){ ?>
		document.getElementById("<?= $stage ?>"),
		<?php } ?>
	],{

		removeOnSpill: false

	})

	.on("drag", function(el){

		// el.className.replace("ex-moved", "");

	})

	.on("drop", function(el){

		update(el)

		// el.className += "ex-moved";

		// alert("Droped");

	})

	.on("over", function(el, container){

		// container.className += "ex-over";

	})

	.on("out", function(el, container){

		// container.className.replace("ex-over", "");

	});



	/* Vanilla JS to add a new task */

	function addTask(card,name,id,contactname=''){

		/* Get task text from input */

		// var inputTask = document.getElementById("taskText").value;

		/* Add task to the 'To Do' column */

		// document.getElementById(card).innerHTML +=

		// "<li id='"+id+"' class='task'><p>" + name + "</p></li>";

		// <span class="card__tag card__tag--high">High Priority</span>
		// console.log(name)
		if (!document.getElementById(card)){
			$('.statuses').append($(`<div class="col-md-3 col-lg-3 col-sm-12 mt-3">

					<li class="column__item">

					<div class="column__title--wrapper">

						<h2>`+card+`</h2>

						<i class="fas fa-ellipsis-h" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false"></i>
						
						<ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
							<li><a class="dropdown-item" href="select_template.php">Create Workflow</a></li>
							<li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#assign_workflow" onclick="assinged_stauts(this)" data-status="booked">Assign Workflow</a></li>
						</ul>
					</div>

					<ul class="card__list dragula_card" id="`+card+`">

					

					</ul>

				</li>

				</div>`))
		}
		document.getElementById(card).innerHTML += "<li id='"+id+"' class='card__item' onclick='getBookingId(this)' data-name='"+ contactname +"' data-bs-toggle='modal' data-bs-target='#assign_opportunity'><h6 class='card__title'>" + name + "</h6></li>";





		/* Clear task text from input after adding task */

		// document.getElementById("taskText").value = "";

	}

	/* Vanilla JS to delete tasks in 'Trash' column */

	function emptyTrash(){

		/* Clear tasks from 'Trash' column */

		document.getElementById("trash").innerHTML = "";

	}

	function update(el){

		jQuery.ajax({

	         type : "post",

	         dataType : "json",

	         url : "server.php",

	         data : {cmd: "update_booking_status",id: el.id,status: el.parentElement.id},

	         success: function(response) {

	            

	         }

      	})

	}

	function update_order(el){

		var data ={};

		jQuery('.dragula_card').each(function(a,b,c){ 

            if (b.id != ''){

    			data[b.id]=[];

    			var o=1;

    		 	jQuery(b).children().each(function(d,e,f){ 

    			   data[b.id].push({id: e.id,order:o})

    			})

            }

		})

		// console.log(data)

		d=data;

		jQuery.ajax({

	         type : "post",

	         dataType : "json",

	         url : "server.php",

	         data : {cmd: "update_pipeline_order",data: d},

	         success: function(response) {

	            

	         }

      	})

	}

	function getRandomColor() {

	  var letters = '0123456789ABCDEF';

	  var color = '#';

	    color += letters[Math.floor(Math.random() * 16)];

	    color += letters[Math.floor(Math.random() * 16)];

	    color += '00';

	    // color += letters[Math.floor(Math.random() * 16)];

	    color += letters[Math.floor(Math.random() * 16)];

	    color += letters[Math.floor(Math.random() * 16)];

	  

	  return color;

	}

	function assinged_stauts(e){
		var boking_status = $(e).attr('data-status');
		console.log(boking_status);
		$('input[name=booking_status]').val(boking_status);
	}

	function getBookingId(e){
		var boking_id = $(e).attr('id');
		var boking_contact_name = $(e).attr('data-name');
		console.log(boking_id);
		$('input[name=booking_id]').val(boking_id);
		$('#loading_data').removeClass('d-none');
		$('#showforms').addClass('d-none');
		jQuery.ajax({

			type : "post",

			dataType : "json",

			url : "server.php",

			data : {cmd: "get_bookings_oppert_details", booking_id: boking_id},

			success: function(response) {
				$('#loading_data').addClass('d-none');
				$('#showforms').removeClass('d-none');
				if(response){
					// console.log(response['contact_companyname']);
					$('input[name=opper_booking_id]').val(response['id']);
					$('input[name=contact_companyname]').val(response['contact_companyname']);
					$('input[name=contact_email]').val(response['contact_email']);
					$('input[name=contact_name]').val(response['contact_name']);
					$('input[name=contact_phone]').val(response['contact_phone']);
					$('input[name=contact_tags]').val(response['contact_tags']);
					$('input[name=opportunity_leadvalue]').val(response['opportunity_leadvalue']);
					$('input[name=opportunity_name]').val(response['opportunity_name']);
					$('input[name=opportunity_source]').val(response['opportunity_source']);
					$('select[name=opportunity_pipeline]').val(response['opportunity_pipeline']);
					$('select[name=opportunity_stage]').val(response['opportunity_stage']);
					$('select[name=opportunity_status]').val(response['opportunity_status']);
					$('select[name=task_assignto]').val(response['task_assignto']);
					$('input[name=task_title]').val(response['task_title']);
					$('input[name=task_due_date]').val(response['task_due_date']);
					$('textarea[name=task_description]').val(response['task_description']);
					$('textarea[name=notes]').val(response['notes']);

				}else{
					$('input[name=opper_booking_id]').val(0);
					$('input[name=contact_companyname]').val('');
					$('input[name=contact_email]').val('');
					$('input[name=contact_name]').val('');
					$('input[name=contact_phone]').val('');
					$('input[name=contact_tags]').val('');
					$('input[name=opportunity_leadvalue]').val('');
					$('input[name=opportunity_name]').val('');
					$('input[name=opportunity_source]').val('');
					$('input[name=task_title]').val('');
					$('input[name=task_due_date]').val('');
					$('textarea[name=task_description]').val('');
					$('textarea[name=notes]').val('');
				}
				$('input[name=contact_name]').val(boking_contact_name);
			}

		})
	}

	jQuery(document).ready(function(){

		jQuery.ajax({

	         type : "post",

	         dataType : "json",

	         url : "server.php",

	         data : {cmd: "all_pipeline_booking",'pipeline_id': <?= $pipeline_row['id'] ?>},

	         success: function(response) {
				console.log(response);
	            for (var i = 0; i < response.data.length; i++) {

	            	name = ` <span class="card__tag " style="background-color: `+getRandomColor()+`;" >`+response.data[i].time+`</span>

          <h6 class="card__title">`+response.data[i].firstName+`</h6>`;



	            	id = 'booking_'+response.data[i].id; 

	            	addTask(response.data[i].status,name,id,response.data[i].firstName)

	            }

	         }

      	})   

	});

</script>

<style type="text/css">

	.task p{

		font-size: 11px;

	}

	.mt-3 .column__item {

		height: 100%;

	}

	.dragula_card {

		min-height: 100px;

	}
	.nav-link-custom {
		padding: 8px !important;
		margin: 0px !important;
	}
</style>