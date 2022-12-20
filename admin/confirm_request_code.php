<?php

use PHPMailer\PHPMailer\PHPMailer;

include 'includes/session.php';

require '../vendor/autoload.php';

if (isset($_POST['print'])) {

    //update requests
    $conn = $pdo->open();
    $stmt = $conn->prepare("UPDATE requests SET status=1 WHERE id=:id");
    $stmt->execute(['id' => $_POST['request_id']]);


    $message = '<p>Hello '.$_POST['name'] .', Your request <b>#' . $_POST['request_id'] . ' is appected by admin. Here some offer: </p>';
    $message .= $_POST['message'];

//    var_dump($_POST);
//    die();

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
        $mail->addAddress($_POST['email']);
        $mail->addReplyTo('test.sifat.mail@gmail.com');
        //Content
        $mail->isHTML(true);
        $mail->Subject = 'ECommerce Site Request Declined Mail';
        $mail->Body = $message;
        $mail->send();


        $_SESSION['success'] = 'Request Accepted.';
        header('location: requests.php');

    } catch (Exception $e) {
        $_SESSION['error'] = 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
        header('location: confirm_request.php?id=' . $_POST['request_id'] . '&user_id=' . $_POST['user_id']);
    }
} else {
    $_SESSION['error'] = 'Something went wrong';
    header('location: requests.php');
}
