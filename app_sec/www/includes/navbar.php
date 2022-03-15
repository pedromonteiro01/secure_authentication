<div id="logoArea" class="navbar">
<a id="smallScreen" data-target="#topMenu" data-toggle="collapse" class="btn btn-navbar">
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
</a>
  <div class="navbar-inner">
    <a class="brand" href="index.php"><img src="themes/images/ua-logo.png" alt="Bootsshop" width="135px" /></a>
		<form class="form-inline navbar-search" method="post" action="search-products.php">
		<input name="input" id="srchFld" class="srchTxt" type="text" placeholder="Search"/>
		  <button name="search" type="submit" id="submitButton" class="btn btn-primary">Go</button>
    </form>
    <ul id="topMenu" class="nav pull-right">
	<li class=""><a href="products.php">Products</a></li>
	 <li class=""><a href="contact.php">Contact</a></li>
	 <li class=""><a href="special_offer.php">Team</a></li>
	 <li class="">
	 <a hidden id="logout" href="logout.php" role="button" style="padding-right:0"><span hidden class="btn btn-large btn-danger">Logout</span></a>
	 <a id="login" href="login.php" role="button" style="padding-right:0"><span class="btn btn-large btn-success">Login</span></a>
	 <script type=text/javascript> 
			if (localStorage.getItem('loggedIn')==1){
				document.getElementById("logout").style.display = "visible";
				document.getElementById("login").style.display = "none";
			}
			else{
				document.getElementById("login").style.display= "visible";
				document.getElementById("logout").style.display = "none";
				window.localStorage.removeItem("loggedIn");
			}
	</script>
	<div id="login" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
	</li>
    </ul>
  </div>
</div>
</div>
</div>
