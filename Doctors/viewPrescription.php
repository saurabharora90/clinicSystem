<?php
    include("../Templates/Doctors_Template.php");
    require_once("../includes/connection.php");
    require_once("SQLQueries.php");
    
    $patient = isPatientIdCorrect($_POST['visit_id'], $_id, 0);

    if(!isset($_POST['visit_id']))
    {
        $location ="welcome.php";
        header("Location: {$location}");
    }
?>

<link href="../stylesheets/TableStyles.css" rel="stylesheet" type="text/css" />
<title>Welcome DR. <?php echo $_name?></title>

<div id="content">
    <h2>Welcome <span style="color:#B29B35;"> DR. <?php echo $_name?></span></h2>
    <blockquote>You can access other useful information from the navigation options on the left.</br </br> Have a good day :)</blockquote>
    <?php
        if(!sqlsrv_has_rows($patient))
        {
            echo "<font color=\"red\">Sorry, you have entered a wrong Patient Id</font>";
            exit(); //Terminate this page over here.
        }

?>
    <p>Here are the prescription for <?php echo $_POST['visit_id']?> </p>

    <div class="datagrid">
	<table>
		<thead>
			<tr>
				<th>Diagnosis</th>
				<th>Visit Time</th>
                <th>Drug prescribed</th>
                <th>Manufacturer</th>
			</tr>
		</thead>
		<!--<tfoot>
			<tr>
				<td colspan="4">
					<div id="paging">
						<ul><li><a href="#"><span>Previous</span></a></li><li><a href="#" class="active"><span>1</span></a></li><li><a href="#"><span>2</span></a></li><li><a href="#"><span>3</span></a></li><li><a href="#"><span>4</span></a></li><li><a href="#"><span>5</span></a></li><li><a href="#"><span>Next</span></a></li></ul>
					</div>
				</td>
			</tr>
		</tfoot>-->
		<tbody>
            <?php
                $alt_count =0;
                    while( $row = sqlsrv_fetch_array($patient) )
                    {
                        //echo date_format($row['visit'], 'd-m-y');
                        if($alt_count%2==0)
                        {
                            echo "<tr> <td>". $row[1]."</td> <td>".date_format($row[4], 'dS M Y, h:i A')."</td> <td>".$row[2]."</td> <td>". $row[3]."</td> </tr>";
                        }
                        else
                        {
                            echo "<tr class=\"alt\"> <td>". $row[1]."</td> <td>".date_format($row[4], 'dS M Y, h:i A')."</td> <td>".$row[2]."</td> <td>". $row[3]."</td> </tr>";
                        }
                        $alt_count++;
                    }

                //sqlsrv_free_stmt($row);
            ?>
		</tbody>
	</table>
</div>

    <!--<p>You can click on the patient name to view more details about him.</p>    required?-->
  </div>
</div>
</body>


<?php
    require_once("../includes/footer.php");
?>
