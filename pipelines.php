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

	

	

	

	$permission_qry = mysqli_query($link,"select * from permission where inner_user_id='$user_id' AND module_name='pipelines' AND view_permission='1'");

	



	

	if(mysqli_num_rows($permission_qry)==1 || $role_data['role']=="Full Admin" || $user_type==1){

	    

    	$userID = $_SESSION['company_id'];

    	if($_REQUEST['pipeline_id'] != ''){

    		$res = mysqli_query($link, "SELECT * FROM `pipeline_list` WHERE id=".$_REQUEST['pipeline_id']);

    	}else{

    		$res = mysqli_query($link, "SELECT * FROM `pipeline_list` WHERE user_id=".$userID." ORDER BY id ASC LIMIT 1");

    	}

    	$pipelineres = mysqli_query( $link, "SELECT * FROM `pipeline_list` WHERE user_id=" . $userID . " ORDER BY id ASC" );

    	$pipelineress = mysqli_query( $link, "SELECT * FROM `pipeline_list` WHERE user_id=" . $userID . " ORDER BY id ASC" );

    	if(mysqli_num_rows($res)){

    		$pipeline_row = mysqli_fetch_assoc( $res );

    		$stages = json_decode( $pipeline_row[ 'stages' ] );

    	}else{

    		$pipeline_row[ 'title' ] = '';

    		$pipeline_row[ 'id' ] = 0;

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

	

	.columnHeader {

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

	

	li {

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

		font-family: 'Helvetica Neue', Arial, Helvetica, sans-serif;

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

		box-shadow: 0 1px 0 rgba(9, 45, 66, .25);

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

	

	.bookingCardIcons {

		margin-right: 7px;

	}

</style>

<div class="py-2">

	<h3>

		<?= $pipeline_row['title'] ?>

		<select name="pipeliness" class="form-control" onchange="getPipelinelist(this)" style="display: inline-block;width: auto;">

			<option value="">-- select Pipeline ---</option>

			<?php while($rowdatt = mysqli_fetch_assoc($pipelineres)){ ?>

			<option value="<?= $rowdatt['id'] ?>" <?=$pipeline_row['id']==$rowdatt['id']? 'selected': '' ?>>

				<?= $rowdatt['title'] ?>

			</option>

			<?php } ?>

		</select>

		<a href="pipelineslist.php" class="btn btn-primary" style="float: right">All Piplines</a>

	</h3>

	<?php if(isset($_SESSION['message']) && $_SESSION['message']!='')echo $_SESSION['message'];unset($_SESSION['message']); ?>

</div>



<div class="card">

	<div class="row statuses" style="display: flex;padding: 5px;flex-direction: row;">

		<?php foreach($stages as $stage){ ?>

		

		<div class="col-md-3 col-lg-3 col-sm-12 mt-3">

			<li class="column__item">

				

				<div class="column__title--wrapper">

					<h2><?= $stage ?></h2>

					<i class="fas fa-ellipsis-h" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false"></i>

					<ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">

						<li><a class="dropdown-item" href="select_template.php">Create Workflow</a>

						</li>

						<li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#assign_workflow" onclick="assinged_stauts(this)" data-status="<?= strtolower($stage) ?>">Assign Workflow</a>

						</li>

					</ul>

				</div>

				

				<ul class="card__list dragula_card" id="<?= strtolower($stage) ?>"></ul>

				

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

							$workflow_data = mysqli_query($link,"select * from workflow where user_id=".$_SESSION['company_id']);

							if(mysqli_num_rows($workflow_data)>0){

			

								while($row = mysqli_fetch_assoc($workflow_data)){

							?>

						<option value="<?= $row['id'] ?>">

							<?= $row['name'] ?>

						</option>

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

			<!--<form action="server.php" method="post">-->

				<div class="modal-header">

					<h2 class="h6 modal-title">Edit Opportunity</h2>

					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

				</div>

				<div class="modal-body">

					<div class="text-center d-none" id="loading_data">

						<p>Loading...</p>

					</div>

					<div id="showforms">

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

								<div class="row">

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

											<?php while($rowdattt = mysqli_fetch_assoc($pipelineress)){ ?>

											<option value="<?= $rowdattt['id'] ?>">

												<?= $rowdattt['title'] ?>

											</option>

											<?php } ?>

										</select>

									</div>

									<div class="col-md-6">

										<label class="mb-0 mt-1">Stage</label>

										<select name="opportunity_stage" id="opportunity_stage" class="form-control">

											</select>

									

									</div>

									<div class="col-md-6">

										<label class="mb-0 mt-1">Lead Value</label>

										<input type="number" name="opportunity_leadvalue" class="form-control" value="" placeholder="Lead Value"/>

									</div>

									<div class="col-md-6">

										<label class="mb-0 mt-1">Opportunity Source</label>

										<input type="text" name="opportunity_source" class="form-control" value="" placeholder="Opportunity Source"/>

									</div>

									<div class="col-md-12">

										<label class="mb-0 mt-1">Address</label>

										<textarea type="text" name="opportunity_address" id="opportunity_address" class="form-control" value="" placeholder="Enter Address"></textarea>

									</div>

									<div class="col-md-12" id="notesContainer">

										<label class="mb-0 mt-1"><h5>Notes</h5></label>

										<div class="notesContainer">
											<?php

												$sqlN = "select * from contact_notes where user_id='" . $_SESSION[ 'company_id' ] . "'";

												$resN = mysqli_query( $link, $sqlN );

												if ( mysqli_num_rows( $resN ) ) {

													while ( $rowN = mysqli_fetch_assoc( $resN ) ) {

														echo '<p>' . date( "d/m/Y h:ia", strtotime( $rowN[ 'created_date' ] ) ) . ' => ' . $rowN[ 'notes' ] . '</p>';

													}

												}

											?>
										</div>

										<div class="form-group">

											<div class="row">

												<div class="col-md-11">

													<textarea name="opportunity_notes" id="opportunity_notes" class="form-control"></textarea>

												</div>

												<div class="col-md-1 text-center">

													<i class="fa fa-plus" title="Save notes" onClick="saveNotes()" style="cursor: pointer"></i>

												</div>

											</div>

										</div>

									</div>

								</div>

								

								<div class="modal-footer">

									<input type="button" value="Update" class="btn btn-primary" onClick="updateOpportunityBox()">

									

									<input type="button" value="Delete" class="btn btn-danger" onClick="removeFromPipeline()">

									

									<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>

								</div>

							</div>

							

							<div class="tab-pane fade" id="nav-book-ppointment" role="tabpanel" aria-labelledby="nav-book-ppointment-tab" style="min-height: 50px">

							<?php

								if($_SESSION['google_calendar_access_token']!=''){

							?>

									<form action="server.php" method="post">

										<div class="row mt-3">

											<div class="col-md-12">

												<input type="text" name="event_title" class="form-control" placeholder="Add title" required style="font-size: 1.4rem; border-radius: 0px; border: none; border-bottom: 1px solid"/>

											</div>

										</div>

										<div class="row mt-3">

											<div class="col-md-1 mt-3 text-center" style="font-size: 1.2rem; padding-left: 15px;">

												<i class="fa fa-clock"></i>

											</div>

											<?php //echo date("l, M, d")?>

											<div class="col-md-4 mt-3" style="font-size: 1.2rem; padding-left: 15px;">

												<input type="text" name="event_date" class="datePicker form-control" readonly placeholder="Select date" required value="<?php echo date("m/d/Y");?>">

											</div>

											<div class="col-md-2 mt-3 text-center" style="font-size: 1.2rem; padding-left: 15px;">

												<select name="start_time" class="form-control">

												<?php

													$timeArray = getTimeArray();

													foreach($timeArray as $key => $value){

														echo '<option value="'.$key.'">'.$value.'</option>';

													}

												?>	

												</select>

											</div> 

											<div class="col-md-1 mt-3 text-center" style="font-size: 1.2rem">-</div>

											<div class="col-md-2 mt-3 text-center" style="font-size: 1.2rem">

												<select name="end_time" class="form-control">

												<?php

													$timeArray = getTimeArray();

													foreach($timeArray as $key => $value){

														echo '<option value="'.$key.'">'.$value.'</option>';

													}

												?>	

												</select>

											</div>

											<div class="col-md-1 mt-3" style="font-size: 1.2rem"></div>

										</div>

										<div class="row mt-3">

											<div class="col-md-1 text-center" style="font-size: 1.2rem; padding-left: 15px;">

												<i class="fa fa-users"></i>

											</div>

											<div class="col-md-11">

												<input type="text" name="event_attendies" class="form-control" placeholder="Add guests" style="font-size: 1.2rem; border-radius: 0px; border: none; border-bottom: 1px solid"/>

											</div>

										</div>

										<div class="row mt-3">

											<div class="col-md-1 text-center" style="font-size: 1.2rem; padding-left: 15px;">

												<i class="fa fa-align-left"></i>

											</div>

											<div class="col-md-11">

												<input type="text" name="event_description" class="form-control" placeholder="Add description" style="font-size: 1.2rem; border-radius: 0px; border: none; border-bottom: 1px solid"/>

											</div>

										</div>

										<div class="modal-footer">

											<input type="hidden" name="cmd" value="create_google_calendar_event">

											<input type="submit" value="Save" class="btn btn-primary">

											<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>

										</div>

									</form>

								<?php

								}else{

									$login_url = 'https://accounts.google.com/o/oauth2/auth?scope='.urlencode('https://www.googleapis.com/auth/calendar').'&redirect_uri='. urlencode($redirectUrl).'&response_type=code&client_id='.$clientID.'&access_type=online';

							?>

									<p style="text-align: center; margin-top: 20px;"><a id="logo" href="<?= $login_url ?>" style="margin: auto">Login with Google</a></p>	

							<?php

								}

							?>

							</div>

							<!--

							<div class="tab-pane fade" id="nav-book-ppointment" role="tabpanel" aria-labelledby="nav-book-ppointment-tab">

								<div class="row mt-3">

									<div class="col-md-12">

										<input type="text" name="event_title" class="form-control" placeholder="Add title" style="font-size: 1.4rem; border-radius: 0px; border: none; border-bottom: 1px solid"/>

									</div>

								</div>

								<div class="row mt-3">

									<div class="col-md-1 mt-3 text-center" style="font-size: 1.2rem; padding-left: 15px;">

										<i class="fa fa-clock"></i>

									</div>

									<div class="col-md-4 mt-3" style="font-size: 1.2rem; padding-left: 15px;">

										<?php echo date("l, M, d")?>

									</div>

									<div class="col-md-2 mt-3 text-center" style="font-size: 1.2rem; padding-left: 15px;">

										<select name="start_time" class="form-control">

										<?php

											$timeArray = getTimeArray();

											foreach($timeArray as $key => $value){

												echo '<option value="'.$key.'">'.$value.'</option>';

											}

										?>	

										</select>

									</div> 

									<div class="col-md-1 mt-3 text-center" style="font-size: 1.2rem">-</div>

									<div class="col-md-2 mt-3 text-center" style="font-size: 1.2rem">

										<select name="end_time" class="form-control">

										<?php

											$timeArray = getTimeArray();

											foreach($timeArray as $key => $value){

												echo '<option value="'.$key.'">'.$value.'</option>';

											}

										?>	

										</select>

									</div>

									<div class="col-md-1 mt-3" style="font-size: 1.2rem"></div>

								</div>

								<div class="row mt-3">

									<div class="col-md-1 text-center" style="font-size: 1.2rem; padding-left: 15px;">

										<i class="fa fa-users"></i>

									</div>

									<div class="col-md-11">

										<input type="text" name="event_title" class="form-control" placeholder="Add guests" style="font-size: 1.2rem; border-radius: 0px; border: none; border-bottom: 1px solid"/>

									</div>

								</div>

								<div class="row mt-3">

									<div class="col-md-1 text-center" style="font-size: 1.2rem; padding-left: 15px;">

										<i class="fa fa-align-left"></i>

									</div>

									<div class="col-md-11">

										<input type="text" name="event_title" class="form-control" placeholder="Add description and attachments" style="font-size: 1.2rem; border-radius: 0px; border: none; border-bottom: 1px solid"/>

									</div>

								</div>

								

								<div class="modal-footer">

									<input type="button" value="Save" class="btn btn-primary">

									<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>

								</div>

							</div>

							-->

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

				

			<!--</form>-->

		</div>

	</div>

</div>

<div class="modal fade" id="showContactMedia" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">

	<div class="modal-dialog modal-dialog-centered" role="document">

		<div class="modal-content">

				<div class="modal-header">

					<h2 class="h6 modal-title">Media list</h2>

					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

				</div>

				<div class="modal-body showMedia" style="max-height: 450px;text-align: center; overflow-y: auto"></div>

				<div class="modal-footer">

					<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>

				</div>

		</div>
	</div>
</div>

<div class="modal fade" id="sendSMSToContact" tabindex="-1" aria-labelledby="callBack_modal_label" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="">Send SMS to <span class="showToNumber"></span></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form method="post" action="server.php?cmd=send_sms_to_call_log" enctype="multipart/form-data">
					<div class="form-group mt-2">
						<label>Recipient</label>
						<input type="text" name="recipient_number" id="recipient_number" class="form-control" value="" readonly>
					</div>
					<div class="form-group mt-2">
						<label>Write message</label>
						<textarea type="text" name="recipient_message" class="form-control" required></textarea>
					</div>
					<div class="form-group mt-2">
						<label>Attachment <span style="font-size: 10px">(png, jpg, jpeg, bmp, gif)</sapn></label>
						<input type="file" name="recipient_media" class="form-control">
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-secondary">Send now</button>
						<button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<input type="hidden" name="contact_id" id="contact_id" value="">

<input type="hidden" id="last_name" value="">



<?php

	}else{

	    

	    echo "<h3 style='height: 250px;' class='mt-4'>Access Denied</h3>";

	}

?>	

<?php include_once("footer.php");?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<link rel="stylesheet" href="/resources/demos/style.css">

<script src="https://code.jquery.com/jquery-3.6.0.js"></script>

<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>



<script src="./js/dragula.js"></script>

<script>

$("#sendSMSToContact").on("hidden.bs.modal", function (){
	$('.videoPlayer')[0].pause();
});
function sendSMSToContact(phone){
	$("#sendSMSToContact").modal('show');
	//$(".showMedia").html("Loading...");
	//$(".showMedia").html();
	$("#recipient_number").val(phone);
	$(".showToNumber").text(phone);
}

	/*

	$(document).ready(function(){

		$('#eventForm').on('submit',(function(e){

			e.preventDefault();

			var formData = new FormData(this);

			$.ajax({

				type:'POST',

				url: 'server.php?cmd=create_google_calendar_event',

				data:formData,

				cache:false,

				contentType: false,

				processData: false,

				success:function(data){

					if(data == 'error'){

						alert("Error in successs");

					}else{

						alert("success");

					}

				},

				error: function(data){

					alert("Error");

				}

			});

			return false;

		}));	

	});

	*/

	$(function(){

		$(".datePicker").datepicker();

	});

	

	$("#showContactMedia").on("hidden.bs.modal", function (){

		$('.videoPlayer')[0].pause();

	});

	function showContactMedia(phone){

		$("#showContactMedia").modal('show');

		$(".showMedia").html("Loading...");

		$.post("server.php",{"cmd":"get_contact_media",phone:phone},function(r){

			$(".showMedia").html(r);

		});	

	}

	function removeFromPipeline(){

		var contactID = $("#contact_id").val();

		var pipelineID = '<?php echo $_REQUEST['pipeline_id']?>';

		if(confirm("Are you sure you want to remove this booking card?")){

			$(".overlay").show();

			$.post("server.php",{"cmd":"remove_contact_from_pipeline",contactID:contactID},function(){

				if($.trim(pipelineID)!='')

					window.location = 'pipelines.php?pipeline_id='+pipelineID;

				else

					window.location = 'pipelines.php';

			});

		}

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

		var notes = $( "#opportunity_notes" ).val();

		var opportunityAddress = $('#opportunity_address').val();



		$.post("server.php",{"cmd":"update_opportunity",firstName:firstName,lastName:lastName,email:email,phone:phone,companyName:companyName,OpportunityName:OpportunityName,pipeLineID:pipeLineID,pipeLineStage:pipeLineStage,leadValue:leadValue,opportunitySource:opportunitySource,contactID:contactID,notes:notes,opportunityAddress:opportunityAddress},function(){

			window.location = 'pipelines.php?pipeline_id='+pipeLineID;

		});

	}

	function saveNotes() {

		var notes = $( "#opportunity_notes" ).val();

		var contactID = $( "#contact_id" ).val();

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

	function getPipelinelist( obj ) {

		var pipelineID = $( obj ).val();

		$( ".overlay" ).show();

		window.location = "pipelines.php?pipeline_id=" + pipelineID;

	}

	dragula( [

		<?php foreach($stages as $stage){ ?>

		document.getElementById( "<?= strtolower($stage) ?>" ),

		<?php } ?>

	], {

		removeOnSpill: false

	} )

	.on( "drag", function ( el ) {

		// el.className.replace("ex-moved", "");

	} )

	.on( "drop", function ( el ) {

		update( el )

		// el.className += "ex-moved";

		// alert("Droped");

	} )

	.on( "over", function ( el, container ) {

		// container.className += "ex-over";

	} )

	.on( "out", function ( el, container ) {

		// container.className.replace("ex-over", "");

	} );

	

	/* Vanilla JS to delete tasks in 'Trash' column */

	function emptyTrash() {

		/* Clear tasks from 'Trash' column */

		document.getElementById( "trash" ).innerHTML = "";

	}

	function update( el ) {

		jQuery.ajax( {

			type: "post",

			dataType: "json",

			url: "server.php",

			data: {

				cmd: "update_booking_status",

				id: el.id,

				status: el.parentElement.id

			},

			success: function ( response ) {

			}

		} )

	}

	function update_order( el ) {

		var data = {};

		jQuery( '.dragula_card' ).each( function ( a, b, c ) {

			if ( b.id != '' ) {

				data[ b.id ] = [];

				var o = 1;

				jQuery( b ).children().each( function ( d, e, f ) {

					data[ b.id ].push( {

						id: e.id,

						order: o

					} )

				} )

			}

		} )

		// console.log(data)

		d = data;

		jQuery.ajax( {

			type: "post",

			dataType: "json",

			url: "server.php",

			data: {

				cmd: "update_pipeline_order",

				data: d

			},

			success: function ( response ) {

			}

		} )

	}

	function getRandomColor() {

		var letters = '0123456789ABCDEF';

		var color = '#';

		color += letters[ Math.floor( Math.random() * 16 ) ];

		color += letters[ Math.floor( Math.random() * 16 ) ];

		color += '00';

		// color += letters[Math.floor(Math.random() * 16)];

		color += letters[ Math.floor( Math.random() * 16 ) ];

		color += letters[ Math.floor( Math.random() * 16 ) ];

		return color;

	}

	function assinged_stauts( e ){

		var boking_status = $( e ).attr( 'data-status' );

		console.log( boking_status );

		$( 'input[name=booking_status]' ).val( boking_status );

	}

	function getStages( e ) {

		var pipelinss_id = $( e ).val();

		// console.log(pipelinss_id);

		jQuery.ajax( {

			type: "post",

			dataType: "json",

			url: "server.php",

			data: {

				cmd: "get_pipeline_stages",

				pipeline_id: pipelinss_id

			},

			success: function ( response ) {

				console.log( response );

				if ( response ) {

					$( '#opportunity_stage' ).html( response );

				}

			}

		} )

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

	jQuery(document).ready(function(){

		jQuery.ajax({

			type: "post",

			dataType: "json",

			url: "server.php",

			data: {

				cmd: "all_pipeline_booking",

				'pipeline_id': <?= $pipeline_row['id'] ?>

			},

			success: function(response){

				//console.log(response);

				for ( var i = 0; i < response.data.length; i++ ) {

					

					var card = response.data[i].pipeline_stage;

					var firstName = response.data[i].first_name;

					var lastName  = response.data[i].last_name;
					if($.trim(lastName)!='' || $.trim(lastName)!='null'){
						var fullName  = firstName+' '+lastName;
					}else{
						var fullName  = firstName;
					}

					if($.trim(fullName)=='')

						fullName = response.data[i].phone;

							

					var bookingTime = response.data[i].booking_time;

					if($.trim(bookingTime)=='' || $.trim(bookingTime)=='null'){

						bookingTime = '';

					}

					var leadValue = response.data[i].lead_value;

					var opportunityName = response.data[i].opportunity_name;

					if($.trim(opportunityName)=='')

						opportunityName = 'Opportunity name';

					var contactID = response.data[i].id;

					var phone = response.data[i].phone;

					var address = '';
					var complete_address = response.data[i].address;

					// Old Address Method:
					// var streetAddress = $.trim(response.data[i].street_address);
					// var city = $.trim(response.data[i].city);
					// var zipCode = $.trim(response.data[i].zipcode);
					// var kcgState = $.trim(response.data[i].kcg_state);
					// if(streetAddress!='')
						// address += streetAddress;
					// if(city!='')
						// address += ', '+city;
					// if(zipCode!='')
						// address += ', '+zipCode;
					// if(kcgState!='')
						// address += ', '+kcgState;

					if(complete_address!=null){
						if(complete_address != ''){
							address = `<h6 class="card__title">` + complete_address + `</h6>`;
						}else{
							address = '';
						}
					}else{
						address = '';
					}

					

					var header = `<span class="card__tag" style="background-color: ` + getRandomColor() + `;float:left;margin-top:2px;text-align:left">`+opportunityName+`</span> <span class="card__tag" style="background-color: ` + getRandomColor() + `;">` + bookingTime + `</span><h6 class="card__title" onclick='getBookingId(this,`+contactID+`)' data-bs-toggle='modal' data-bs-target='#assign_opportunity' style="cursor:pointer">`+fullName+`</h6>`+address;


					//id = 'booking_' + response.data[ i ].id;

					//addTask(response.data[i].status, name, id, response.data[i].firstName)

					addTask(card, header, contactID, fullName, leadValue, phone)

				}

			}

		})

	});

	function addTask(card, header, contactID, fullName='',leadValue, phone){

		document.getElementById(card).innerHTML += "<li id='"+contactID+"' class='card__item' data-name='" + fullName + "'><h6 class='card__title' style='text-align:right'>" + header + "</h6><p style='margin-bottom:0;margin-top:1rem'> <i class='fa fa-comments bookingCardIcons' onclick='sendSMSToContact("+phone+")' style=\'color:blue;cursor:pointer\'></i> <i class='fa fa-phone-square bookingCardIcons' onclick='alert(\"working at backend\")' style=\'color:green;cursor:pointer;margin-right:11px;\'></i><i class='fa fa-folder bookingCardIcons' onclick='showContactMedia("+phone+")' style=\'color:darkorange;cursor:pointer\'></i><span style='float:right'>"+leadValue+"</span></p></li>";

	}

	function getBookingId(e,contactID){

		//var boking_id = $(e).attr('id');

		var boking_id = contactID;

		//var boking_contact_name = $(e).attr('data-name');

		//console.log( boking_id );

		$('input[name=booking_id]').val(boking_id);

		$('#loading_data').removeClass('d-none');

		$('#showforms').addClass('d-none');

		$(".notesContainer").html('');

		jQuery.ajax({

			type: "post",

			dataType: "json",

			url: "server.php",

			data:{

				cmd: "get_bookings_oppert_details",

				booking_id: boking_id

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

					$('select[name=opportunity_stage]').val(contactInfo.pipeline_stage);

					$('input[name=opportunity_leadvalue]').val(contactInfo.lead_value);

					$('input[name=opportunity_source]').val(contactInfo.opportunity_source);

					$('#contact_id').val(contactInfo.id);

					$('#opportunity_address').val(contactInfo.address);

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




</script>

<style type="text/css">

	.card__item {

		cursor: default;

	}

	.task p {

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