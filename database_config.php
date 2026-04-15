<?php
// database_config.php
$db_host = 'localhost';
$db_user = 'themis_user';        // ΑΛΛΑΞΕ ΜΕ ΤΑ ΔΙΚΑ ΣΟΥ ΣΤΟΙΧΕΙΑ
$db_pass = 'Το_Συνθηματικό_σου';  // ΑΛΛΑΞΕ ΜΕ ΤΑ ΔΙΚΑ ΣΟΥ ΣΤΟΙΧΕΙΑ
$db_name = 'themis_db';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>