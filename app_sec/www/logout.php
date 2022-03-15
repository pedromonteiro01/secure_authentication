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
    <ul class="breadcrumb">
		<li><a href="index.php">Home</a> <span class="divider">/</span></li>
		<li class="active">Login</li>
    </ul>
	<h3> Logout</h3>	
	<hr class="soft"/>

    <h4> Do you want to end your session? </h4>
    <form method="POST">
        <?php
        if (isset($_POST['btnLogOut'])) {
            setcookie("loggedIn",'', time()-3600, '/');
            echo "<script type=\"text/javascript\"> 
                        window.localStorage.removeItem('loggedIn');
                        window.location.href = '/';
                    </script>";
        }
        ?>
        <div class="controls">
            <button type="submit" name="btnLogOut" class="btn block">Logout</button>
        </div>
    </div>
    </form>
	
</div>
</div></div>
</div>
<!-- MainBody End ============================= -->
<?php include('includes/footer.php') ?>