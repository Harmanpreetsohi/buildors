<!doctype html>
<html>

	<head>
		<meta charset="utf-8">
		<title>Untitled Document</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
	  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

	</head>

	<body>
		<header>
			<div class="container">
				<h1 class="text-center">
					Demo Workflow Diagram
				</h1>
			</div>
		</header>
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<p class="lead text-center bg-info btn text-info center-block" data-toggle="modal" data-target="#myModal">Add new workflow trigger</p>
					<div class="row">
						<div class="col-xs-12 text-center">
							<p class="btn"><span class="glyphicon glyphicon-arrow-down"></span>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-xs-12 text-center">
					<p class="center-block">
						<!--
						<span class="btn btn-danger btn-lg" data-bs-toggle="modal" data-bs-target="#myModal">No</span>
						-->
						<button type="button" class="btn btn-danger btn-lg">Demo</button>
					</p>
					<p class="btn center-block"><span class="glyphicon glyphicon-arrow-down"></span>
					</p>
					<p class="lead text-center bg-info btn text-info center-block" data-toggle="modal" data-target="#myModal">Add your first action</p>
					<div class="row">
						<div class="col-xs-6 text-center">
							<p class="btn"><span class="glyphicon glyphicon-arrow-down"></span>
						</div>
						<div class="col-xs-6 text-center">
							<p class="btn">
								<span class="glyphicon glyphicon-arrow-down"></span>
							</p>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6">
							<p class="center-block">
								<!--
								<span class="btn btn-success btn-lg">Yes</span>
								-->
								<button type="button" class="btn btn-success btn-lg" >Demo</button>
							</p>
							<p class="bg-success text-success btn text-wrap">hhjkjuhjhkjuhjk</p>
						</div>
						<div class="col-xs-6 text-center">
							<p class="center-block">
								<button type="button" class="btn btn-danger btn-lg">Demo</button>
							</p>
							<p class="btn bg-danger text-danger text-wrap">hjklhkljhjlkjkjklj</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>

	
<div class="modal fade" id="myModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Add Options to Workflow</h4>
			</div>
			<div class="modal-body">
				<div class="list-group">
				  <a href="javascript:void(0)" class="list-group-item list-group-item-action" onClick="addWidget('send_sms')">Send SMS</a>
				  <a href="#" class="list-group-item list-group-item-action list-group-item-primary">SMS Reply</a>
				  <a href="#" class="list-group-item list-group-item-action list-group-item-secondary">Send MMS</a>
				  <a href="#" class="list-group-item list-group-item-action list-group-item-success">Connect Call</a>
				  <a href="#" class="list-group-item list-group-item-action list-group-item-danger">Customer Book Appointment</a>
				  <a href="#" class="list-group-item list-group-item-action list-group-item-warning">Contact changed</a>
				  <a href="#" class="list-group-item list-group-item-action list-group-item-info">Oppertunity</a>
				  <a href="#" class="list-group-item list-group-item-action list-group-item-light">Oppertunity status changed</a>
				  <a href="#" class="list-group-item list-group-item-action list-group-item-dark">Pipeline stage changed</a>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>

	</div>
</div>
	
</html>
<script>
	function addWidget(type){
		
			var html = `<p class="center-block">
						<button type="button" class="btn btn-danger btn-lg">Demo</button>
					</p>
					<p class="btn center-block"><span class="glyphicon glyphicon-arrow-down"></span>
					</p>
					<p class="lead text-center bg-info btn text-info center-block" data-toggle="modal" data-target="#myModal">Select your desired action</p>`;
		$("#myModal").modal("hide");
		//$(".container").append(html);
	}
</script>