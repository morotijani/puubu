<?php

if (isset($_POST['email_data'])) {
    require "../PHPMailer/PHPMailerAutoload.php";

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

        $mail = new PHPMailer();
        try {
            $mail->IsSMTP();
            $mail->SMTPAuth = true; 
                         
            $mail->SMTPSecure = 'ssl'; 
            $mail->Host = 'smtp.namibra.io';
            $mail->Port = 465;  
            $mail->Username = 'info@puubu.namibra.io';
            $mail->Password = 'Ys7eeeeb9'; 
                           
            $mail->IsHTML(true);
            $mail->WordWrap = 50;
            $mail->From = "info@puubu.namibra.io";
            $mail->FromName = $from_name;
            $mail->Sender = $from;
            $mail->AddReplyTo($from, $from_name);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AddAddress($to);
            $mail->Send();

            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}

?>