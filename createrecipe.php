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
        return;
    }
    else if (array_key_exists('new_recipe', $_POST))
    {
        if (is_null($_POST['pic'])|| $_POST['pic']='') $pic=NULL;
        else $pic = $_POST['pic'];

        if ($_POST['rname']=='')
        {
            echo ("Error: You must enter a recipe name.");
        }
        else if ($_POST['ctime']=='')
        {
            echo ("Error: You must enter a cook time.");
        }
        else if ($_POST['serves']=='')
        {
            echo ("Error: You must enter a serving number.");
        }
        else if ($_POST['instruct']=='')
        {
            echo ("Error: You must enter some instructions.");
        }
        else {
                $rname = htmlentities(trim($_POST['rname']));
                $instruct= htmlentities(trim($_POST['instruct']));
                $ctime=htmlentities(trim($_POST['ctime']));
                $ptime=$ctime;
                $serves=htmlentities(trim($_POST['serves']));
                
                $src = NULL;
                $create = oci_parse($conn, "BEGIN recipepack.create_recipe( :rname, :ptime, :ctime, :servings, :instruct, :pic, :src, :cid, :rid);  END;");
                oci_bind_by_name($create,":rname",$rname);
                oci_bind_by_name($create,":ptime",$ptime);
                oci_bind_by_name($create,":ctime",$ctime);
                oci_bind_by_name($create,":servings",$serves);
                oci_bind_by_name($create,":instruct",$instruct);
                oci_bind_by_name($create,":pic",$pic);
                oci_bind_by_name($create,":src", $src);
                oci_bind_by_name($create,":cid",$_SESSION['uid']);
                oci_bind_by_name($create,":rid", $newrid, 32);

                if (!@oci_execute($create))  
                {
                  $err = oci_error($create);
                  echo $err['message'];
                }
                else
                {   
                    echo "Recipe successfully created.";

                   	$ingrno = 1;
                   	$desc = 'descingr';
                   	$type = 'typeingr';
                   	$qty = 'qtyingr';
                	while ($ingrno <= 20)
                	{
                		$d = $desc.$ingrno;
                		$t = $type.$ingrno;
                		$q = $qty.$ingrno;

                		if ($_POST[$d]==NULL || $_POST[$d]=='' || $_POST[$d]==' ')
                		{
                			break;
                		}
                		else
                		{
                            $strip_d = htmlentities(trim($_POST[$d]),ENT_QUOTES);
                            $strip_q = htmlentities(trim($_POST[$q]),ENT_QUOTES);
                            $strip_t =htmlentities(trim($_POST[$t]),ENT_QUOTES);

                			$adding = oci_parse($conn, "BEGIN recipepack.add_ingredients_to_recipe(:rid, :desc, :qty, :type); END;");
                			oci_bind_by_name($adding, ":rid", $newrid);
                			oci_bind_by_name($adding, ":desc", $strip_d);
                			oci_bind_by_name($adding, ":qty", $strip_q);
                			ocibindbyname($adding, ":type", $strip_t);
                			 if (!@oci_execute($adding))  
                			 {
                 			     $err = oci_error($adding);
                  				 echo $err['message'];
               				 }
               				 oci_free_statement($adding);
                		}

                		$ingrno = $ingrno+1;
                	}
                } 

                if (array_key_exists('boxadd', $_POST))
                {
                    $addtobox = oci_parse($conn, "BEGIN recipepack.add_recipe_to_box(:uid, :rid); END;");
                    oci_bind_by_name($addtobox, ":uid", $_SESSION['uid']);
                    oci_bind_by_name($addtobox, ":rid", $newrid);
                    oci_execute($addtobox);

                    oci_free_statement($addtobox);
                }

                oci_free_statement($create);
                unset($_POST);
        } 

    }
?>
<h2> General Info </h2>
<form method="post" action="createrecipe.php">
<strong> Recipe Name </strong><input type=text name="rname" />
<strong> Cook Time (minutes) </strong><input type=number name="ctime" /> 
<strong> Serves </strong> <input type=number name="serves" /> <br><br>
<strong> Instructions </strong> <textarea name="instruct" width:100%; rows='10' class="formInput"/> </textarea> <br><br>
<strong> Picture Link (not required) </strong> <input type=text name="pic" />

<h2> Ingredients (up to 20)</h2>  					  
<strong> Description </strong> <input type=text name='descingr1' >
<strong> Measure (Cups, Ounces, Package, Box, etc.)</strong> <input type=text name='typeingr1' >
<strong> Quantity (Enter a number 1,2,3,etc)</strong> <input type=text name='qtyingr1' > <hr>
<strong> Description </strong> <input type=text name='descingr2' >
<strong> Measure (Cups, Ounces, Package, Box, etc.)</strong> <input type=text name='typeingr2' >
<strong> Quantity (Enter a number 1,2,3,etc)</strong> <input type=text name='qtyingr2' > <hr>
<strong> Description </strong> <input type=text name='descingr3' >
<strong> Measure (Cups, Ounces, Package, Box, etc.)</strong> <input type=text name='typeingr3' >
<strong> Quantity (Enter a number 1,2,3,etc)</strong> <input type=text name='qtyingr3' > <hr>
<strong> Description </strong> <input type=text name='descingr4' >
<strong> Measure (Cups, Ounces, Package, Box, etc.)</strong> <input type=text name='typeingr4' >
<strong> Quantity (Enter a number 1,2,3,etc)</strong> <input type=text name='qtyingr4' > <hr>
<strong> Description </strong> <input type=text name='descingr5' >
<strong> Measure (Cups, Ounces, Package, Box, etc.)</strong> <input type=text name='typeingr5' >
<strong> Quantity (Enter a number 1,2,3,etc)</strong> <input type=text name='qtyingr5' > <hr>
<strong> Description </strong> <input type=text name='descingr6' >
<strong> Measure (Cups, Ounces, Package, Box, etc.)</strong> <input type=text name='typeingr6' >
<strong> Quantity (Enter a number 1,2,3,etc)</strong> <input type=text name='qtyingr6' > <hr>
<strong> Description </strong> <input type=text name='descingr7' >
<strong> Measure (Cups, Ounces, Package, Box, etc.)</strong> <input type=text name='typeingr7' >
<strong> Quantity (Enter a number 1,2,3,etc)</strong> <input type=text name='qtyingr7' > <hr>
<strong> Description </strong> <input type=text name='descingr8' >
<strong> Measure (Cups, Ounces, Package, Box, etc.)</strong> <input type=text name='typeingr8' >
<strong> Quantity (Enter a number 1,2,3,etc)</strong> <input type=text name='qtyingr8' > <hr>
<strong> Description </strong> <input type=text name='descingr9' >
<strong> Measure (Cups, Ounces, Package, Box, etc.)</strong> <input type=text name='typeingr9' >
<strong> Quantity (Enter a number 1,2,3,etc)</strong> <input type=text name='qtyingr9' > <hr>
<strong> Description </strong> <input type=text name='descingr10' >
<strong> Measure (Cups, Ounces, Package, Box, etc.)</strong> <input type=text name='typeingr10' >
<strong> Quantity (Enter a number 1,2,3,etc)</strong> <input type=text name='qtyingr10' > <hr>
<strong> Description </strong> <input type=text name='descingr11' >
<strong> Measure (Cups, Ounces, Package, Box, etc.)</strong> <input type=text name='typeingr11' >
<strong> Quantity (Enter a number 1,2,3,etc)</strong> <input type=text name='qtyingr11' > <hr>
<strong> Description </strong> <input type=text name='descingr12' >
<strong> Measure (Cups, Ounces, Package, Box, etc.)</strong> <input type=text name='typeingr12' >
<strong> Quantity (Enter a number 1,2,3,etc)</strong> <input type=text name='qtyingr12' > <hr>
<strong> Description </strong> <input type=text name='descingr13' >
<strong> Measure (Cups, Ounces, Package, Box, etc.)</strong> <input type=text name='typeingr13' >
<strong> Quantity (Enter a number 1,2,3,etc)</strong> <input type=text name='qtyingr13' > <hr>
<strong> Description </strong> <input type=text name='descingr14' >
<strong> Measure (Cups, Ounces, Package, Box, etc.)</strong> <input type=text name='typeingr14' >
<strong> Quantity (Enter a number 1,2,3,etc)</strong> <input type=text name='qtyingr14' > <hr>
<strong> Description </strong> <input type=text name='descingr15' >
<strong> Measure (Cups, Ounces, Package, Box, etc.)</strong> <input type=text name='typeingr15' >
<strong> Quantity (Enter a number 1,2,3,etc)</strong> <input type=text name='qtyingr15' > <hr>
<strong> Description </strong> <input type=text name='descingr16' >
<strong> Measure (Cups, Ounces, Package, Box, etc.)</strong> <input type=text name='typeingr16' >
<strong> Quantity (Enter a number 1,2,3,etc)</strong> <input type=text name='qtyingr16' > <hr>
<strong> Description </strong> <input type=text name='descingr17' >
<strong> Measure (Cups, Ounces, Package, Box, etc.)</strong> <input type=text name='typeingr17' >
<strong> Quantity (Enter a number 1,2,3,etc)</strong> <input type=text name='qtyingr17' > <hr>
<strong> Description </strong> <input type=text name='descingr18' >
<strong> Measure (Cups, Ounces, Package, Box, etc.)</strong> <input type=text name='typeingr18' >
<strong> Quantity (Enter a number 1,2,3,etc)</strong> <input type=text name='qtyingr18' > <hr>
<strong> Description </strong> <input type=text name='descingr19' >
<strong> Measure (Cups, Ounces, Package, Box, etc.)</strong> <input type=text name='typeingr19' >
<strong> Quantity (Enter a number 1,2,3,etc)</strong> <input type=text name='qtyingr19' > <hr>
<strong> Description </strong> <input type=text name='descingr20' >
<strong> Measure (Cups, Ounces, Package, Box, etc.)</strong> <input type=text name='typeingr20' >
<strong> Quantity (Enter a number 1,2,3,etc)</strong> <input type=text name='qtyingr20' > <hr>

<strong> Add To My Box </strong> <input type='checkbox' name='boxadd' value='yes'/><hr>


<input type=hidden name='new_recipe' value='YES' />

<input type=submit value='Add Recipe'>
</form>

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

