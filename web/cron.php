<?php
<<<<<<< HEAD
$clearTextPassword = rand(100,1000000);
$password = crypt($clearTextPassword, base64_encode($clearTextPassword));
$myfile = fopen(".htpasswd", "w") or die("Unable to open file!");
$txt = 'mariam:'.$password;
fwrite($myfile, $txt);
fclose($myfile);

$to = "mariam@gmail.com";
=======
// Password to be encrypted for a .htpasswd file
$clearTextPassword = rand(100,1000000);
//echo $clearTextPassword;
// Encrypt password
$password = crypt($clearTextPassword, base64_encode($clearTextPassword));

// Print encrypted password
//echo 'mariam:'.$password;
$myfile = fopen(".htpasswd", "w") or die("Unable to open file!");
$txt = 'mariam:'.$password;;
fwrite($myfile, $txt);
fclose($myfile);

$to = "hovsepyanh1994@gmail.com";
>>>>>>> b4ad8b2693c501ce4775c190e8d8ca4479f1afbc
$subject = "New password";
$message = $txt;
$headers = "From: sender@example.com\r\n" .
    "Reply-To: sender@example.com\r\n" .
<<<<<<< HEAD
    "X-Mailer: PHP/" .phpversion();
$mail_success = mail($to, $subject, $message, $headers);
if ($mail_success){
    echo "Email sent successfully.";
}
else{
    echo "Email sending failed.";
}
=======
    "X-Mailer: PHP/" . phpversion();

$mail_success = mail($to, $subject, $message, $headers);

if ($mail_success) {
    echo "Email sent successfully.";
} else {
    echo "Email sending failed.";
}

?>
>>>>>>> b4ad8b2693c501ce4775c190e8d8ca4479f1afbc
