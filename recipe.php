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

    $rid=trim($_GET['rid']);

    if (array_key_exists('box', $_POST))
    {
        $query = oci_parse($conn, "BEGIN recipepack.add_recipe_to_box(:user, :recipe); END;");
        oci_bind_by_name($query, ":user", $_SESSION['uid']);
        oci_bind_by_name($query, ":recipe", $rid);

        if (!@oci_execute($query))  
        {
            $err = oci_error($query);
            echo $err['message'];
        }
        else
        echo('<strong>Recipe Added to Your Box!</strong><br>');
    }

    if (array_key_exists('plan', $_POST))
    {
       if ($_POST['recdate']=='')
       {
         echo "<strong> You must select a date when planning a meal. </strong><br>";
       }
       else if (intval($_POST['servings'])<=0)
       {
         echo "<strong> You must have a positve number of servings. </strong><br>";
       }
       else
       {
         $plan = oci_parse($conn, "BEGIN recipepack.plan_meal(:uid, :rid, :numserv, to_date(:thedate, 'yyyy-mm-dd'), :mdesc); END;");
         $prid = trim($_GET['rid']);
         oci_bind_by_name($plan, ":uid", $_SESSION['uid']);
         oci_bind_by_name($plan, ":rid", $prid);
         oci_bind_by_name($plan, ":numserv", $_POST['servings']);
         oci_bind_by_name($plan, ":thedate", $_POST['recdate']);
         oci_bind_by_name($plan, ":mdesc", $_POST['meal_desc']);

         if (!@oci_execute($plan))  
         {
            $err = oci_error($plan);
            echo $err['message'];
         }
         else 
         echo '<strong>Meal successfully planned!</strong><br>';

         oci_free_statement($plan);
       }
    }

    if (!array_key_exists('rid', $_GET))
    {
        echo ('<b> Error: No recipe specified </b>');
    }
    else
    {
        $getRname = oci_parse($conn,"select recipepack.get_recipe_name_by_id(:rid) from dual");
        oci_bind_by_name($getRname, ":rid", $rid);
        oci_execute($getRname);
        $info = oci_fetch_row($getRname);
        if ($info[0]=='')
        {
            echo ('<b> Error: Recipe does not exist </b>');
            return;
        }
        else
        {
            //get all data procedure
            /*$getAll = oci_parse($conn, "BEGIN recipepack.get_recipe_data(:rid, :rname, :ptime, :ctime, :servings, :instruct, :pic, :src, :cid);  END;");
                oci_bind_by_name($getAll, ":rid", $rid);
                oci_bind_by_name($getAll,":rname",$rname,1000);
                oci_bind_by_name($getAll,":ptime",$ptime,1000);
                oci_bind_by_name($getAll,":ctime",$ctime,1000);
                oci_bind_by_name($getAll,":servings",$servings,1000);
                oci_bind_by_name($getAll,":instruct",$instruct,16000);
                oci_bind_by_name($getAll,":pic",$pic,256);
                oci_bind_by_name($getAll,":src",$src,256);
                oci_bind_by_name($getAll,":cid",$cid);

                if (!@oci_execute($getAll))  
                {
                    $err = oci_error($getAll);
                    echo $err['message'];
                }*/
            $getAll = oci_parse($conn, "select * from Recipes where recipe_id=:rid");
                oci_bind_by_name($getAll, ":rid", $rid);

                if (!@oci_execute($getAll))  
                {
                    $err = oci_error($getAll);
                    echo $err['message'];
                }

                $info = oci_fetch_row($getAll);

                $rname = $info[1];
                $ctime = $info[3];
                $servings = $info[4];
                $instruct = $info[5]->read($info[5]->size());
                $pic = $info[6];
                $src = $info[7];
                $cid = $info[8];
        }

        echo ('<div class="one-third column"> <h1>'.$rname.'</h1>');
        if (is_null($pic))
        {
            echo ('<img src="nophoto.gif" height=375 width=375>');
        }
        else
        {
            echo ('<img src="'.$pic.'" height=375 width=375>');
        }
            
        echo('</div>
        <div class="one-third column">
            <h3>Overview</h3>');
        echo( '<p>
               <strong> Cook Time: </strong>'.$ctime.' minutes<br>
               <strong> Number of Servings: </strong>'.$servings.'<br>');

        if (!is_null($src))
        {
            echo('<strong> Original Source:</strong> '.$src.'');
        }

        if (!is_null($cid))
        {
            $query = oci_parse($conn,"select recipepack.get_uname_by_id(".$cid.") from dual");
            oci_execute($query);
            $info = oci_fetch_row($query);
            if ($cid != "")
            {
                echo('<strong> Creator: '.$info[0].'</strong>');
            }
            oci_free_statement($query);
        }

        echo ('</p>');
        echo ('</div>
        <div class="one-third column">
            <h3>Ingredients</h3><p>');

        $curs = oci_new_cursor($conn);
        $query = oci_parse($conn, "BEGIN :ingr:=recipepack.get_recipe_ingredients(".$_GET['rid']."); END;");
        oci_bind_by_name($query, ':ingr', $curs, -1, OCI_B_CURSOR);
        oci_execute($query);
        oci_execute($curs);

      //  echo "<table cellpadding=2 bgcolor=#f8f5ef cellspacing=2 style='width:10px; valign:top;'><tr><th> Ingredient </th> <th> Measure </th></tr>";
        while($info=oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS))
        {
                if ($info['NUM_UNITS']==0)
                {
                    $info['NUM_UNITS']='';
                }
                echo("<tr><td>".$info['INGREDIENT_DESC']."</td><td> ".$info['NUM_UNITS']."  ".$info['UNIT_TYPE']."</td></tr>  <br>");
        } 

        //echo "</table>";

        oci_free_statement($query);
        oci_free_statement($curs);

        echo ('</div>
        <div class="one-third column">
            <h3>Instructions</h3>
            <p>'.$instruct.'</p>
        </div>'
        ); 

        //check if recipe is in user box
        $query = oci_parse($conn, "BEGIN :isit:=recipepack.recipe_in_user_box(:uid, :rid);  END;");
        oci_bind_by_name($query, ":isit", $info, 32);
        oci_bind_by_name($query, ":uid", $_SESSION['uid']);
        oci_bind_by_name($query, ":rid", $rid);
        oci_execute($query);

        if ($info==-1)
        {
            echo ("<form method='post' action='recipe.php?rid=".$_GET['rid']."'> 
                <input value='Add To Your Box' name='box' type=submit />
                </form>");
        }
        else
        {
            echo ("<strong>This recipe is in your box!</strong>");
        }

        oci_free_statement($query);
    }

?>

<br><h3> Plan this meal </h3>

<?php
echo "<form method='post' action='recipe.php?rid=".$_GET['rid']."''>"
?>
    Date: <input type=date name='recdate'><br>
    Meal: <select name='meal_desc'>
    <option value="breakfast"> Breakfast </option>
    <option value="lunch"> Lunch </option>
    <option value="dinner"> Dinner </option>
    <option value="other"> Other </option>
    </select> 
    Desired Servings <input type='number' name='servings'>
    <input type='submit' name='plan' value='Plan'>
</form>

<?php
        $curs = oci_new_cursor($conn);
        $query = oci_parse($conn, " BEGIN :meals:=recipepack.recipe_history(:uid, :rid); END;");
        oci_bind_by_name($query, ":meals", $curs, -1, OCI_B_CURSOR);
        oci_bind_by_name($query, ":rid", $_GET['rid']);
        oci_bind_by_name($query, ":uid", $_SESSION['uid']);
        oci_execute($query);
        oci_execute($curs); 

        $numr = 0;

        $table = "<h4> Previously Planned </h4> <table bgcolor=#f8f5ef cellspacing=2 cellpadding=10 cellspacing=0 >";

        while($info=oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS))
        {
            ++$numr;
             $table.= "<tr>";
             $table.=  "<td>".$info['DATE_PLANNED']."</td>";
             $table.=  "<td>".$info['MEAL_DESC']."</td>";
             $table.=  "</tr>";
        }

        if ($numr==0)
        {
            echo "You have yet to plan this meal.  Give it a try!";
        }
        else
        echo  $table."</table>";

    oci_free_statement($curs);
    oci_free_statement($query);
?>

</div> <!-- End of container block-->
<div class="container contentblock">
<div class="one-third column">
<h1>Leave a Comment!</h1>
<?php
    echo("<form method='post' action='recipe.php?rid=".$_GET['rid']."''>");
?>
<textarea name="comment_content" width:100%; rows='6' class="formInput" />Share your thoughts.</textarea> 
<input type='submit' value='Add Comment' name='comment'/>
</form>
<?php
	if (array_key_exists('rid', $_GET))
	{
    echo ("</div><div class='one-third column'>");
    echo ("<h2>Recipe Rating</h2>");

    if (array_key_exists('rating', $_POST))
    {
        $query = oci_parse($conn, "BEGIN recipepack.rate_recipe(:rate, :uid, :rid, :conf); END;");
        oci_bind_by_name($query, ":rate", $_POST['rating']);
        oci_bind_by_name($query, ":uid", $_SESSION['uid']);
        oci_bind_by_name($query, ":rid", $rid);
        oci_bind_by_name($query, ":conf", $hasrated, 8);
        oci_execute($query);

        if ($hasrated == -1)
        {
            echo ("<strong> You have already rated this recipe. </strong><br>");
        }
        else 
        {
            echo ("<strong> Thank you for your rating! </strong><br>");
        }

        oci_free_statement($query);
    }

    $query = oci_parse($conn, "select recipepack.get_average_rating(:rid) from dual");
    oci_bind_by_name($query, ":rid", $_GET['rid']);
    oci_execute($query);

    $info = oci_fetch_row($query);

    if ($info[0]==-1)
    {
        echo("Be the first to rate this recipe!<hr>");
    }
    else
    {
        echo("Average rating: <strong>".round($info[0], 2)."/5.0</strong>");
    }

    echo("<hr><form method='post' action='recipe.php?rid=".$_GET['rid']."'>
        <input type='radio' name='rating' value='5'/> 5 <br>
        <input type='radio' name='rating' value='4'/> 4 <br>
        <input type='radio' name='rating' value='3'/> 3 <br>
        <input type='radio' name='rating' value='2'/> 2 <br>
        <input type='radio' name='rating' value='1'/> 1 <br>
        <input type='submit' value='Rate!'> </form>");

    oci_free_statement($query);
	}
?>
</div>
<div class="one-third column">
<?php
	
	if (array_key_exists('rid', $_GET))
	{
    if (array_key_exists('comment', $_POST))
    {
        if($_POST['comment_content']=='' || $_POST['comment_content']==NULL || $_POST['comment_content']=="Share your thoughts.")
        {
            echo("<strong> Error: you must enter comment text.</strong>");
        }
        else
        {
            $comment_clean_content=trim(htmlentities($_POST['comment_content'],ENT_QUOTES));
            $query = oci_parse($conn, "BEGIN recipepack.comment_recipe(:txt, :userid, :recipeid, :parentid); END;");
            $parentid=NULL;
            oci_bind_by_name($query, ":txt", $comment_clean_content);
            oci_bind_by_name($query, ":userid", $_SESSION['uid']);
            oci_bind_by_name($query, ":recipeid", $_GET['rid']);
            oci_bind_by_name($query, ":parentid", $parentid);

            if (!@oci_execute($query))  
            {
                $err = oci_error($query);
                echo $err['message'];
            }
            else
            {   
                echo("<strong>Comment submitted!</strong>");
            }
                unset($_POST);
                $_POST = array();
        }
    }

    echo ("<h2>Previous Comments</h2><hr>");
    $curs = oci_new_cursor($conn);
    $query = oci_parse($conn, "BEGIN :allcomments:=recipepack.get_recipe_comments(".$rid."); END;");
    oci_bind_by_name($query, ':allcomments', $curs, -1, OCI_B_CURSOR);
    oci_execute($query);
    oci_execute($curs);

    $numr = 0;

    while($info=oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS))
    {
        ++$numr;
        echo($info['USERNAME']." Posted on ".$info['DATE_POSTED'].":<br>");
        echo($info['CONTENT']."<hr>");
    }
    oci_free_statement($query);
    oci_free_statement($curs);

        if ($numr==0)
        {
            echo "Be the first to comment.";
        }

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

