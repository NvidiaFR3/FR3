<?php
include 'database.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Pterodactyl Premium</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="Mid-client-4IQksDMEXLGXTab8"></script>
</head>
<body>
    <header>
        <h1>Panel Pterodactyl Premium</h1>
        <p>Harga Rp 1.000 per GB - Pakai sesuai kebutuhan Anda</p>
    </header>

    <main>
        <section class="pricing">
            <?php
            for($gb = 1; $gb <= 10; $gb++):
                $price = $gb * 1000;
                $popular = ($gb == 4 || $gb == 8) ? 'popular' : '';
            ?>
            <div class="package <?= $popular ?>">
                <?php if($popular): ?>
                <div class="popular-tag">POPULAR</div>
                <?php endif; ?>
                <h2>Paket <?= $gb ?>GB</h2>
                <div class="price">Rp <?= number_format($price, 0, ',', '.') ?><span>/bulan</span></div>
                <ul>
                    <li><?= $gb ?>GB RAM</li>
                    <li><?= $gb ?>GB Storage</li>
                    <li><?= min(30 + ($gb * 5), 100) ?>% CPU Priority</li>
                    <li>1 Core Dedicated</li>
                    <li>Full Root Access</li>
                </ul>
                <button class="buy-btn" 
                        data-price="<?= $price ?>" 
                        data-package="<?= $gb ?>GB"
                        data-ram="<?= $gb ?>"
                        data-disk="<?= $gb ?>"
                        data-cpu="<?= min(30 + ($gb * 5), 100) ?>">
                    Pesan Sekarang
                </button>
            </div>
            <?php endfor; ?>

            <div class="package unlimited">
                <div class="popular-tag">BEST VALUE</div>
                <h2>Paket Unlimited</h2>
                <div class="price">Rp 15.000<span>/bulan</span></div>
                <ul>
                    <li>RAM: Dynamic Scaling</li>
                    <li>Storage: Unlimited (SSD)</li>
                    <li>100% CPU Priority</li>
                    <li>4 Cores Dedicated</li>
                    <li>Full Root Access</li>
                    <li>Priority Support</li>
                </ul>
                <button class="buy-btn" 
                        data-price="15000" 
                        data-package="Unlimited"
                        data-ram="unlimited"
                        data-disk="unlimited"
                        data-cpu="unlimited">
                    Pesan Sekarang
                </button>
            </div>
        </section>

        <div class="checkout-modal" id="checkoutModal">
            <div class="modal-content">
                <span class="close-btn">&times;</span>
                <h2>Checkout</h2>
                <form id="checkoutForm">
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" id="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" required>
                    </div>
                    <div class="form-group">
                        <label for="whatsapp">Nomor WhatsApp</label>
                        <input type="tel" id="whatsapp" required>
                    </div>
                    <div class="form-group">
                        <label>Paket</label>
                        <input type="text" id="packageDisplay" readonly>
                        <input type="hidden" id="package" name="package">
                    </div>
                    <div class="form-group">
                        <label>Spesifikasi</label>
                        <div class="specs">
                            <div><span>RAM:</span> <span id="ramDisplay"></span></div>
                            <div><span>Storage:</span> <span id="diskDisplay"></span></div>
                            <div><span>CPU:</span> <span id="cpuDisplay"></span></div>
                        </div>
                        <input type="hidden" id="ram" name="ram">
                        <input type="hidden" id="disk" name="disk">
                        <input type="hidden" id="cpu" name="cpu">
                    </div>
                    <div class="form-group">
                        <label>Harga</label>
                        <input type="text" id="priceDisplay" readonly>
                        <input type="hidden" id="price" name="price">
                    </div>
                    <button type="submit" class="pay-btn">Bayar Sekarang</button>
                </form>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 FR3 DEV. All rights reserved.</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>