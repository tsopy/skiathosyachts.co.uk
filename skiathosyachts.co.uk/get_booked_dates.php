<?php
header('Content-Type: application/json');
$bookedFile = 'booked_dates.json';
if (file_exists($bookedFile)) {
    echo file_get_contents($bookedFile);
} else {
    echo '[]';
}
?>