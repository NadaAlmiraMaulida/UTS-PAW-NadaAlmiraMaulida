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

if (isset($_POST['edit_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $ram = $_POST['ram'];
    $ram_unit = $_POST['ram_unit'];
    $storage = $_POST['storage'];
    $storage_unit = $_POST['storage_unit'];
    $quality = $_POST['quality'];
    $target_file = $product['image']; 

    if (isset($_FILES['image']) && $_FILES['image']['name'] != '') {
        $target_dir = "upload/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Successfully uploaded
        } else {
            echo "Error uploading the file.";
            exit;
        }
    }

    // Combine specifications into a single string
    $specifications = "RAM: $ram $ram_unit, Storage: $storage $storage_unit, Condition: $quality";

    $sql = "UPDATE products SET name = ?, price = ?, specifications = ?, image = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdssi", $name, $price, $specifications, $target_file, $id);

    if ($stmt->execute()) {
        header("Location: detail.php?id=" . $id); 
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f5f5f7;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #0071e3;
            margin-bottom: 20px;
        }
        form {
            max-width: 600px; /* Set a maximum width for the form */
            margin: 0 auto; /* Center the form */
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
        }
        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .specifications-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px; /* Space below the table */
        }
        .specifications-table td {
            padding: 10px;
            border: 1px solid #ccc; /* Border for each cell */
        }
        button {
            padding: 10px 15px;
            margin-top: 15px;
            background-color: #0071e3;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: block; /* Center the button */
            width: 100%; /* Make the button full-width */
        }
        button:hover {
            background-color: #005bb5; /* Darker shade on hover */
        }
        .back-link {
            display: block; /* Block display for the link */
            margin: 20px auto; /* Center and space the link */
            text-align: center; /* Center the link text */
            text-decoration: none; /* Remove underline */
            color: #0071e3; /* Link color */
            font-weight: bold; /* Make link bold */
            width: fit-content; /* Fit the link to its content */
        }
        .back-link:hover {
            text-decoration: underline; /* Underline on hover */
        }
        .unit-select {
            width: auto; /* Width fit for select box */
        }
    </style>
</head>
<body>

<h1>Edit Product</h1>
<form action="" method="post" enctype="multipart/form-data">
    <label for="name">Product Name:</label>
    <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

    <label for="price">Price:</label>
    <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>

    <label for="specifications">Specifications:</label>
    <table class="specifications-table">
        <tr>
            <td><label for="ram">RAM:</label></td>
            <td>
                <input type="text" name="ram" value="<?php echo htmlspecialchars(explode(', ', $product['specifications'])[0] ?? ''); ?>" required>
                <select name="ram_unit" class="unit-select">
                    <option value="GB" <?php echo (strpos($product['specifications'], 'GB') !== false) ? 'selected' : ''; ?>>GB</option>
                    <option value="MB" <?php echo (strpos($product['specifications'], 'MB') !== false) ? 'selected' : ''; ?>>MB</option>
                    <option value="TB" <?php echo (strpos($product['specifications'], 'TB') !== false) ? 'selected' : ''; ?>>TB</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><label for="storage">Storage:</label></td>
            <td>
                <input type="text" name="storage" value="<?php echo htmlspecialchars(explode(', ', $product['specifications'])[1] ?? ''); ?>" required>
                <select name="storage_unit" class="unit-select">
                    <option value="GB" <?php echo (strpos($product['specifications'], 'GB') !== false) ? 'selected' : ''; ?>>GB</option>
                    <option value="MB" <?php echo (strpos($product['specifications'], 'MB') !== false) ? 'selected' : ''; ?>>MB</option>
                    <option value="TB" <?php echo (strpos($product['specifications'], 'TB') !== false) ? 'selected' : ''; ?>>TB</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><label for="quality">Condition:</label></td>
            <td>
                <select name="quality" class="unit-select" required>
                    <option value="New" <?php echo (strpos($product['specifications'], 'New') !== false) ? 'selected' : ''; ?>>New</option>
                    <option value="Used" <?php echo (strpos($product['specifications'], 'Used') !== false) ? 'selected' : ''; ?>>Used</option>
                </select>
            </td>
        </tr>
    </table>

    <label for="image">Product Image:</label>
    <input type="file" name="image">

    <button type="submit" name="edit_product">Update Product</button>
</form>

<!-- Centered Back to Details Link -->
<a class="back-link" href="detail.php?id=<?php echo $product['id']; ?>">Back to Details</a>

</body>
</html>
