<?php
    session_start();
    require_once('dbtest.php');

        if (array_key_exists('uname', $_POST))
        {
            $uname = htmlentities( trim($_POST['uname']));
            $pass = $_POST['pwd'];
            $query = oci_parse($conn, "BEGIN :success:=recipepack.authenticate_user(:uname, :pass); END;");
            oci_bind_by_name($query, ":uname",  $uname);
            oci_bind_by_name($query, ":pass", $pass);
            oci_bind_by_name($query, ":success", $auth, 8);

            if (!@oci_execute($query))  
            {
                $err = oci_error($query);
                echo $err['message'];
            }

            if ($auth==-1)
            {
                echo "Login failed.  <a href=index.php> Click here to retry </a>";
                return;
            }
            else if ($auth==1)
            {
                $getName = oci_parse($conn, "select recipepack.get_id_by_uname(:uname) from dual");
                oci_bind_by_name($getName, ":uname", $uname);

                if (!@oci_execute($getName))  
                {
                    $err = oci_error($getName);
                    echo $err['message'];
                }
                else
                {
                    $info = oci_fetch_row($getName);
                    $_SESSION['uid']=$info[0];
                }
                oci_free_statement($getName);
            }

            oci_free_statement($query);
        }
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
    <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script> -->
    <script src="js/jquery.flexslider-min.js"></script>
    <!-- <script src="js/scripts.js"></script> -->
    <script src="js/jquery/js/jquery-ui-1.10.4.custom.js"></script>
    <script src="js/jquery/js/jquery-1.11.0.js"></script>
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

<div class="container">
<table><tr><td><div class="userblock contentblock" >
<?php

    	if (!(array_key_exists('uid', $_SESSION)))
    	{
    		echo "You need to login to use this page.";
            echo "<a href=index.php> Click here to retry </a>";
            return;
    	}

        else if (array_key_exists('iidtorem', $_POST))
        {
            $query = oci_parse($conn, "BEGIN recipepack.remove_ingredient_box(:uid, :iid); END;");
            oci_bind_by_name($query, ":uid", $_SESSION['uid']);
            oci_bind_by_name($query, ":iid", $_POST['iidtorem']);

            if (!@oci_execute($query))  
            {
                $err = oci_error($query);
                echo $err['message'];
            }
            else echo "Ingredient removed.";

            oci_free_statement($query);
        }

        else if (array_key_exists('ridtorem', $_POST))
        {
            $query = oci_parse($conn, "BEGIN recipepack.remove_recipe_box(:uid, :rid); END;");
            oci_bind_by_name($query, ":uid", $_SESSION['uid']);
            oci_bind_by_name($query, ":rid", $_POST['ridtorem']);

            if (!@oci_execute($query))  
            {
                $err = oci_error($query);
                echo $err['message'];
            }
            else echo "Recipe removed.";

            oci_free_statement($query);
        }

    	else if (array_key_exists('newmail', $_POST))
    	{
    		if ($_POST['newmail']=='')
    		{
    			echo "New email field cannot be blank.";
    		}
    		else
    		{
    		$query = oci_parse($conn, "BEGIN recipepack.update_user_email(:uid, :email); END;");
    		$newmail = htmlentities(trim($_POST['newmail']));
    		oci_bind_by_name($query, ":uid", $_SESSION['uid']);
    		oci_bind_by_name($query, ":email", $newmail);

    		if (!@oci_execute($query))  
            {
                $err = oci_error($query);
                echo $err['message'];
            }
            else echo "<strong> Email updated. </strong>";

            oci_free_statement($query);
        	}

    	}
    	else if (array_key_exists('newblurb', $_POST))
    	{
    		if ($_POST['newblurb']=='')
    		{
    			echo "New blurb field cannot be blank.";
    		}
    		else
    		{
    		$query = oci_parse($conn, "BEGIN recipepack.update_user_desc(:uid, :desc); END;");
    		$desc = htmlentities(trim($_POST['newblurb']));
    		oci_bind_by_name($query, ":uid", $_SESSION['uid']);
    		oci_bind_by_name($query, ":desc", $desc);

    		if (!@oci_execute($query))  
            {
                $err = oci_error($query);
                echo $err['message'];
            }
            else echo "<strong> Blurb updated. </strong>";

            oci_free_statement($query);
        	}

    	}

    	if ((array_key_exists('uid', $_SESSION)))
    	{

           		$getAll = oci_parse($conn, "BEGIN recipepack.get_user_data(:uid, :first, :last, :uname, :description, :email);  END;");
                oci_bind_by_name($getAll, ":uid", $_SESSION['uid']);
                oci_bind_by_name($getAll,":first",$first,200);
                oci_bind_by_name($getAll,":last",$last,200);
                oci_bind_by_name($getAll,":uname",$uname,200);
                oci_bind_by_name($getAll,":description",$description,5120);
                oci_bind_by_name($getAll,":email",$email,200);

                if (!@oci_execute($getAll))  
                {
                    $err = oci_error($getAll);
                    echo $err['message'];
                    //return;
                }

                else 
                {
               		echo ('<h1>Welcome '.$uname.'</h1>');
                    echo "Real Name: ".$first." ".$last;
                	echo "<br>Email: ".$email;
                	echo "<br>Blurb: ".$description;

                }

                oci_free_statement($getAll);
        }

?>

<hr><strong>Change Email </strong>
<form method=post action=user.php>
	<input type=text name='newmail'>
	<input type=submit value='Update Email'>
</form>

<hr><strong>Change Blurb </strong>
<form method=post action=user.php>
	<textarea name='newblurb'> Enter a short summary. </textarea>
	<input type=submit value='Update Blurb'>
</form>
<script>
$(document).ready(function() {
       // $( "#accordion" ).accordion();
    });
</script>

<div id="accordions">
    <hr><h3>My Recipes</h3>
    <div id="myrecipes">
        <?php
        $curs = oci_new_cursor($conn);
        $query = oci_parse($conn, "BEGIN :res:=recipepack.get_recipes_for_user(:uid); END;");
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

        $displayString="<table align=center bgcolor=#f8f5ef cellpadding=2 cellspacing=10 ><th> Recipe Name </th> <th> Cook Time (min) </th><th> Serves </th><th>Remove? </th>";
        $numr=0;

        while($info=oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS))
        {       
                ++$numr;
                $displayString .= "<tr><td><a href=recipe.php?rid=".$info['RECIPE_ID']." >".$info['RECIPE_NAME']."</a></td>
                      <td>".$info['COOK_TIME']."</td><td>".$info['SERVING_NUMBER']."<td><form method=post action=user.php><input type=hidden name=ridtorem value='".$info['RECIPE_ID']."'><input type=submit value='Remove'></form>  </td></tr>";
        } 

        $displayString.="</table>";

        if ($numr==0) echo "<strong> Your box is empty.  Find some recipes that you like.</strong>";
        else echo $displayString;

        oci_free_statement($query);
        oci_free_statement($curs);

        ?>
    </div>
    <hr><h3>My Ingredients</h3>
    <div id="myingredients">
        <?php

        $curs = oci_new_cursor($conn);
        $query = oci_parse($conn, "BEGIN :res:=recipepack.get_ingredients_for_user(:uid); END;");
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

        $displayString="<table align=center bgcolor=#f8f5ef cellpadding=2 cellspacing=10 ><th> Ingredient Name </th><th> Remove? </th>";
        $numr=0;

        while($info=oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS))
        {       
                //print_r($info);
                ++$numr;
                $displayString .= "<tr><td>".$info['INGREDIENT_DESC']."</td><td><form method=post action=user.php><input type=hidden name=iidtorem value='".$info['INGREDIENT_ID']."'><input type=submit value='Remove'></form> </td></tr>";
        } 

        $displayString.="</table>";

        if ($numr==0) echo "<strong> Your box is empty.  Use the search page to find new ingredients.</strong>";
        else echo $displayString;

        oci_free_statement($query);
        oci_free_statement($curs);

        ?>
</div>
</div></td>
<td><div class="eventblock contentblock">
    <?php
        echo "<h3> Friend Comments </h3>";
        $curs = oci_new_cursor($conn);
        $query = oci_parse($conn, "BEGIN :res:=recipepack.get_follow_comments(:uid); END;");
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

        $displayString="";
        $numr=0;

        while($info=oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS))
        {       
                //print_r($info);
                ++$numr;
                $displayString .= $info["USERNAME"]." commented on <a href='recipe.php?rid=".$info['RECIPE_ID']."'> ".$info['RECIPE_NAME']."</a><br>";
        } 


        if ($numr==0) echo "<strong> No activity.  Find some more people to <a href='usersearch.php'>follow.</a></strong>";
        else echo $displayString;

        oci_free_statement($query);
        oci_free_statement($curs);

        echo "<hr><h3> Friend Ratings </h3>";
        $curs = oci_new_cursor($conn);
        $query = oci_parse($conn, "BEGIN :res:=recipepack.get_follow_ratings(:uid); END;");
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

        $displayString="";
        $numr=0;

        while($info=oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS))
        {       
                //print_r($info);
                ++$numr;
                $displayString .= $info["USERNAME"]." rated <a href='recipe.php?rid=".$info['RECIPE_ID']."'> ".$info['RECIPE_NAME']."</a> a ".$info['VALUE']." out of 5.<br>";
        } 


        if ($numr==0) echo "<strong> No activity.  Find some more people to <a href='usersearch.php'>follow.</a></strong>";
        else echo $displayString;

        oci_free_statement($query);
        oci_free_statement($curs);


        echo "<hr><h3> Today's Friend Meals </h3>";
        $curs = oci_new_cursor($conn);
        $query = oci_parse($conn, "BEGIN :res:=recipepack.get_follow_meals(:uid); END;");
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

        $displayString="";
        $numr=0;

        while($info=oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS))
        {       
                //print_r($info);
                ++$numr;
                $displayString .= $info["USERNAME"]." is planning <a href='recipe.php?rid=".$info['RECIPE_ID']."'> ".$info['RECIPE_NAME']."</a> for ".$info['MEAL_DESC']."<br>";
        } 


        if ($numr==0) echo "<strong> No activity.  Find some more people to <a href='usersearch.php'>follow.</a></strong>";
        else echo $displayString;

        oci_free_statement($query);
        oci_free_statement($curs);

    ?>
</div></td></tr></table>
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

