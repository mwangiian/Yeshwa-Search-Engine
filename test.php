<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require 'config.php';
/*
This call sends a message to one recipient.
*/
require 'vendor/autoload.php';
use \Mailjet\Resources;
$mj = new \Mailjet\Client(MJ_APIKEY_PUBLIC, MJ_APIKEY_PRIVATE, true,['version' => 'v3.1']);
$email = "ray_bez@hotmail.com";
$validation_code = "testvalcode";
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
$response = $mj->post(Resources::$Email, ['body' => $body]);
$response->success() && var_dump($response->getData());

?>