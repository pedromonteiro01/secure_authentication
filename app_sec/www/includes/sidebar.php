<style>
	.well-small:hover{
		background-color: #ccc;
		cursor: pointer;
	}
</style>
<div id="sidebar" class="span3">
		<div id="shop" class="well well-small buy" style="text-align: center; "><a id="myCart" href="product_summary.php" style="font-size: 17px;"><img src="themes/images/ico-cart.png" alt="cart" style="margin: 0; padding: 0; margin-left: 10px;">SHOPPING CART</a></div>
		<div id="msg" class="well well-small" style="text-align: center; "><a id="mymsg" href="login.php" style="font-size: 17px;">LOGIN REQUIRED TO PURCHASE OFFERS</a></div>
		<script type=text/javascript> 
			if (localStorage.getItem('loggedIn')==1){
				document.getElementById("shop").style.display = "visible";
				document.getElementById("msg").style.display = "none";
			}
			else{
				document.getElementById("msg").style.display= "visible";
				document.getElementById("shop").style.display = "none";
			}
		</script>
		<br/>
		  <div class="thumbnail">
			<img src="themes/images/products/panasonic.jpg" alt="Bootshop panasonoc New camera"/>
			<div class="caption">
			  <h5>Panasonic</h5>
				<h4 style="text-align:center"><a class="btn" href="product_details.php"> <i class="icon-zoom-in"></i></a> <a class="btn" href="#">Add to <i class="icon-shopping-cart"></i></a> <a class="btn btn-primary" href="#">$222.00</a></h4>
			</div>
		  </div><br/>
			<div class="thumbnail">
				<img src="themes/images/products/kindle.png" title="Bootshop New Kindel" alt="Bootshop Kindel">
				<div class="caption">
				  <h5>Kindle</h5>
				    <h4 style="text-align:center"><a class="btn" href="product_details.php"> <i class="icon-zoom-in"></i></a> <a class="btn" href="#">Add to <i class="icon-shopping-cart"></i></a> <a class="btn btn-primary" href="#">$222.00</a></h4>
				</div>
			  </div><br/>
			<div class="thumbnail">
				<img src="themes/images/payment_methods.png" title="Bootshop Payment Methods" alt="Payments Methods">
				<div class="caption">
				  <h5>Payment Methods</h5>
				</div>
			  </div>
	</div>