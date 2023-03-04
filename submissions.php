<!DOCTYPE html>
<html>
<head>
  <title>Search @ TTN</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="icon" href="./favicon.png">
  <style>
  body {
  display: flex;
  justify-content: center;
  align-items: flex-start;
  height: 100vh;
  margin: 0;
}

table {
  border: 1px solid #ccc;
  border-collapse: collapse;
  width: 80%;
  max-width: 800px;
  margin-top: 50px;
}

th, td {
  text-align: left;
  padding: 8px;
}

th {
  background-color: #f2f2f2;
  font-weight: bold;
}

button {
  background-color: #2fba4a;
  color: #fff;
  border: none;
  padding: 6px 12px;
  border-radius: 4px;
  cursor: pointer;
}
button.dec{
  background-color: #E32742;
  color: #fff;
  border: none;
  padding: 6px 12px;
  border-radius: 4px;
  cursor: pointer;
}
a:focus, a:hover {
  color: #E32742;
  text-decoration: underline;
}
a {
  color: #2FBA4A;
}
</style>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

  // Include the config.php file to connect to the database
  include_once 'config.php';

  // Connect to the database
  $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

  // Check the connection
  if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
  }

  // Fetch the records from the database where "email_validated" = 1 and "crawl" = 0
  $sql = "SELECT * FROM sites WHERE email_validated = 1 AND crawl = 0 AND isnull(declined);";
  $result = mysqli_query($conn, $sql);
  
  // Check if the query was successful
  if (mysqli_num_rows($result) > 0) {
      echo "<table>";
      echo "<tr><th>ID</th><th>URL</th><th>Email</th><th></th><th></th></tr>";
      // Loop through the results and display them in a table
      while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr><td>" . $row['id'] . "</td><td><a href='" . $row['url'] . "' target='_blank'>" . $row['url'] . "</a></td><td>" . $row['email'] . "</td><td><button onclick='updateCrawl(" . $row['id'] . ")'>Accept</button></td><td><button class='dec' onclick='declineRecord(" . $row['id'] . ")'>Decline</button></td></tr>";
      }
      echo "</table>";
  } else {
      echo "No records found.";
  }

  // Close the connection
  mysqli_close($conn);
?>

<script>
  function updateCrawl(id) {
    // Make an AJAX request to the update-crawl.php file
    $.ajax({
      url: 'update-crawl.php',
      type: 'post',
      data: { id: id },
      success: function(response) {
        // Update the table with the new data
        location.reload();
      }
    });
  }
  function declineRecord(id) {
  if(confirm("Are you sure you want to decline this submission?")) {
    $.ajax({
      url: 'decline_submission.php',
      type: 'POST',
      data: { id: id },
      success: function(response) {
        // Update the table with the new data
        location.reload();
      },
      error: function(jqXHR, textStatus, errorThrown) {
        // do something on error
        console.log(textStatus, errorThrown);
      }
    });
  }
}
</script>
</body>