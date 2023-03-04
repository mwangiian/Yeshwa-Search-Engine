<?php
require 'config.php';
require 'vendor/autoload.php';
use \Mailjet\Resources;

// Connect to the database
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($db->connect_error) {
      $response = array("status" => "error");
}

// Prepare the data
$url = $db->real_escape_string($_POST["url"]);
$email = $db->real_escape_string($_POST["email"]);

// Generate a unique validation code
$validation_code = md5(uniqid(rand(), true));
  
// Insert the data into the database
$query = "INSERT INTO sites (url, email, validation_code) VALUES ('$url', '$email', '$validation_code')";
if ($db->query($query) === TRUE) {
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
                      'Email' => $email
                  ]
              ],
              'Subject' => "Email validation",
              'TextPart' => "Hi,\r\n\r\nA website has been submitted for review to be potentially added to the TTN Search Engine on https://search.ttn.place.\r\n\r\nIf this was you then please copy and paste the following link in your browser to validate your email address: http://search.ttn.place/validate.php?code=" . $validation_code . ".\r\n\r\nThanks,\r\n\r\nThe TTN Search Team",
              'HTMLPart' => "Hi,<p>A website has been submitted for review to be potentially added to <a href='https://search.ttn.place'>TTN Search Engine</a>.</p><p> If this was you then please click the following link to validate your email address: <a href='http://search.ttn.place/validate.php?code=" . $validation_code . "'>Validate email</a></p><p>Thanks,<br>The TTN Search Team</p>"
          ]
      ]
  ];
  $response = array("status" => "fail");
  $mresponse = $mj->post(Resources::$Email, ['body' => $body]);
  $mresponse->success() && $response = array("status" => "success");
}

// Close the database connection
$db->close();

// Return the response to the client
header("Content-Type: application/json");
echo json_encode($response);

?>