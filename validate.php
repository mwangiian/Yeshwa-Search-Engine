<html>
<header>
  <title>Search @ TTN</title>
</header>
<body>
<?php
// Include the config file
require 'config.php';
require 'vendor/autoload.php';
use \Mailjet\Resources;

// Connect to the database
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($db->connect_error) {
  echo "Connection failed: " . $db->connect_error;
}

// Prepare the data
$validation_code = $db->real_escape_string($_GET["code"]);

// Update the email validation status in the database
$query = "UPDATE sites SET email_validated=1 WHERE validation_code='$validation_code';";
if ($db->query($query) === TRUE) {
  $result = mysqli_query($db, "SELECT url from sites WHERE validation_code='$validation_code';");
  $row = mysqli_fetch_assoc($result);
  $url = $row['url'];

  echo "<h1>Your email has been successfully validated.</h1>";
  $mj = new \Mailjet\Client(MJ_APIKEY_PUBLIC, MJ_APIKEY_PRIVATE, true,['version' => 'v3.1']);
  $body = [
      'Messages' => [
          [
              'From' => [
                  'Email' => "support@thetorahnetwork.com",
                  'Name' => "Support @ TTN Search"
              ],
              'To' => [
                  [
                    'Email' => "support@thetorahnetwork.com",
                    'Name' => "Support @ TTN Search"
                  ]
              ],
              'Subject' => "TTN Search - Website submitted",
              'TextPart' => "Hi,\r\n\r\n" .$query. " has been submitted for review.",
              'HTMLPart' => "Hi,<p><a href='" .$url. "'>" .$url. "</a> has been <a href='https://search.ttn.place/admincp.php'>submitted for review</a>.</p>"
          ]
      ]
  ];
  $response = $mj->post(Resources::$Email, ['body' => $body]);
  $response->success();
} else {
  echo "<h1>There was a problem validating your email.</h1>";
}

// Close the database connection
$db->close();
echo "<p><b>You will be redirected back to the home page shortly...</b></p>";
header("Refresh:5; url=/");
?>
</body>