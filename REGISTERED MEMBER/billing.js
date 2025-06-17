// script.js

// Sample order data - In a real application, this would come from your backend
const orderData = {
    customer: {
        name: "John Doe",
        email: "john.doe@example.com"
    },
    orderId: "PK-2025-001",
    orderDate: new Date(),
    paymentMethod: "Credit Card",
    orderType: "Dine In",
    items: [
        {
            id: 1,
            name: "Product 1",
            price: 25.50,
            quantity: 2,
            image: "📦"
        },
        {
            id: 2,
            name: "Product 2",
            price: 15.75,
            quantity: 1,
            image: "📦"
        },
        {
            id: 3,
            name: "Product 3",
            price: 8.25,
            quantity: 3,
            image: "📦"
        }
    ],
    deliveryFee: 5.00,
    remarks: "Please handle with care"
};

// Initialize the page when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    loadOrderData();
    updateDateTime();
    setInterval(updateDateTime, 1000); // Update time every second
});

// Load order data into the page
function loadOrderData() {
    // Populate order info
    document.getElementById('customerName').textContent = orderData.customer.name;
    document.getElementById('orderId').textContent = orderData.orderId;
    document.getElementById('paymentMethod').textContent = orderData.paymentMethod;
    document.getElementById('orderType').textContent = orderData.orderType;
    document.getElementById('emailInput').value = orderData.customer.email;
    
    // Populate cart items
    const cartContainer = document.getElementById('cartItems');
    cartContainer.innerHTML = '';
    
    orderData.items.forEach(item => {
        const cartItem = createCartItemElement(item);
        cartContainer.appendChild(cartItem);
    });
    
    // Calculate and display totals
    calculateTotals();
}

// Create cart item element
function createCartItemElement(item) {
    const cartItem = document.createElement('div');
    cartItem.className = 'cart-item';
    
    cartItem.innerHTML = `
        <div class="product-image">${item.image}</div>
        <div class="product-info">
            <div class="product-name">${item.name}</div>
            <div class="product-price">RM ${item.price.toFixed(2)}</div>
        </div>
        <div class="quantity-info">
            <div class="quantity-label">Quantity:</div>
            <div class="quantity-value">${item.quantity}</div>
        </div>
    `;
    
    return cartItem;
}

// Calculate totals
function calculateTotals() {
    let subtotal = 0;
    
    orderData.items.forEach(item => {
        subtotal += item.price * item.quantity;
    });
    
    const total = subtotal + orderData.deliveryFee;
    
    // Update display
    document.getElementById('subtotal').textContent = `RM ${subtotal.toFixed(2)}`;
    document.getElementById('deliveryFee').textContent = `RM ${orderData.deliveryFee.toFixed(2)}`;
    document.getElementById('totalAmount').textContent = `RM ${total.toFixed(2)}`;
}

// Update date and time
function updateDateTime() {
    const now = new Date();
    const dateStr = now.toLocaleDateString('en-GB');
    const timeStr = now.toLocaleTimeString('en-GB');
    document.getElementById('orderDateTime').textContent = `${dateStr} - ${timeStr}`;
}

// Print receipt function
function printReceipt() {
    showLoadingModal();
    
    // Generate PDF receipt
    const receiptContent = generateReceiptHTML();
    const receiptTemplate = document.getElementById('receiptTemplate');
    receiptTemplate.querySelector('.receipt-content').innerHTML = receiptContent;
    receiptTemplate.style.display = 'block';
    
    const options = {
        margin: 10,
        filename: `receipt-${orderData.orderId}.pdf`,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };
    
    html2pdf().set(options).from(receiptTemplate).save().then(() => {
        receiptTemplate.style.display = 'none';
        hideLoadingModal();
        showSuccessModal('Receipt downloaded successfully!');
    }).catch(error => {
        console.error('Error generating PDF:', error);
        receiptTemplate.style.display = 'none';
        hideLoadingModal();
        alert('Error generating PDF. Please try again.');
    });
}

// Generate receipt HTML content
function generateReceiptHTML() {
    const now = new Date();
    const dateStr = now.toLocaleDateString('en-GB');
    const timeStr = now.toLocaleTimeString('en-GB');
    
    let itemsHTML = '';
    let subtotal = 0;
    
    orderData.items.forEach(item => {
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;
        
        itemsHTML += `
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px dotted #ccc; padding-bottom: 5px;">
                <div>
                    <div style="font-weight: bold;">${item.name}</div>
                    <div style="font-size: 0.9em; color: #666;">${item.quantity} x RM ${item.price.toFixed(2)}</div>
                </div>
                <div style="font-weight: bold;">RM ${itemTotal.toFixed(2)}</div>
            </div>
        `;
    });
    
    const total = subtotal + orderData.deliveryFee;
    
    return `
        <div style="margin-bottom: 20px;">
            <h2 style="text-align: center; margin-bottom: 10px;">ORDER RECEIPT</h2>
            <div style="text-align: center; margin-bottom: 20px; font-size: 0.9em;">
                <div>Order ID: ${orderData.orderId}</div>
                <div>Date: ${dateStr} ${timeStr}</div>
                <div>Customer: ${orderData.customer.name}</div>
                <div>Payment: ${orderData.paymentMethod}</div>
                <div>Type: ${orderData.orderType}</div>
            </div>
        </div>
        
        <div style="margin-bottom: 20px;">
            <h3 style="border-bottom: 2px solid #333; padding-bottom: 5px; margin-bottom: 15px;">ITEMS ORDERED</h3>
            ${itemsHTML}
        </div>
        
        <div style="margin-top: 20px; border-top: 2px solid #333; padding-top: 10px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                <span>Subtotal:</span>
                <span>RM ${subtotal.toFixed(2)}</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <span>Delivery Fee:</span>
                <span>RM ${orderData.deliveryFee.toFixed(2)}</span>
            </div>
            <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 1.2em; border-top: 1px solid #333; padding-top: 5px;">
                <span>TOTAL:</span>
                <span>RM ${total.toFixed(2)}</span>
            </div>
        </div>
        
        ${orderData.remarks ? `<div style="margin-top: 20px; padding-top: 10px; border-top: 1px dotted #ccc;"><strong>Remarks:</strong> ${orderData.remarks}</div>` : ''}
        
        <div style="text-align: center; margin-top: 30px; font-size: 0.9em; color: #666;">
            <p>Thank you for your order!</p>
            <p>Visit us again at POSIT KIOSK</p>
        </div>
    `;
}

// Track order function
function trackOrder() {
    showLoadingModal();
    
    // Simulate API call to track order
    setTimeout(() => {
        hideLoadingModal();
        showSuccessModal(`Order ${orderData.orderId} is being prepared. Estimated completion: 15-20 minutes.`);
    }, 2000);
}

// Send email function
function sendEmail() {
    const emailInput = document.getElementById('emailInput');
    const email = emailInput.value.trim();
    
    if (!email) {
        alert('Please enter an email address.');
        return;
    }
    
    if (!isValidEmail(email)) {
        alert('Please enter a valid email address.');
        return;
    }
    
    showLoadingModal();
    
    // Prepare email data
    const emailData = {
        to: email,
        subject: `Order Receipt - ${orderData.orderId}`,
        orderData: orderData,
        receiptHTML: generateReceiptHTML()
    };
    
    // Send email via PHP
    fetch('send_email.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(emailData)
    })
    .then(response => response.json())
    .then(data => {
        hideLoadingModal();
        if (data.success) {
            showSuccessModal(`Receipt sent successfully to ${email}!`);
        } else {
            alert('Failed to send email. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error sending email:', error);
        hideLoadingModal();
        alert('Error sending email. Please try again.');
    });
}

// Validate email format
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Close order info section
function closeOrderInfo() {
    // In a real application, this might navigate back or hide the section
    if (confirm('Are you sure you want to close the order information?')) {
        window.location.href = 'index.html'; // Redirect to home page
    }
}

// Modal functions
function showLoadingModal() {
    document.getElementById('loadingModal').style.display = 'flex';
}

function hideLoadingModal() {
    document.getElementById('loadingModal').style.display = 'none';
}

function showSuccessModal(message) {
    document.getElementById('successMessage').textContent = message;
    document.getElementById('successModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('successModal').style.display = 'none';
}

// Close modals when clicking outside
window.addEventListener('click', function(event) {
    const loadingModal = document.getElementById('loadingModal');
    const successModal = document.getElementById('successModal');
    
    if (event.target === loadingModal) {
        hideLoadingModal();
    }
    
    if (event.target === successModal) {
        closeModal();
    }
});

// Keyboard shortcuts
document.addEventListener('keydown', function(event) {
    // Ctrl/Cmd + P for print receipt
    if ((event.ctrlKey || event.metaKey) && event.key === 'p') {
        event.preventDefault();
        printReceipt();
    }
    
    // Escape key to close modals
    if (event.key === 'Escape') {
        hideLoadingModal();
        closeModal();
    }
});

// Auto-save remarks
let remarksTimeout;
document.getElementById('cartRemarks').addEventListener('input', function() {
    clearTimeout(remarksTimeout);
    remarksTimeout = setTimeout(() => {
        orderData.remarks = this.value;
        console.log('Remarks saved:', orderData.remarks);
    }, 1000);
});

document.getElementById('orderRemarks').addEventListener('input', function() {
    clearTimeout(remarksTimeout);
    remarksTimeout = setTimeout(() => {
        orderData.remarks = this.value;
        console.log('Order remarks saved:', orderData.remarks);
    }, 1000);
});

// Print receipt on screen (alternative to PDF)
function printReceiptOnScreen() {
    const printWindow = window.open('', '_blank');
    const receiptHTML = generateReceiptHTML();
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Receipt - ${orderData.orderId}</title>
            <style>
                body { font-family: 'Courier New', monospace; padding: 20px; }
                .receipt-container { max-width: 600px; margin: 0 auto; }
            </style>
        </head>
        <body>
            <div class="receipt-container">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h1>POSIT KIOSK</h1>
                    <p>Order Receipt</p>
                </div>
                ${receiptHTML}
            </div>
            <script>
                window.onload = function() {
                    window.print();
                    window.onafterprint = function() {
                        window.close();
                    };
                };
            </script>
        </body>
        </html>
    `);
    
    printWindow.document.close();
}

// Export order data as JSON (for development/debugging)
function exportOrderData() {
    const dataStr = JSON.stringify(orderData, null, 2);
    const dataBlob = new Blob([dataStr], {type: 'application/json'});
    const url = URL.createObjectURL(dataBlob);
    
    const link = document.createElement('a');
    link.href = url;
    link.download = `order-${orderData.orderId}.json`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
}