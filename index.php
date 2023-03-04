<!DOCTYPE html>
<html>
<head>
  <title>Search @ TTN</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="icon" href="./favicon.png">
</head>
<body>
  <div>
    <div class="logo">
      <a href="/"><img src="search_ttn_logo.png" alt="The Torah Network logo" style="height:100px;"/></a>
    </div>
    <div class="search-box">
		<script async src="https://cse.google.com/cse.js?cx=f249c174bd0164abe">
		</script>
		<div class="gcse-search"></div>
    </div>
  </div>
  <div id="popup-menu">
    <button id="menu-button"><img src="hamburger.png" alt="Menu"></button>
    <ul id="menu-list">
      <li>
		<a href="#" id="submit-link">Submit a website</a>
	  	<!-- Trigger the modal with a button -->
	  	<button type="button" class="btn btn-info btn-lg" style="display:none;" id="modal-button" data-toggle="modal" data-target="#myModal">Open Form</button>
  	  </li>
      <!-- other menu items can go here -->
    </ul>
  </div>


  <!-- Modal -->
  <div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
        <form id="form-submit" action="" method="post">
		  <div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h4 class="modal-title">Submit a website</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
				  <label for="url">URL:</label>
				  <input type="url" class="form-control" id="url" name="url" required placeholder="https://">
				</div>
				<div class="form-group">
				  <label for="email">Email:</label>
				  <input type="email" class="form-control" id="email" name="email" required>
				</div>
			</div>
			<div class="modal-footer">
			  <button type="submit" class="btn btn-primary mr-auto">Submit</button>
			  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		  </div>
        </form>
    </div>
  </div>

  <div class="footer">
	  <a target="_blank" href="https://ttn.place">Copyright 2023 - The Torah Network</a>
  </div>

  <script>
    $("#submit-link").click(function() {
      $("#modal-button").click();
    });
	$(document).ready(function() {
		$("#menu-button").click(function() {
			$("#menu-list").toggle();
		});
		
		$("#menu-submit-link").click(function(e) {
			e.preventDefault();
			$("#menu-list").hide();
			$("#modal-form").show();
		});
	});
	$("#form-submit").submit(function(e) {
	  e.preventDefault();

	  // Validate URL
	  var url = $("#url").val();
	  var urlPattern = /(?:(?:https?|http):\/\/|www\.)(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[-A-Z0-9+&@#\/%=~_|$?!:,.])*(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[A-Z0-9+&@#\/%=~_|$])/img;
	  if (!urlPattern.test(url)) {
		alert("Invalid URL");
		return;
	  }

	  // Validate email
	  var email = $("#email").val();
	  var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
	  if (!emailPattern.test(email)) {
		alert("Invalid email address");
		return;
	  }

	  $.ajax({
		type: "POST",
		url: "xhr.php",
		data: $("#form-submit").serialize(),
		dataType: "json",
		success: function(response) {
		  if (response.status == "success") {
			alert("Form submitted successfully!");
			$("#url").val("");
			$("#email").val("");
			return true;
		  } else if (response.status == "error") {
			alert("Form submission failed. DB connection failed.");
			return;			  
		  } else {
			alert("Form submission failed. Please try again later. 1");
			return;
		  }
		},
		error: function(response) {
		  alert("Form submission failed. Please try again later. 2");
			return;
		}
	  });
	});
  </script>
</body>
</html>