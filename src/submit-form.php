<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

// Initialize response array
$response = ['errors' => [], 'success' => false];

// Validate inputs
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$contact_method = isset($_POST['contact-method']) ? trim($_POST['contact-method']) : '';
$date = isset($_POST['date']) ? trim($_POST['date']) : '';
$budget = isset($_POST['budget']) ? trim($_POST['budget']) : '';
$occupants = isset($_POST['occupants']) ? trim($_POST['occupants']) : '';
$current = isset($_POST['current']) ? trim($_POST['current']) : '';
$children = isset($_POST['children']) ? trim($_POST['children']) : '';
$city = isset($_POST['city']) ? trim($_POST['city']) : '';
$pets = isset($_POST['pets']) ? trim($_POST['pets']) : '';
$special = isset($_POST['special']) ? trim($_POST['special']) : '';
$referral = isset($_POST['referral']) ? trim($_POST['referral']) : '';
$comments = isset($_POST['comments']) ? trim($_POST['comments']) : '';

if (empty($name)) {
    $response['errors'][] = 'Name is required.';
}
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response['errors'][] = 'A valid email is required.';
}
if (empty($phone)) {
    $response['errors'][] = 'Phone is required.';
}

// if (empty($message)) {
//     $response['errors'][] = 'Message is required.';
// }

if (empty($contact_method)) {
    $response['errors'][] = 'Contact method is required.';
}

if (empty($date)) {
    $response['errors'][] = 'Move in date is required.';
}

if (empty($current)) {
    $response['errors'][] = 'Current situation is required.';
}

if ($children === '') {
    $response['errors'][] = 'Number of children is required.';
} else {
    // Process the number of children
    $children = (int) $children;
}

if (empty($date)) {
    $response['errors'][] = 'Move in date is required.';
}

if (empty($contact_method)) {
    $response['errors'][] = 'Contact Method is required.';
}

//unit size

if (empty($budget)) {
    $response['errors'][] = 'Budget is required.';
}


if (empty($occupants)) {
    $response['errors'][] = 'Number of occupants is required.';
}

if (!isset($_POST['unitSize']) || empty($_POST['unitSize'])) {
    $response['errors'][] = 'At least one Unit Size Preference is required.';
} else {
    // If it's set and not empty, process it
    $unitSizes = $_POST['unitSize'];
    $unitSizesString = implode(", ", $unitSizes); // Convert array to string for the email
}

// Check if there are any errors
if (!empty($response['errors'])) {
    echo json_encode($response);
    exit;
}

// Send email
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
    $mail->SMTPAuth = true;
    $mail->Username = 'sunningdaledevelopments@gmail.com'; // SMTP username
    $mail->Password = 'wjyc jqjv crhl gqod'; // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;
    $mail->Debug = 2;

    //Recipients
    $mail->setFrom('sunningdaledevelopments@gmail.com', 'New Contact');
    $mail->addADdress('info@auragoc.com');
    $mail->addADdress('rentals@sunningdaleheights.com');
    $mail->addReplyTo($email, $name);
    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Contact Form Submission';
    $mail->Body = <<<EOT
    <!DOCTYPE html>
    <html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                background-color: #f4f4f4;
            }
            .container {
                background-color: #ffffff;
                margin: 0 auto;
                padding: 20px;
                max-width: 600px;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            }
            .header {
                text-align: center;
                padding-bottom: 20px;
            }
            .header img {
                max-width: 100px;
            }
            .content {
                line-height: 1.6;
            }
            .footer {
                margin-top: 20px;
                text-align: center;
                font-size: 0.9em;
                color: #888;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <img src="https://mbtechs.ca/sunningdale/public/assets/images/icon.png" alt="Logo">
                <h1>Contact Form Submission</h1>
            </div>
            <div class="content">
                <p><strong>Name:</strong> {$name}</p>
                <p><strong>Email:</strong> <a href="mailto:{$email}" target="_blank">{$email}</a></p>
                <p><strong>Phone:</strong> {$phone}</p>
                <p><strong>Contact Method:</strong> {$contact_method}</p>
                <p><strong>Move-in Date:</strong> {$date}</p>
                <p><strong>Unit Size Preference:</strong> {$unitSizesString}</p>
                <p><strong>Budget:</strong> {$budget}</p>
                <p><strong>Number of Occupants:</strong> {$occupants}</p>
                <p><strong>Number of Children:</strong> {$children}</p>
                <p><strong>Are you brining any pets:</strong> {$pets}</p>
                <p><strong>Where do you currently live?:</strong> {$current}</p>
                <p><strong>What city do you currently live in?:</strong> {$city}</p>
                <p><strong>Special Requirements or Preference:</strong> {$special}</p>
                <p><strong>How did you hear about us?:</strong> {$referral}</p>
                <p><strong>Questions/Comments:</strong><br>{$message}</p>
            </div>
            <div class="footer">
                <p>Thank you for contacting us!</p>
            </div>
        </div>
    </body>
    </html>
    EOT;
    

    $mail->send();
    $response['success'] = true;
} catch (Exception $e) {
    $response['errors'][] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

header('Content-Type: application/json');

echo json_encode($response);