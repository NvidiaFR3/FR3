<?php
$db = new mysqli('localhost', 'username', 'password', 'pterodactyl_panel');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$db->query("
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(50) NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    whatsapp VARCHAR(20) NOT NULL,
    package VARCHAR(50) NOT NULL,
    ram VARCHAR(20) NOT NULL,
    disk VARCHAR(20) NOT NULL,
    cpu VARCHAR(20) NOT NULL,
    price INT NOT NULL,
    status ENUM('pending', 'paid', 'failed', 'completed') DEFAULT 'pending',
    pterodactyl_user_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");
?>