<?php 
	include('includes/header.php');
	include('connection.php'); 
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
        $sql2 = "SELECT * FROM products";
        $result2 = $conn->query($sql2);
        echo '<h3> Products Name <small class="pull-right"> ' . $result2->num_rows . ' products are available</small></h3>'
    ?>
	<hr class="soft"/>
<div id="myTab" class="pull-right">
</div>
<br class="clr"/>
<div class="tab-content">
		<div class="tab-pane  active" id="blockView">
		<ul class="thumbnails">
		<?php	
			$sql = "SELECT * FROM products";
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
			// output data of each row
				while($row = $result->fetch_assoc()) {
					echo '
				<li class="span3">
				<form method="POST" action="product_summary.php">
					<div class="thumbnail card">
						<a href="product_details.php?id=' . $row["id"] . '"><img src="' . $row["image"] . '" alt=""/></a>
						<div class="caption">
						<h5>' . $row["title"] . '</h5>
						<p><?php echo $post["paragraph"] ?></p>
						<h4 style="text-align:center" ' . $row["price"] . '&euro;</h4>
						<h4 style="text-align:center"><a>' . $row["price"] . '&euro;</a></h4>
						</div>
					</div>
				</form>
				</li>
				';
				
				if(isset($_POST['buy-one-product']))	{
					$userId = $_COOKIE['loggedIn'];
					$qt = 1;
					$id = $row['id'];
					$sql2 = "INSERT INTO shoppingList VALUES($userId, $id,$qt)";
					if ($conn->query($sql2) === TRUE) {
						echo "New record created successfully";
					  } else {
						echo "Error: " . $sql . "<br>" . $conn->error;
					  }
				}
				?>
				<?php }
					} else {
						echo "<div class='error'><p>0 results</p></div>";
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
<?php include('includes/footer.php');?> 