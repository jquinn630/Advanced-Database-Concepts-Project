<?php session_start();?>

<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>

	<!-- Basic Page Needs
  ================================================== -->
	<meta charset="utf-8">
	<title>La Bonne Bouffe | Search Recipes and Plan Your Meals with Friends!</title>
	<meta name="description" content="La Bonne Bouffe is a recipe planning and sharing site.">
	<meta name="author" content="">

	<!-- Mobile Specific Metas
  ================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- CSS
  ================================================== -->
	<link rel="stylesheet" href="stylesheets/base.css">
	<link rel="stylesheet" href="stylesheets/skeleton.css">
	<link rel="stylesheet" href="stylesheets/layout.css">
    	<link rel="stylesheet" href="stylesheets/flexslider.css">
    	<link rel="stylesheet" href="stylesheets/prettyPhoto.css">
   	<link rel="stylesheet" href="js/jquery/css/pepper-grinder/jquery-ui-1.10.4.custom.css">
 
    <!-- CSS
  ================================================== -->
 	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
    	<script src="js/jquery.flexslider-min.js"></script>
   <!-- 	<script src="js/scripts.js"></script> -->
    <script src="js/jquery/js/jquery-1.11.0.js"></script>
   	<script src="js/jquery/js/jquery-ui-1.10.4.custom.js"></script>
	<script src="js/login.js" type="javascript"></script>
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- Favicons
	================================================== -->
	<link rel="shortcut icon" href="images/favicon.ico">
	<link rel="apple-touch-icon" href="images/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">

</head>
<body class="wrap">



	<!-- Primary Page Layout
	================================================== -->

	<header id="header" class="site-header" role="banner">
    <div id="header-inner" class="container sixteen columns over">
    <hgroup class="one-third column alpha">
    <h1 id="site-title" class="site-title">
    <a href="index.php" id="logo"><img src="images/logo.png" alt="La Bonne Bouffe logo" height="80" width="250" /></a>
    </h1>
    </hgroup>
     <div id="dialog">


    </header>


	<div class="container contentblock">

 
	<div class="sixteen columns">
        
        <div class="flex-container">

        </div> <!-- End of flex slider -->

<div class="tagline">
        <p>Welcome to <strong>La Bonne Boufe</strong>, a social recipe site.<br />
        Simple. Minimal. Easy-to-use.</p>
        </div>

		</div><!-- container -->
        
        <hr>
   <?php
  require_once('dbtest.php');

    if (array_key_exists('uname', $_POST))  {
        if ($_POST['fname']=='') {
    echo("Error: You must provide your first name.");
  } else if ($_POST['lname'] =='') {
    echo("Error: You must provide your last name.");
  } else if ($_POST['email'] == '') {
    echo("Error: You must provide a valid email address.");
  } else if ($_POST['password'] == '') {
    echo("Error: You must provide a password for your account.");
  } else if ($_POST['password'] != $_POST['pwd-check']) {
    echo("Error: Passwords do not match.");
  } else {

    $uname=trim($_POST['uname']);

    $checkUname = oci_parse($conn, "select recipepack.username_exists(:uname) from dual");
    oci_bind_by_name($checkUname, ":uname", $uname);
    oci_execute($checkUname);
    $info=oci_fetch_row($checkUname);

    if ($info[0]==1)
    {
      echo "Username is already taken.  Please choose another.";
    }
    else
    {
    $statement = "BEGIN recipepack.create_account(:f, :l, :u, :p, :e, :uid); END;";
    $register = oci_parse($conn, $statement);
    oci_bind_by_name($register,":f",$_POST['fname']);
    oci_bind_by_name($register,":l",$_POST['lname']);
    oci_bind_by_name($register,":u",$uname);
    oci_bind_by_name($register,":e",$_POST['email']);
    oci_bind_by_name($register,":p",$_POST['password']);
    oci_bind_by_name($register,":uid",$_SESSION['uid'],8);
    if (!@oci_execute($register)) {
      $err = oci_error($register);
      echo $err['message'];
    } else {
      //echo "User account successfully created.";
      $USER_ID = $_POST['uid'];
     echo "<script type='text/javascript'>";
     echo "window.location = 'http://csevm05.crc.nd.edu:8405/user.php';";
     echo "</script>";
    }
  }
}
  


    }
?>

        
        
        <section class="container">
        
        <article id="photo-item-1" class="feature-column one-third column">
        <div class="featured-image img-wrapper">
        <div class="overlay zoom"></div>
        </a>
        </div>

    <form action="register.php" name="register" method="post">
        <table>
          <tr><td>Username: <input type="text" name="uname" id="uname"><br></tr></td>
          <tr><td>First Name: <input type="text" name="fname" id="fname"><br>
          </td></tr><tr><td>Last Name: <input type="text" name="lname" id="lname"><br>
          </td></tr><tr><td>Email: <input type="text" name="email" id="email"><br>
          </td></tr><tr><td>Password: <input type="password" name="password" id="pwd"><br>
          </td></tr><tr><td>Confirm Password: <input type="password" name="pwd-check" id="pwd"><br>
        </td></tr></table><input type="submit" value="Create User">
        <input type="button" value="Cancel">
    </form>        

     
        </article>
        

       
	</section><!-- container -->
    
     <hr>
     
     <section class="container recent-posts">
     
  

     
     <aside class="eight columns clear">
     
     <article class="eight columns alpha">
     
     <div class="two columns alpha">
     <div class="featured-image img-wrapper">
     </div>
     </div>
     

     
     </article>
     
     </aside>
     
     <aside class="eight columns">
     
     
     </aside>
     
     </section>
    
    </div>

<footer>

<div class="footer-inner container">








</div>

<div id="footer-base">
<div class="container">
<div class="eight columns">
<a href="http://www.opendesigns.org/design/icebrrrg/">Icebrrg Website Template</a> &copy; 2012
</div>

<div class="eight columns far-edge">
Design by <a href="http://www.opendesigns.org">OD</a>
</div>
</div>
</div>

</footer>

<!-- End Document
================================================== -->

<script src="js/jquery.prettyPhoto