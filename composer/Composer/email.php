<?php
use PHPMailer\PHPMailer\PHPMailer;
require __DIR__ . '/vendor/autoload.php';
$subject = $name = $email = $message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $purifier = new HTMLPurifier();
    $message = $purifier->purify (filter_input(INPUT_POST,'message'));
    
    $subject = trim(filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING));
    $name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING));
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));


    if ($subject == "" || $name == "" || $email == "" || $message == "") {
        $error_message = "You must specify a value for name, email address and message.";
    }

    if (!isset($error_message)) {
        foreach( $_POST as $value ){
            if( stripos($value,'Content-Type:') !== FALSE ){
                $error_message = "There was a problem with the information you entered.";
            }
        }
    }

    if (!isset($error_message) && $_POST["address"] != "") {
        $error_message = "Your form submission has an error.";
    }

    $mail = new PHPMailer();

    if (!isset($error_message) && !$mail->ValidateAddress($email)) {
        $error_message = "You must specify a valid email address.";
    }


    if (!isset($error_message)) {
        $email_body = "";
        $email_body = $email_body . "Subject: " . $subject . "<br />\n";
        $email_body = $email_body . "Name: " . $name . "<br />\n";
        $email_body = $email_body . "Email: " . $email . "<br />\n";
        $email_body = $email_body . "Message: " . $message;

        //Set who the message is to be sent from
        $mail->setFrom($email, $name);
        //Set who the message is to be sent to
        $mail->addAddress('reyhan@gmail.com', 'John Doe');
        //Set the subject line
        $mail->Subject = $subject;
        $mail->msgHTML($email_body);
        //Replace the plain text body with one created manually
        $mail->AltBody = strip_tags($email_body);
        //send the message, check for errors
        if ($mail->send()) {
            file_put_contents('logs/' . time() . '.txt',$email_body);
            header("Location: index.php");
            exit;
        } else {
            $error_message = "Mailer Error: " . $mail->ErrorInfo;
        }
    }
}

include("inc/header.php"); ?>
 <script src="vendor/ckeditor/ckeditor/ckeditor.js"></script>
                <div class="new-entry">
                    <h2>HTML Email</h2>
                    <?php
                    if (isset($error_message)) {
                        echo '<p class="message">' . $error_message . '</p>';
                    }
                    ?>
                    <form action="email.php" method="post">
                        <label for="name">Name (required)</label>
                        <input type="text" name="name" id="name" value="<?php echo $name; ?>" />
                        <label for="email">Email (required)</label>
                        <input type="text" name="email" id="email" value="<?php echo $email; ?>" />
                        <label for="name">Subject (required)</label>
                        <input type="text" name="subject" id="subject" value="<?php echo $subject; ?>" />
                        <label for="message">Message (required)</label>
                        <textarea rows="5" name="message"><?php echo $message; ?></textarea>
                        <script>CKEDITOR.replace('message');</script>
                        <input type="submit" value="Send" class="button">
                        <div style="display:none">
                        <label for="address">Address</label>
                            <input type="text" id="address" name="address" />
                            <p>Please leave this field blank</p>
                        </div>
                    </form>
                </div>
<?php include("inc/footer.php"); ?>