<?php
    include("Templates/MainPage_Template.php");
?>
<?php
    require_once("includes/functions.php");
?>
<?php
    require_once("includes/connection.php");
?>
<?php
    $result = search_doctor_from_view($_POST['txtsearch']);
?>
  <div id="content">
    <h2>Search Results</h2>
    <div class="datagrid">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Specialization</th>
                    <th>Department</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while ($doctor = sqlsrv_fetch_array($result)) {
                        $output .= "<tr><td>" . $doctor["name"] . "</td>";
                        $output .= "<td>" . $doctor["specialization"] . "</td>";
                        $output .= "<td>" . $doctor["department"] . "</td>";
                        $output .= "</tr>";
                        echo $output;
                    }
                ?>
            </tbody>
        </table>
    </div>
  </div>
</div>
</body>