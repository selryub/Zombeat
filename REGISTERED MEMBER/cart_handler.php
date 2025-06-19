<?php
session_start();
header('Content-Type: application/json');

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = ['success' => false, 'message' => '', 'debug' => ''];

try {
    // Check if action is set
    if (!isset($_POST['action'])) {
        $response['message'] = 'No action specified';
        echo json_encode($response);
        exit;
    }
    
    $action = $_POST['action'];
    
    // Handle add to cart
    if ($action === 'add_to_cart') {
        if (!isset($_POST['product_id']) || !isset($_POST['product_name']) || !isset($_POST['price']) || !isset($_POST['image_url'])) {
            $response['message'] = 'Missing required parameters for add to cart';
            echo json_encode($response);
            exit;
        }
        
        $product_id = intval($_POST['product_id']);
        $product_name = $_POST['product_name'];
        $price = floatval($_POST['price']);
        $image_url = $_POST['image_url'];
        
        // Initialize cart if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        // Check if item already exists in cart
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] == $product_id) {
                $item['quantity']++;
                $found = true;
                break;
            }
        }
        
        // If item not found, add new item
        if (!$found) {
            $_SESSION['cart'][] = [
                'product_id' => $product_id,
                'product_name' => $product_name,
                'price' => $price,
                'image_url' => $image_url,
                'quantity' => 1
            ];
        }
        
        $response['success'] = true;
        $response['message'] = 'Item added to cart!';
    }
    
    // Handle remove from cart
    else if ($action === 'remove_from_cart') {
        if (!isset($_POST['product_id'])) {
            $response['message'] = 'Product ID is required for remove from cart';
            echo json_encode($response);
            exit;
        }
        
        $product_id = intval($_POST['product_id']);
        $removed = false;
        
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $key => $item) {
                if (intval($item['product_id']) == $product_id) {
                    unset($_SESSION['cart'][$key]);
                    $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex array
                    $removed = true;
                    break;
                }
            }
        }
        
        if ($removed) {
            $response['success'] = true;
            $response['message'] = 'Item removed from cart!';
        } else {
            $response['success'] = false;
            $response['message'] = 'Item not found in cart';
        }
    }
    
    // Handle update cart quantity
    else if ($action === 'update_cart') {
        if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
            $response['message'] = 'Product ID and quantity are required for update cart';
            echo json_encode($response);
            exit;
        }
        
        $product_id = intval($_POST['product_id']);
        $quantity = max(1, intval($_POST['quantity'])); // Ensure minimum quantity of 1
        $updated = false;
        
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as &$item) {
                if (intval($item['product_id']) == $product_id) {
                    $item['quantity'] = $quantity;
                    $updated = true;
                    break;
                }
            }
        }
        
        if ($updated) {
            $response['success'] = true;
            $response['message'] = 'Cart updated!';
        } else {
            $response['success'] = false;
            $response['message'] = 'Item not found in cart for update';
        }
    }
    
    else {
        $response['message'] = 'Invalid action: ' . $action;
        echo json_encode($response);
        exit;
    }
    
    // Calculate cart totals
    $cart_total = 0;
    $cart_count = 0;
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $cart_total += floatval($item['price']) * intval($item['quantity']);
            $cart_count += intval($item['quantity']);
        }
    }
    
    $response['cart_total'] = number_format($cart_total, 2);
    $response['cart_count'] = $cart_count;
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = 'Server error: ' . $e->getMessage();
    $response['debug'] = $e->getTraceAsString();
}

echo json_encode($response);
?>