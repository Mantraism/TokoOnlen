<?php
// Include your database connection here
include "header.php";
include 'db.php';

// Get the user's search query
$query = $_GET['Query'];

// Initialize a variable to store the cart product IDs
$cartProductIDs = [];

// Check if a cart session is already set
if (isset($_SESSION['cart'])) {
    $cartProductIDs = $_SESSION['cart'];
}

// Perform a database query to search for products
$search_sql = "SELECT * FROM products WHERE product_title LIKE '%$query%'";
$search_result = mysqli_query($con, $search_sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
</head>
<body>

<div style="max-width: 800px; margin: 0 auto; padding: 20px; text-align: center;">
    <h2>Search Results</h2>

    <div class="row">
        <?php
        $count = 0;
        if (mysqli_num_rows($search_result) > 0) {
            while ($row = mysqli_fetch_assoc($search_result)) {
                if ($count % 4 === 0) {
                    echo '</div><div class="row">'; // Start a new row every 4 products
                }
                ?>
                <div class="col-md-3" style="margin-bottom: 20px;">
                    <div style="border: 1px solid #ccc; padding: 10px;">
                        <img src="product_images/<?= $row['product_image'] ?>" alt="<?= $row['product_title'] ?>"
                             style="max-width: 100%; height: auto;">
                        <h3><?= $row['product_title'] ?></h3>
                        <p style="font-weight: bold;">$<?= $row['product_price'] ?></p>
                        <a href="product.php?p=<?= $row['product_id'] ?>"
                           style="text-decoration: none; color: #fff;">
                            <button style="background-color: #007bff; color: #fff; border: none; padding: 5px 10px; cursor: pointer;">
                                View Product
                            </button>
                        </a>
                        <!-- <button style="background-color: #007bff; color: #fff; border: none; padding: 5px 10px; cursor: pointer;"
                                class="add-to-cart-btn" pid="<?= $row['product_id'] ?>" id="product<?= $row['product_id'] ?>">
                            <i class="fa fa-shopping-cart"></i> Add to Cart
                        </button> -->
                    </div>
                </div>
                <script>
                    // JavaScript to handle adding products to the cart
                    document.getElementById("product<?= $row['product_id'] ?>").addEventListener("click", function () {
                        var productId = <?= $row['product_id'] ?>;
                        // Check if productId is not already in the cart
                        if (!<?= json_encode(in_array($row['product_id'], $cartProductIDs)) ?>) {
                            // Add productId to the cartProductIDs array
                            <?php
                            array_push($cartProductIDs, $row['product_id']);
                            $_SESSION['cart'] = $cartProductIDs;
                            ?>
                            alert("Product added to cart!");
                        } else {
                            alert("Product is already in the cart!");
                        }
                    });
                </script>
                <?php
                $count++;
            }
        } else {
            // No search results found
            ?>
            <p>No products found for your search query.</p>
            <?php
        }
        ?>
    </div>

</div>

</body>
</html>

<?php
// Close the database connection
mysqli_close($con);
?>
