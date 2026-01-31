<?php
/**
 * Products API Endpoint
 * VULNERABILITIES: No authentication, CORS issues, Information Disclosure
 */
header('Content-Type: application/json');

// VULNERABILITY: No authentication required for API access
// VULNERABILITY: Permissive CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

include "../config/db.php";

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        // Get all products or specific product
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            // VULNERABILITY: SQL Injection
            $query = "SELECT * FROM products WHERE id='$id'";
            $result = mysqli_query($conn, $query);
            
            if ($result && mysqli_num_rows($result) > 0) {
                $product = mysqli_fetch_assoc($result);
                echo json_encode([
                    'success' => true,
                    'data' => $product
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Product not found'
                ]);
            }
        } else {
            // Get all products
            $query = "SELECT * FROM products";
            $result = mysqli_query($conn, $query);
            $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
            
            echo json_encode([
                'success' => true,
                'data' => $products,
                'count' => count($products)
            ]);
        }
        break;
        
    case 'POST':
        // VULNERABILITY: No authentication, mass assignment
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (isset($data['name']) && isset($data['price'])) {
            $name = $data['name'];
            $description = isset($data['description']) ? $data['description'] : '';
            $price = $data['price'];
            
            // VULNERABILITY: SQL Injection
            $query = "INSERT INTO products (name, description, price) VALUES ('$name', '$description', '$price')";
            
            if (mysqli_query($conn, $query)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Product created',
                    'id' => mysqli_insert_id($conn)
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to create product',
                    'error' => mysqli_error($conn) // Information disclosure
                ]);
            }
        } else {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Missing required fields'
            ]);
        }
        break;
        
    case 'PUT':
        // Update product
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (isset($data['id'])) {
            $id = $data['id'];
            $updates = [];
            
            if (isset($data['name'])) $updates[] = "name='{$data['name']}'";
            if (isset($data['description'])) $updates[] = "description='{$data['description']}'";
            if (isset($data['price'])) $updates[] = "price='{$data['price']}'";
            
            if (count($updates) > 0) {
                $query = "UPDATE products SET " . implode(', ', $updates) . " WHERE id='$id'";
                
                if (mysqli_query($conn, $query)) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Product updated'
                    ]);
                } else {
                    http_response_code(500);
                    echo json_encode([
                        'success' => false,
                        'message' => 'Failed to update product'
                    ]);
                }
            }
        }
        break;
        
    case 'DELETE':
        // Delete product
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $query = "DELETE FROM products WHERE id='$id'";
            
            if (mysqli_query($conn, $query)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Product deleted'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to delete product'
                ]);
            }
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode([
            'success' => false,
            'message' => 'Method not allowed'
        ]);
        break;
}
?>
