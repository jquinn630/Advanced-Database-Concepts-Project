<?php session_start();?>


<?php
    require_once('dbtest.php');

	$sdate=$_GET['s'];
	$edate=$_GET['e'];

	date_default_timezone_set("America/New_York");

	$dates = new DateTime($sdate);
	$datee = new DateTime($edate);

	if ($sdate==""||$edate=="")
	{
		echo "Please select two valid dates.";
	}
	else if ($sdate > $edate)
	{
		echo "Invalid date range.";
	}
	else
	{
		echo "<hr><h4> List for ".$sdate." to ".$edate."</h4>";

		$curs = oci_new_cursor($conn);
		$query = oci_parse($conn, " BEGIN :meals:=recipepack.generate_shopping_list(:uid, to_date(:thedate, 'yyyy-mm-dd'), to_date(:thedate2, 'yyyy-mm-dd')); END;");
		oci_bind_by_name($query, ":meals", $curs, -1, OCI_B_CURSOR);
		oci_bind_by_name($query, ":thedate", $sdate);
		oci_bind_by_name($query, ":thedate2", $edate);
		oci_bind_by_name($query, ":uid", $_SESSION['uid']);
		oci_execute($query);
    	oci_execute($curs);	

    	$numr = 0;

    	$table = "<table cellpadding=2 bgcolor=#f8f5ef cellspacing=10 ><th> Ingredient </th> <th> Measure </th>";

		while($info=oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS))
		{
			++$numr;
			$total = (floatval($info['NUM_UNITS']) * floatval($info['NUMBER_SERVINGS'])) / floatval($info['SERVING_NUMBER']);
			if ($total==0)
			{
				$total="N/A";
			}
			if($total=="N/A" && $info['UNIT_TYPE']!="")
			{
				$total="1s";
			}
			$table.= "<tr>";
			$table.= "<td>".$info['INGREDIENT_DESC']."</td>";
			$table.= "<td>".$total." ".$info['UNIT_TYPE']."</td>";
			$table.= "</tr>";
		}

		if ($numr==0)
		{
			echo "You have no meals planned for those dates.";
		}
		else
		echo $table."</table>";

		oci_execute($query);
		oci_execute($curs);

	}
?>