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
            display: flex; /* Use flexbox for layout */
            width: 80%; /* Adjust width as needed */
            margin: 0 auto; /* Center the container */
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            gap: 20px; /* Space between text and image */
        }
        .details {
            flex: 1; /* Take up available space */
        }
        .details p {
            font-size: 16px;
            line-height: 1.5;
            margin: 10px 0;
        }
        .details .price {
            font-weight: bold;
            font-size: 18px;
            color: #e67e22; /* Price color */
        }
        .product-details img {
            max-width: 300px; /* Set a max-width for the image */
            border-radius: 8px;
            display: block;
            margin: 0 auto; /* Center the image */
        }
        .actions {
            text-align: left; /* Align text to the left */
            margin-top: 20px; /* Add some space above buttons */
        }
        .actions a {
            padding: 10px 15px;
            background-color: #0071e3; /* Button color */
            color: white; /* Text color */
            border-radius: 5px;
            text-decoration: none; /* Remove underline */
            margin-right: 5px; /* Space between buttons */
            transition: background-color 0.3s; /* Smooth transition */
        }
        .actions a:hover {
            background-color: #005bb5; /* Darker shade on hover */
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
            <a href="delete_product.php?id=<?php echo $product['id']; ?>">Delete</a>
        </div>
    </div>
    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
</div>

</body>
</html>
