<?php session_start();?>

<?php
	// this PHP program handles an AJAX call from schedule.php, in order to make the javascript calendar widget effective.  
    require_once('dbtest.php');

    $breakfast = "";
    $lunch = "";
    $dinner = "";
    $others = "";

	$date = substr($_GET['date'], 8,18);
	echo '<hr>';

	$curs = oci_new_cursor($conn);
	$query = oci_parse($conn, " BEGIN :meals:=recipepack.get_mealplan_for_user(:uid, to_date(:thedate, 'yyyy-mm-dd'), to_date(:thedate2, 'yyyy-mm-dd')); END;");
	oci_bind_by_name($query, ":meals", $curs, -1, OCI_B_CURSOR);
	oci_bind_by_name($query, ":thedate", $date);
	oci_bind_by_name($query, ":thedate2", $date);
	oci_bind_by_name($query, ":uid", $_SESSION['uid']);

	oci_execute($query);
	oci_execute($curs);


	while($info=oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS))
	{
		if($info['MEAL_DESC']=="breakfast")
		{
			$breakfast.="<a href=recipe.php?rid=".$info['RECIPE_ID'].">".$info['RECIPE_NAME']."</a> - Planned Servings: ".$info['NUMBER_SERVINGS']." <form><button type='button' class='something' onclick='removeMeal(".$info['MEAL_ID'].")' >Remove </button></form>";
		}
		else if($info['MEAL_DESC']=="lunch")
		{
			$lunch.="<a href=recipe.php?rid=".$info['RECIPE_ID'].">".$info['RECIPE_NAME']."</a> - Planned Servings: ".$info['NUMBER_SERVINGS']." <form><button type='button' onclick='removeMeal(".$info['MEAL_ID'].");' >Remove </button></form>";
		}
		else if($info['MEAL_DESC']=="dinner")
		{
			$dinner.="<a href=recipe.php?rid=".$info['RECIPE_ID'].">".$info['RECIPE_NAME']."</a> - Planned Servings: ".$info['NUMBER_SERVINGS']." <form><button type='button' onclick='removeMeal(".$info['MEAL_ID'].")' >Remove </button></form>";
		}
		else 
		{
			$others.="<a href=recipe.php?rid=".$info['RECIPE_ID'].">".$info['RECIPE_NAME']."</a> - Planned Servings: ".$info['NUMBER_SERVINGS']." <form> <button type='button' onclick='removeMeal(".$info['MEAL_ID'].")' > Remove </button></form>";
		}
	}

	if ($breakfast=="") $breakfast="Nothing planned.";
	if ($lunch=="") $lunch="Nothing planned.";
	if ($dinner=="") $dinner="Nothing planned.";
	if ($others=="") $others="Nothing planned.";

	echo "<h3> Breakfast </h3>".$breakfast."<br><br><h3> Lunch </h3>".$lunch."<br><br><h3> Dinner </h3>".$dinner."<br><br><h3> Others </h3>".$others;

	oci_free_statement($query);
	oci_free_statement($curs);


?>