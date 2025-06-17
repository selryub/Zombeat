<?php
// send_email.php

// Set content type to JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get JSON input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validate input data
if (!$data || !isset($data['to']) || !isset($data['subject']) || !isset($data['orderData'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit;
}

$to = filter_var($data['to'], FILTER_SANITIZE_EMAIL);
$subject = htmlspecialchars($data['subject']);
$orderData = $data['orderData'];

// Validate email
if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

// Generate email content
$emailHTML = generateEmailHTML($orderData);

// Email headers
$headers = [
    'MIME-Version: 1.0',
    'Content-type: text/html; charset=UTF-8',
    'From: POSIT KIOSK <noreply@positkiosk.com>',
    'Reply-To: support@positkiosk.com',
    'X-Mailer: PHP/' . phpversion()
];

try {
    // Send email using PHP's mail function
    // Note: For production, consider using PHPMailer or similar library
    $success = mail($to, $subject, $emailHTML, implode("\r\n", $headers));
    
    if ($success) {
        // Log successful email (optional)
        logEmail($to, $orderData['orderId'], true);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Email sent successfully',
            'orderId' => $orderData['orderId']
        ]);
    } else {
        // Log failed email (optional)
        logEmail($to, $orderData['orderId'], false);
        
        echo json_encode([
            'success' => false, 
            'message' => 'Failed to send email'
        ]);
    }
} catch (Exception $e) {
    error_log('Email sending error: ' . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'Email service error'
    ]);
}

/**
 * Generate HTML email content
 */
function generateEmailHTML($orderData) {
    $orderDate = date('d/m/Y H:i:s');
    
    // Calculate totals
    $subtotal = 0;
    foreach ($orderData['items'] as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    $total = $subtotal + $orderData['deliveryFee'];
    
    // Generate items HTML
    $itemsHTML = '';
    foreach ($orderData['items'] as $item) {
        $itemTotal = $item['price'] * $item['quantity'];
        $itemsHTML .= "
            <tr>
                <td style='padding: 10px; border-bottom: 1px solid #eee;'>
                    <strong>{$item['name']}</strong><br>
                    <small>Quantity: {$item['quantity']}</small>
                </td>
                <td style='padding: 10px; border-bottom: 1px solid #eee; text-align: right;'>
                    RM " . number_format($item['price'], 2) . "
                </td>
                <td style='padding: 10px; border-bottom: 1px solid #eee; text-align: right;'>
                    <strong>RM " . number_format($itemTotal, 2) . "</strong>
                </td>
            </tr>
        ";
    }
    
    $remarksHTML = '';
    if (!empty($orderData['remarks'])) {
        $remarksHTML = "
            <div style='margin-top: 20px; padding: 15px; background-color: #f9f9f9; border-left: 4px solid #4a90e2;'>
                <strong>Remarks:</strong><br>
                " . htmlspecialchars($orderData['remarks']) . "
            </div>
        ";
    }
    
    return "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Order Receipt - {$orderData['orderId']}</title>
    </head>
    <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4;'>
        <div style='max-width: 600px; margin: 20px auto; background-color: white; border-radius: 10px; overflow: hidden; box-shadow: 0 0 20px rgba(0,0,0,0.1);'>
            
            <!-- Header -->
            <div style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center;'>
                <h1 style='margin: 0; font-size: 2em; letter-spacing: 2px;'>POSIT KIOSK</h1>
                <p style='margin: 10px 0 0 0; font-size: 1.1em;'>Order Receipt</p>
            </div>
            
            <!-- Order Info -->
            <div style='padding: 30px;'>
                <div style='background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;'>
                    <h2 style='margin: 0 0 15px 0; color: #333; border-bottom: 2px solid #4a90e2; padding-bottom: 10px;'>Order Information</h2>
                    <table style='width: 100%; border-collapse: collapse;'>
                        <tr>
                            <td style='padding: 8px 0; font-weight: bold;'>Order ID:</td>
                            <td style='padding: 8px 0;'>{$orderData['orderId']}</td>
                        </tr>
                        <tr>
                            <td style='padding: 8px 0; font-weight: bold;'>Customer:</td>
                            <td style='padding: 8px 0;'>{$orderData['customer']['name']}</td>
                        </tr>
                        <tr>
                            <td style='padding: 8px 0; font-weight: bold;'>Date & Time:</td>
                            <td style='padding: 8px 0;'>{$orderDate}</td>
                        </tr>
                        <tr>
                            <td style='padding: 8px 0; font-weight: bold;'>Payment Method:</td>
                            <td style='padding: 8px 0;'>{$orderData['paymentMethod']}</td>
                        </tr>
                        <tr>
                            <td style='padding: 8px 0; font-weight: bold;'>Order Type:</td>
                            <td style='padding: 8px 0;'>{$orderData['orderType']}</td>
                        </tr>
                    </table>
                </div>
                
                <!-- Items -->
                <div style='margin-bottom: 30px;'>
                    <h3 style='color: #333; border-bottom: 2px solid #4a90e2; padding-bottom: 10px;'>Items Ordered</h3>
                    <table style='width: 100%; border-collapse: collapse; margin-top: 15px;'>
                        <thead>
                            <tr style='background-color: #f8f9fa;'>
                                <th style='padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;'>Item</th>
                                <th style='padding: 12px; text-align: right; border-bottom: 2px solid #dee2e6;'>Unit Price</th>
                                <th style='padding: 12px; text-align: right; border-bottom: 2px solid #dee2e6;'>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            {$itemsHTML}
                        </tbody>
                    </table>
                </div>
                
                <!-- Summary -->
                <div style='background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;'>
                    <h3 style='margin: 0 0 15px 0; color: #333;'>Order Summary</h3>
                    <table style='width: 100%; border-collapse: collapse;'>
                        <tr>
                            <td style='padding: 8px 0; font-size: 1.1em;'>Subtotal:</td>
                            <td style='padding: 8px 0; text-align: right; font-size: 1.1em;'>RM " . number_format($subtotal, 2) . "</td>
                        </tr>
                        <tr>
                            <td style='padding: 8px 0; font-size: 1.1em;'>Delivery Fee:</td>
                            <td style='padding: 8px 0; text-align: right; font-size: 1.1em;'>RM " . number_format($orderData['deliveryFee'], 2) . "</td>
                        </tr>
                        <tr style='border-top: 2px solid #4a90e2;'>
                            <td style='padding: 15px 0 0 0; font-size: 1.3em; font-weight: bold; color: #4a90e2;'>TOTAL:</td>
                            <td style='padding: 15px 0 0 0; text-align: right; font-size: 1.3em; font-weight: bold; color: #4a90e2;'>RM " . number_format($total, 2) . "</td>
                        </tr>
                    </table>
                </div>
                
                {$remarksHTML}
                
                <!-- Footer -->
                <div style='text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; color: #666;'>
                    <p style='margin: 0 0 10px 0; font-size: 1.1em;'>Thank you for your order!</p>
                    <p style='margin: 0; font-size: 0.9em;'>If you have any questions, please contact us at support@positkiosk.com</p>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div style='text-align: center; padding: 20px; color: #666; font-size: 0.8em;'>
            <p>This is an automated email from POSIT KIOSK. Please do not reply to this email.</p>
        </div>
    </body>
    </html>
    ";
}

/**
 * Log email activity (optional)
 */
function logEmail($to, $orderId, $success) {
    $timestamp = date('Y-m-d H:i:s');
    $status = $success ? 'SUCCESS' : 'FAILED';
    $logEntry = "[{$timestamp}] Email {$status}: {$to} - Order: {$orderId}\n";
    
    // Log to file (make sure the logs directory exists and is writable)
    $logFile = 'logs/email_log.txt';
    
    // Create logs directory if it doesn't exist
    if (!is_dir('logs')) {
        mkdir('logs', 0755, true);
    }
    
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}

/**
 * Alternative email function using PHPMailer (if available)
 * Uncomment and configure if you want to use PHPMailer instead
 */
/*
function sendEmailWithPHPMailer($to, $subject, $body, $orderData) {
    require_once 'vendor/autoload.php'; // Include PHPMailer
    
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Set your SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your-email@gmail.com'; // Your email
        $mail->Password   = 'your-app-password'; // Your app password
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        
        // Recipients
        $mail->setFrom('noreply@positkiosk.com', 'POSIT KIOSK');
        $mail->addAddress($to);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("PHPMailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
*/
?>