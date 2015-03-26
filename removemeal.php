<?php session_start();?>

<?php
    require_once('dbtest.php');

    /// REMOVE THIS WHEN ACCOUNTS WORK

	$mid = $_POST['mealid'];

	$query = oci_parse($conn, "BEGIN recipepack.remove_meal(:mid); END;");
    oci_bind_by_name($query, ":mid", $mid);

    if (!@oci_execute($query))  
    {
        $err = oci_error($query);
        echo $err['message'];
    }

    else
    echo('<strong>Meal successfully removed.  Select a date.</strong><br>');

?>