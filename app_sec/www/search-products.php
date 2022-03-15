<?php 
    include('includes/header.php');
    include('connection.php'); 

    $search =  mysqli_real_escape_string($conn, htmlentities($_POST["input"]));
?> 

<!-- Navbar ================================================== -->
<?php 
    include('includes/navbar.php');
?> 
<!-- Header End====================================================================== -->
<div id="mainBody">
    <div class="container">
    <div class="row">
<!-- Sidebar ================================================== -->
<?php 
    include('includes/sidebar.php');
?> 
<!-- Sidebar end=============================================== -->
    <div class="span9">
    <ul class="breadcrumb">
        <li><a href="index.php">Home</a> <span class="divider">/</span></li>
        <li class="active">Products Name</li>
    </ul>
    <?php
        $sql2 = "SELECT * FROM products WHERE title LIKE '%$search%'";
        $result2 = $conn->query($sql2);
        if (!empty($result2) && $result2->num_rows > 0) {
            echo '<h3> Products Name <small class="pull-right"> ' . $result2->num_rows . ' products are available for "' . $search . '" </small></h3>';
        } else {
            if (mysqli_error($conn)) {
                echo '<h3> Products Name <small class="pull-right">"' . mysqli_error($conn) . '" </small></h3>';
            } else {
                echo '<h3> Products Name <small class="pull-right"> 0 products are available for "' . $search . '" </small></h3>';
            }
        }
    ?>
    <hr class="soft"/>
      
<div id="myTab" class="pull-right">
</div>
<br class="clr"/>
<div class="tab-content">
        <div class="tab-pane  active" id="blockView">
        <ul class="thumbnails">
        <?php    
            if ($search!=''){
                $sql = "SELECT * FROM products WHERE title LIKE '%$search%'";
                $result = $conn->query($sql);

                if (!empty($result) && $result->num_rows > 0) {
                // output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo '
                    <li class="span3">
                    <div class="thumbnail card">
                        <a href="product_details.php?id=' . $row["id"] . '"><img src="' . $row["image"] . '" alt=""/></a>
                        <div class="caption">
                        <h5>' . $row["title"] . '</h5>
                        <p><?php echo $post["paragraph"] ?></p>
                        <h4 style="text-align:center"><a>' . $row["price"] . '&euro;</a></h4>
                        </div>
                    </div>
                    </li>
                    ';
                    }
                }
                else {
                    echo "<div class='error'><p>0 results</p></div>";
                }
            }
            else{
                echo '<div class="error"><p>Invalid input!</p></div>';
            }        
                    ?>
        </ul>
    <hr class="soft"/>
    </div>
</div>
            <br class="clr"/>
</div>
</div>
</div>
</div>
<!-- MainBody End ============================= -->
<!-- Footer ================================================================== -->
<?php 
    include('includes/footer.php');
?>