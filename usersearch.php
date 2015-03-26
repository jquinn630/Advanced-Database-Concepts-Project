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
<div id="searchBox" style="background-color: #efefef; padding: 10px; -webkit-border-radius: 12px; -moz-border-radius: 12px; margin-right: 10px; margin-left: 10px; border:1px solid; box-shadow: 3px 3px 3px #888888;">

<hr>
<h3> User Search </h3>
Search for users.
<form method='get' acition='search.php'>
    First Name: <input type='search' name='fname'> 
    Last Name: <input type='search' name='lname'>
    User Name: <input type='search' name='uname'>
    Email: <input type='search' name='email'>
    <input type='submit' value='Find Users'  name='usersearch'>
</form>


</div>

<?php
    require_once('dbtest.php');

    if (array_key_exists('addbox', $_POST))
    {
        $addbox=oci_parse($conn, "BEGIN recipepack.follow_user(:uid, :fid); END;");
        oci_bind_by_name($addbox, ":uid", $_SESSION['uid']);
        oci_bind_by_name($addbox, ":fid", $_POST['fid']);

        if (!@oci_execute($addbox))  
        {
            $err = oci_error($addbox);
            echo $err['message'];
        }

        else echo "<strong> User followed. </strong>";

        oci_free_statement($addbox);
    }

    if (array_key_exists('usersearch', $_GET))
    {
        echo "<hr><h1> Results </h1>";  

        $cleanfname=htmlentities(trim($_GET['fname']), ENT_QUOTES);

        $cleanlname=htmlentities(trim($_GET['lname']), ENT_QUOTES);
        $cleanuname=htmlentities(trim($_GET['uname']), ENT_QUOTES);
        $cleanemail=htmlentities(trim($_GET['email']), ENT_QUOTES);
        $curs = oci_new_cursor($conn);
        $query = oci_parse($conn, "BEGIN recipepack.search_users_by_query(:fname,:lname,:uname,:email,:res); END;");
        oci_bind_by_name($query, ":fname", $cleanfname);
        oci_bind_by_name($query, ":lname", $cleanlname);
        oci_bind_by_name($query, ":uname", $cleanuname);
        oci_bind_by_name($query, ":email", $cleanemail);
        oci_bind_by_name($query, ':res', $curs, -1, OCI_B_CURSOR);

        if (!@oci_execute($query))  
        {
            $err = oci_error($query);
            echo $err['message'];
        }

        if (!@oci_execute($curs))  
        {
            $err = oci_error($curs);
            echo $err['message'];
        }

        $displayString="<table align=center bgcolor=#f8f5ef cellpadding=2 cellspacing=10 ><th> Name </th> <th> User Name </th><th> Email </th> <th> Description </th> <th> Follow? </th>";
        $numr=0;

        while($info=oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS))
        {       
                ++$numr;
                $displayString .= "<tr><td><a href=user.php?uid=".$info['USER_ID']." >".$info['FIRST_NAME']." ".$info['LAST_NAME']."</a></td>
                      <td>".$info['USERNAME']."</td><td>".$info['EMAIL']."</td><td>".$info['DESCRIPTION'].$stuff."</td><td><form method='post' action='usersearch.php'>
                          <input type=hidden name='fid' value='".$info["USER_ID"]."'> <input type=submit value='Follow' name='addbox'></form></td></tr>";
        } 

        $displayString.="</table>";

        if ($numr==0) echo "<strong> No results found. </strong>";
        else echo $displayString;

        oci_free_statement($query);
        oci_free_statement($curs);

    }

?>

</div>
</div>

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
<