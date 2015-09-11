<html>
	<head>
		<title>Chemical Inventory | Moritz Lab</title>
		
		<link rel="stylesheet" href="main.css">
		<script type="text/javascript" src="jquery.min.js"></script>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:700,400' rel='stylesheet' type='text/css'>
			
		<?php
			$tab = "	";
			$filename = "play.txt";
			$file = fopen($filename, 'r') or die("Unable to open inventory file. If this message appears after reloading, try locating '" . $filename . "'");
			
			if($_GET["add-name"] != "") {
				$file = fopen($filename, 'r+') or die("Unable to open inventory file! If this message appears after reloading, try locating '" . $filename . "'");
				
				file_put_contents($filename, "\n" . $_GET["add-name"] . $tab, FILE_APPEND);
				file_put_contents($filename, $_GET["add-location"] . $tab, FILE_APPEND);
				file_put_contents($filename, $_GET["add-size"] . $tab, FILE_APPEND);
				file_put_contents($filename, $_GET["add-notes"] . $tab, FILE_APPEND);
				file_put_contents($filename, $_GET["add-phy"] . $tab, FILE_APPEND);
				file_put_contents($filename, $_GET["add-type"] . $tab, FILE_APPEND);
				file_put_contents($filename, $_GET["add-cas"] . $tab, FILE_APPEND);
			}
			
			if($_GET["edit-name"] != "") {
				// read into array
				$arr = file($filename);
				// remove second line
				$search = $_GET["edit-name"];
				$line_number = false;
				while(list($key, $line) = each($arr) and !$line_number) {
				   $line_number = (strpos($line, $search) !== FALSE) ? $key + 1 : $line_number;
				}
				unset($arr[$line_number - 1]);
				// reindex array
				$arr = array_values($arr);
				// write back to file
				file_put_contents($filename,implode($arr));
			}
		?>
		<script type="text/javascript">
			$(document).ready(function() {
				<!--shitty solution-->
				if(window.location.href.toLowerCase().indexOf("add-name") > -1) {
					window.location.href = "http://db.systemsbiology.net/MoritzInventory/";
				}
				if(window.location.href.toLowerCase().indexOf("edit-name") > -1) {
					window.location.href = "http://db.systemsbiology.net/MoritzInventory/";
				}
				<!--row-selected-->
				$('tr:not(.table-head)').click(function() {
					$('tr').removeClass('row-selected');
					$(this).addClass('row-selected');
				});
				<!--search-->
				$('#search-name').keyup(function() {
					_this = this;
					$.each($('#table tbody').find('tr'), function() {
						console.log($(this).text());
						if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) == -1)
							$(this).hide();
						else {
							$(this).show();
						}
					});
				});
				<!--set tab for csv or tsv-->
				var tab = "	";
				<!--open or close window-->
				$('.fade').hide();
				$('.add-chemical').hide();
				$('.edit-chemical').hide();
				$('#search-add-edit').click(function() {
					$('.fade').fadeIn('fast');
					$('.add-chemical').fadeIn('fast');
				});
				function closeWindow() {
					$('.fade').fadeOut('fast');
					$('.add-chemical').fadeOut('fast');
					$('.add-chemical #error').css("display", "none");
					$('#add-name, #add-location, #add-notes, #add-phy, #add-type, #add-size, #add-cas').val('');
					$('.edit-chemical').fadeOut('fast');
					$('.edit-chemical #error').css("display", "none");
					$('#edit-name, #edit-location, #edit-notes, #edit-phy, #edit-type, #edit-size, #edit-cas').val('');
				};
				$('.fade').click(function() {
					closeWindow();
				});
				$(document).keyup(function(e) {
					if (e.keyCode == 27) {
						closeWindow();
						$('#search-name').val('');
						//this runs the search on the blank value
						_this = this;
						$.each($('#table tbody').find('tr'), function() {
							console.log($(this).text());
							if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
								$(this).hide();
							else
								$(this).show();
						});
					};
				});
				<!--switch between add and edit-->
				$('.add-chemical h3').click(function() {
					$('.add-chemical').fadeOut('fast');
					$('.edit-chemical #error').css("display", "none");
					$('.edit-chemical').fadeIn('fast');
				});
				$('.edit-chemical h3').click(function() {
					$('.edit-chemical').fadeOut('fast');
					$('.add-chemical #error').css("display", "none");
					$('.add-chemical').fadeIn('fast');
				});
				<!--effects of clicking submit-->
				$('#add-chemical-submit').click(function() {
					if($('#add-name').val() == "" || $('#add-location').val() == "") {
						$('.add-chemical #error').css("display", "block");
					}
					else {
						window.location.href = "http://db.systemsbiology.net/MoritzInventory/?add-name=" + $('#add-name').val() + "&add-location=" + $('#add-location').val() + "&add-notes=" + $('#add-notes').val() + "&add-phy=" + $('#add-phy').val() + "&add-type=" + $('#add-type').val() + "&add-size=" + $('#add-size').val() + "&add-cas=" + $('#add-cas').val();
						
						$('.troubleshooting').html("<strong>ADDED THE FOLLOWING: </strong>" + add_string);
						
						$('.fade').fadeOut('fast');
						$('.add-chemical').fadeOut('fast');
						$('.add-chemical #error').css("display", "none");
						$('#add-name, #add-location, #add-notes, #add-phy, #add-type, #add-size, #add-cas').val('');
					}
				});
				$('#edit-chemical-submit').click(function() {
					if($('#edit-name').val() == "") {
						$('.edit-chemical #error').css("display", "block");
					}
					else {
						var edit_string = $('#edit-name').val() + tab + $('#edit-location').val() + tab + $('#edit-size').val() + tab + $('#edit-notes').val() + tab + $('#edit-phy').val() + tab + $('#edit-type').val()+ tab + $('#edit-cas').val() + tab;
						
						$('.troubleshooting').html("<strong>EDITED THE FOLLOWING: </strong>" + edit_string);
						
						$('.fade').fadeOut('fast');
						$('.edit-chemical').fadeOut('fast');
						$('.edit-chemical #error').css("display", "none");
						$('#edit-name, #edit-location, #edit-notes, #edit-phy, #edit-type, #edit-size, #edit-cas').val('');
					}
				});
				$('#delete-chemical-submit').click(function() {
					//Code
					if($('#edit-name').val() == "") {
						$('.edit-chemical #error').css("display", "block");
					}
					else {
						window.location.href = "http://db.systemsbiology.net/MoritzInventory/?edit-name=" + $('#edit-name').val() + "&edit-location=" + $('#add-location').val() + "&add-notes=" + $('#add-notes').val() + "&add-phy=" + $('#add-phy').val() + "&add-type=" + $('#add-type').val() + "&add-size=" + $('#add-size').val() + "&add-cas=" + $('#add-cas').val();
						
						$('.troubleshooting').html("<strong>DELETED THE FOLLOWING: </strong>" + edit_string);
						
						$('.fade').fadeOut('fast');
						$('.edit-chemical').fadeOut('fast');
						$('.edit-chemical #error').css("display", "none");
						$('#edit-name, #edit-location, #edit-notes, #edit-phy, #edit-type, #edit-size, #edit-cas').val('');
					}
				});
				$('#edit-name').change(function () {
					$('.troubleshooting').html("<strong>SELECTED THE FOLLOWING: </strong>" + $('#edit-name').val());
					$('#edit-location').val('');
					$('#edit-notes').val('');
					$('#edit-phy').val('');
					$('#edit-type').val('');
					$('#edit-size').val('');
					$('#edit-cas').val('');
					//add code that fills in new values
				});
			});
		</script>
		
		<!--TAB LOGO-->
		<link rel="apple-touch-icon" sizes="57x57" href="icons/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="icons/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="icons/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="icons/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="icons/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="icons/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="icons/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="icons/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="icons/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="icons/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="icons/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="icons/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="icons/favicon-16x16.png">
		<link rel="manifest" href="icons/manifest.json">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="icons/ms-icon-144x144.png">
		<meta name="theme-color" content="#ffffff">
	</head>
	<body>
		<span class="troubleshooting" style="font-size: 20px"></span>
		
		<div class="tagline"></div>
		
		<div class="fade"></div>
		
		<div class="add-chemical-container">
			<div class="add-chemical">
				<form>
					<h3>Add Chemical<span style="color: #CCCCCC"> / Edit Chemical</span></h3>
					<input type="text" id="add-name" name="add-name" placeholder="Name">
					<input type="text" id="add-location" name="add-location" placeholder="Location">
					<input type="text" id="add-notes" name="add-notes" placeholder="Notes">
					<select id="add-phy" name="add-phy">
						<option value="" disabled selected>Physical State</option>
						<option value="S">Solid</option>
						<option value="L">Liquid</option>
					</select>
					<select type="text" id="add-type" name="add-type">
						<option value="" disabled selected>Bottle Type</option>
						<option value="P">Plastic</option>
						<option value="G">Glass</option>
					</select>
					<input type="text" id= "add-size" name="add-size" placeholder="Size">
					<input type="text" id= "add-cas" name="add-cas" placeholder="CAS">
					</br>
					
					<input type="button" id="add-chemical-submit" value="Add Chemical"></input>
					<p id="error">Please include at least a name and a location.</p>
				</form>
			</div>
			<div class="edit-chemical">
				<form>
					<h3>Edit Chemical<span style="color: #CCCCCC"> / Add Chemical</span></h3>
					<select id= "edit-name"  name="edit-name">
						<option value="" disabled selected>Name</option>
						<?php
							$file = fopen($filename, 'r') or die("Unable to open inventory file! If this message appears after reloading, try locating '" . $filename . "'");
							while (($line = fgets($file)) !== false) {
								$name = substr($line, 0, strpos($line, $tab));
								echo "<option value=\"" . $name . "\">" . $name . "</option>";
							}
						?>
					</select>
					<input type="text" id= "edit-location" name="edit-location" placeholder="Location">
					<input type="text" id= "edit-notes" name="edit-notes" placeholder="Notes">
					<select id="edit-phy" name="edit-phy">
						<option value="" disabled selected>Physical State</option>
						<option value="S">Solid</option>
						<option value="L">Liquid</option>
					</select>
					<select id="edit-type" name="edit-type">
						<option value="" disabled selected>Bottle Type</option>
						<option value="P">Plastic</option>
						<option value="G">Glass</option>
					</select>
					<input type="text" id= "edit-size" name="edit-size" placeholder="Size">
					<input type="text" id= "edit-cas" name="edit-cas" placeholder="CAS">
					</br>
					
					<input type="button" id="edit-chemical-submit" value="Edit Chemical"></input>
					<div class="delete-chemical-submit">
						<input type="button" id="delete-chemical-submit" value="Delete Chemical"></input>
					</div>
					<p id="error">Please select a chemical to edit or delete.</p>
				</form>
			</div>
		</div>
		
		<div class="container">
			<div class="header">
				<h1>Moritz Lab &ndash; Chemical Inventory</h1>
				<input type="text" data-table="order-table" id="search-name" placeholder="Type here to search">
				<!--<input type="text" data-table="order-table" id="search-cas" placeholder="CAS">
				<input type="text" data-table="order-table" id="search-location" placeholder="Location">-->
				<button type="button" id="search-add-edit">Add/Edit Chemical</button>
			</div>
			
			<div class="table">
				<table id="table">
					<thead>
						<tr class="table-head">
							<th>Name</th>
							<th>Location</th>
							<th>Size</th> 
							<th>Notes</th>
							<th>Physical</br>State</th>
							<th>Bottle</br>Type</th>
							<th>CAS</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$file = fopen($filename, 'r') or die("Unable to open inventory file! If this message appears after reloading, try locating '" . $filename . "'");
							while (($line = fgets($file)) !== false) {
								if($line != "\n") {
								echo "<tr>";
									echo "<td id=\"name\">" . substr($line, 0, strpos($line, $tab)) . "</td>";
									$line = substr($line, strpos($line, $tab)+1);
									echo "<td id=\"location\">" . substr($line, 0, strpos($line, $tab)) . "</td>";
									$line = substr($line, strpos($line, $tab)+1);
									echo "<td id=\"size\">" . substr($line, 0, strpos($line, $tab)) . "</td>";
									$line = substr($line, strpos($line, $tab)+1);
									echo "<td id=\"notes\">" . substr($line, 0, strpos($line, $tab)) . "</td>";
									$line = substr($line, strpos($line, $tab)+1);
									echo "<td id=\"phy\">" . substr($line, 0, strpos($line, $tab)) . "</td>";
									$line = substr($line, strpos($line, $tab)+1);
									echo "<td id=\"type\">" . substr($line, 0, strpos($line, $tab)) . "</td>";
									$line = substr($line, strpos($line, $tab)+1);
									echo "<td id=\"cas\">" . $line . "</td>";
								echo "</tr>";
								}
							//fclose($file);
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</body>
</html>
