// Payment form handling
document.querySelector('.btn-primary').addEventListener('click', function(e) {
    e.preventDefault();
    const selectedPayment = document.querySelector('input[name="payment"]:checked').value;

    switch(selectedPayment) {
        case 'credit-card':
        processCreditCard();
        break;

        case 'paypal':
        processPayPal();
        break;

        case 'cash':
        processCash();
        break;
    }
});

    function processCreditCard() {
        const cardNumber = document.querySelector('.card-number').value;
        const expiry = document.querySelector('.expiry').value;
        const cvv = document.querySelector('.cvv').value;
        const cardName = document.querySelector('.card-name').value;

        // Credit card processing 
        alert('Processing credit card payment...');
    }

    function processPayPal() {
        // PayPal integration
        alert('Redirecting to PayPal...');
    }

    function processCash() {
        // Cash payment processing
        alert('Cash payment selected. Please pay at the counter');
    }