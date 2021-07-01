<?php
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
	header("location: index.php");
	exit;
}
echo($_SESSION['server_root_path']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
	<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="../../assets/css/datatables.css">
	<link rel="stylesheet" href="../../assets/css/style.css">

	<!-- script -->
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script src="../../assets/js/country.js"></script>

</head>
<body>
	<!-- menu -->
	<div id="server_root_path" style="display: none;"><?= $_SESSION['server_root_path'] ?></div>
	<div class="nav-side-menu">
		<div class="brand"><a href=''>Dashboard</a></div>
		<i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
			<div class="menu-list">
				<ul id="menu-content" class="menu-content collapse out">
					<li class="mb-1">
					  <a id="dashboard" href="<?= $_SESSION['server_root_path'] . 'pages/dashboard.php'?>"><i class="fab fa-chrome sidebar-icon"></i> Dashboard</a>
					</li>
					
					<li data-toggle="collapse" data-target="#ajuda" class="collapsed mb-1">
						<a href="#">
							<i class="fa fa-life-ring sidebar-icon"></i> Items 
							<span class="arrow ml-auto"><i class="fa fa-angle-down"></i></span>
						</a>
					</li>
					<ul class="sub-menu collapse" id="ajuda">
						<li><a id="statistic" href="">Statistic</a></li>
						<li><a id="subpage2" href="subpage2.php">Item2</a></li>
					</ul>
				</ul>
		 </div>
	</div>
	<div class="main">
		<div style="padding: 20px;">
			<div class="" style="background-color: white;border-radius: 10px;padding:20px;box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;">
				<h2>User Statistic By Country</h2>
				<div id="countryChart"></div>
			</div>

			<div class="" style="background-color: white;border-radius: 10px;margin-top:30px;padding:20px;box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;">
				<h2>User Online Percent</h2>
				<div id="onlineChart"></div>
			</div>
		</div>
		
	</div>
	<!-- Styles -->
	<style>
		#countryChart {
			width: 100%;
			height: 500px;
		}
		#onlineChart {
			width: 100%;
			height: 500px;
		}
	</style>

	<!-- Resources -->
	<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
	<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
	<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>


	<script type="text/javascript">
		$(document).ready(function() {
			showCharts()
		})

		function showCharts() {
			var server_root_path = document.querySelector('#server_root_path').innerText
			$.ajax({
				type: "post",
				url: `${server_root_path}api/client_data.php`,
				success: function (res) {
					if(res.data.clients.length !== 0) {
						showCountryChart(res)
						showOnlineChart(res)
					}
				}
			})
		}

		function showCountryChart(res) {
			var clients_per_country = res.data.clients_per_country
			var array_var = []
			for (let index = 0; index < clients_per_country.length; index++) {
				array_var.push({
					country: getCountryName(clients_per_country[index].country),
					visits: Number(clients_per_country[index]['count(id)'])
				})
			}
			am4core.ready(() => {
				// Themes begin
				am4core.useTheme(am4themes_animated);
				// Themes end

				var chart = am4core.create("countryChart", am4charts.XYChart);
				chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

				// chart.data = [
				// 	{
				// 		country: "United States",
				// 		visits: 725
				// 	},
				// 	{
				// 		country: "United Kingdom",
				// 		visits: 625
				// 	},
				// ];

				chart.data = array_var

				var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
				categoryAxis.renderer.grid.template.location = 0;
				categoryAxis.dataFields.category = "country";
				categoryAxis.renderer.minGridDistance = 10;
				categoryAxis.fontSize = 15;
				categoryAxis.renderer.labels.template.dy = 10;



				var image = new am4core.Image();
				image.horizontalCenter = "middle";
				image.width = 30;
				image.height = 30;
				image.verticalCenter = "middle";
				image.adapter.add("href", (href, target)=>{
				let category = target.dataItem.category;
				if(category){
					return "https://www.amcharts.com/wp-content/uploads/flags/" + category.split(" ").join("-").toLowerCase() + ".svg";
				}
				return href;
				})
				categoryAxis.dataItems.template.bullet = image;



				var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
				valueAxis.min = 0;
				valueAxis.renderer.minGridDistance = 70;
				valueAxis.renderer.baseGrid.disabled = true;


				var series = chart.series.push(new am4charts.ColumnSeries());
				series.dataFields.categoryX = "country";
				series.dataFields.valueY = "visits";
				series.columns.template.tooltipText = "{valueY.value}";
				series.columns.template.tooltipY = 0;
				series.columns.template.strokeOpacity = 0;

				// as by default columns of the same series are of the same color, we add adapter which takes colors from chart.colors color set
				series.columns.template.adapter.add("fill", function(fill, target) {
					return chart.colors.getIndex(target.dataItem.index);
				});

				

			}); // end am4core.ready()
			
		}

		function showOnlineChart(res) {
			var current_timestamp = new Date(res.data.current_timestamp).getTime() - 10000
			var clients = res.data.clients
			var count_online_client = 0
			for (let index = 0; index < clients.length; index++) {
				if (new Date(clients[index].last_activity).getTime() > current_timestamp) {
					// online client
					count_online_client = count_online_client + 1
				}
			}
			
			am4core.ready(() => {

				// Themes begin
				am4core.useTheme(am4themes_animated);
				// Themes end

				// Create chart instance
				var chart = am4core.create("onlineChart", am4charts.PieChart);

				// Add data
				chart.data = [ 
					{
						"online_status": "Online",
						"users": count_online_client
					}, 
					{
						"online_status": "Offline",
						"users": clients.length - count_online_client
					}
				];

				// Set inner radius
				chart.innerRadius = am4core.percent(50);

				// Add and configure Series
				var pieSeries = chart.series.push(new am4charts.PieSeries());
				pieSeries.dataFields.value = "users";
				pieSeries.dataFields.category = "online_status";
				pieSeries.slices.template.stroke = am4core.color("#fff");
				pieSeries.slices.template.strokeWidth = 2;
				pieSeries.slices.template.strokeOpacity = 1;

				// This creates initial animation
				pieSeries.hiddenState.properties.opacity = 1;
				pieSeries.hiddenState.properties.endAngle = -90;
				pieSeries.hiddenState.properties.startAngle = -90;

				}); // end am4core.ready()
		}
	</script>
</body>
</html>