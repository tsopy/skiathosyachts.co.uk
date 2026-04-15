<?php
require_once 'database_config.php';

// Απλό login protection - ΑΛΛΑΞΕ το username και password
$valid_username = 'admin';
$valid_password = 'Themis2026!';

if (!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] != $valid_username || $_SERVER['PHP_AUTH_PW'] != $valid_password) {
    header('WWW-Authenticate: Basic realm="THEMIS IV Admin"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Access Denied';
    exit;
}

// Handle approve/reject via GET
if (isset($_GET['approve'])) {
    $id = (int)$_GET['approve'];
    $stmt = $conn->prepare("UPDATE bookings SET status = 'approved' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    // Get user email
    $email_stmt = $conn->prepare("SELECT email, name FROM bookings WHERE id = ?");
    $email_stmt->bind_param("i", $id);
    $email_stmt->execute();
    $user = $email_stmt->get_result()->fetch_assoc();
    
    mail($user['email'], "THEMIS IV Booking Approved", 
         "Dear {$user['name']},\n\nYour exclusive charter has been APPROVED. Our concierge will contact you shortly.\n\n- THEMIS IV Team",
         "From: concierge@themisiv.com");
    
    header("Location: admin_dashboard.php");
    exit();
}

if (isset($_GET['reject'])) {
    $id = (int)$_GET['reject'];
    $stmt = $conn->prepare("UPDATE bookings SET status = 'rejected' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    $email_stmt = $conn->prepare("SELECT email, name FROM bookings WHERE id = ?");
    $email_stmt->bind_param("i", $id);
    $email_stmt->execute();
    $user = $email_stmt->get_result()->fetch_assoc();
    
    mail($user['email'], "THEMIS IV Booking Update", 
         "Dear {$user['name']},\n\nWe regret to inform you that your booking request could not be approved at this time.\n\nPlease contact us for alternative dates.\n\n- THEMIS IV Concierge",
         "From: concierge@themisiv.com");
    
    header("Location: admin_dashboard.php");
    exit();
}

$sql = "SELECT * FROM bookings ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>THEMIS IV - Admin Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Montserrat', sans-serif; background: #0a1128; color: white; padding: 2rem; }
        h1 { color: #d4af37; margin-bottom: 2rem; }
        table { width: 100%; border-collapse: collapse; background: rgba(255,255,255,0.05); }
        th, td { border: 1px solid #d4af37; padding: 12px; text-align: left; }
        th { background: #d4af37; color: #0a1128; font-weight: 700; }
        .approved { color: #00ff00; font-weight: bold; }
        .rejected { color: #ff4444; font-weight: bold; }
        .pending { color: #ffaa00; font-weight: bold; }
        .btn-approve { background: #00cc44; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; margin-right: 5px; display: inline-block; }
        .btn-reject { background: #cc0000; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; display: inline-block; }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; }
        .filters { margin-bottom: 1rem; }
        .stats { background: rgba(212, 175, 55, 0.2); padding: 1rem; border-radius: 8px; margin-bottom: 2rem; }
    </style>
</head>
<body>
    <h1>⚓ THEMIS IV - Executive Booking Dashboard</h1>
    
    <?php
    $total = $conn->query("SELECT COUNT(*) as count FROM bookings")->fetch_assoc()['count'];
    $pending = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE status='pending'")->fetch_assoc()['count'];
    $approved = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE status='approved'")->fetch_assoc()['count'];
    ?>
    
    <div class="stats">
        <strong>📊 Statistics:</strong> Total: <?= $total ?> | Pending: <?= $pending ?> | Approved: <?= $approved ?>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>ID</th><th>Name</th><th>Email</th><th>Income</th><th>Cruise Type</th><th>Date</th><th>Guests</th><th>Message</th><th>Status</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= $row['annual_income'] ?></td>
                <td><?= $row['cruise_type'] ?></td>
                <td><?= $row['preferred_date'] ?></td>
                <td><?= $row['guests'] ?></td>
                <td style="max-width: 200px; word-wrap: break-word;"><?= htmlspecialchars(substr($row['message'], 0, 50)) ?>...</td>
                <td class="<?= $row['status'] ?>"><?= strtoupper($row['status']) ?></td>
                <td>
                    <?php if($row['status'] == 'pending'): ?>
                        <a href="?approve=<?= $row['id'] ?>" class="btn-approve" onclick="return confirm('Approve this booking?')">✅ Approve</a>
                        <a href="?reject=<?= $row['id'] ?>" class="btn-reject" onclick="return confirm('Reject this booking?')">❌ Reject</a>
                    <?php else: ?>
                        <span style="color: #888;">—</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>