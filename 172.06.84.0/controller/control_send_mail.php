<?php

	require_once("../../connection/conn.php");

if (isset($_POST['email_data'])) {

    foreach ($_POST['email_data'] as $row) {
        $to   = $row["email"];
        
        // Generate new password
        $string = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_';
        $generatedpassword = substr(str_shuffle($string), 0, 8);
        $hashed_password = password_hash($generatedpassword, PASSWORD_DEFAULT);
        
        // Update database
        $updateQuery = "UPDATE registrars SET std_password = ? WHERE std_email = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->execute([$hashed_password, $to]);

        $from = 'info@puubu.namibra.io';
        $from_name = 'Puubu Group';
        $subject = 'Your NEW password for Voting';
        $body = '
            <p>Hola, '.$row["email"].',</p>
            <p>Your voting password has been reset. Your new password is: <b>'.$generatedpassword.'</b></p>
            <br>
            <p>Visit this link to vote https://puubu.namibra.io</p>
            <p>Best Regards, Puubu Group.</p>
            <br>
            <small>Contact Mr. Mohammed Bilal the CSS EC on <a href="tel:+233549574584">+233549574584</a> incase of any challenges.
            
        ';
        
        try {
            send_email($to, $subject, $body);
            echo 'ok';
        } catch (Exception $e) {
           echo "Message could not be sent. Mailer Error";
        }
    }
}

?>