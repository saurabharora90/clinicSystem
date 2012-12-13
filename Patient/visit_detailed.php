
<?php
    require_once("../Templates/Patients_Template.php");
?>

<?php    
    /*function showData($doc, $patient_id, $datetime, $diag)
    {
        $query = "SELECT * FROM prescribe WHERE doctor=(?) AND patient=(?) AND datetime=(?)";
        $params = array($doc, $patient_id, $datetime);
        $query_results = sqlsrv_query($conn, $query, $params);
        $pres = "<table><tr><td>Prescription:</td>";
        $no_pres = "Sorry you have no prescriptions!!!";
        $html = "<table><tr><td>Date:</td><td>". $datetime ."</td></tr><tr><td>Doctor Seen:</td><td>". $doc ."</td></tr><tr><td>Diagnosis:</td><td>". $diag ."</td></tr></table>";
        if(!sqlsrv_has_rows($query_results))
           { echo $html . "<br />" . $no_pres;
             return;
           }
        else
            $pres = $pres . "</tr>";
        
        while($row = sqlsrv_fetch_array($query_results))
        {
            $p = "<tr><td>Drug Name</td><td>".$row['drug']."</td></tr>
                            <tr><td>Manufacturer</td><td>".$row['manufacturer']."</td></tr>";
            $pres = $pres . $p;
            echo $p;
        }

        $pres = $pres . "</table>";
        echo $html . $pres;
        return $html;
    }*/

    /*function showData($patient_id, $datetime, $conn)
    {
        //echo $patient_id ."</br>";
        //global $conn;
        $date = date_format($datetime, 'M d Y h:i:sA');
        echo $date;
        echo "</br>";

        $query = "SELECT * FROM prescribe WHERE patient=? AND datetime=?";
        $params = array($patient_id, $datetime);
        $query_results = sqlsrv_query($conn, $query, $params);
        $row = sqlsrv_fetch_array($query_results);
        $pres = "<table><tr><td>Prescription:</td>";
        $no_pres = "Sorry you have no prescriptions!!!";
        $html = "<table><tr><td>Date:</td><td>". date_format($datetime, 'dS M, Y') ."</td></tr><tr><td>Doctor Seen:</td><td>". $row['doctor'] ."</td></tr></table>";
        //<tr><td>Diagnosis:</td><td>". $diag ."</td></tr>
        if(sqlsrv_has_rows($query_results))
           { echo "Yes!!";
               //echo $html . "<br />" . $no_pres;
             //return;
           }
           else
            echo "Nope!";
        //else
            //$pres = $pres . "</tr>";
        
        do
        {
            $p = "<tr><td>Drug Name</td><td>".$row['drug']."</td></tr>
                            <tr><td>Manufacturer</td><td>".$row['manufacturer']."</td></tr>";
            $pres = $pres . $p;
            echo $p;
        } while($row = sqlsrv_fetch_array($query_results));

        $pres = $pres . "</table>";
        echo $html . $pres;
        return $html;
    }
    */

   function showData($patient_id, $datetime, $docName, $diagnosis, $conn)
    {
        $query = "SELECT * FROM prescribe WHERE patient=(?) AND datetime=(?)";
        $params = array($patient_id, $datetime);
        $query_results = sqlsrv_query($conn, $query, $params);

        $pres = "<table><tr><td>Prescription:</td>";
        $no_pres = "Sorry you have no prescriptions!!!";
        $html = "<table><tr><td>Date:</td><td>". date_format($datetime, 'dS M, Y') ."</td></tr><tr><td>Doctor Seen:</td><td>". $docName ."</td></tr><tr><td>Diagnosis:</td><td>" . $diagnosis. "</td></tr></table>";
        echo $html;
        echo sqlsrv_num_rows($query_results);

        if(!$query_results)
           { 
               echo $no_pres;
           }
           
            $pres = $pres . "</tr>";
        $row = sqlsrv_fetch_array($query_results);
        do
        {   //$i = $i + 1; 
            echo "Here";
            echo $row[0] . "cscc";
        //    $p = "<tr><td>Drug Name</td><td>".$row['drug']."</td></tr>
        //                    <tr><td>Manufacturer</td><td>".$row['manufacturer']."</td></tr>";
        //    $pres = $pres . $p;
        //    //echo $p;
        } while($row = sqlsrv_fetch_array($query_results));
        echo "Tere";
        //$pres = $pres . "</table>";
        //echo $pres;
        ////return $html;
        //return;
    }
?>

<div id="content">
   <a href="visits.php"><input type="button" name="cancel" value="Back to Visits"></a><br><br>
   <?php
       //echo $_POST['details']."</br>";
       $date = date_create_from_format('dS M, Y h:i:s A', $_POST['details']);
       
       //$data = showData($patient_id, $_POST['details']);
       //$data = showData($patient_id, $date, $conn);
        $row_num = $_POST['details'];
        $i = 0;
        $query = "SELECT  v.datetime, d.name, v.diagnosis FROM visit v, doctor d WHERE patient=(?) AND v.doctor = d.id_number ORDER BY datetime DESC";
        $params = array($patient_id);
        $query_results = sqlsrv_query($conn, $query, $params);
        while($i < $row_num)
        {
            $row = sqlsrv_fetch_array($query_results);
            $i++;
        }
        //showData($patient_id, $row[0], $row[1], $row[2], $conn);
        $datetime = $row[0];
        $docName = $row[1];
        $diagnosis = $row[2];
        //$datetime = "%" . $row[0] . "%";
        $query = "SELECT p.drug, p.manufacturer FROM prescribe p WHERE p.patient=(?) AND p.datetime=(?)";
        $params = array($patient_id, $datetime);
        $query_results = sqlsrv_query($conn, $query, $params);

        $pres = "<table><tr><td>Prescription:</td>";
        $no_pres = "Sorry you have no prescriptions!!!";
        $html = "<table><tr><td>Date:</td><td>". date_format($datetime, 'dS M, Y') ."</td></tr><tr><td>Doctor Seen:</td><td>". $docName ."</td></tr><tr><td>Diagnosis:</td><td>" . $diagnosis. "</td></tr></table>";
        echo $html;
        if (sqlsrv_has_rows($query_results))
        echo "t";
        else echo "f";

        if(!$query_results)
           { 
               echo $no_pres;
           }
 
            $pres = $pres . "</tr>";
        $row = sqlsrv_fetch_array($query_results);
               
        echo $row[0] . $row[1] . $row[2];
        do
        {   //$i = $i + 1; 
            
        //    $p = "<tr><td>Drug Name</td><td>".$row['drug']."</td></tr>
        //                    <tr><td>Manufacturer</td><td>".$row['manufacturer']."</td></tr>";
        //    $pres = $pres . $p;
        //    //echo $p;
        } while($row = sqlsrv_fetch_array($query_results));


   ?>
</div>

</div>
</body>

