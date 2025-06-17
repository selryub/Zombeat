<?php
// config.php - Configuration file for FCSIT KIOSK

// Database Configuration (if needed)
define('DB_HOST', 'localhost');
define('DB_NAME', 'posit_kiosk');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');

// Email Configuration
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-app-password');
define('FROM_EMAIL', 'noreply@positkiosk.com');
define('FROM_NAME', 'POSIT KIOSK');
define('SUPPORT_EMAIL', 'support@positkiosk.com');

// Application Settings
define('APP_NAME', 'POSIT KIOSK');
define('APP_URL', 'https://your-domain.com');
define('TIMEZONE', 'Asia/Kuala_Lumpur');

// File Upload Settings
define('UPLOAD_DIR', 'uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

// Security Settings
define('SESSION_TIMEOUT', 3600); // 1 hour
define('MAX_LOGIN_ATTEMPTS', 5);

// Payment Gateway Settings (if applicable)
define('PAYMENT_GATEWAY_URL', 'https://payment-gateway.com/api');
define('PAYMENT_API_KEY', 'your-payment-api-key');

// Logging
define('LOG_DIR', 'logs/');
define('LOG_LEVEL', 'INFO'); // DEBUG, INFO, WARNING, ERROR

// Set timezone
date_default_timezone_set(TIMEZONE);

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Database connection function
 */
function getDBConnection() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        error_log("Database connection failed: " . $e->getMessage());
        return null;
    }
}

/**
 * Log function
 */
function writeLog($level, $message) {
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[{$timestamp}] [{$level}] {$message}\n";
    
    // Create logs directory if it doesn't exist
    if (!is_dir(LOG_DIR)) {
        mkdir(LOG_DIR, 0755, true);
    }
    
    $logFile = LOG_DIR . 'app_' . date('Y-m-d') . '.log';
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}

/**
 * Send JSON response
 */
function sendJSONResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Sanitize input data
 */
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Generate random string
 */
function generateRandomString($length = 10) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}

/**
 * Format currency
 */
function formatCurrency($amount) {
    return 'RM ' . number_format($amount, 2);
}

/**
 * Get current timestamp in MySQL format
 */
function getCurrentTimestamp() {
    return date('Y-m-d H:i:s');
}

/**
 * Calculate order total
 */
function calculateOrderTotal($items, $deliveryFee = 0) {
    $subtotal = 0;
    foreach ($items as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    return $subtotal + $deliveryFee;
}

/**
 * Generate order ID
 */
function generateOrderId() {
    $prefix = 'PK';
    $year = date('Y');
    $random = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    return $prefix . '-' . $year . '-' . $random;
}

/**
 * Check if user is authenticated (if you implement authentication)
 */
function isAuthenticated() {
    session_start();
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * CORS headers
 */
function setCORSHeaders() {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    
    // Handle preflight requests
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }
}

/**
 * Rate limiting (simple implementation)
 */
function checkRateLimit($identifier, $maxRequests = 60, $timeWindow = 3600) {
    $cacheFile = LOG_DIR . 'rate_limit_' . md5($identifier) . '.txt';
    
    if (file_exists($cacheFile)) {
        $data = json_decode(file_get_contents($cacheFile), true);
        $currentTime = time();
        
        // Reset if time window has passed
        if ($currentTime - $data['start_time'] > $timeWindow) {
            $data = ['count' => 1, 'start_time' => $currentTime];
        } else {
            $data['count']++;
            if ($data['count'] > $maxRequests) {
                return false; // Rate limit exceeded
            }
        }
    } else {
        $data = ['count' => 1, 'start_time' => time()];
    }
    
    file_put_contents($cacheFile, json_encode($data));
    return true;
}

// Initialize session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>