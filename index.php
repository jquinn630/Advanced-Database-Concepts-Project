<?php 
session_start();
session_unset();
session_destroy();
?>

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
	<script src="js/jquery.prettyPhoto.js"></script>
   	<script src="js/scripts.js"></script>
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
    <a href="index.php" id="logo"><img src="images/logo.png" alt="La Bonne Bouffe logo" height="100" width="350" /></a>
    </h1>
    </hgroup>

    </header>
	<div class="container contentblock">

 
	<div class="sixteen columns">
        
        <div class="flex-container">
        <div class="flexslider">
  	  <ul class="slides" style="width = %55;">
    	    <li>
    		<a href="#"><img src="images/pie.jpg" alt="Search for recipes." /></a>
      		<div class="flex-caption">
		   <h5><a href="#">Search for recipes</a></h5>
		     <p>Enter any ingredient or recipe keyword to find links to amazing recipes.</p>	   	       </div>
    	    </li>
    	    <li>
      		<a href="#"><img src="images/sitead2.jpg" alt="Share with friends." /></a>
      		<div class="flex-caption" style="display:none;">
		   <h5><a href="#">Share with friends</a></h5>
		      <p>Easily follow what your friends are making and share recipes. Try planning a meal together!</p></div>
    	    </li>
    	    <li>
      		<a href="#"><img src="images/produce.jpg" alt="Plan for the week." /></a>
      		<div class="flex-caption" style="display:none;">
		   <h5><a href="#">Plan ahead</a></h5>
		      <p>Plan your meals on a calendar and generate a shopping list for each week.</p>		       </div>
    	    </li>
  	  </ul>
	</div>
        </div> <!-- End of flex slider -->

<div class="tagline">
        <p>Welcome to <strong>La Bonne Boufe</strong>, a social recipe site.<br />
        Simple. Minimal. Easy-to-use.</p>
        </div>

		</div><!-- container -->
        
        <hr>
        
        
        
        
        <section class="container">

        
       
	</section><!-- container -->
    
     
     <section class="container recent-posts">
     
     <aside class="eight columns">

     
     <article class="eight columns alpha">
     

     

     
     </article>
     
     </aside>

     
     <aside class="eight columns clear">
     
     <article class="eight columns alpha">
     

     
     </article>
     
     </aside>
     
     <hgroup class="two-thirds column omega userbox">
    <form action="user.php" method="post">
    <table><tr><td>Username</td><td>Password</td></tr>
           <tr><td><input type="text" name="uname"></td>
           <td><input type="password" name="pwd"></td></tr></table>
    <input type="submit" value="Login">
    </form><span> | </span> 
    <a href="register.php" id="dialog-link">Register</a>


     </hgroup>
     
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

<script src="js/jquery.prettyPhoto.js"></script>
</b
