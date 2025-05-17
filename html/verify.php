<?php

include 'connection.php';

$countdown_seconds = 30;

//$_data = $_POST['otp'];
$tokeen = '';
if (isset($_GET['token'])) {
    $tokeen = $_GET['token'];
} elseif (isset($_POST['token'])) {
    $tokeen = $_POST['token'];
}
$timer = 1;
$otp_expiry_time = time() + 60 * $timer; // 1 minute
$otpcreatedtime = time();
$_otpcreatedtime = null;
if (!empty($tokeen)) {
    $Query = "SELECT * FROM register_tbl WHERE verify_token = '$tokeen' AND detect = '0'";
    $execu = mysqli_query($connecr, $Query);
    if (mysqli_num_rows($execu) > 0) {
        $row = mysqli_fetch_assoc($execu);
        $_otpcreatedtime = time();
        if (strtotime($_otpcreatedtime + $otp_expiry_time) > time()) {
            echo "<script>alert('OTP expired! Please request a new one.');</script>";
            echo "<script>window.location.href='signup.php';</script>";
        }
    }
}

if (isset($_POST['verify'])) {
    if (!empty($tokeen)) {
        // $tokeen = $_GET['token'];

        $_data = $_POST['otp'];
        $Query = "SELECT * FROM register_tbl WHERE verify_token = '$tokeen' AND detect = '0'";

        $execu = mysqli_query($connecr, $Query);

        if (mysqli_num_rows($execu) > 0) {
            $row = mysqli_fetch_assoc($execu);
            $email = $row['email'];
            $otpp = $row['otp'];
            $_data = $_POST['otp'];

            if ($_data == $otpp) {
                $update = "UPDATE register_tbl SET detect = '1' WHERE verify_token = '$tokeen'";
                $upd = mysqli_query($connecr, $update);
                if ($upd) {
                    echo "<script>alert('Email verified successfully!');</script>";
                    echo "<script>window.location.href='login.php';</script>";

                } else {
                    echo "<h1>Invalid Otp PLEASE REENTER IT </h1>";

                }
            } else {
                echo "<h1>Invalid OTP</h1>";
                echo "<script>alert('Invalid OTP!');</script>";
                echo "<script>window.location.href='verify.php?token=$tokeen';</script>";
            }
        } else {
            echo "<script>alert('Email Aready exixts');</script>";
            echo "<script>window.location.href='signup.php';</script>";
        }

    } else {
        echo "<script>alert('INVALID TOKEN!');</script>";
        echo "<script>window.location.href='signup.php';</script>";
    }
}

?>











<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body style="background-color: #f0f0f0; display: flex; justify-content: center; align-items: center; height: 100vh;">
    <form action="verify.php" method="post">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($tokeen); ?>">
        <div
            style="background-color: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
            <h2 style="text-align: center;">Verify Your Email</h2>
            <p style="text-align: center;">Please enter the OTP sent to your email.</p>


            <input type="Integer" name="otp" placeholder="Enter 5-DIGIT OTP" required>
            <input type="submit" name="verify" value="Verify"
                style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
            <h1>Countdown Timer</h1>
            <p id="timer"></p>
            <script>
                // Set the countdown time from PHP
                let timeLeft = <?php echo $countdown_seconds; ?>;

                // Function to display the countdown
                function updateTimer() {
                    const timerElement = document.getElementById("timer");

                    if (timeLeft > 0) {
                        timerElement.textContent = timeLeft + " seconds remaining";
                        timeLeft--;
                    } else {
                        timerElement.textContent = "Time's up!";
                        clearInterval(timerInterval);
                    }
                }

                // Start the countdown
                updateTimer(); // Call once immediately
                const timerInterval = setInterval(updateTimer, 1000); // Then every 1 second
            </script>

</body>

</html>

</html>