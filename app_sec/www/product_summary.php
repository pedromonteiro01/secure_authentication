<?php 
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
	header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token"); 
	session_start();
    include('includes/header.php');
    include('connection.php');
    include('includes/navbar.php');

    $total = 0;

    $email = $_COOKIE["email"];

    $sql4 = "SELECT id FROM users WHERE email = '$email'";
    $result4 = $conn->query($sql4);
    while($row = $result4->fetch_assoc()){
        $uid = $row["id"];
    }

    function populateList() {
        global $conn, $total;

        $email = $_COOKIE["email"];
    
        $sql4 = "SELECT id FROM users WHERE email = '$email'";
        $result4 = $conn->query($sql4);
        while($row = $result4->fetch_assoc()){
            $uid = $row["id"];
        }

        $sql = "SELECT userID, productID, sum(amount) as amount FROM shoppingList WHERE userID = $uid GROUP BY userID, productID";
        $result = $conn->query($sql);

        while($row = $result->fetch_assoc()) {
            $productid = $row["productID"];
            $qnt = $row["amount"];

            $sql2 = "SELECT * FROM products WHERE id = $productid";
            $result2 = $conn->query($sql2);

            if ($result2->num_rows > 0) {
                while($row2 = $result2->fetch_assoc()) {
                    $total = $total + $qnt * $row2['price'];
                    echo '
                        <tr>
                        <td> <img width="60" src="'. $row2['image'] . '" alt=""/></td>
                        <td>'. $row2['title'] . '</td>
                        <td>
                            <div class="input-append"><input class="span1" onkeyup="updatePrices(' . $productid . ', this.value)" id="quantity' . $productid . '" name="quantity" style="max-width:34px" value="'. $qnt . '"</div>
                            </td>
                        <td id="price' . $productid . '">'. $qnt * $row2['price'] . '€</td>
                        </tr>
                        </tbody>
                    '; 
                    ?>
                    <?php
                }
                $_SESSION['total'] = $total;
            } else {
                echo "<div class='error'><p>0 results</p></div>";
            }
        }
    }

    function getUserBalance() {
        global $conn;

        $email = $_COOKIE["email"];

        $sql4 = "SELECT id FROM users WHERE email = '$email'";
        $result4 = $conn->query($sql4);
        while($row = $result4->fetch_assoc()){
            $uid = $row["id"];
        }

        $sql = "SELECT balance from users WHERE id = $uid";
        $result = $conn->query($sql);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $balance = $row["balance"];
                echo "<td class='label label-important' id='balance' style='display:block'> <strong> ".$balance." € </strong></td>";
            }
        }
        else {
            if (mysqli_error($conn)) {
                echo mysqli_error($conn);
            }
        }
    }

    function getProductPrice() {
        global $conn;


		if (TRUE) {
			$id = $_POST["id"];
			echo $id;
			$sql = "SELECT price from products WHERE id = '".$id."'";
			$result = $conn->query($sql);

			while ($row = $result->fetch_assoc()) {
				$ret = $row["price"];
			}

			return $ret;
		}

		return -1;
    }

	function test() {
		if (!empty($_POST["id"])) {
			$id = $_POST["id"];
			echo $id;
		} else {
			echo -3;
		}
	}
?>
<div id="mainBody">
    <div class="container">
    <div class="row">
<!-- Sidebar ================================================== -->
<?php include("includes/sidebar.php") ?>
<!-- Sidebar end=============================================== -->
<?php
?>
    <div class="span9">
    <ul class="breadcrumb">
        <li><a href="index.php">Home</a> <span class="divider">/</span></li>
        <li class="active"> SHOPPING CART</li>
    </ul>
    <h3>  SHOPPING CART<a href="products.php" class="btn btn-large pull-right"><i class="icon-arrow-left"></i> Continue Shopping </a></h3>    
    <hr class="soft"/>    
    <form method="POST">    
        <table id="shoppingList" class="table table-bordered">
                <thead>
                    <tr>
                    <th>Product</th>
                    <th>Description</th>
                    <th>Quantity/Update</th>
                    <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                <?php populateList() ?>
                    <tr>
                    <td colspan="6" style="text-align:right"><strong>BALANCE </strong></td>
                    <?php getUserBalance() ?>
                    <td colspan="6" style="text-align:right"><strong>TOTAL </strong></td>
                    <td class="label label-important" id="total_price" style="display:block"> <strong> <?php echo "$total €" ?> </strong></td>
                    </tr>
                </table>
        <a href="products.php" class="btn btn-large"><i class="icon-arrow-left"></i> Continue Shopping </a>
        <button type="submit" name="btnBuy" class="btn btn-large pull-right">Buy</button>
    </form>

    <?php
                if (isset($_POST['btnBuy'])) {
                    $q = $_POST['quantity'];
                    $sql = "DELETE FROM shoppingList WHERE userID = $uid";
                    $sql2 = "SELECT balance from users WHERE id = $uid";
                    $result2 = $conn->query($sql2);
                    if ($result2) {
                        while ($row = $result2->fetch_assoc()) {
                            $balance = $row["balance"];
                        }
                    }
                    else {
                        if (mysqli_error($conn)) {
                            echo mysqli_error($conn);
                        }
                    }
                    if ($balance > $total){
                        $balance = $balance - ($total);
                        $result = $conn->query($sql);
                        $sql3 = "UPDATE users SET balance = $balance WHERE id = $uid";
                        $result3 = $conn->query($sql3);
                        if ($result){
                            echo "Products deleted!";
                        }
                        else{
                            echo "Erro" . $sql . "<br>" . $conn->error;
                        }
                        if ($result3){
                            echo "Balance updated!";
                        }
                        else{
                            echo "Erro" . $sql . "<br>" . $conn->error;
                        }
                    }
                }
            ?>

    
    
</div>
</div></div>
</div>
<!-- MainBody End ============================= -->
<!-- Footer ================================================================== -->
<?php include('includes/footer.php') ?>
<script type="text/javascript">
    function updatePrices(id, amount) {
		if (amount != "") {
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function(){
				if(this.readyState == 4 && this.status == 200){
					document.getElementById("price"+id).innerHTML = this.responseText;

					var table = document.getElementById("shoppingList");
					var total_price = 0;
					for (var i = 1; i < table.rows.length -1; i++) {
						total_price = total_price + parseInt(table.rows[i].cells[3].innerHTML.replace('€',''));
					}
					document.getElementById("total_price").innerHTML = total_price+"€";
				}
			}
			xmlhttp.open("GET", "requests.php?product_id="+id+"&amount="+amount, true);
			xmlhttp.send();
		}
	}
</script>