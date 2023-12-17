
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
      <div class="content">
        <div class="container-fluid">
          <!-- your content here -->
          <div class="col-md-14">
            <div class="card ">
              <div class="card-header card-header-primary">
                <h4 class="card-title">Data Jumlah User Berdasarkan Lokasi <?php echo $page;?> </h4>
              </div>
              <div class="card-body">
                <div class="table-responsive ps">
                  <table class="table table-hover tablesorter " id="">
                    <tbody>
<table>
  <tr>
    <th>Data Jumlah User Berdasarkan Lokasi</th>
  </tr>
</table>

<div class="table-responsive">
    <table class="table table-hover">
        <thead class="text-primary">
            <tr>
                <th>Lokasi</th>
                <th>Banyak User</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT u.address1, COUNT(u.address1) AS total_banyakuser
            FROM user_info u
            GROUP BY u.address1";
            $result = mysqli_query($con, $query);

            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>
                        <td>' . $row['address1'] . '</td>
                        <td>' . $row['total_banyakuser'] . '</td>
                      </tr>';
            }
            ?>
        </tbody>
    </table>
</div>
                    </tbody>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Include Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <?php
// Your database connection code here

$query = "SELECT u.address1, COUNT(u.address1) AS total_banyakuser
FROM user_info u
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
    var totalQuantity = <?php echo json_encode(array_map('intval', array_column($data, 'total_banyakuser'))); ?>;
    var totalAmount = <?php echo json_encode(array_map('intval', array_column($data, 'total_banyakuser'))); ?>;

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


                <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>
              </div>
            </div>
          </div>
          
        </div>
      </div>
      <?php
include "footer.php";
?>