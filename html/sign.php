<?php

require '../vendor/autoload.php';
include("connection.php");
// Collect and sanitize inputs
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
$firstNamee = $_POST['firstName'];
$lastNamee = $_POST['lastName'];
$emaill = $_POST['email'];
$contactt = $_POST['contact'];
$passwordd = $_POST['password'];
$hashedPassword = password_hash($passwordd, PASSWORD_DEFAULT);
$conPassword = $_POST['confirmPassword'];
$dobb = $_POST['DOB'];
$countryy = "Nepal";
$userTypee = $_POST['userType'];
$genderr = $_POST['gender'];
$check_email = "SELECT * FROM register_tbl WHERE email = '$emaill' AND  detect ='1'";
$check_eemail = mysqli_query($connecr, $check_email);

function sendmail_verify($firstNamee, $emaill, $_vrerify, $otp_code)
{



    $mail = new PHPMailer(true);
    //  $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->isSMTP();

    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    //Enable SMTP authentication
    $mail->Username = 'saradcr7adhikari@gmail.com';
    $mail->Password = 'vtsl wtoo zusf ojbp';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('noreply@gmail.com', $firstNamee);
    $mail->addAddress($emaill);
    $mail->isHTML(true);
    $mail->Subject = 'Email Verification From PARABOOK SYSTEM';
    $email_body = "<h1>Hi $firstNamee,</h1> <h5> VERIFY YOUR EMAIL</h5><p>YOUR CODE IS: ></h5> <h1>$otp_code</h1>";
    $mail->Body = $email_body;
    $mail->send();
    echo "<script>alert('Verification email sent!');</script>";
    // echo"<script>window.location.href='verify.php';</script>";


}


if (mysqli_num_rows($check_eemail) > 0) {
    echo "<script>alert('Email already exists!');</script>";
    echo "<script>window.location.href='signup.php';</script>";
    //exit();
} else {

    $otp_code = mt_rand(12456, 99999);
    $_vrerify = md5(rand());
    $_data = ("INSERT INTO register_tbl (firstname, lastname,email,contact,password,d_o_b,country,status,Gender,verify_token,otp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)");
    $_data = $connecr->prepare("$_data");
    $_data->bind_param("sssssssssss", $firstNamee, $lastNamee, $emaill, $contactt, $hashedPassword, $dobb, $countryy, $userTypee, $genderr, $_vrerify, $otp_code);
    $_data->execute();

    if ($_data) {
        echo "<script>alert('Registration successful!');</script>";
        //  echo "<script>window.location.href='login.php';</script>";
        sendmail_verify($firstNamee, $emaill, $_vrerify, $otp_code);
        echo "sendmail_verify";
        echo "<script>window.location.href='verify.php?token=$_vrerify';</script>";
    } else {
        echo "<script>alert('Registration failed!');</script>";
        // echo "<script>window.location.href='sign.php?token=$_vrerify';</script>";
    }
    $connecr->close();
    $_data->close();
}

?>