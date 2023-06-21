<nav class="navbar navbar-dark navbar-theme-primary px-4 col-12 d-lg-none"> <a class="navbar-brand me-lg-5" href="./index.php"> <img class="navbar-brand-dark" src="./assets/img/brand/logo.jpg" alt="Volt logo" /> <img class="navbar-brand-light" src="./assets/img/brand/dark.svg" alt="Volt logo" /> </a>
	<div class="d-flex align-items-center">
		<button class="navbar-toggler d-lg-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
	</div>
</nav>
<nav id="sidebarMenu" class="sidebar d-lg-block bg-gray-800 text-white collapse" data-simplebar>
	<script src="./js/sidebar.js"></script>
	<div class="sidebar-inner px-4 pt-3">
		<div class="user-card d-flex d-md-none justify-content-between justify-content-md-center pb-4">
			<div class="d-flex align-items-center">
				<div class="avatar-lg me-4"> <img src="./assets/img/team/profile-picture-3.jpg" class="card-img-top rounded-circle border-white" alt="Jordan Javier"> </div>
				<div class="d-block">
					<h2 class="h5 mb-3">Hi, Jordan</h2>
					<a href="./pages/examples/sign-in.html" class="btn btn-secondary btn-sm d-inline-flex align-items-center">
					<svg class="icon icon-xxs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
					</svg>
					Sign Out </a>
					</div>
			</div>
			<div class="collapse-close d-md-none"> <a href="#sidebarMenu" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="true" aria-label="Toggle navigation">
				<svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
				</svg>
				</a>
			</div>
		</div>
		<ul class="nav flex-column pt-3 pt-md-0">
			<li class="nav-item" style="text-align: center"> 
				<a href="dashboard.php"> 
					<img src="./assets/img/logo.jpg" height="100" width="100" alt="Logo"> 
				</a> 
			</li>
			<li class="nav-item" style="text-align: center;margin-bottom: 10px;">
				<select name="compaines" id="selected_company" class="form-control" onChange="getCompanyName(this)">
					<?php
					
					    $user_id = $_SESSION['user_id'];
                    	$user_type = $_SESSION['user_type'];
                    	
                    	
                    	$user_qry = mysqli_query($link,"select role_id from users where id='$user_id'");
                    	$user_data = mysqli_fetch_assoc($user_qry);
                    	$role_id = $user_data['role_id'];
                    	
                    	
                    	$role_qry = mysqli_query($link,"select * from role_master where role_id='$role_id'");
                    	$role_data = mysqli_fetch_assoc($role_qry);
                    	
                    	
						$sqlCompany = "select id,business_name from users where business_name IS NOT NULL order by id asc";
						$resCompany = mysqli_query($link,$sqlCompany);
						if(mysqli_num_rows($resCompany)){
							while($rowCompany = mysqli_fetch_assoc($resCompany)){
							    if($role_data['role']=="Full Admin" || $user_type==1){
    								if($_SESSION['company_id'] == $rowCompany['id'])
    									$selCompany = 'selected="selected"';
    								else
    									$selCompany = '';
    								echo '<option '.$selCompany.' value="'.$rowCompany['id'].'">'.$rowCompany['business_name'].'</option>';
							    }
							    else{
							        $sql_Company = "select company_id from users where id='$user_id'";
						            $res_Company = mysqli_query($link,$sql_Company);
						            
						            $company_data = mysqli_fetch_assoc($res_Company);
					                $companyid = $company_data['company_id'];
					                $_SESSION['company_id'] = $companyid;
					                if($companyid == $rowCompany['id'])
					                    echo '<option selected value="'.$rowCompany['id'].'">'.$rowCompany['business_name'].'</option>';
					                else
					                    echo '<option disabled value="'.$rowCompany['id'].'">'.$rowCompany['business_name'].'</option>';
							    }
							}
						}
					?>
				</select>
			</li>
			
			<li class="nav-item <?php if($pageName=='about.php')echo 'active';?>"> 
				<a href="about.php" class="nav-link d-flex align-items-center justify-content-between"> 
					<span> 
						<span class="sidebar-icon">
							<svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
								<path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
								<path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
							</svg>
						</span> 
						<span class="sidebar-text">About</span> 
					</span>
				</a>
			</li>
			<li class="nav-item <?php if($pageName=='dashboard.php')echo 'active';?>"> 
				<a href="dashboard.php" class="nav-link d-flex align-items-center justify-content-between"> 
					<span> 
						<span class="sidebar-icon">
							<svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
								<path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
								<path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
							</svg>
						</span> 
						<span class="sidebar-text">Dashboard</span> 
					</span>
				</a>
			</li>
			<li class="nav-item <?php if($pageName=='conversations.php')echo 'active';?>"> 
				<a href="conversations.php" class="nav-link d-flex align-items-center justify-content-between"> 
					<span> 
						<span class="sidebar-icon">
							<i class="icon icon-xs me-2 fa fa fa-comments" style="margin-left: 2px"></i>
						</span> 
						<span class="sidebar-text">Inbox</span> 
					</span>
				</a>
			</li>
			<li class="nav-item <?php if($pageName=='pipelines.php')echo 'active';?>"> 
				<a href="pipelines.php" class="nav-link d-flex align-items-center justify-content-between"> 
					<span> 
						<span class="sidebar-icon">
							<i class="icon icon-xs me-2 fa fa fa-inbox" style="margin-left: 2px"></i>
						</span> 
						<span class="sidebar-text">Pipelines</span> 
					</span>
				</a>
			</li>
			<li class="nav-item <?php if($pageName=='workflows.php')echo 'active';?>"> 
				<a href="workflows.php" class="nav-link d-flex align-items-center justify-content-between"> 
					<span> 
						<span class="sidebar-icon">
							<i class="icon icon-xs me-2 fa fa fa-inbox" style="margin-left: 2px"></i>
						</span> 
						<span class="sidebar-text">Workflow builder</span> 
					</span>
				</a>
			</li>
			<li class="nav-item <?php if($pageName=='ivr.php')echo 'active';?>"> 
				<a href="ivr.php" class="nav-link d-flex align-items-center justify-content-between"> 
					<span> 
						<span class="sidebar-icon">
							<i class="icon icon-xs me-2 fa fa fa-inbox" style="margin-left: 2px"></i>
						</span> 
						<span class="sidebar-text">IVR</span> 
					</span>
				</a>
			</li>
			<li class="nav-item <?php if($pageName=='scheduler.php')echo 'active';?>"> 
				<a href="scheduler.php" class="nav-link d-flex align-items-center justify-content-between"> 
					<span> 
						<span class="sidebar-icon">
							<i class="icon icon-xs me-2 fa fa-calendar" style="margin-left: 2px"></i>
						</span> 
						<span class="sidebar-text">SMS Scheduler</span> 
					</span>
				</a>
			</li>
			<li class="nav-item <?php if($pageName=='calendar.php')echo 'active';?>"> 
				<a href="calendar.php" class="nav-link d-flex align-items-center justify-content-between"> 
					<span> 
						<span class="sidebar-icon">
							<i class="icon icon-xs me-2 fa fa-calendar" style="margin-left: 2px"></i>
						</span> 
						<span class="sidebar-text">Calendar</span> 
					</span>
				</a>
			</li>
			<li class="nav-item <?php if($pageName=='bulksms.php')echo 'active';?>"> 
				<a href="bulksms.php" class="nav-link d-flex align-items-center justify-content-between"> 
					<span> 
						<span class="sidebar-icon">
							<i class="icon icon-xs me-2 fa fa-comments" style="margin-left: 2px"></i>
						</span> 
						<span class="sidebar-text">Bulk SMS</span> 
					</span>
				</a>
			</li>
			<!--
			<li class="nav-item">
				<span class="nav-link d-flex justify-content-between align-items-center collapsed" data-bs-toggle="collapse" data-bs-target="#submenu-app" aria-expanded="false">
					<span>
						<span class="sidebar-icon">
						<svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm-1 9v-1h5v2H5a1 1 0 01-1-1zm7 1h4a1 1 0 001-1v-1h-5v2zm0-4h5V8h-5v2zM9 8H4v2h5V8z" clip-rule="evenodd"></path></svg>
						</span> 
						<span class="sidebar-text">Workflows</span>
					</span>
					<span class="link-arrow">
						<svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
					</span> 
				</span>
				<div class="multi-level collapse <?php if( ($pageName=='workflows.php') || ($pageName=='pipelines.php') )echo 'show';?>" role="list" id="submenu-app" aria-expanded="false" style="">
					<ul class="flex-column nav">
						<li class="nav-item <?php if($pageName=='pipelines.php')echo 'active';?>">
							<a class="nav-link" href="pipelines.php">
								<span class="sidebar-text-contracted">D</span>
								<span class="sidebar-text">Pipelines</span>
							</a>
						</li>
						<li class="nav-item <?php if($pageName=='workflows.php')echo 'active';?>">
							<a class="nav-link" href="workflows.php">
								<span class="sidebar-text-contracted">B</span>
								<span class="sidebar-text">Workflow builder</span>
							</a>
						</li>
					</ul>
				</div>
			</li>
			-->
			
			<li class="nav-item <?php if($pageName=='contacts.php')echo 'active';?>"> 
				<a href="contacts.php" class="nav-link d-flex align-items-center justify-content-between"> 
					<span> 
						<span class="sidebar-icon">
							<i class="icon icon-xs me-2 fa fa-users" style="margin-left: 2px"></i>
						</span> 
						<span class="sidebar-text">Contacts</span> 
					</span>
				</a>
			</li>
			
			<?php 
				if($_SESSION['user_id']=='1'){}
				else if($_SESSION['user_id']=='2'){}
				else if($_SESSION['user_id']=='3'){}
				else if($_SESSION['user_id']=='4'){}
				else if($_SESSION['user_id']=='5'){}
				else if($_SESSION['user_id']=='6'){}
				else if($_SESSION['user_id']=='12'){}
			?>
		</ul>
	</div>
</nav>