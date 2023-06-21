<?php
	include_once("header.php");
?>
	<div class="py-3">
		<!--
		<div class="dropdown">
			<button class="btn btn-gray-800 d-inline-flex align-items-center me-2 dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
			</svg>
			New Task </button>
			<div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1"> <a class="dropdown-item d-flex align-items-center" href="#">
				<svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
					<path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
				</svg>
				Add User </a> <a class="dropdown-item d-flex align-items-center" href="#">
				<svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
					<path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path>
				</svg>
				Add Widget </a> <a class="dropdown-item d-flex align-items-center" href="#">
				<svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
					<path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"></path>
					<path d="M9 13h2v5a1 1 0 11-2 0v-5z"></path>
				</svg>
				Upload Files </a> <a class="dropdown-item d-flex align-items-center" href="#">
				<svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
				</svg>
				Preview Security </a>
				<div role="separator" class="dropdown-divider my-1"></div>
				<a class="dropdown-item d-flex align-items-center" href="#">
				<svg class="dropdown-icon text-danger me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path>
				</svg>
				Upgrade to Pro </a> </div>
		</div>
		-->
	</div>

	<div class="row justify-content-lg-center">
		<div class="col-12 mb-4">
			<div class="card border-0 bg-yellow-100 shadow">
				<div class="card-header d-sm-flex flex-row align-items-center border-yellow-200 flex-0">
					<div class="d-block mb-3 mb-sm-0">
						<div class="fs-5 fw-normal mb-2">Daily Analytics</div>
						<!-- <h2 class="fs-3 fw-extrabold">0</h2> -->
						<!-- <div class="small mt-2"> <span class="fw-normal me-2">Yesterday's Calls</span> <span class="fas fa-angle-up text-success"></span> <span class="text-success fw-bold">10</span> </div> -->
					</div>
					<div class="btn-group ms-auto" role="group" aria-label="Basic example">
						<!--
						<button type="button" class="btn btn-secondary active">Day</button>
						<button type="button" class="btn btn-secondary">Month</button>
						<button type="button" class="btn btn-secondary">Year</button>
						-->
					</div>
				</div>
				<div class="card-body p-2">
					<div id="chart_dash"></div>
				</div>
			</div>
		</div>
	</div>
<?php
	include_once("footer.php");
	$usrid = $_SESSION['company_id'];
	$sql = "SELECT date_list.created_date, COUNT( conversations.created_date) as count 
	FROM (
		SELECT DATE(NOW() - INTERVAL n DAY) as created_date
		FROM (
		SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL 
			SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7
		) m
	) date_list
	LEFT JOIN (
		SELECT DATE(created_date) AS created_date
		FROM conversations
		WHERE direction = 'in'
			AND user_id = $usrid
			AND created_date >= DATE(NOW()) - INTERVAL 7 DAY
	) conversations
	ON date_list.created_date = DATE(conversations.created_date)
	GROUP BY date_list.created_date
	ORDER BY date_list.created_date DESC";

	$res = mysqli_query($link,$sql);

	$incoming_sms_data = [];

	while($row = mysqli_fetch_assoc($res)){

		$incoming_sms_data[] = $row['count'];

	}
	$sql = "SELECT date_list.created_date, COUNT( conversations.created_date) as count 
	FROM (
		SELECT DATE(NOW() - INTERVAL n DAY) as created_date
		FROM (
		SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL 
			SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7
		) m
	) date_list
	LEFT JOIN (
		SELECT DATE(created_date) AS created_date
		FROM conversations
		WHERE direction = 'out'
			AND user_id = $usrid
			AND created_date >= DATE(NOW()) - INTERVAL 7 DAY
	) conversations
	ON date_list.created_date = DATE(conversations.created_date)
	GROUP BY date_list.created_date
	ORDER BY date_list.created_date DESC";

	$res = mysqli_query($link,$sql);

	$outcoming_sms_data = [];

	while($row = mysqli_fetch_assoc($res)){

		$outcoming_sms_data[] = $row['count'];

	}
	$sql = "SELECT date_list.created_at, COUNT( twillio_call_log.created_at) as count 
	FROM (
		SELECT DATE(NOW() - INTERVAL n DAY) as created_at
		FROM (
		SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL 
			SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7
		) m
	) date_list
	LEFT JOIN (
		SELECT DATE(created_at) AS created_at
		FROM twillio_call_log
		WHERE mode = 'in'
			AND user_id = $usrid
			AND created_at >= DATE(NOW()) - INTERVAL 7 DAY
	) twillio_call_log
	ON date_list.created_at = DATE(twillio_call_log.created_at)
	GROUP BY date_list.created_at
	ORDER BY date_list.created_at DESC";

	$res = mysqli_query($link,$sql);

	$incoming_calls_data = [];

	while($row = mysqli_fetch_assoc($res)){

		$incoming_calls_data[] = $row['count'];

	}
	$sql = "SELECT date_list.created_at, COUNT( bookings.created_at) as count 
	FROM (
		SELECT DATE(NOW() - INTERVAL n DAY) as created_at
		FROM (
		SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL 
			SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7
		) m
	) date_list
	LEFT JOIN (
		SELECT DATE(created_at) AS created_at
		FROM bookings
		WHERE user_id = $usrid
			AND created_at >= DATE(NOW()) - INTERVAL 7 DAY
	) bookings
	ON date_list.created_at = DATE(bookings.created_at)
	GROUP BY date_list.created_at
	ORDER BY date_list.created_at DESC";

	$res = mysqli_query($link,$sql);

	$booking_data = [];

	while($row = mysqli_fetch_assoc($res)){

		$booking_data[] = $row['count'];

	}
	// echo('<pre>');
	// print_r($incoming_calls_data);
	// die;
?>
<script>
	function addDays(dateObj, numDays) {
		const days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
		dateObj.setDate(dateObj.getDate() - numDays);
		return dateObj.toDateString();
		return days[dateObj.getDay()];
		return dateobj;
	}
	var options = {
            'series': [
				{
					'name': 'InComing Calls',
					'data': <?= json_encode($incoming_calls_data) ?>
				},
				{
					'name': 'InComing SMS',
					'data': <?= json_encode($incoming_sms_data) ?>
				},
				{
					'name': 'OutGoing SMS',
					'data': <?= json_encode($outcoming_sms_data) ?>
				},
				{
					'name': 'Booking',
					'data': <?= json_encode($booking_data) ?>
				}
			],
            'chart': {
                'type': "bar",
                'sparkline': {
                    'enabled': false
                },
				'toolbar': {
                        'show': true,
                        'tools': {
                            'download': false,
                            'selection': false,
                            'zoom': false,
                            'zoomin': true,
                            'zoomout': true,
                            'pan': false,
                            'reset': false,
                            'customIcons': []
                        },
                        'autoSelected': 'zoom'
                    }
            },
            'theme': {
                'monochrome': {
                    'enabled': false,
                    'color': "#247BA0"
                }
            },
            'xaxis': {
                'categories': [addDays(new Date(), 0),addDays(new Date(), 1),addDays(new Date(), 2),addDays(new Date(), 3),addDays(new Date(), 4),addDays(new Date(), 5),addDays(new Date(), 6)]
            },
            'tooltip': {
                'fillSeriesColor': true	,
                'onDatasetHover': {
                    'highlightDataSeries': true
                },
                'style': {
                    'fontSize': 12,
                    'fontFamily': 'Inter'
                }
            }
        }
	// {
	// 	chart: {
	// 		type: 'line'
	// 	},
	// 	series: [{
	// 		name: 'InComing Calls',
	// 		data: [30,40,35,50,49,60,70]
	// 	}],
	// 	xaxis: {
	// 		categories: [1991,1992,1993,1994,1995,1996,1997]
	// 	}
	// }

	var chart = new ApexCharts(document.querySelector("#chart_dash"), options);

	chart.render();
</script>