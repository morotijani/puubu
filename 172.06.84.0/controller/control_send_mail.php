<?php

	require_once("../../connection/conn.php");

if (isset($_POST['email_data'])) {

    foreach ($_POST['email_data'] as $row) {
        $to   = $row["email"];
        $from = 'info@puubu.namibra.io';
        $from_name = 'Puubu Group';
        $subject = 'Your password for Voting';
        $body = '
            <p>Hola, '.$row["email"].',</p>
            <p>Your voting password is this: <b>'.$row["password"].'</b></p>
            <br>
            <p>Visit this link to vote https://puubu.namibra.io</p>
            <p>Best Regards, Puubu Group.</p>
            <br>
            <small>Contact Mr. Mohammed Bilal the CSS EC on <a href="tel:+233549574584">+233549574584</a> incase of any challenges.
            
        ';
        
        try {
            send_email($to, $subject, $body);
            echo 'Message has been sent';
        } catch (Exception $e) {
           // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}

?>