<?php
include('connection.php');

if (isset($_REQUEST['product_id']) && isset($_REQUEST['amount'])) {
    $price = 0;
    $id = $_REQUEST['product_id'];
    $amount = $_REQUEST['amount'];
    
    if ($amount < 1){
        echo "Must insert a valid quantity!";
    } else {
        if($id !== "" && $amount !== ""){
            // update amount in database
            $sql = "UPDATE shoppingList SET amount = '".$amount."' WHERE productID = '".$id."'";
            $result = $conn->query($sql);
    
            //get product price
            $sql = "SELECT price from products WHERE id = '".$id."'";
            $result = $conn->query($sql);
    
            while ($row = $result->fetch_assoc()) {
                $price = $row["price"];
            }
        }
    
        echo "".$price*$amount."€";
    }
}

if (isset($_REQUEST['total_price'])) {
    echo "TEST";
}
?>