<?php 
	include('includes/header.php');
	include('connection.php'); 
?> 

<!-- Header End====================================================================== -->
<div id="mainBody">
	<div class="container">
	<div class="row">
<!-- Sidebar end=============================================== -->
	<div class="span9">
    <ul class="breadcrumb">
		<li><a href="index.php">Home</a> <span class="divider">/</span></li>
		<li class="active">Login</li>
    </ul>
	<h3> Login</h3>	
	<hr class="soft"/>

    <h4> Congratulations! You are logged in the website!</h4>
    <form method="POST">
        <?php
        if (isset($_POST['btnLogOut'])) {
            //setcookie("loggedIn",'', time()-3600, '/');
            echo "<script type=\"text/javascript\"> 
                        window.localStorage.setItem('loggedIn', 1);
                        window.location.href = '/';
                    </script>";
        }
        ?>
        <div class="controls">
            <button type="submit" name="btnLogOut" class="btn block" onclick="setCoockie('email', <?php echo $_GET['logged'];?>, 10)">Home</button>
        </div>
    </div>
    </form>
    <script>
        function setCoockie(name, value, days){
            var expires;
            var days=10;
            
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (10 * 24 * 60 * 60 * 1000));
                expires = '; expires=' + date.toGMTString();
            }
            else {
                expires = '';
            }
            
            document.cookie = escape(name) + "=" + 
                escape(value) + expires + "; path=/";
        }
    </script>
</div>
</div>
<!-- MainBody End ============================= -->
<?php include('includes/footer.php') ?>