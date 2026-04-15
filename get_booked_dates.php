<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Mock data - replace with your database query
$booked_dates = ['2026-07-15', '2026-07-20', '2026-08-01'];

echo json_encode($booked_dates);
?>