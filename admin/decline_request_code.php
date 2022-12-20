<?php

use PHPMailer\PHPMailer\PHPMailer;

include 'includes/session.php';

require '../vendor/autoload.php';

$user = [];
$request = null;

$user_id = $_GET['user_id'];
$request_id = $_GET['id'];

$conn = $pdo->open();
$stmt = $conn->prepare("SELECT * FROM users WHERE id=:id");
$stmt->execute(['id' => $user_id]);

foreach ($stmt as $row) {
    $user = $row;
}

$conn = $pdo->open();
$stmt = $conn->prepare("SELECT * FROM requests WHERE id=:id");
$stmt->execute(['id' => $request_id]);

foreach ($stmt as $row) {
    $request = $row;
}

//update requests
$conn = $pdo->open();
$stmt = $conn->prepare("UPDATE requests SET status=2 WHERE id=:id");
$stmt->execute(['id' => $request_id]);


$message = '<p>Hello '.$user['firstname'] .' '. $user['lastname'].', Your request <b>#' . $request['id'] . ' is declined by admin</p>';

$mail = new PHPMailer(true);
try {
    //Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'test.sifat.mail@gmail.com';
    $mail->Password = 'oudejbircllwwtgx';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->setFrom('test.sifat.mail@gmail.com');
    //Recipients
    $mail->addAddress($user['email']);
    $mail->addReplyTo('test.sifat.mail@gmail.com');
    //Content
    $mail->isHTML(true);
    $mail->Subject = 'ECommerce Site Request Declined Mail';
    $mail->Body = $message;
    $mail->send();


    $_SESSION['success'] = 'Request Declined.';
    header('location: requests.php');

} catch (Exception $e) {
    $_SESSION['error'] = 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    header('location: requests.php');
}