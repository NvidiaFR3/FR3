document.addEventListener('DOMContentLoaded', function() {
    const buyButtons = document.querySelectorAll('.buy-btn');
    const modal = document.getElementById('checkoutModal');
    const closeBtn = document.querySelector('.close-btn');
    const checkoutForm = document.getElementById('checkoutForm');
    const packageDisplay = document.getElementById('packageDisplay');
    const priceDisplay = document.getElementById('priceDisplay');
    const ramDisplay = document.getElementById('ramDisplay');
    const diskDisplay = document.getElementById('diskDisplay');
    const cpuDisplay = document.getElementById('cpuDisplay');
    
    const packageInput = document.getElementById('package');
    const priceInput = document.getElementById('price');
    const ramInput = document.getElementById('ram');
    const diskInput = document.getElementById('disk');
    const cpuInput = document.getElementById('cpu');
    
    // Open modal when buy button is clicked
    buyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const package = this.getAttribute('data-package');
            const price = this.getAttribute('data-price');
            const ram = this.getAttribute('data-ram');
            const disk = this.getAttribute('data-disk');
            const cpu = this.getAttribute('data-cpu');
            
            packageDisplay.value = `Paket ${package}`;
            priceDisplay.value = `Rp ${parseInt(price).toLocaleString('id-ID')}`;
            
            // Display specifications
            ramDisplay.textContent = ram === 'unlimited' ? 'Unlimited (Scaling)' : `${ram}GB`;
            diskDisplay.textContent = disk === 'unlimited' ? 'Unlimited SSD' : `${disk}GB`;
            cpuDisplay.textContent = `${cpu}% Priority`;
            
            // Set hidden values
            packageInput.value = package;
            priceInput.value = price;
            ramInput.value = ram;
            diskInput.value = disk;
            cpuInput.value = cpu;
            
            modal.style.display = 'flex';
        });
    });
    
    // Close modal
    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
    
    // Handle form submission
    checkoutForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const whatsapp = document.getElementById('whatsapp').value;
        const package = packageInput.value;
        const price = priceInput.value;
        const ram = ramInput.value;
        const disk = diskInput.value;
        const cpu = cpuInput.value;
        
        // Process payment
        processPayment(name, email, whatsapp, package, price, ram, disk, cpu);
    });
});

function processPayment(name, email, whatsapp, package, price, ram, disk, cpu) {
    // Show loading state
    const payBtn = document.querySelector('.pay-btn');
    payBtn.disabled = true;
    payBtn.textContent = 'Memproses...';
    
    // Prepare transaction data
    const transactionData = {
        name: name,
        email: email,
        whatsapp: whatsapp,
        package: package,
        price: price,
        ram: ram,
        disk: disk,
        cpu: cpu
    };
    
    // Send data to server
    fetch('payment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(transactionData)
    })
    .then(response => response.json())
    .then(data => {
        if(data.token) {
            // Redirect to Midtrans payment page
            snap.pay(data.token, {
                onSuccess: function(result) {
                    window.location.href = 'success.php?order_id=' + result.order_id;
                },
                onPending: function(result) {
                    window.location.href = 'success.php?order_id=' + result.order_id;
                },
                onError: function(result) {
                    window.location.href = 'failed.php?order_id=' + result.order_id;
                },
                onClose: function() {
                    payBtn.disabled = false;
                    payBtn.textContent = 'Bayar Sekarang';
                }
            });
        } else {
            alert('Error: ' + (data.message || 'Gagal memproses pembayaran'));
            payBtn.disabled = false;
            payBtn.textContent = 'Bayar Sekarang';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memproses pembayaran');
        payBtn.disabled = false;
        payBtn.textContent = 'Bayar Sekarang';
    });
}