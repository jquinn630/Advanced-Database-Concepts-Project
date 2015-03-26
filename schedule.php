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
	<title>La Bonne Bouffe | View Recipe</title>
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
    	<script src="js/scripts.js"></script>
   	<script src="js/jquery-ui-1.10.4.custom.js"></script>
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
<!--Old Navigation
    <nav id="main-nav" class="two thirds column omega">
    <ul id="main-nav-menu" class="nav-menu">
    <li id="menu-item-1" class="current">
    <a href="index.html">Home</a>
    </li>
    <li id="menu-item-2">
    <a href="profile.php">Profile</a>
    </li>
    <li id="menu-item-3">
    <a href="calendar.php">Calendar</a>
    </li>
    <li id="menu-item-4">
    <a href="friends.php">Friends</a>
    </li>
    </ul>
    </nav>
-->
    </div>
    </div>
    </header>

<div id="footer-base">
<div class="container">
<div class="eight columns">
<a href="user.php">Your Home</a>
<a href="schedule.php">My Meal Plan</a>
<a href="search.php">Find More Recipes</a>
<a href="usersearch.php">Find Friends</a>

</div>
<div class="eight columns far-edge">
<a href="createrecipe.php">Add Your Own Recipe</a>
<a href="index.php">Logout</a>
</div>
</div>
</div>

<div class="container contentblock">


<?php
    require_once('dbtest.php');

    if (!array_key_exists('uid', $_SESSION))
    {
        echo ('<b> Error: Please login </b>');
    }
    else
    {
        echo ('<h1> Your Meal Plan </h1>');
    }
?>
    
    <link rel="stylesheet" href="calendarview/stylesheets/calendarview.css">
    <style>
      body {
        font-family: Trebuchet MS;
      }
      div.calendar {
        max-width: 240px;
        margin-left: auto;
        margin-right: auto;
      }
      div.calendar table {
        width: 100%;
      }
      div.dateField {
        width: 140px;
        padding: 6px;
        -webkit-border-radius: 6px;
        -moz-border-radius: 6px;
        color: #555;
        background-color: white;
        margin-left: auto;
        margin-right: auto;
        text-align: center;
      }
    </style>


    <script src="calendarview/javascripts/prototype.js"></script>
    <script src="calendarview/javascripts/calendarview.js"></script>
    <script>
    function setupCalendars() {
        // Embedded Calendar
        Calendar.setup(
          {
            dateField: 'embeddedDateField',
            parentElement: 'embeddedCalendar',
            recipeData:'recipeData'
          }
        )
      }

      Event.observe(window, 'load', function() { setupCalendars() })

      function genShoppingList() {
        var startDate = document.getElementById('startDate').value;
        var endDate = document.getElementById('endDate').value;

        if(window.XMLHttpRequest) 
          {
            xmlhttp= new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
              if(xmlhttp.readyState==4 && xmlhttp.status==200){
                document.getElementById("currentList").innerHTML=xmlhttp.responseText;
              }
            }
            xmlhttp.open("GET", "shoplist.php?s="+startDate.toString()+"&e="+endDate.toString(), true);
            xmlhttp.send();
          }
      }

      function removeMeal(meal_id) {
          var vars = "mealid="+meal_id;
          if(window.XMLHttpRequest) 
          {
            xmlhttp= new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
              if(xmlhttp.readyState==4 && xmlhttp.status==200){
                document.getElementById("responsePhp").innerHTML=xmlhttp.responseText;
                document.getElementById("recipeData").innerHTML="";
              }
            }
            xmlhttp.open("POST", "removemeal.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send(vars);
          }
      }

    </script>
  </head>
  <body>

    <div style="float: left; width: 60%">
      <div style="background-color: #efefef; padding: 10px; -webkit-border-radius: 12px; -moz-border-radius: 12px; margin-right: 10px">
        <div id="calendarBox" style="">
          <div id="embeddedCalendar" style="margin-left: auto; margin-right: auto">
          </div>
          <br />
          <br />
          <h3> Shopping List </h3>
          <p> Select a date range. </p>
          <form >
            <input id="startDate" type="date"> to
            <input id="endDate" type="date">
            <input type="button" value="Generate List" onclick="genShoppingList()">
          </form> </div>
          <div id='currentList' >
           
          </div>
      </div>
    </div>

<div class="one-third column"> 

<h1> Daily Schedule </h1>
<div id="responsePhp"> </div>
<div id="embeddedDateField" >
            Select a date on the widget to your left.
          </div>

<div id="recipeData"> </div>

</div>

</div> <!-- End of container block-->


<footer>

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
</body>
</html>

