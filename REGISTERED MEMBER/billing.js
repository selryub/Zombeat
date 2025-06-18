// Automatically run when the page is ready
document.addEventListener('DOMContentLoaded', () => {
    const receiptElement = document.querySelector('.receipt-container');
    if (receiptElement) {
        const receiptHTML = receiptElement.outerHTML;
        sendEmail(receiptHTML); // Send email when the billing page loads
    }
});

// Function to send the email
function sendEmail(receiptHTML) {
    fetch('send_email.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'receipt=' + encodeURIComponent(receiptHTML),
    })
    .then(response => response.text())
    .then(data => {
        if (data.trim() === 'success') {
            console.log('Email sent successfully!');
        } else {
            console.error('Failed to send email:', data);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// You can still use your other functions like printReceipt(), etc.
function printReceipt() {
    const element = document.querySelector('.receipt-container');
    html2pdf().from(element).save('receipt.pdf');
}

function closeOrderInfo() {
    document.querySelector('.order-info-section').style.display = 'none';
}

function trackOrder() {
    alert("Tracking order..."); // Replace this with actual logic
}

