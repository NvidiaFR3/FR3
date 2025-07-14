<?php
include 'database.php';

$order_id = $_GET['order_id'] ?? '';

// Update order status to paid
if ($order_id) {
    $stmt = $db->prepare("UPDATE orders SET status = 'paid' WHERE order_id = ?");
    $stmt->bind_param("s", $order_id);
    $stmt->execute();
    $stmt->close();
    
    // Get order details for display
    $stmt = $db->prepare("SELECT * FROM orders WHERE order_id = ?");
    $stmt->bind_param("s", $order_id);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="success-container">
        <div class="success-icon">✓</div>
        <h1>Pembayaran Berhasil</h1>
        <p>Terima kasih telah membeli Panel Pterodactyl Premium.</p>
        
        <?php if($order_id): ?>
            <p>ID Pesanan Anda:</p>
            <div class="order-id"><?php echo htmlspecialchars($order_id); ?></div>
            
            <?php if($order): ?>
            <div class="order-details">
                <h3>Detail Pesanan:</h3>
                <ul>
                    <li><strong>Paket:</strong> <?= htmlspecialchars($order['package']) ?></li>
                    <li><strong>RAM:</strong> <?= htmlspecialchars($order['ram']) == 'unlimited' ? 'Unlimited' : htmlspecialchars($order['ram']) . 'GB' ?></li>
                    <li><strong>Storage:</strong> <?= htmlspecialchars($order['disk']) == 'unlimited' ? 'Unlimited' : htmlspecialchars($order['disk']) . 'GB' ?></li>
                    <li><strong>CPU Priority:</strong> <?= htmlspecialchars($order['cpu']) ?>%</li>
                </ul>
            </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <div class="instructions">
            <h3>Instruksi:</h3>
            <ol>
                <li>Anda akan menerima email dengan detail akun dalam beberapa menit.</li>
                <li>Jika email tidak diterima dalam 15 menit, cek folder spam atau hubungi support.</li>
                <li>Ganti password setelah login pertama kali untuk keamanan.</li>
            </ol>
        </div>
        
        <a href="https://wa.me/62882008771871" class="btn whatsapp-btn">
            Hubungi Support via WhatsApp
        </a>
        <a href="/" class="btn">Kembali ke Beranda</a>
    </div>
</body>
</html>