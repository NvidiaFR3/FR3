<?php
include 'database.php';

$order_id = $_POST['order_id'] ?? $_GET['order_id'] ?? null;

if (!$order_id) {
    die("Order ID required");
}

$stmt = $db->prepare("SELECT * FROM orders WHERE order_id = ?");
$stmt->bind_param("s", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) {
    die("Order not found");
}

if ($order['status'] === 'paid' && !$order['pterodactyl_user_id']) {
    $pterodactyl_api_key = "YOUR_PTERODACTYL_API_KEY";
    $pterodactyl_panel_url = "https://panel.anda.com";
    
    $username = strtolower(preg_replace('/[^a-z0-9]/i', '', $order['customer_name'])) . rand(100, 999);
    $password = bin2hex(random_bytes(8)); 
    
    $data = [
        "username" => $username,
        "email" => $order['email'],
        "first_name" => $order['customer_name'],
        "last_name" => "",
        "password" => $password,
        "root_admin" => false
    ];
    
    $ch = curl_init("$pterodactyl_panel_url/api/application/users");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $pterodactyl_api_key",
        "Content-Type: application/json",
        "Accept: Application/vnd.pterodactyl.v1+json"
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 201) {
        $responseData = json_decode($response, true);
        $pterodactyl_user_id = $responseData['attributes']['id'];
        
        $stmt = $db->prepare("UPDATE orders SET status = 'completed', pterodactyl_user_id = ? WHERE order_id = ?");
        $stmt->bind_param("is", $pterodactyl_user_id, $order_id);
        $stmt->execute();
        $stmt->close();
        
        // Send email to customer
        $to = $order['email'];
        $subject = "Akun Pterodactyl Anda Telah Aktif";
        $message = "
            <h2>Terima kasih telah memesan Panel Pterodactyl!</h2>
            <p>Detail akun Anda:</p>
            <ul>
                <li><strong>URL Panel:</strong> $pterodactyl_panel_url</li>
                <li><strong>Username:</strong> $username</li>
                <li><strong>Password:</strong> $password</li>
                <li><strong>Paket:</strong> {$order['package']}</li>
                <li><strong>RAM:</strong> {$order['ram']}GB</li>
                <li><strong>Storage:</strong> {$order['disk']}GB</li>
                <li><strong>CPU Priority:</strong> {$order['cpu']}%</li>
            </ul>
            <p>Harap ganti password setelah login pertama kali.</p>
        ";
        
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: Panel Pterodactyl <noreply@panelanda.com>" . "\r\n";
        
        mail($to, $subject, $message, $headers);
        
        echo "Account created successfully!";
    } else {
        // Log error
        error_log("Failed to create Pterodactyl account: $response");
        echo "Failed to create account. Please contact support.";
    }
} else {
    echo "Order already processed or payment not completed.";
}
?>