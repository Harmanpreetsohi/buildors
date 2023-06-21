<?php
	include_once( "header.php" );
	
	$user_id = $_SESSION['user_id'];
// 	$role_id = $_SESSION['role_id'];
	$user_type = $_SESSION['user_type'];
	
	$user_qry = mysqli_query($link,"select role_id from users where id='$user_id'");
	$user_data = mysqli_fetch_assoc($user_qry);
	$role_id = $user_data['role_id'];
	
	$role_qry = mysqli_query($link,"select * from role_master where role_id='$role_id'");
	$role_data = mysqli_fetch_assoc($role_qry);
	
	$permission_qry = mysqli_query($link,"select * from permission where inner_user_id='$user_id' AND module_name='calendar' AND view_permission='1'");
	
	if(mysqli_num_rows($permission_qry)==1 || $role_data['role']=="Full Admin" || $user_type==1){
?>
<button id="signout_button" onclick="handleSignoutClick()" style="display: none">Sign Out</button>
<script async defer src="https://apis.google.com/js/api.js" onload="gapiLoaded()"></script> 
<script async defer src="https://accounts.google.com/gsi/client" onload="gisLoaded()"></script>

<div class="py-4">
	<div class="d-flex justify-content-between w-100 flex-wrap">
		<div class="mb-3 mb-lg-0">
			<h1 class="h4">Appointments Calendar</h1>
			<p class="mb-0">All of you appointments booked from ivr will be shown here.</p>
		</div>
		<button id="authorize_button" class="btn btn-primary btn-sm" onclick="handleAuthClick()">Authorize</button>
	</div>
</div>

<script>
	/* exported gapiLoaded */
	/* exported gisLoaded */
	/* exported handleAuthClick */
	/* exported handleSignoutClick */

	// TODO(developer): Set to client ID and API key from the Developer Console
	const CLIENT_ID = '894644777761-ja8i6p9isc7k4flvhec78edol1t9gg1l.apps.googleusercontent.com';
	const API_KEY = 'AIzaSyCu7Nb3J0KuU123fsYdB5XS77OeXtGlnP4';

	// Discovery doc URL for APIs used by the quickstart
	const DISCOVERY_DOC = 'https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest';

	// Authorization scopes required by the API; multiple scopes can be
	// included, separated by spaces.
	const SCOPES = 'https://www.googleapis.com/auth/calendar.readonly';

	let tokenClient;
	let gapiInited = false;
	let gisInited = false;

	document.getElementById('authorize_button').style.visibility = 'hidden';
	document.getElementById('signout_button').style.visibility = 'hidden';

	/**
	* Callback after api.js is loaded.
	*/
	function gapiLoaded(){
		gapi.load('client', initializeGapiClient);
	}

	/**
	* Callback after the API client is loaded. Loads the
	* discovery doc to initialize the API.
	*/
	async function initializeGapiClient(){
		await gapi.client.init({
			apiKey: API_KEY,
			discoveryDocs: [DISCOVERY_DOC],
		});
		gapiInited = true;
		maybeEnableButtons();
	}

	/**
	* Callback after Google Identity Services are loaded.
	*/
	function gisLoaded(){
		tokenClient = google.accounts.oauth2.initTokenClient({
			client_id: CLIENT_ID,
			scope: SCOPES,
			callback: '', // defined later
		});
		gisInited = true;
		maybeEnableButtons();
	}

	/**
	* Enables user interaction after all libraries are loaded.
	*/
	function maybeEnableButtons(){
		if(gapiInited && gisInited){
			document.getElementById('authorize_button').style.visibility = 'visible';
		}
	}

	/**
	*  Sign in the user upon button click.
	*/
	function handleAuthClick(){
		tokenClient.callback = async (resp) =>{
			if(resp.error !== undefined){
				throw (resp);
			}
			document.getElementById('signout_button').style.visibility = 'visible';
			document.getElementById('authorize_button').innerText = 'Refresh';
			await listUpcomingEvents();
		};
		if(gapi.client.getToken() === null){
			// Prompt the user to select a Google Account and ask for consent to share their data
			// when establishing a new session.
			tokenClient.requestAccessToken({prompt: 'consent'});
		}else{
			// Skip display of account chooser and consent dialog for an existing session.
			tokenClient.requestAccessToken({prompt: ''});
		}
	}

	/**
	*  Sign out the user upon button click.
	*/
	function handleSignoutClick(){
		const token = gapi.client.getToken();
		if(token !== null){
			google.accounts.oauth2.revoke(token.access_token);
			gapi.client.setToken('');
			document.getElementById('content').innerText = '';
			document.getElementById('authorize_button').innerText = 'Authorize';
			document.getElementById('signout_button').style.visibility = 'hidden';
		}
	}

	/**
	* Print the summary and start datetime/date of the next ten events in
	* the authorized user's calendar. If no events are found an
	* appropriate message is printed.
	*/

	function formatDate(date) {
		var d = new Date(date),
		month = '' + (d.getMonth() + 1),
		day = '' + d.getDate(),
		year = d.getFullYear();

		if (month.length < 2) 
			month = '0' + month;
		if (day.length < 2) 
			day = '0' + day;

		return [year, month, day].join('-');
	}
	
	async function listUpcomingEvents(){
		let response;
		try{
			const request = {
				'calendarId': 'primary',
				'timeMin': (new Date()).toISOString(),
				'showDeleted': false,
				'singleEvents': true,
				//'maxResults': 10,
				'orderBy': 'startTime',
			};
			response = await gapi.client.calendar.events.list(request);
		}catch(err){
			document.getElementById('content').innerText = err.message;
			return;
		}
		const events = response.result.items;
		// console.log(events);
		if(!events || events.length == 0){
			document.getElementById('content').innerText = 'No events found.';
			return;
		}
		// Flatten to string to display
		
		
		var myEvents = [];
		// const output = events.reduce((str, event) => {
		// 	console.log(event);
		// 	//alert(event.summary+"-"+event.start.dateTime+"-"+event.end.dateTime);
		// 	var eventTitle = event.summary;
		// 	var startDate = formatDate(event.start.dateTime);
		// 	var endDate   = formatDate(event.end.dateTime);
		// 	var eventsInfo = {
		// 		title: eventTitle,
		// 		start: startDate,
		// 		end: endDate
		// 	}
		// 	console.log(eventsInfo);
		// 	//return myEvents;
		// 	myEvents.push(eventsInfo);
		// 	//console.log("after function \n");
		// });
		events.forEach((event) => {
			// console.log(event);
			var eventTitle = event.summary;
			var startDate = formatDate(event.start.dateTime);
			var endDate   = formatDate(event.end.dateTime);
			var eventsInfo = {
				title: eventTitle,
				start: startDate,
				end: endDate
			}
			// console.log(eventsInfo);
			//return myEvents;
			myEvents.push(eventsInfo);
			//console.log("after function \n");
		});
		
		// console.log(myEvents);
		var calendarEl = document.getElementById('calendar');
		var calendar = new FullCalendar.Calendar(calendarEl, {
			initialView: 'dayGridMonth',
			height: 650,
			selectable: true,
			select: async function (start, end, allDay) { // add new event
				$("#modal-new-event").modal('show');
				//$("#dateStart").val(start.startStr);
				//alert("adding");
			},
			eventClick: function(info){ // Edit event
				// console.log(info);
				info.jsEvent.preventDefault();
				$("#modal-edit-event").modal('show');
				//alert("updating..");
				//alert("updating..");
			},
			events: myEvents
		});
		calendar.render();
		//console.log(formatDate('Sun May 11,2014'));

		//document.getElementById('content').innerText = output;
		//console.log(output);
	}
	
	/*
	// create event
	const event = {
		'summary': 'AA-Web Consultants Have Fun!',
		'location': '800 Howard St., San Francisco, CA 94103',
		'description': 'just a test event created via buildors.com dashboard',
		'start': {
			'dateTime': '2023-01-20T09:00:00-07:00',
			'timeZone': 'America/Los_Angeles'
		},
		'end': {
			'dateTime': '2023-01-20T17:00:00-07:00',
			'timeZone': 'America/Los_Angeles'
		},
		'recurrence': [
			'RRULE:FREQ=DAILY;COUNT=2'
		],
		'attendees': [
			{'email': 'ahsan@example.com'}
		],
		'reminders': {
			'useDefault': false,
			'overrides': [
				{'method': 'email', 'minutes': 24 * 60},
				{'method': 'popup', 'minutes': 10}
			]
		}
	};

	
	function createEvent(event){
		const request = gapi.client.calendar.events.insert({
			'calendarId': 'primary',
			'resource': event
		});
		request.execute(function(event) {
			appendPre('Event created: ' + event.htmlLink);
		});
	}
	*/
	/// end
	
	
</script>

	<div class="card border-0 shadow">
		<!--<button onClick="createEvent(event)" class="btn btn-primary">Create Event</button>-->
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
		<form id="addNewCalendarEventForm" class="modal-content" method="post">
			<div class="modal-body">
				<div class="mb-4"><label for="eventTitle">Event title</label>
					<input type="text" name="event_title" class="form-control" id="eventTitle" required>
				</div>
				<div class="row">
					<div class="col-12 col-lg-6">
						<div class="mb-4"><label for="dateStart">Select start date</label>
							<div class="input-group">
								<span class="input-group-text">
									<svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
								</span>
								<input data-datepicker="" class="form-control datePicker" id="dateStart" type="text" placeholder="dd/mm/yyyy" required autocomplete="off">
							</div>
						</div>
					</div>
					<div class="col-12 col-lg-6">
						<div class="mb-2"><label for="dateEnd">Select end date</label>
							<div class="input-group date" data-provide="datepicker">
								<span class="input-group-text">
									<svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
								</span>
								<input class="form-control datePicker" id="dateEnd" type="text" placeholder="dd/mm/yyyy" required autocomplete="off"> 
							</div>
						</div>
					</div>
				</div>
				<div class="mb-4">
					<label><input type="checkbox" name="is_recurring" id="is_recurring" value="1"> Recurring?</label>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-gray-800" id="aaaddNewEvent" onClick="addNewEvent(this)">Add new event</button>
				<button type="button" class="btn btn-gray-300 ms-auto" data-bs-dismiss="modal">Close</button>
			</div>
		</form>
	</div>
</div>

<div class="modal fade" id="modal-edit-event" tabindex="-1" role="dialog" aria-labelledby="modal-edit-event" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<form id="editEventForm" class="modal-content">
			<div class="modal-body">
				<div class="mb-4">
					<label for="eventTitleEdit">Event title</label>
					<input type="text" name="eventTitleEdit" class="form-control" id="eventTitleEdit" required>
				</div>
				<div class="row">
					<div class="col-12 col-lg-6">
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
					<div class="col-12 col-lg-6">
						<div class="mb-2">
							<label for="dateEndEdit">Select end date</label>
							<div class="input-group">
								<span class="input-group-text">
									<svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
								</span>
								<input name="dateEndEdit" class="form-control datePicker" id="dateEndEdit" type="text" placeholder="dd/mm/yyyy" required autocomplete="off">                                               
							</div>
						</div>
					</div>
					<div class="mb-4">
						<label><input type="checkbox" name="is_recurring" id="is_recurring" value="1"> Recurring?</label>
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
	</div>
</div>

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

<!-- Volt JS -->
<!--<script src="../js/volt.js"></script>-->


</body>
<style>
	#ui-datepicker-div {
		z-index: 99999 !important;
	}
</style>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>
	$(document).ready(function(){
		$(".datePicker").datepicker({
			dateFormat: 'yy-mm-dd'
		});
	});
	function getCompanyName(obj){
		var companyID = $(obj).val();
		$(".overlay").show();
		$.post("server.php",{"cmd":"switch_company",companyID:companyID},function(r){
			window.location = "dashboard.php";
		});
	}
</script>