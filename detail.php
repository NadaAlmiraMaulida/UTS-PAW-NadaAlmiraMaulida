<?php
session_start();
require 'db.php'; 

if (!isset($_SESSION['username'])) { 
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];  

$sql = "SELECT * FROM products WHERE id = ?"; 
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    echo "Product not found!"; 
    exit;
}


if (isset($_POST['delete'])) {
    $deleteSql = "DELETE FROM products WHERE id = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param("i", $id);
    if ($deleteStmt->execute()) {
        header("Location: index.php"); 
        exit;
    } else {
        echo "Failed to delete product!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f5f5f7;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        h1 {
            text-align: left;
            color: #0071e3;
            margin-bottom: 20px;
        }
        .product-details {
            display: flex; 
            width: 80%; 
            margin: 0 auto; 
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            gap: 20px; 
        }
        .details {
            flex: 1; 
        }
        .details p {
            font-size: 16px;
            line-height: 1.5;
            margin: 10px 0;
        }
        .details .price {
            font-weight: bold;
            font-size: 18px;
            color: #e67e22; 
        }
        .product-details img {
            max-width: 300px; 
            border-radius: 8px;
            display: block;
            margin: 0 auto; 
        }
        .actions {
            text-align: left; 
            margin-top: 20px; 
        }
        .actions a {
            padding: 10px 15px;
            background-color: #0071e3; 
            color: white; 
            border-radius: 5px;
            text-decoration: none; 
            margin-right: 5px; 
            transition: background-color 0.3s; 
        }
        .actions a:hover {
            background-color: #005bb5; 
        }
        .actions form {
            display: inline; 
        }
        .actions button {
            padding: 10px 15px; 
            background-color: #0071e3; 
            color: white; 
            border-radius: 5px; 
            border: none; 
            cursor: pointer; 
            text-decoration: none; 
            margin-right: 5px; 
            transition: background-color 0.3s; 
        }
        .actions button:hover {
            background-color: #005bb5; 
        }
    </style>
</head>
<body>

<div class="product-details">
    <div class="details">
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
        <p class="price">Price: $<?php echo htmlspecialchars($product['price']); ?></p>
        <p><strong>Specifications:</strong> <?php echo nl2br(htmlspecialchars($product['specifications'])); ?></p>
        
        <div class="actions">
            <a href="index.php">Back to Home</a>
            <a href="edit_product.php?id=<?php echo $product['id']; ?>">Edit</a>
            <form action="" method="post" style="display:inline;">
                <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this product?');">Delete</button>
            </form>
        </div>
    </div>
    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
</div>

</body>
</html>
