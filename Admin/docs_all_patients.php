<?php require_once("../includes/connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php include("../Templates/Admin_Template.php"); ?>
<link href="../stylesheets/TableStyles.css" rel="stylesheet" type="text/css" />


<div id="content">
    <h2>                 
        Doctors who have been visited by all patients<br/> <br/>
    </h2>
        <form action="Welcome.php" method="post">
            <input type="submit" name="back" value="<< BACK">
        </form>
        <div class="datagrid">            
        <table>
        <thead>
			<tr>
                <th>Name</th>
                <th>ID Number</th>
			</tr>
		</thead>        
        <tbody>
        <?php
            $doc_set = get_docs_all_patients();
            while ($doc = sqlsrv_fetch_array($doc_set)) {
                $output = "<tr>";
                $output .= "<td>" . $doc["name"] . "</td>";
                $output .= "<td>" . $doc["id_number"] . "</td>";
                $output .= "</tr>";
                echo $output;
            }
        ?>
              <tr><td></td></tr>
        </tbody>
        </table>
        </div> 
</div>

<?php include("../includes/footer.php"); ?>
