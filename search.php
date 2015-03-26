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

<h3> Recipe Search </h3>
Search for recipes in our database.
<?php
echo "<form method='get' action='search.php'>";
   echo "Recipe Name: <input name='rname' value='".$_GET['rname']."' type='search'>";
    echo "Total Time Range: <input name='minttime' value='".$_GET['minttime']."' type='number'> to <input name='maxttime' value='".$_GET['maxttime']."' type='number'>";
    echo "<input type=submit name='recwordsearch' value='Find Recipes'>";
echo "</form>";
?>
<hr>
<h3> Ingredients Search </h3>
Search for ingredients in our database.
<form method='get' acition='search.php'>
    Ingredient Name: <input type='search' name='ingname' 
<?php
    echo ' value="'.$_GET['ingname'].'" >';
?>
    <input type='submit' value='Find Ingredients'  name='ingrsearch'>
</form>

<hr><h3> What can I make with what I have?</h3>
Find recipes based on what you have in your Ingredients box.
<form method='get' action='search.php'>
    <input type=submit value='Find Recipes' name='userrecsearch'> 
</form>
</div>

<?php
    require_once('dbtest.php');

    if (array_key_exists('addbox', $_POST))
    {
        $qty=1;
        $addbox=oci_parse($conn, "BEGIN recipepack.add_ingredient_to_box(:uid, :iid, :qty); END;");
        oci_bind_by_name($addbox, ":uid", $_SESSION['uid']);
        oci_bind_by_name($addbox, ":iid", $_POST['iid']);
        oci_bind_by_name($addbox, ":qty", $qty);

        if (!@oci_execute($addbox))  
        {
            $err = oci_error($addbox);
            echo $err['message'];
        }

        else echo "<strong> Ingredient added to box. </strong>";

        oci_free_statement($addbox);
    }

    if (array_key_exists('recwordsearch', $_GET))
    {
        echo "<hr><h1> Results </h1>";

        $rname=htmlentities(trim($_GET['rname']), ENT_QUOTES);
        $mintime=htmlentities(trim($_GET['minttime']), ENT_QUOTES);
        $maxtime=htmlentities(trim($_GET['maxttime']), ENT_QUOTES);

        if($maxtime=='') $maxtime=0;
        if($mintime=='') $mintime=0;

        $curs = oci_new_cursor($conn);
        $query = oci_parse($conn, "BEGIN recipepack.search_recipes_by_query(:rname, :mintime, :maxtime, :res); END;");
        oci_bind_by_name($query, ":rname", $rname);
        oci_bind_by_name($query, ":mintime", $mintime);
        oci_bind_by_name($query, ":maxtime", $maxtime);
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

        $displayString="<table align=center bgcolor=#f8f5ef cellpadding=2 cellspacing=10 ><th> Recipe Name </th> <th> Cook Time (min) </th><th> Serves </th>";
        $numr=0;

        while($info=oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS))
        {       
                ++$numr;
                $displayString .= "<tr><td><a href=recipe.php?rid=".$info['RECIPE_ID']." >".$info['RECIPE_NAME']."</a></td>
                      <td>".$info['COOK_TIME']."</td><td>".$info['SERVING_NUMBER']."</tr>";
        } 

        $displayString.="</table>";

        if ($numr==0) echo "<strong> No results found. </strong>";
        else echo $displayString;

        oci_free_statement($query);
        oci_free_statement($curs);
    }

    if (array_key_exists('userrecsearch', $_GET))
    {
        echo "<hr><h1> Results </h1>";   

        $curs = oci_new_cursor($conn);
        $query = oci_parse($conn, "BEGIN recipepack.search_recipes_by_ingredients(:uid, :res); END;");
        oci_bind_by_name($query, ":uid", $_SESSION['uid']);
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

        $displayString="<table align=center bgcolor=#f8f5ef cellpadding=2 cellspacing=10 ><th> Recipe Name </th> <th> Cook Time (min) </th><th> Serves </th>";
        $numr=0;

        while($info=oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS))
        {       
                ++$numr;
                $displayString .= "<tr><td><a href=recipe.php?rid=".$info['RECIPE_ID']." >".$info['RECIPE_NAME']."</a></td>
                      <td>".$info['COOK_TIME']."</td><td>".$info['SERVING_NUMBER']."</tr>";
        } 

        $displayString.="</table>";

        if ($numr==0) echo "<strong> No results found. </strong>";
        else echo $displayString;

        oci_free_statement($query);
        oci_free_statement($curs);
    }

    if (array_key_exists('ingrsearch', $_GET))
    {
        echo "<hr><h1> Results </h1>";  

        $cleantext=htmlentities(trim($_GET['ingname']), ENT_QUOTES); 

        $curs = oci_new_cursor($conn);
        $query = oci_parse($conn, "BEGIN recipepack.search_ingredients_by_query(:qry, :res); END;");
        oci_bind_by_name($query, ":qry", $cleantext);
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

        $displayString="<table align=center bgcolor=#f8f5ef cellpadding=2 cellspacing=10 ><th> Ingredient Name </th><th> Add To Box? </th>";
        $numr=0;

        while($info=oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS))
        {       
                //print_r($info);
                ++$numr;
                $displayString .= "<tr><td>".$info['INGREDIENT_DESC']."</td>
                      <td>".$stuff."<form method='post' action='search.php'>
                          <input type=hidden name='iid' value='".$info["INGREDIENT_ID"]."'> <input type=submit value='Add To Box' name='addbox'></form></td></tr>";
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
</body>
</html>

