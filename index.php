<!-- THIS IS USING GOOGLES reCaptcha V2 -->

<?php 
    if(isset($_POST['ContactButton'])) {
        $url = "https://www.google.com/recaptcha/api/siteverify";
        $privateKey = "PUT YOUR PRIVATE KEY HERE";
        $response = file_get_contents($url."?secret=".$privateKey."&response=".$_POST['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']);
        $data = json_decode($response);
        if (isset($data->success) AND $data->success==true) {
            $error = "";
            $successMsg = "";
            if ($_POST) {
                if ($_POST['email'] && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
                    $error .= "The email is invalid!<br>";
                }
                if (!$_POST['email']) {
                    $error .= "An email address is required!<br>";
                }
                if (!$_POST['subject']) {
                    $error .= "A subject is required!<br>";
                }
                if (!$_POST['body']) {
                    $error .= "Content in the body is required!<br>";
                }
                if ($error != "") {
                    $error = '<div class="alert alert-danger" role="alert"><strong>There is an error with your form!</strong><br>' . $error . '</div>';
                } else {
                    $emailTo = 'CONTACT FORM DELIVERY EMAIL';
                    $subject = $_POST['subject'];
                    $body = $_POST['body'];
                    $headers = "From: ".$_POST['email'];
                    if (mail($emailTo, $subject, $body, $headers)) {
                        $successMsg = '<div class="alert alert-success" role="alert">The message has successfully been sent. We will contact you ASAP!</div>';
                    } else {
                        $error = '<div class="alert alert-danger" role="alert">There was a problem sending your message, please try again later!</div>';
                    }
                }
            }
        } else {
            $captchaFail = '<div class="alert alert-danger" role="alert"><strong>There is an error with your form!</strong><br>reCaptcha Verification Failed, Please Try Again.</div>';
        }
    }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="UTF-8">
        <title>Contact Form</title>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.reboot.css">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.grid.css">
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    </head>
    <body>
        <br><br>
        <form method="POST" class="container">
            <h2 style="text-align:center;">Get in Contact with Me</h2>
            <br>
            <div id="error"><?php echo $successMsg ?><?php echo $error ?><?php echo $captchaFail ?></div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="inputEmail">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                </div>
            </div>
            <div class="form-group">
                <label for="inputSubject">Subject</label>
                <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject">
            </div>
            <label for="inputBody">Body</label>
            <div class="form-group input-group">
                <textarea class="form-control" aria-label="With textarea" placeholder="Body" name="body" id="body"></textarea>
            </div>
            <div class="g-recaptcha" data-sitekey="PUT YOUR SITEKEY HERE"></div>
            <br>
            <button type="submit" class="btn btn-primary" name="ContactButton" id="ContactButton">Submit</button>
        </form>
    </body>
</html>