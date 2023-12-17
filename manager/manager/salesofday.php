
    <?php
session_start();
include("./includes/db.php");

error_reporting(0);
if(isset($_GET['action']) && $_GET['action']!="" && $_GET['action']=='delete')
{
$order_id=$_GET['order_id'];

/*this is delet query*/
mysqli_query($con,"delete from orders where order_id='$order_id'")or die("delete query is incorrect...");
} 

///pagination
$page=$_GET['page'];

if($page=="" || $page=="1")
{
$page1=0; 
}
else
{
$page1=($page*10)-10; 
}

include "sidenav.php";
include "topheader.php";

?>
      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          <!-- your content here -->
          <div class="col-md-14">
            <div class="card ">
              <div class="card-header card-header-primary">
                <h4 class="card-title">sales / Page <?php echo $page;?> </h4>
              </div>
              <div class="card-body">
                <div class="table-responsive ps">
                  <table class="table table-hover tablesorter " id="">
                    <thead class=" text-primary">
                      <tr><th>order_id</th><th>Products</th><th>Contact | Email</th><th>Address</th><th>amount</th><th>Quantity</th>
                    </tr></thead>
                    <tbody>
                      <?php
                      $query = "SELECT * FROM orders_info";
                      $run = mysqli_query($con,$query);
                      if(mysqli_num_rows($run) > 0){


                       while($row = mysqli_fetch_array($run)){
                         $order_id = $row['order_id'];
                         $email = $row['email'];
                         $address = $row['address'];
                         $total_amount = $row['total_amt'];
                         $user_id = $row['user_id'];
                         $qty = $row['prod_count'];

                      ?>
                          <tr>
                            <td><?php echo $order_id ?></td>
                           <td> <?php
                            $query1 = "SELECT * FROM order_products where order_id = $order_id";
                            $run1 = mysqli_query($con,$query1); 
                              while($row1 = mysqli_fetch_array($run1)){
                               $product_id = $row1['product_id'];

                               $query2 = "SELECT * FROM products where product_id = $product_id";
                               $run2 = mysqli_query($con,$query2);

                               while($row2 = mysqli_fetch_array($run2)){
                               $product_title = $row2['product_title'];
                           ?>
                              <?php echo $product_title ?><br>
                            <?php }}?></td>
                            <td><?php echo $email ?></td>
                            <td><?php echo $address ?></td>
                            <td><?php echo $total_amount ?></td>
                            <td><?php echo $qty ?></td>
                         </tr>
                         <?php } ?>
                        
                    </tbody>
                     <?php
                   }else {
                     echo "<center><h2>No users Available</h2><br><hr></center>";
                     }
                  ?>
                  </table>
                  <div class="table-responsive">
<?php
$totalAmountQuery = "SELECT SUM(total_amt) AS total FROM orders_info";
$totalAmountResult = mysqli_query($con, $totalAmountQuery);
$totalAmountRow = mysqli_fetch_assoc($totalAmountResult);
$totalAmount = $totalAmountRow['total'];
echo '<script>document.getElementById("totalAmountCell").textContent = "' . $totalAmount . '";</script>';
?>

    <table class="table table-hover">
        <thead class="text-primary">
            <tr>
                <th>Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td id="totalAmountCell">Calculating...</td>  
            </tr>
        </tbody>
    </table>
</div>
<?php
echo '<script>document.getElementById("totalAmountCell").textContent = "' . $totalAmount . '";</script>';
?>
<table>
  <tr>
    <th>Data User</th>
  </tr>
</table>

<div class="table-responsive">
    <table class="table table-hover">
        <thead class="text-primary">
            <tr>
                <th>Email</th>
                <th>Total barang yang dibeli</th>
                <th>Total Uang hasil transaksi dari user</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT u.email, SUM(upq.prod_count) AS total_quantity, SUM(upq.total_amt) AS total_amount
            FROM user_info u
            JOIN orders_info upq ON u.email = upq.email
            GROUP BY u.email";
            $result = mysqli_query($con, $query);

            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>
                        <td>' . $row['email'] . '</td>
                        <td>' . $row['total_quantity'] . '</td>
                        <td>' . $row['total_amount'] . '</td>
                      </tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<table>
  <tr>
    <th>Data berdasarkan Lokasi</th>
  </tr>
</table>

<div class="table-responsive">
    <table class="table table-hover">
        <thead class="text-primary">
            <tr>
                <th>Lokasi</th>
                <th>Total barang yang dibeli</th>
                <th>Total Uang hasil transaksi dari user</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT u.address1, SUM(upq.prod_count) AS total_quantity, SUM(upq.total_amt) AS total_amount
            FROM user_info u
            JOIN orders_info upq ON u.address1 = upq.address
            GROUP BY u.address1";
            $result = mysqli_query($con, $query);

            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>
                        <td>' . $row['address1'] . '</td>
                        <td>' . $row['total_quantity'] . '</td>
                        <td>' . $row['total_amount'] . '</td>
                      </tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Include Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <?php
// Your database connection code here

$query = "SELECT u.address1, SUM(upq.prod_count) AS total_quantity, SUM(upq.total_amt) AS total_amount
FROM user_info u
JOIN orders_info upq ON u.address1 = upq.address
GROUP BY u.address1";
$result = mysqli_query($con, $query);

// Check if the query was successful
if ($result) {
    // Initialize an empty array to store the data
    $data = array();

    // Fetch associative array from the result set
    while ($row = mysqli_fetch_assoc($result)) {
        // Add each row to the data array
        $data[] = $row;

        // Output the table rows
        echo '';
    }

    // Free the result set
    mysqli_free_result($result);

    // Close the MySQLi connection
    mysqli_close($con);

    // Now, $data contains the result set as an array
    // Print_r($data); // You can uncomment this line for debugging
} else {
    // Handle the case where the query failed
    echo "Error: " . mysqli_error($con);
}
?>
<!-- Script to create a bar chart -->
<script>
    // Extract data from PHP to JavaScript
    var locations = <?php echo json_encode(array_column($data, 'address1')); ?>;
    var totalQuantity = <?php echo json_encode(array_map('intval', array_column($data, 'total_quantity'))); ?>;
    var totalAmount = <?php echo json_encode(array_map('intval', array_column($data, 'total_amount'))); ?>;

    // Debugging: Log data to the console
    console.log('Locations:', locations);
    console.log('Total Quantity:', totalQuantity);
    console.log('Total Amount:', totalAmount);

    // Create a bar chart
    var ctx = document.createElement('canvas').getContext('2d');
    document.body.appendChild(ctx.canvas);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: locations,
            datasets: [{
                label: 'Total Amount',
                data: totalAmount,
                backgroundColor: 'rgba(227, 245, 66, 0.2)',
                borderColor: 'rgba(227, 245, 66, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            layout: {
                padding: {
                    left: 150 // Adjust the left padding as needed
                }
            }
        }
    });
</script>
<script>
    // Extract data from PHP to JavaScript
    var locations = <?php echo json_encode(array_column($data, 'address1')); ?>;
    var totalQuantity = <?php echo json_encode(array_map('intval', array_column($data, 'total_quantity'))); ?>;
    var totalAmount = <?php echo json_encode(array_map('intval', array_column($data, 'total_amount'))); ?>;

    // Debugging: Log data to the console
    console.log('Locations:', locations);
    console.log('Total Quantity:', totalQuantity);
    console.log('Total Amount:', totalAmount);

    // Create a bar chart
    var ctx = document.createElement('canvas').getContext('2d');
    document.body.appendChild(ctx.canvas);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: locations,
            datasets: [{
                label: 'Total Quantity',
                data: totalQuantity,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            layout: {
                padding: {
                    left: 170 // Adjust the left padding as needed
                }
            }
        }
    });
</script>


                <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>
              </div>
            </div>
          </div>
          
        </div>
      </div>
      <?php
include "footer.php";
?>