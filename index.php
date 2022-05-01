<!doctype html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script>
		document.getElementsByTagName("html")[0].className += " js";
	</script>
	<link rel="stylesheet" href="assets/css/style.css">
	<title>Front End Test</title>
	<style>
		* {
			padding: 0;
			margin: 0;
			cursor: default;
			color: #333;
			font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
		}

		.container {
			margin: 0 auto;
			padding: 0 0px;
			max-width: 900px;
			min-width: 300px;
		}

		.row {
			width: 100%;
			overflow: none;
		}

		.column {
			float: left;
			width: 50%;
		}

		.connected-sortable {
			margin: 0 auto;
			list-style: none;
			width: 90%;
		}

		li.draggable-item {
			width: inherit;
			padding: 15px 20px;
			background-color: #f5f5f5;
			-webkit-transition: transform .25s ease-in-out;
			-moz-transition: transform .25s ease-in-out;
			-o-transition: transform .25s ease-in-out;
			transition: transform .25s ease-in-out;

			-webkit-transition: box-shadow .25s ease-in-out;
			-moz-transition: box-shadow .25s ease-in-out;
			-o-transition: box-shadow .25s ease-in-out;
			transition: box-shadow .25s ease-in-out;

			&:hover {
				cursor: pointer;
				background-color: #eaeaea;
			}
		}

		/* styles during drag */
		li.draggable-item.ui-sortable-helper {
			background-color: #e5e5e5;
			-webkit-box-shadow: 0 0 8px rgba(53, 41, 41, .8);
			-moz-box-shadow: 0 0 8px rgba(53, 41, 41, .8);
			box-shadow: 0 0 8px rgba(53, 41, 41, .8);
			transform: scale(1.015);
			z-index: 100;
		}

		li.draggable-item.ui-sortable-placeholder {
			background-color: #ddd;
			-moz-box-shadow: inset 0 0 10px #000000;
			-webkit-box-shadow: inset 0 0 10px #000000;
			box-shadow: inset 0 0 10px #000000;
		}

		.divPromotions {
			width: 100%;
			height: auto;
			border: 1px solid black;
			margin: 10px 0 0 0;
		}

		.divCustomers {
			width: 100%;
			height: auto;
			margin: 10px 0 0 0;
			background: #f2f2f2;
		}
		.cd-tab-ul li a{
			min-width: 120px;
		}
	</style>
</head>

<body>

	<div class="cd-tabs cd-tabs--vertical container max-width-md margin-top-xl margin-bottom-lg js-cd-tabs">
		<nav class="cd-tabs__navigation">
			<ul class="cd-tabs__list cd-tab-ul">
				<li>
					<a href="#all-promotions" class="cd-tabs__item cd-tabs__item--selected">All Promotions</a>
				</li>

				<li>
					<a href="#new-customers" class="cd-tabs__item">New Customers</a>
				</li>
			</ul>
		</nav>

		<ul class="cd-tabs__panels">
			<li id="all-promotions" class="cd-tabs__panel cd-tabs__panel--selected text-component">
				<div id="divPromotions" class="promotionsList">

				</div>
			</li>

			<li id="new-customers" class="cd-tabs__panel text-component">
				<div id="divCustomers">

				</div>
			</li>
		</ul>
	</div>
	<script src="assets/js/util.js"></script>
	<script src="assets/js/main.js"></script>
	<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.5.0.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script src="assets/js/jquery-ui-touch-punch.js"></script>
	
	

	<script type="text/javascript">
		if (localStorage.getItem("allPromotions") === null) {
			axios.get("https://run.mocky.io/v3/484016a8-3cdb-44ad-97db-3e5d20d84298").then(response => {					
					var resData = response.data;
					localStorage.setItem("allPromotions", JSON.stringify(resData));
					SetPomotionData(resData);
				}).catch(error => console.error(error));
		}else{
			var allPromotions = JSON.parse(localStorage.getItem("allPromotions"));
			SetPomotionData(allPromotions);
		}		
		function SetPomotionData(allPromotions){
			allPromotions = sortByKey(allPromotions, 'sequence');
			allPromotions.map(function(e, i) {				
				var id = e.id;
				var html = '';
				var html = '<div class="divPromotions draggable-item" data-sequence="' + e.sequence + '" id="' + e.id + '"><ul style="list-style: none;"> <li>' + e.name + '</li><li>' + e.description + '</li></ul></div>';
				if(e.onlyNewCustomers){
					var customer = "";
					var customer = '<div class="divCustomers" id="' + id + '">' +
						'<ul style="list-style: none;">' +
						'<li>' + e.name + '</li>' +
						'<li><img src="' + e.heroImageUrl + '" height="300" width="300"></li>' +
						'<li>' + e.description + '</li>' +
						'<li><button type="button" style="margin-right:20px;">Terms & Condition</button><button type="button">Join Now</button></li>' +
						'</ul>' +
						'</div>';
					$('#divCustomers').append(customer);
				}
				$('#divPromotions').append(html);
			});
		}		

		(function() {
			$('.promotionsList').sortable({
				connectWith: '.sortable-list',
				update: function(event, ui) {
					var order = $(this).sortable('toArray');
					var allPromotions = JSON.parse(localStorage.getItem("allPromotions"));
					var updatedArr = [];
					$.each(order, function(i, ordernumber) {						
						let obj = allPromotions.filter(e=>e.id==ordernumber);						
						obj[0].sequence = i;						
						updatedArr.push(obj[0]);
					})
					localStorage.setItem("allPromotions", JSON.stringify(updatedArr));
				}
			});

		})();

		function sortByKey(array, key) {
			return array.sort((a, b) => {
				let x = a[key];
				let y = b[key];
				return ((x < y) ? -1 : ((x > y) ? 1 : 0));
			});
		}
	</script>
	
</body>

</html>