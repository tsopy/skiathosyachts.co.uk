<?php
header('Content-Type: text/html; charset=UTF-8');

$requestId = $_GET['id'] ?? '';

if (empty($requestId)) {
    die('Λείπει το ID του αιτήματος');
}

$pendingFile = 'pending_' . $requestId . '.json';

if (!file_exists($pendingFile)) {
    die('Το αίτημα δεν βρέθηκε ή έχει ήδη υποβληθεί');
}

$requestData = json_decode(file_get_contents($pendingFile), true);
$name = $requestData['name'];
$email = $requestData['email'];
$date = $requestData['date'];

$to = $email;
$subject = "❌ Η κράτησή σας στο THEMIS IV";
$message = "
<html>
<head><meta charset='UTF-8'><title>Κράτηση Απορρίφθηκε</title></head>
<body>
    <h2>Αγαπητέ/ή {$name},</h2>
    <p>Δυστυχώς, η κράτησή σας για την ημερομηνία <strong>{$date}</strong> δεν μπόρεσε να επιβεβαιωθεί.</p>
    <p>Παρακαλώ επικοινωνήστε μαζί μας για εναλλακτικές ημερομηνίες.</p>
    <br><p>Με εκτίμηση,<br><strong>THEMIS IV Yachting</strong></p>
    <p>📧 tsopy@otenet.gr | 📞 +30 6976292001</p>
</body>
</html>";
$headers = "MIME-Version: 1.0\r\nContent-type:text/html;charset=UTF-8\r\nFrom: THEMIS IV <bookings@skiathosyachts.co.uk>";
mail($to, $subject, $message, $headers);

unlink($pendingFile);
?>
<!DOCTYPE html>
<html lang="el">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Κράτηση Απορρίφθηκε</title>
<style>body{font-family:Arial;background:#0a1128;color:#fff;display:flex;justify-content:center;align-items:center;height:100vh;margin:0}.container{text-align:center;background:rgba(255,255,255,0.1);padding:40px;border-radius:10px;border:1px solid #d4af37}h1{color:#d4af37}.btn{display:inline-block;margin-top:20px;padding:10px 20px;background:#d4af37;color:#0a1128;text-decoration:none;border-radius:5px;font-weight:bold}</style>
</head>
<body>
<div class="container">
    <h1>❌ Η κράτηση απορρίφθηκε</h1>
    <p>Ο πελάτης ειδοποιήθηκε μέσω email.</p>
    <a href="https://skiathosyachts.co.uk/" class="btn">Επιστροφή</a>
</div>
</body>
</html>