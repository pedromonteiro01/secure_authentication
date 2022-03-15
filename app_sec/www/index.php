<?php 
	include('includes/header.php'); 
	include('connection.php'); 	
?>
<!-- Navbar ================================================== -->
<?php include('includes/navbar.php') ?>
<!-- Header End====================================================================== -->
<div id="mainBody">
	<div class="container">
	<div class="row">
<!-- Sidebar ================================================== -->
<?php include('includes/sidebar.php') ?>
<!-- Sidebar end=============================================== -->
		<div class="span9">		
		<h4>Latest Products </h4>
			  <ul class="thumbnails">
			  <?php	
			$sql = "SELECT id, title, image, price FROM products ORDER BY id DESC LIMIT 3";
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
			// output data of each row
				while($row = $result->fetch_assoc()) {
					echo '
					<li class="span3">
					<div class="thumbnail card">
					  <a  href="product_details.php?id='.$row["id"].'"><img src="' . $row["image"] . '" alt=""/></a>
					  <div class="caption">
						<h5>'. $row["title"] . '</h5>
					   
						<h4 style="text-align:center"><a>' . $row["price"] . '&euro;</a></h4>
					  </div>
					</div>
				  </li>
				'?>
				<?php }
					} else {
						echo "0 results";
					}
				?>
			  </ul>	

		</div>
		</div>
	</div>
</div>
<?php include('includes/footer.php') ?>