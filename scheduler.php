<?php 

	include_once("header.php"); 
	
	$user_id = $_SESSION['user_id'];
// 	$role_id = $_SESSION['role_id'];
	$user_type = $_SESSION['user_type'];
	
	$user_qry = mysqli_query($link,"select role_id from users where id='$user_id'");
	$user_data = mysqli_fetch_assoc($user_qry);
	$role_id = $user_data['role_id'];
	
	$role_qry = mysqli_query($link,"select * from role_master where role_id='$role_id'");
	$role_data = mysqli_fetch_assoc($role_qry);
	
	$permission_qry = mysqli_query($link,"select * from permission where inner_user_id='$user_id' AND module_name='scheduler' AND view_permission='1'");
	
	if(mysqli_num_rows($permission_qry)==1 || $role_data['role']=="Full Admin" || $user_type==1){

    	$sql = "select * from schedulers where user_id='".$_SESSION['company_id']."'";
    
    	$res = mysqli_query($link,$sql);
    
    	$events = '';
    
    	$event  = [];
    
    	$index = 0;
    
    	if(mysqli_num_rows($res)){
    
    		while($row = mysqli_fetch_assoc($res)){
    			$event  = [];
    			if($row['is_recurring']=='1')
    
    				$allDay = true;
    
    			else
    
    				$allDay = false;
    
    			
    
    			$event['id']	= $row['id'];
    
    			$event['title'] = $row['event_title'];
    
    			$event['start'] = $row['start_date'];
    			// $event['start'] = date_format(date_create_from_format('Y-m-d H:i',$row['start_date']),'Y-m-d H:i');
    			// print_r($event['start']);die;
    			$event['end']   = $row['end_date'];
    
    			$event['groupId']   = $row['is_recurring'];
    
    			$event['description'] = DBout($row["message"]);
    
    			//$event['description'] = $row["message"];
    
    			$event["department"] = $row['department'];
    
    			$event["attendies"] = $row["attendies"];
    
    			$event["workflow_id"] = $row["workflow_id"];
    
    			
    			if($event['groupId'] == 1){
    				$day = strtolower(date("l",strtotime($row['start_date'])));
    				$dayindex = getDayIndex($day);			
    				$event['allDay']   = false;
    				$event['daysOfWeek']   = json_encode([$dayindex]);
    				$event['startTime']   = strtolower(date("h:i",strtotime($row['start_date'])));
    
    			}
    
    
    			$events .= json_encode($event).',';
    
    		}
    
    		$events = trim($events,',');
    
    	}
    
    	// $sql = "select * from staff where user_id='".$_SESSION['user_id']."' order by id asc";
    	$sql = "select * from contacts where user_id='".$_SESSION['company_id']."' order by id asc ";
    
    	$res = mysqli_query($link,$sql);
    
    	$options = '';
    	$options2 = [];
    
    	if(mysqli_num_rows($res)){
    
    		while($row = mysqli_fetch_assoc($res)){
    
    			//$optionsArray[$row['phone']] = removeCountryCode($row["name"]);
    
    			// $options .= '"'.removeCountryCode($row['phone']).'":"'.$row["name"].'",';
    			$options .= '"'.removeCountryCode($row['phone']).'":"'.$row["first_name"].'",';
    			$options2[] = ['id'=>removeCountryCode($row['phone']),'value'=>removeCountryCode($row['phone']), 'text'=>$row["first_name"]." ".$row["last_name"]];
    
    		}
    
    		$options = trim($options,',');
    
    		//$optionsArray = json_encode($optionsArray);
    		$options2 = json_encode($options2);
    		// echo('<pre>');
    		// print_r($options2);
    		// die;
    
    	}

?>

<!--<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">-->

<!-- <link href="./multi.select.css" rel="stylesheet" type="text/css"> -->

<style>

	.button-red.fill{

		background-color: transparent;

		

	}

	.button-red:hover, .button-red:active{

		border-color: transparent

	}

	.button-red.fill{

		border-color: transparent

	}

	.button-red{

		border-color: transparent

	}

	.multi-button .button-text{

		color: black

	}

	.multi-menu li a span.text{

		color: black

	}

</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

	<div class="py-4">

		<div class="d-flex justify-content-between w-100 flex-wrap">

			<div class="mb-3 mb-lg-0">

				<h1 class="h4">SMS Scheduler</h1>

				<p class="mb-0">Schedule your one time or recurring messages from here. </p>

			</div>

		</div>

	</div>

	

	<?php if(isset($_SESSION['message']) && $_SESSION['message']!='')echo $_SESSION['message'];unset($_SESSION['message']); ?>



	<div class="card border-0 shadow">

		<div id="calendar" class="p-4"></div>

	</div>

</main>

<?php
	}else{
	    
	    echo "<h3 style='height: 250px;' class='mt-4'>Access Denied</h3>";
	}
?>





<div class="modal fade" id="modal-new-event" tabindex="-1" role="dialog" aria-labelledby="modal-new-event" aria-hidden="true">

	<div class="modal-dialog modal-dialog-centered" role="document">
        <?php
            $permission_qry = mysqli_query($link,"select * from permission where inner_user_id='$user_id' AND module_name='scheduler' AND insert_permission='1'");
    	
            if(mysqli_num_rows($permission_qry)==1 || $role_data['role']=="Full Admin" || $user_type==1){
    	?>
		<form id="addNewCalendarEventForm" class="modal-content" method="post">

			<div class="modal-body">

				<div class="mb-4"><label for="eventTitle">Event title</label>

					<input type="text" name="event_title" class="form-control" id="eventTitle" required>

				</div>

				<div class="row">

					<div class="col-12 col-lg-12">

						<div class="mb-4">

							<label for="dateStart">Select start date</label>

							<div class="input-group">

								<span class="input-group-text">

									<svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>

								</span>

								<input data-datepicker="" class="form-control datePicker" id="dateStart" type="text" placeholder="dd/mm/yyyy" required autocomplete="off">

							</div>

						</div>

					</div>

					<div class="col-12 col-lg-12">

						<div class="mb-4"><label>Message</label>

							<textarea name="new_schedule_message" id="new_schedule_message" class="form-control" placeholder="Write your message here"></textarea>

						</div>

					</div>

					<div class="col-12 col-lg-12 d-none">

						<div class="mb-4"><label>If Done</label>

							<textarea name="if_done" id="if_done" class="form-control" placeholder="Will send if replied done."></textarea>

						</div>

					</div>

					<div class="col-12 col-lg-12 d-none">

						<div class="mb-4"><label>If Help</label>

							<textarea name="if_help" id="if_help" class="form-control" placeholder="Will send if replied help."></textarea>

						</div>

					</div>

					<div class="col-12 col-lg-12 mb-4">
						<label>Select Workflow</label>

						<select class="form-control" name="workflow" required>
							<option value="">Select Workflow</option>
							<?php 
							$workflow_data = mysqli_query($link,"select * from workflow where user_id=".$_SESSION['company_id']);

							if(mysqli_num_rows($workflow_data)>0){

								while($row = mysqli_fetch_assoc($workflow_data)){
							?>
							<option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
							<?php }
							} ?>
						</select>
					</div>

				</div>

				<!--

				<div class="row">

					<div class="mb-4">

						<label>Select Department</label>

						<select name="department" id="department" class="form-control" onChange="getAttendies(this)">

							<option value="">- Select One -</option>

						<?php

							$roles = staffRoles();

							foreach($roles as $role){

								echo '<option value="'.$role.'">'.$role.'</option>';

							}	

						?>	

						</select>

					</div>

				</div>

				-->

				<div class="row">

					<div class="mb-4">

						<!-- <div class="multi" id="" style="border-radius: 5px;padding: 10px;border: 1px solid #D1D5DB;"></div> -->
						<select id="attendies_new" multiple style="width: 100%">
							<optgroup label="select all">
							</optgroup>
						</select>
						<div class="wrapper"></div>

					</div>

				</div>

				<div class="row">

					<label><input type="checkbox" name="is_recurring" id="is_recurring" value="1"> Recurring?</label>

				</div>

			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-gray-800" id="aaaddNewEvent" onClick="addNewEvent(this)">Add new event</button>

				<button type="button" class="btn btn-gray-300 ms-auto" data-bs-dismiss="modal">Close</button>

			</div>

		</form>
        <?php
        	}else{
        	    
        	    echo "<h3 class='mt-4'>Create event, Access Denied!</h3>";
        	}
        ?>
	</div>

</div>



<div class="modal fade" id="modal-edit-event" tabindex="-1" role="dialog" aria-labelledby="modal-edit-event" aria-hidden="true">

	<div class="modal-dialog modal-dialog-centered" role="document">
        <?php
            $permission_qry = mysqli_query($link,"select * from permission where inner_user_id='$user_id' AND module_name='scheduler' AND update_permission='1'");
    	
            if(mysqli_num_rows($permission_qry)==1 || $role_data['role']=="Full Admin" || $user_type==1){
    	?>
		<form id="editEventForm" class="modal-content">

			<div class="modal-body">

				<div class="mb-4">

					<label for="eventTitleEdit">Event title</label>

					<input type="text" name="eventTitleEdit" class="form-control" id="eventTitleEdit" required>

				</div>

				<div class="row">

					<div class="col-12 col-lg-12">

						<div class="">

							<label for="dateStartEdit">Select start date</label>

							<div class="input-group">

								<span class="input-group-text">

									<svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>

								</span>

								<input name="dateStartEdit" class="form-control datePicker" id="dateStartEdit" type="text" placeholder="dd/mm/yyyy" required autocomplete="off">                                             

							</div>

						</div>

					</div>

					<div class="col-12 col-lg-12">

						<div class="mb-4"><label>Message</label>

							<textarea name="edit_schedule_message" id="edit_schedule_message" class="form-control" placeholder="Write your message here"></textarea>

						</div>

					</div>

					<div class="col-12 col-lg-12 d-none">

						<div class="mb-4"><label>If Done</label>

							<textarea name="if_done" id="if_done" class="form-control" placeholder="Will send if replied done."></textarea>

						</div>

					</div>

					<div class="col-12 col-lg-12 d-none">

						<div class="mb-4"><label>If Help</label>

							<textarea name="if_help" id="if_help" class="form-control" placeholder="Will send if replied help."></textarea>

						</div>

					</div>

					<div class="col-12 col-lg-12 mb-4">
						<label>Select Workflow</label>

						<select class="form-control" id="workflow_id" name="workflow">
							<option value="">Select Workflow</option>
							<?php 
							$workflow_data = mysqli_query($link,"select * from workflow where user_id=".$_SESSION['company_id']);

							if(mysqli_num_rows($workflow_data)>0){

								while($row = mysqli_fetch_assoc($workflow_data)){
							?>
							<option value="<?= $row['id'] ?>" <?= $row['id']==1?'selected':'' ?>><?= $row['name'] ?></option>
							<?php }
							} ?>
						</select>
					</div>
					<!--

					<div class="row">

						<div class="mb-4">

							<label>Select Department</label>

							<select name="edit_department" id="edit_department" class="form-control" onChange="getAttendies(this)">

								<option value="">- Select One -</option>

							<?php

								$roles = staffRoles();

								foreach($roles as $role){

									echo '<option '.$sel.' value="'.$role.'">'.$role.'</option>';

								}	

							?>	

							</select>

						</div>

					</div>

					-->

					<div class="row">

						<div class="mb-4">

							<!-- <div class="multi" id="" style="border-radius: 5px;padding: 10px;border: 1px solid #D1D5DB;"></div> -->
							<select id="attendies_edit" multiple style="width: 100%">
								<optgroup label="select all">
								</optgroup>
							</select>
							<div class="wrapper"></div>

						</div>

					</div>

					<div class="mb-4">

						<label><input type="checkbox" name="is_recurring" id="edit_is_recurring" value="1"> Recurring?</label>

					</div>

				</div>

			</div>

			<div class="modal-footer">

				<input type="hidden" name="event_id" id="event_id" value="">

				<button type="button" class="btn btn-gray-800 me-2" id="eeeeditEvent" onClick="updateEvent(this)">Update event</button>

				<button type="button" class="btn btn-danger" id="ddddeleteEvent" onClick="deleteEvent(this)">Delete event</button>

				<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>

			</div>

		</form>
        <?php
        	}else{
        	    
        	    echo "<h3 class='mt-4'>Update event, Access Denied!</h3>";
        	}
        ?>
	</div>

</div>

<input type="hidden" name="attendie_numbers" id="attendie_numbers" value="">



<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>

<!-- Core -->

<script src="../vendor/@popperjs/core/dist/umd/popper.min.js"></script>

<script src="../vendor/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- Vendor JS -->

<script src="../vendor/onscreen/dist/on-screen.umd.min.js"></script>

<!-- Slider -->

<script src="../vendor/nouislider/distribute/nouislider.min.js"></script>

<!-- Smooth scroll -->

<script src="../vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>

<!-- Count up -->

<script src="../vendor/countup.js/dist/countUp.umd.js"></script>

<!-- Apex Charts -->

<script src="../vendor/apexcharts/dist/apexcharts.min.js"></script>

<!-- Datepicker -->

<script src="../vendor/vanillajs-datepicker/dist/js/datepicker.min.js"></script>

<!-- DataTables -->

<script src="../vendor/simple-datatables/dist/umd/simple-datatables.js"></script>

<!-- Sweet Alerts 2 -->

<script src="../vendor/sweetalert2/dist/sweetalert2.min.js"></script>

<!-- Moment JS -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>

<!-- Vanilla JS Datepicker -->

<script src="../vendor/vanillajs-datepicker/dist/js/datepicker.min.js"></script>

<!-- Full Calendar -->

<script src="../vendor/fullcalendar/main.min.js"></script>

<!-- Dropzone -->

<script src="../vendor/dropzone/dist/min/dropzone.min.js"></script>

<!-- Choices.js -->

<script src="../vendor/choices.js/public/assets/scripts/choices.min.js"></script>

<!-- Notyf -->

<script src="../vendor/notyf/notyf.min.js"></script>

<!-- Mapbox & Leaflet.js -->

<script src="../vendor/leaflet/dist/leaflet.js"></script>

<!-- SVG Map -->

<script src="../vendor/svg-pan-zoom/dist/svg-pan-zoom.min.js"></script>

<script src="../vendor/svgmap/dist/svgMap.min.js"></script>

<!-- Simplebar -->

<script src="../vendor/simplebar/dist/simplebar.min.js"></script>

<!-- Sortable Js -->

<script src="../vendor/sortablejs/Sortable.min.js"></script>

<!-- Github buttons -->

<script async defer src="https://buttons.github.io/buttons.js"></script>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.1.9/jquery.datetimepicker.min.css" />

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.1.9/jquery.datetimepicker.min.js"></script>

<!-- Volt JS -->

<!--<script src="../js/volt.js"></script>-->

<style>

	#ui-datepicker-div {

		z-index: 99999 !important;

	}
	.select2-results__group
	{
	cursor:pointer !important;
	}
</style>

</body>

</html>

<!-- <script src="./multi.select.js"></script> -->
<!-- <link href="multi_select/jquery.dropdown.min.css" rel="stylesheet" type="text/css">
<script src="multi_select/jquery.dropdown.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>

	function getAttendies(obj){

		var department = $(obj).val();

		$(".overlay").show();

		$.ajax({

			type: "POST",

			url: "server.php?cmd=get_attendies",

			data: {department:department},

			dataType: "json",

			success: function (response){

				$('.multi').multi_select({

					//selectColor: 'purple',

					//selectSize: 'small',

					selectText: 'Select Attendies',

					duration: 300,

					easing: 'slide',

					listMaxHeight: 300,

					selectedCount: 2,

					sortByText: true,

					fillButton: true,

					data: response,

					onSelect: function(values){

						var contacts = JSON.stringify(values);

						$("#attendie_numbers").val(contacts);

						//console.log('return values: ', values);

					}

				});

				$(".overlay").hide();

			},

			error: function(jqXHR, textStatus, errorThrown) {

				$(".overlay").hide();

				alert("errror"+errorThrown);

			}

		});

	}

	function deleteEvent(obj){

		if(confirm("Are you sure you want to delete this event?")){

			$(".overlay").show();

			var eventID = $("#event_id").val();

			$.post("server.php",{"cmd":"delete_event",eventID:eventID},function(){

				window.location = 'scheduler.php';

			});

		}

	}

	function updateEvent(){

		var eventID = $("#event_id").val();

		var title = $("#eventTitleEdit").val();

		var startDate = $("#dateStartEdit").val();

		var message = $("#edit_schedule_message").val();

		var department = $("#edit_department").val();

		// var attendies = $("#attendie_numbers").val();
		if($('#attendies_edit').select2().val()){
			// var attendies = $('#attendies_edit').select2().val().toString();
			var attendies = JSON.stringify($('#attendies_edit').select2().val());
		}else{
			// var attendies = $('#attendies_edit').select2().val();
			var attendies = JSON.stringify($('#attendies_edit').select2().val());
		}

		var workflow_id = $("#workflow_id").val();

		var isRecurring = '0';

		if($("#edit_is_recurring").is(":checked")==true){

			isRecurring = '1';

		}

		$(".overlay").show();

		$.post("server.php",{"cmd":"update_schedule_message",title:title,startDate:startDate,message:message,department:department,attendies:attendies,isRecurring:isRecurring,eventID:eventID,workflow_id:workflow_id},function(){

			window.location = 'scheduler.php';

		});

	}

	function addNewEvent(obj){

		var title = $("#eventTitle").val();

		var startDate = $("#dateStart").val();

		var message = $("#new_schedule_message").val();

		var department = $("#department").val();

		// var attendies = $("#attendie_numbers").val();
		var attendies = JSON.stringify($('#attendies_new').select2().val());

		var workflow_id = $("#workflow_id").val();

		var isRecurring = '0';

		if($("#is_recurring").is(":checked")==true){

			isRecurring = '1';

		}

		

		$(".overlay").show();

		$.post("server.php",{"cmd":"schedule_message",title:title,startDate:startDate,message:message,department:department,isRecurring:isRecurring,attendies:attendies,workflow_id:workflow_id},function(){

			window.location = 'scheduler.php';

		});

	}
	var optionsssss = <?php echo $options2 ?>;
	function newRunSelect2(){
		$('#attendies_new').select2({
			data: optionsssss,
			placeholder: "Select Contacts",
			allowClear: true,
			closeOnSelect: false,
		}).on('select2:open', function() {  

			setTimeout(function() {
				$(".select2-results__option .select2-results__group").bind( "click", selectAlllicknewHandler ); 
			}, 0);

		});
	}

	newRunSelect2();

	var selectAlllicknewHandler = function() {
		console.log('dd');
		$(".select2-results__option .select2-results__group").unbind( "click", selectAlllicknewHandler );        
		$('#attendies_new').select2('destroy').find('option').prop('selected', 'selected').end();
		// console.log($('#attendies_new').select2().val());
		newRunSelect2();
	};
	function editRunSelect2(){
		$('#attendies_edit').select2({
			data: optionsssss,
			placeholder: "Select Contacts",
			allowClear: true,
			closeOnSelect: false,
		}).on('select2:open', function() {  

			setTimeout(function() {
				$(".select2-results__option .select2-results__group").bind( "click", selectAlllickeditHandler ); 
			}, 0);

		});
	}

	editRunSelect2();

	var selectAlllickeditHandler = function() {
		console.log('dd');
		$(".select2-results__option .select2-results__group").unbind( "click", selectAlllickeditHandler );        
		$('#attendies_edit').select2('destroy').find('option').prop('selected', 'selected').end();
		// console.log($('#attendies_edit').select2().val());
		editRunSelect2();
	};
	document.addEventListener('DOMContentLoaded', function(){

		var calendarEl = document.getElementById('calendar');

		var calendar = new FullCalendar.Calendar(calendarEl, {

			initialView: 'dayGridWeek',

			height: 650,

			selectable: true,

			description: 'Lecture',

			department: "",

			attendies: "",

			rrules: [{

				frequency: 'WEEKLY'

			}],

			select: async function (start, end, allDay) { // add new event

				$("#modal-new-event").modal('show');

				$("#dateStart").val(start.startStr);
				// $('.multi').multi_select({

				// 	//selectColor: 'purple',

				// 	//selectSize: 'small',

				// 	selectText: 'Select Attendies',

				// 	duration: 300,

				// 	easing: 'slide',

				// 	listMaxHeight: 300,

				// 	selectedCount: 2,

				// 	sortByText: true,

				// 	fillButton: true,

				// 	data:{<?php //echo $options?>},

				// 	onSelect: function(values){

				// 		var contacts = JSON.stringify(values);

				// 		$("#attendie_numbers").val(contacts);

				// 		//console.log('return values: ', values);

				// 	}

				// });
				// $('.dropdown-sin-2').dropdown({
				// 	// data: json2.data,
				// 	input: '<input type="text" maxLength="20" placeholder="Search">'
				// });

			},

			eventClick: function(info){ // Edit event

				info.jsEvent.preventDefault();

				$("#modal-edit-event").modal('show');
				// console.log(info.event.groupId);

				if(info.event.groupId == '1'){

					$("#edit_is_recurring").prop("checked",true);

				}

				var eventTitle = info.event.title;

				var workflow_id = info.event.extendedProps.workflow_id;

				var eventID = info.event.id;
				// console.log(info.event);

				// var startDate = new Date(info.event.startStr).toISOString().slice(0, 16).replace('T',' ');
				var startDate = moment(info.event.startStr).format('YYYY-MM-DD HH:mm:ss');
				// console.log(startDate);
				var endDate = info.event.endStr;

				$("#event_id").val(eventID);

				$("#eventTitleEdit").val(eventTitle);

				$("#workflow_id").val(workflow_id);

				$("#dateStartEdit").val(startDate);

				$("#edit_schedule_message").val(info.event.extendedProps.description);

				//$("#edit_department option[value="+info.event.extendedProps.department+"]");

				$("#edit_department option[value='"+info.event.extendedProps.department+"']").attr("selected", "selected");

				//$("#edit_department option:contains(" + info.event.extendedProps.department + ")").attr('selected', 'selected');

				//alert(info.event.extendedProps.department);

				//alert(info.event.extendedProps.attendies);

				///alert(info.event.extendedProps.attendies);
				console.log(info.event.extendedProps.attendies);
				$("#attendie_numbers").val(info.event.extendedProps.attendies);
				$('#attendies_edit').val(JSON.parse(info.event.extendedProps.attendies)).trigger('change')
				// $.multi_select.multi_select('init', {
				// 	selectedIndexes: JSON.parse(info.event.extendedProps.attendies),
				// });
				// $('.multi').multi_select({

				// 	//selectColor: 'purple',

				// 	//selectSize: 'small',

				// 	selectText: 'Select Attendies',

				// 	duration: 300,

				// 	easing: 'slide',

				// 	listMaxHeight: 300,

				// 	selectedCount: 2,

				// 	sortByText: true,

				// 	fillButton: true,
				// 	data:{<?php echo $options?>},
				// 	selectedIndexes: JSON.parse(info.event.extendedProps.attendies),

				// 	onSelect: function(values){

				// 		var contacts = JSON.stringify(values);

				// 		$("#attendie_numbers").val(contacts);

				// 		//console.log('return values: ', values);

				// 	}

				// });

			},
			events: [<?php echo $events?>]

				/*

				{

				title  : 'event1',

				start  : '2023-01-15'

				},

				{

				title  : 'event2',

				start  : '2023-01-16',

				end    : '2023-01-17'

				},

				{

				title  : 'event3',

				start  : '2023-01-09T12:30:00',

				allDay : false // will make the time show

				}

				*/

		});

		calendar.render();

	});

	function AdjustMinTime(ct){

		var dtob = new Date(),

		current_date = dtob.getDate(),

		current_month = dtob.getMonth() + 1,

		current_year = dtob.getFullYear();



		var full_date = current_year + '-' +

		( current_month < 10 ? '0' + current_month : current_month ) + '-' + 

		( current_date < 10 ? '0' + current_date : current_date );



		if(ct.dateFormat('Y-m-d') == full_date)

		this.setOptions({ minTime: 0 });

		else 

		this.setOptions({ minTime: false });

	}

	$(".datePicker").datetimepicker({

		format: 'Y-m-d H:i',

		//minDate: 0,

		//minTime: 0,

		step: 5

		//onShow: AdjustMinTime,

		//onSelectDate: AdjustMinTime

	});

	function getCompanyName(obj){

		var companyID = $(obj).val();

		$(".overlay").show();

		$.post("server.php",{"cmd":"switch_company",companyID:companyID},function(r){

			window.location = "dashboard.php";

		});

	}

</script>