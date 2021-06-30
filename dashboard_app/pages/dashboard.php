<?php
require "../module/dbconnection.php";
require "../module/fetch_client_data.php";
require "../module/fetch_user_data.php";

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
// echo($_SESSION["loggedin"]);
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
	header("location: ../index.php");
	exit;
}

$loggedin_user = fetch_user_data($db_connec, $_SESSION['id']);
$clients = $db_connec->getAllQuery('client_tb');
$clients_data = fetch_clients_data($db_connec);

$new_clients = fetch_new_clients($db_connec);

$clients_top_country = fetch_clients_top_country($db_connec);
$clients_top_country_perc = 0;
for ($index = 0; $index < count($clients_top_country); $index++) { 
	$clients_top_country_perc = $clients_top_country_perc + $clients_top_country[$index]['count'];
	if ($index > 2) break;
}
if (count($clients_data) != 0) {
	$clients_top_country_perc = round(($clients_top_country_perc / count($clients_data) * 100), 0);
}else {
	$clients_top_country_perc = 0;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
	<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="../assets/css/datatables.css">
	<link rel="stylesheet" href="../assets/css/style.css">

	<!-- script -->
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script> -->

	<!-- Amchart Resources -->
	<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
	<script src="https://cdn.amcharts.com/lib/4/maps.js"></script>
	<script src="https://cdn.amcharts.com/lib/4/geodata/worldLow.js"></script>
	<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

	<script src="../assets/js/country.js"></script>

	<style>
	#chartdiv {
		width: 100%;
		height: 500px;
		overflow: hidden;
	}
	</style>

</head>
<body>
	<!-- menu -->
	<div class="nav-side-menu">
		<div class="brand"><a href='' >Dashboard</a></div>
		<i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
			<div class="menu-list">
				<ul id="menu-content" class="menu-content collapse out">
					<li class="mb-1">
						<a id="dashboard" href="/pages/dashboard.php">
							<i class="fab fa-chrome sidebar-icon"></i> Dashboard
						</a>
					</li>
					
					<li data-toggle="collapse" data-target="#ajuda" class="collapsed mb-1">
						<a href="#">
							<i class="fa fa-life-ring sidebar-icon"></i> Items 
							<span class="arrow ml-auto"><i class="fa fa-angle-down"></i></span>
						</a>
					</li>
					<ul class="sub-menu collapse" id="ajuda">
						<li><a id="subpage1" href="/pages/item1/statistic.php">Statistic</a></li>
						<li><a id="subpage2" href="/pages/item1/subpage2.php">Item2</a></li>
					</ul>
				</ul>
		 </div>
	</div>
	<div class="main">
		<div class="header">
			<i class="fas fa-user-circle" style="font-size: 30px;color:white;margin-left:auto"></i>
			<a class="logout-btn" href="/api/auth/signout.php"><?=$loggedin_user['username']?></a>
		</div>
		<div class="page-body">
			<div class="state-overview">
				<div class="row">
					<div class="col-xl-3 col-md-3 col-lg-3 col-12">
						<div class="info-box bg-b-green">
							<span class="info-box-icon"><i class="fas fa-users"></i></span>
							<div class="info-box-content">
								<span class="info-box-text">Total Users</span>
								<span class="info-box-number"><?php echo(count($clients))?></span>
								<div class="progress">
									<div class="progress-bar" style="width: <?php if (count($clients) === 0) {
											echo("0%");
										}else {
											echo("100%");
										} ?>"></div>
								</div>
								<span class="progress-description">
									<?php
										if (count($clients) === 0) {
											echo("0%");
										}else {
											echo("100%");
										}
									?>
								</span>
							</div>
							<!-- /.info-box-content -->
						</div>
					</div>
					<!-- /.col -->
					<div class="col-xl-3 col-md-3 col-12">
						<div class="info-box bg-b-yellow">
							<span class="info-box-icon"><i class="fas fa-user-plus"></i></span>
							<div class="info-box-content">
								<span class="info-box-text">New Users (In 24h)</span>
								<span class="info-box-number"><?= count($new_clients)?></span>
								<div class="progress">
									<div class="progress-bar" style="width: <?=count($new_clients) / count($clients) * 100?>%"></div>
								</div>
								<span class="progress-description">
								<?php if(count($clients) !== 0) {
									echo(round(count($new_clients) / count($clients) * 100, 2));
									}else {
										echo(0);
									}
								?>%
								</span>
							</div>
							<!-- /.info-box-content -->
						</div>
						<!-- /.info-box -->
					</div>
					<!-- /.col -->
					<div class="col-xl-3 col-md-3 col-12">
						<div class="info-box bg-b-blue">
							<span class="info-box-icon"><i class="fas fa-globe"></i></span>
							<div class="info-box-content">
								<span class="info-box-text">Online Users</span>
								<span class="info-box-number"></span>
								<div class="progress">
									<div class="progress-bar"></div>
								</div>
								<span class="progress-description"></span>
							</div>
							<!-- /.info-box-content -->
						</div>
						<!-- /.info-box -->
					</div>
					<!-- /.col -->
					<div class="col-xl-3 col-md-3 col-12">
						<div class="info-box bg-b-pink">
							<span class="info-box-icon"><i class="fas fa-user-friends"></i></span>
							<div class="info-box-content">
								<span class="info-box-text">By Top 3 Countries</span>
								<?php 
									if (count($clients_top_country) === 0) {
										echo('<span class="info-box-number" style="margin-right: 10px;">' . 0 . '</span>');
									}else {
										for ($index = 0; $index < count($clients_top_country); $index++) {
											if($index > 2) break; 
											echo('<span class="info-box-number" style="margin-right: 10px;">' . $clients_top_country[$index]['country'] . ": " . $clients_top_country[$index]['count'] . '</span>');		
										}
									}
								?>
								<div class="progress">
								<div class="progress-bar" style="width: <?= $clients_top_country_perc ?>%"></div>
								</div>
								<span class="progress-description"><?= $clients_top_country_perc ?>%</span>
							</div>
							<!-- /.info-box-content -->
						</div>
						<!-- /.info-box -->
					</div>
					<!-- /.col -->
				</div>
			</div>
			<div id="wrap">
				<h2>User Status</h2>
				<div class="client_data_table"></div>
			</div>
			<div style="background: white;padding-inline: 30px;padding-block: 10px;border-radius: 10px;margin-top:30px;box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;">
				<h2>User Position</h2>
				<div id="chartdiv"></div>
			</div>

			<div id="img_modal"
				style="display:none; z-index: 50; position:fixed;top:0px;left:0px;width:100vw;height:100vh;background-color:black;justify-items:center;align-items:center"
				onclick="closeImgModal()">
				<img style="height: 80vh;margin-left:auto;margin-right:auto;">
			</div>
		</div>
	</div>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.9.4/jquery.dataTables.min.js"></script>
	<script src="../assets/js/datatables.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			showClientDataTable()
			showMap()
			updateOnlineStatus()
			setInterval(() => {
				updateOnlineStatus()
			}, 5000);
		})

		function showImgModal(ev) {
			console.log(ev.target.currentSrc)
			document.querySelector('#img_modal img').src = ev.target.currentSrc
			document.querySelector('#img_modal').style.display = "flex"
		}
		function closeImgModal() {
			document.querySelector('#img_modal').style.display = "none"
		}
		function showClientDataTable() {
			$.ajax({
				type: "post",
				url: "/api/client_data.php",
				success: function (response) {
					var current_timestamp = response.data.current_timestamp
					current_timestamp = new Date(current_timestamp).getTime() - 5000
					var table_data = response.data.client_data
					var clients = response.data.clients
					var table_html = `
						<table cellpadding="0" cellspacing="0" class="datatable table table-striped table-bordered">
							<thead>
								<tr>
									<th>Id</th>
									<th>Ip Address</th>
									<th>Country</th>
									<th>Last Connect</th>
									<th>Current Website</th>
									<th>Time Spending</th>
									<th>Screenshot</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>`
						for (let index = 0; index < table_data.length; index++) {
							table_html = table_html + `
							<tr class="gradeX">
								<td>${table_data[index].id}</td>
								<td>${table_data[index].ip_address}</td>
								<td>${getCountryName(table_data[index].country)}</td>
								<td>${table_data[index].last_connect}</td>
								<td>${table_data[index].current_website}</td>
								<td>${table_data[index].time_spending}</td>
								<td>
									<div style="display: flex;">
										<img src="${table_data[index].screenshot}" 
											alt="" 
											style="max-width: 100px;cursor:pointer;margin-left:auto;margin-right:auto;">
									</div>
								</td>
								<td>
							`
							
							var client = clients.filter(item => item.identity === table_data[index].user_identity)
							if (client.length !== 0) {
								if (new Date(client[0].last_activity).getTime() > current_timestamp) {
									table_html = table_html + `
											<div identity=${table_data[index].user_identity} style="padding: 5px 7px;background: #26A69A;border-radius: 20px;color: white;text-align: center;">Online</div>
									`
								}else {
									table_html = table_html + `
											<div identity=${table_data[index].user_identity} style="padding: 5px 7px;background: #D81B60;border-radius: 20px;color: white;text-align: center;">Offline</div>
									`
								}
								table_html = table_html + `</td></tr>`
							}
						}
					table_html = table_html + `</tbody></table>`
					table_html = $.parseHTML(table_html)
					$('.client_data_table').append(table_html);

					$('.datatable').dataTable({
						"sPaginationType": "bs_full"
					});
					// $('.datatable').dataTable({
					// 	// columnDefs: [
					// 	// 	{ orderable: false, targets: 0 }
					// 	// ]
					// 	"ordering": false
					// })	
					$('.datatable').each(function(){
						var datatable = $(this);
						// SEARCH - Add the placeholder for Search and Turn this into in-line form control
						var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
						search_input.attr('placeholder', 'Search');
						search_input.addClass('form-control input-sm');
						// LENGTH - Inline-Form control
						var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
						length_sel.addClass('form-control input-sm');
					});
					var screenshots = document.querySelectorAll('.datatable img')
					for (let index = 0; index < screenshots.length; index++) {
						screenshots[index].addEventListener('click', event => {
							showImgModal(event)
						})
					}
					
				}
			});
		}

		// Chart code
		function showMap() {
			am4core.ready(() => {

				// Themes begin
				am4core.useTheme(am4themes_animated);
				// Themes end

				// Create map instance
				var chart = am4core.create("chartdiv", am4maps.MapChart);

				// Set map definition
				chart.geodata = am4geodata_worldLow;

				// Set projection
				chart.projection = new am4maps.projections.Miller();

				// Create map polygon series
				var polygonSeries = chart.series.push(new am4maps.MapPolygonSeries());

				// Exclude Antartica
				polygonSeries.exclude = ["AQ"];

				// Make map load polygon (like country names) data from GeoJSON
				polygonSeries.useGeodata = true;

				// Configure series
				var polygonTemplate = polygonSeries.mapPolygons.template;
				polygonTemplate.tooltipText = "{name}";
				polygonTemplate.polygon.fillOpacity = 1;


				// Create hover state and set alternative fill color
				var hs = polygonTemplate.states.create("hover");
				hs.properties.fill = chart.colors.getIndex(0);

				// Add image series
				var imageSeries = chart.series.push(new am4maps.MapImageSeries());
				imageSeries.mapImages.template.propertyFields.longitude = "longitude";
				imageSeries.mapImages.template.propertyFields.latitude = "latitude";
				
				imageSeries.mapImages.template.tooltipText = "{title}";
				imageSeries.mapImages.template.propertyFields.url = "url";

				var circle = imageSeries.mapImages.template.createChild(am4core.Circle);
				circle.radius = 4;
				circle.propertyFields.fill = "color";
				circle.nonScaling = true;

				var circle2 = imageSeries.mapImages.template.createChild(am4core.Circle);
				circle2.radius = 5;
				circle2.propertyFields.fill = "color";


				circle2.events.on("inited", function(event){
					animateBullet(event.target);
				})


				function animateBullet(circle) {
					var animation = circle.animate([{ property: "scale", from: 1 / chart.zoomLevel, to: 5 / chart.zoomLevel }, { property: "opacity", from: 1, to: 0 }], 1000, am4core.ease.circleOut);
					animation.events.on("animationended", function(event){
						animateBullet(event.target.object);
					})
				}

				var colorSet = new am4core.ColorSet();
				$.ajax({
					type: "post",
					url: "/api/client_data.php",
					success: (response) => {
						var clients = response.data.clients
						var current_timestamp = new Date(response.data.current_timestamp).getTime() - 5000
						var array_var = []
						for (let index = 0; index < clients.length; index++) {
							var obj_var = {
								title: clients[index].id,
								latitude: Number(clients[index].latitude),
								longitude: Number(clients[index].longitude),
								// "color":colorSet.next()
							}
							if (new Date(clients[index].last_activity).getTime() > current_timestamp) {
								obj_var.color = colorSet.next()
							}
							array_var.push(obj_var)
						}
						imageSeries.data = array_var
					}
				});
				setInterval(() => {
					$.ajax({
						type: "post",
						url: "/api/client_data.php",
						success: (response) => {
							var clients = response.data.clients
							var current_timestamp = new Date(response.data.current_timestamp).getTime() - 5000
							var array_var = []
							for (let index = 0; index < clients.length; index++) {
								var obj_var = {
									title: clients[index].id,
									latitude: Number(clients[index].latitude),
									longitude: Number(clients[index].longitude),
									online_status: true
									// "color":colorSet.next()
								}
								if (new Date(clients[index].last_activity).getTime() > current_timestamp) {
									obj_var.color = colorSet.next()
									obj_var.online_status = false
								}
								array_var.push(obj_var)
							}
							imageSeries.data = array_var
						}
					});
				}, 5000);
			}); // end am4core.ready()
		}

		function updateOnlineStatus() {
			$.ajax({
				type: "post",
				url: "/api/client_data.php",
				success: function (res) {
					var current_timestamp = new Date(res.data.current_timestamp).getTime() - 5000
					var clients = res.data.clients
					var client_data = res.data.client_data
					var table_data = res.data.client_data
					var count_online_client = 0
					if (client_data.length !== 0) {
						for (let index = 0; index < clients.length; index++) {
							var status_ele = document.querySelector(`#DataTables_Table_0 > tbody > tr:nth-child(${index + 1}) > td:nth-child(8) > div`)
							
							if (new Date(clients[index].last_activity).getTime() > current_timestamp) {
								// online client
								status_ele.style.backgroundColor = '#26A69A'
								status_ele.innerText = 'online'
								count_online_client = count_online_client + 1
							}else {
								// offline client
								status_ele.style.backgroundColor = '#D81B60'
								status_ele.innerText = 'offline'
							}
						}
					}
					
					document.querySelector('body > div.main > div > div.state-overview > div > div:nth-child(3) > div > div > span.info-box-number').innerText = count_online_client
					document.querySelector('body > div.main > div > div.state-overview > div > div:nth-child(3) > div > div > div > div.progress-bar').style.width = String(count_online_client / clients.length * 100) + "%"
					document.querySelector('body > div.main > div > div.state-overview > div > div:nth-child(3) > div > div > span.progress-description').innerText = clients.length === 0 ? "0%" : String(count_online_client / clients.length * 100) + "%"
				}
			});
		}
	</script>
</body>
</html>