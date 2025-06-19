document.querySelector('.btn-primary').addEventListener('click', function(e) {
    e.preventDefault();
    const selectedPayment = document.querySelector('input[name="payment"]:checked').value;
    document.getElementById('payment-method').value = selectedPayment;

    switch(selectedPayment) {
        case 'credit-card':
            if (processCreditCard()) {
                document.getElementById('payment-form').submit();
            }
            break;
        case 'paypal':
            alert('Redirecting to PayPal...'); 
            break;
        case 'cash':
            alert('Cash payment selected. Please pay at the counter.');
            document.getElementById('payment-form').submit();
            break;
    }
});

function processCreditCard() {
    const cardNumber = document.querySelector('.card-number').value;
    const expiry = document.querySelector('.expiry').value;
    const cvv = document.querySelector('.cvv').value;
    const cardName = document.querySelector('.card-name').value;

    if (!cardNumber || !expiry || !cvv || !cardName) {
        alert('Please fill in all credit card fields.');
        return false;
    }

    return true; // All good
}
