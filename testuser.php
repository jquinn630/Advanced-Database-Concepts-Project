<html>
<body>

<?php

	// for recipe box

    require_once('dbtest.php');

    /// REMOVE THIS WHEN ACCOUNTS WORK
    $_SESSION['uid']=1;


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

        $displayString="<table align=center bgcolor=#f8f5ef cellpadding=2 cellspacing=10 ><th> Recipe Name </th> <th> Cook Time (min) </th><th> Serves </th>";
        $numr=0;

        while($info=oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS))
        {       
                ++$numr;
                $displayString .= "<tr><td><a href=recipe.php?rid=".$info['RECIPE_ID']." >".$info['RECIPE_NAME']."</a></td>
                      <td>".$info['COOK_TIME']."</td><td>".$info['SERVING_NUMBER']."</tr>";
        } 

        $displayString.="</table>";

        if ($numr==0) echo "<strong> Your box is empty.  Find some recipes that you like.</strong>";
        else echo $displayString;

        oci_free_statement($query);
        oci_free_statement($curs);


?> 

<?php
    require_once('dbtest.php');

    /// REMOVE THIS WHEN ACCOUNTS WORK
    $_SESSION['uid']=1;

	// for ingredients box
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

        $displayString="<table align=center bgcolor=#f8f5ef cellpadding=2 cellspacing=10 ><th> Ingredient Name </th>";
        $numr=0;

        while($info=oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS))
        {       
                //print_r($info);
                ++$numr;
                $displayString .= "<tr><td>".$info['INGREDIENT_DESC']."</td></tr>";
        } 

        $displayString.="</table>";

        if ($numr==0) echo "<strong> Your box is empty.  Use the search page to find new ingredients.</strong>";
        else echo $displayString;

        oci_free_statement($query);
        oci_free_statement($curs);
?>

<?php
    //generates user feed
    require_once('dbtest.php');

    /// REMOVE THIS WHEN ACCOUNTS WORK
    $_SESSION['uid']=1;

    // for ingredients box
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

</body>
</html>

