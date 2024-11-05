<?php
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$errors = [];
$name = $email = $subject = $message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['name'])) {
        $errors[] = "Name is required.";
    } else {
        $name = sanitizeInput($_POST['name']);
    }

    if (empty($_POST['email'])) {
        $errors[] = "Email is required.";
    } else {
        $email = sanitizeInput($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }
    }

    if (empty($_POST['subject'])) {
        $errors[] = "Subject is required.";
    } else {
        $subject = sanitizeInput($_POST['subject']);
    }

    if (empty($_POST['message'])) {
        $errors[] = "Message is required.";
    } else {
        $message = sanitizeInput($_POST['message']);
    }

    if (empty($errors)) {
        $mailheader = "From: $name <$email>\r\n";
        $mailheader .= "Reply-To: $email\r\n";
        $mailheader .= "MIME-Version: 1.0\r\n";
        $mailheader .= "Content-Type: text/html; charset=UTF-8\r\n";

        $recipient = "kavindathimash0@gmail.com";

        if (mail($recipient, $subject, nl2br($message), $mailheader)) {
            header('Location: thank_you.php');
            exit;
        } else {
            $errors[] = "Sorry, your message could not be sent. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Contact Us</h1>

        <?php if (!empty($errors)) : ?>
            <div class="errors">
                <ul>
                    <?php foreach ($errors as $error) : ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>

            <label for="subject">Subject:</label>
            <input type="text" id="subject" name="subject" value="<?php echo $subject; ?>" required>

            <label for="message">Message:</label>
            <textarea id="message" name="message" required><?php echo $message; ?></textarea>

            <button type="submit">Send Message</button>
        </form>
    </div>
</body>
</html>
