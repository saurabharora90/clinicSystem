<?php
    function confirm_query($result_set) {
		if (!$result_set) {
			die("Database query failed: " . sqlsrv_errors());
		}
	}
    
    function get_all_doctors() {
		global $conn;
		$query = "SELECT * 
				FROM doctor 
				ORDER BY name ASC";
		$doctor_set = sqlsrv_query($conn, $query);
		confirm_query($doctor_set);
		return $doctor_set;
	}
        
    function get_all_patients() {
		global $conn;
		$query = "SELECT * 
				FROM patient 
				ORDER BY name ASC";
		$NSpatient_set = sqlsrv_query($conn, $query);
		confirm_query($NSpatient_set);
		return $NSpatient_set;
	}

        function get_all_drugs() {
		global $conn;
		$query = "SELECT * 
				FROM drug 
				ORDER BY commercial_name ASC";
		$NSdrug_set = sqlsrv_query($conn, $query);
		confirm_query($NSdrug_set);
		return $NSdrug_set;
	}

	function get_doctor_by_id($doctor_id) {
		global $conn;
		$query = "SELECT TOP 1 * ";
		$query .= "FROM doctor ";
		$query .= "WHERE id_number='{$doctor_id}' ";
		$result_set = sqlsrv_query($conn, $query);
		confirm_query($result_set);
		// REMEMBER:
		// if no rows are returned, fetch_array will return false
		if ($subject = sqlsrv_fetch_array($result_set)) {
			return $subject;
		} else {
			return NULL;
		}
	}

    	function get_patient_by_id($patient_id) {
		global $conn;
		$query = "SELECT TOP 1 * ";
		$query .= "FROM patient ";
		$query .= "WHERE id_number='{$patient_id}' ";
		$result_set = sqlsrv_query($conn, $query);
		confirm_query($result_set);
		// REMEMBER:
		// if no rows are returned, fetch_array will return false
		if ($subject = sqlsrv_fetch_array($result_set)) {
			return $subject;
		} else {
			return NULL;
		}
	}

       function get_drug_by_id($name, $man) {
		global $conn;
		$query = "SELECT TOP 1 * ";
		$query .= "FROM drug ";
		$query .= "WHERE commercial_name = '" . $name . "' AND manufacturer = '". $man ."';";
		$result_set = sqlsrv_query($conn, $query);
		confirm_query($result_set);
		// REMEMBER:
		// if no rows are returned, fetch_array will return false
		if ($subject = sqlsrv_fetch_array($result_set)) {
			return $subject;
		} else {
			return NULL;
		}
	}
    
    function find_selected_doc() {
        global $selected_doc;
        if (isset($_POST['doctor_id'])) {
			$selected_doc = get_doctor_by_id($_POST['doctor_id']);
		} else {
			$selected_doc = NULL;
		}
    }

        function find_selected_patient() {
        if (isset($_POST['patient_id'])) {
			$row_patient = get_patient_by_id($_POST['patient_id']);
            return $row_patient;
		} else {
			return NULL;
		}
    }

       function find_selected_drug() {
        if (isset($_POST['drug_id'])) {
            $id_data = explode(":", $_POST['drug_id']);
			$row_drug = get_drug_by_id($id_data[0], $id_data[1]);
            return $row_drug;
		} else {
			return NULL;
		}
    }

    function check_if_patient_exists($NSpatient_id){

        if(!isset($NSpatient_id))
            return FALSE;

        global $conn;
		$query = "SELECT TOP 1 * ";
		$query .= "FROM patient ";
		$query .= "WHERE id_number='{$NSpatient_id}' ";
		$result_set = sqlsrv_query($conn, $query);
		confirm_query($result_set);
		// REMEMBER:
		// if no rows are returned, fetch_array will return false
		if (sqlsrv_has_rows($result_set)) {
			return TRUE;
		} else {
			return FALSE;
		}
    }

    function check_if_drug_exists($NSdrug_name, $NSdrug_man)
    {
        if(!isset($NSdrug_name) || !(isset($NSdrug_man)))
            return FALSE;

        global $conn;
		$query = "SELECT TOP 1 * ";
		$query .= "FROM drug ";
		$query .= "WHERE commercial_name='{$NSdrug_name}' ";
        $query .= "AND manufacturer='{$NSdrug_man}'";
		$result_set = sqlsrv_query($conn, $query);
		confirm_query($result_set);
		// REMEMBER:
		// if no rows are returned, fetch_array will return false
		if (sqlsrv_has_rows($result_set)) {
			return TRUE;
		} else {
			return FALSE;
		}


    }

    
	function redirect_to( $location = NULL ) {
		if ($location != NULL) {
			header("Location: {$location}");
			exit;
		}
	}

        function search_patient() {
        global $conn;
        global $result;
        global $message;
        $and_needed = FALSE;

        $query = "SELECT * FROM patient WHERE ";
                
                if (!empty($_POST['search_id'])) {$query .= "id_number LIKE '%" . $_POST['search_id'] . "%' "; $and_needed = TRUE; }
                if (!empty($_POST['search_name'])) {
                    if ($and_needed)  $query .= " AND ";
                    $query .= "name LIKE '%" . $_POST['search_name'] . "%'"; 
                    $and_needed = TRUE;
                }
                if (!empty($_POST['search_contact'])) {
                    if ($and_needed)  $query .= " AND ";
                    $query .= "contact_number LIKE '%" . $_POST['search_contact'] . "%'";
                    $and_needed = TRUE; 
                }
                if (!empty($_POST['search_address'])) {
                    if ($and_needed)  $query .= " AND ";
                    $query .= "address LIKE '%" . $_POST['search_address'] . "%'"; 
                    $and_needed = TRUE;
                }
                if (!empty($_POST['search_gender'])) {
                    if ($and_needed)  $query .= " AND ";
                    $query .= "gender LIKE '%" . $_POST['search_gender'] . "%'"; 
                    $and_needed = TRUE;
                }
                if (!empty($_POST['search_bg'])) {
                    if ($and_needed)  $query .= " AND ";
                    $query .= "blood_group LIKE '%" . $_POST['search_bg'] . "%'"; 
                    $and_needed = TRUE;
                }
                if (!empty($_POST['search_dob'])) {
                    if ($and_needed)  $query .= " AND ";
                    $query .= "CONVERT(VARCHAR(25),date_of_birth,126) LIKE '%" .date_format($_POST['search_dob'], 'Y-m-d') . "%'"; 
                    $and_needed = TRUE;
                }

				$result = sqlsrv_query($conn, $query);
                //echo $query;

				if (count($result) > 0) {
					// Success
					$message = "Yes here are the results";
				} else {
					// Failed
					$message = "No result.";
					$message .= "<br />". sqlsrv_errors();
				}
    }

    function search_doctor() {
        global $conn;
        global $result;
        global $message;
        $and_needed = FALSE;

        $query = "SELECT * FROM doctor WHERE ";
                
                if (!empty($_POST['search_id'])) {$query .= "id_number LIKE '%" . $_POST['search_id'] . "%' "; $and_needed = TRUE; }
                if (!empty($_POST['search_name'])) {
                    if ($and_needed)  $query .= " AND ";
                    $query .= "name LIKE '%" . $_POST['search_name'] . "%'"; 
                    $and_needed = TRUE;
                }
                if (!empty($_POST['search_contact'])) {
                    if ($and_needed)  $query .= " AND ";
                    $query .= "contact_number LIKE '%" . $_POST['search_contact'] . "%'";
                    $and_needed = TRUE; 
                }
                if (!empty($_POST['search_spec'])) {
                    if ($and_needed)  $query .= " AND ";
                    $query .= "specialization LIKE '%" . $_POST['search_spec'] . "%'"; 
                    $and_needed = TRUE;
                }
                if (!empty($_POST['search_dep'])) {
                    if ($and_needed)  $query .= " AND ";
                    $query .= "department LIKE '%" . $_POST['search_dep'] . "%'"; 
                    $and_needed = TRUE;
                }

				$result = sqlsrv_query($conn, $query);
                //echo $query;

				if (count($result) > 0) {
					// Success
					$message = "Yes here are the results";
				} else {
					// Failed
					$message = "No result.";
					$message .= "<br />". sqlsrv_errors();
				}
    }

     function search_doctor_from_view($name) {
        global $conn;
        
        $query = "SELECT * FROM doctor_basic WHERE name LIKE '%{$name}%'";

				$result = sqlsrv_query($conn, $query);
                confirm_query($result);
                //echo $query;
        return $result;
    }

    function search_drug() {
        global $conn;
        global $result;
        global $message;
        $and_needed = FALSE;

        $query = "SELECT * FROM drug WHERE ";
                
                if (!empty($_POST['search_man'])) {$query .= "manufacturer LIKE '%" . $_POST['search_man'] . "%' "; $and_needed = TRUE; }
                if (!empty($_POST['search_name'])) {
                    if ($and_needed)  $query .= " AND ";
                    $query .= "commercial_name LIKE '%" . $_POST['search_name'] . "%'"; 
                    $and_needed = TRUE;
                }
                if (!empty($_POST['search_ingre'])) {
                    if ($and_needed)  $query .= " AND ";
                    $query .= "active_ingredient LIKE '%" . $_POST['search_ingre'] . "%'";
                    $and_needed = TRUE; 
                }

				$result = sqlsrv_query($conn, $query);
                //echo $query;

				if (count($result) > 0) {
					// Success
					$message = "Yes here are the results";
				} else {
					// Failed
					$message = "No result.";
					$message .= "<br />". sqlsrv_errors();
				}
    }

    function search_visit($doctor, $patient, $start_date, $end_date) {
        global $conn;
        global $message;
        $and_needed = FALSE;

        $query = "SELECT * FROM visit WHERE ";
                
                if (!empty($doctor)) {$query .= " doctor LIKE '%" . $doctor . "%' "; $and_needed = TRUE; }
                if (!empty($patient)) {
                    if ($and_needed)  $query .= " AND ";
                    $query .= " patient LIKE '%" . $patient . "%' "; 
                    $and_needed = TRUE;
                }
                if (!empty($start_date)) {
                    $date = date_create_from_format('d/m/Y', $start_date);
                    $date = $date->format('Y-m-d');
                    if ($and_needed)  $query .= " AND ";
                    $query .= " CONVERT(VARCHAR(25),datetime,126) >= '" . $date . "%' ";
                    $and_needed = TRUE; 
                }
                if (!empty($end_date)) {
                    $date = date_create_from_format('d/m/Y', $end_date);
                    $date = $date->format('Y-m-d');
                    if ($and_needed)  $query .= " AND ";
                    $query .= " CONVERT(VARCHAR(25),datetime,126) <= '" . $date . "%' ";
                    $and_needed = TRUE;
                }

				$result = sqlsrv_query($conn, $query);

        return $result;
    }



    function get_visit($datetime) {
        global $conn;
		$query = "SELECT TOP 1 * ";
		$query .= "FROM visit ";
		$query .= "WHERE CONVERT(VARCHAR(25),datetime,126) LIKE '{$datetime}%' ";
		$result_set = sqlsrv_query($conn, $query);
		confirm_query($result_set);
		// REMEMBER:
		// if no rows are returned, fetch_array will return false
        // echo $query;
		if ($subject = sqlsrv_fetch_array($result_set)) {
			return $subject;
		} else {
			return NULL;
		}
    }

    function get_prescription($visit) {
        global $conn;
		$query = "SELECT * ";
		$query .= "FROM prescribe ";
		$query .= "WHERE patient = '". $visit['patient'] ."' AND doctor = '". $visit['doctor'] . "' ";
        $query .= " AND CONVERT(VARCHAR(25),datetime,126) LIKE '". date_format($visit['datetime'], 'Y-m-d\TH:i:s%'). "'";

		$result_set = sqlsrv_query($conn, $query, $params);
		confirm_query($result_set);
		// REMEMBER:
		// if no rows are returned, fetch_array will return false
        // echo $query;
        return $result_set;
    }

    function get_price($drug, $man) {
        global $conn;
		$query = "SELECT selling_unit_price AS price ";
		$query .= "FROM drug ";
		$query .= "WHERE commercial_name = '". $drug ."' AND manufacturer = '". $man . "' ";

		$result = sqlsrv_query($conn, $query, $params);
		confirm_query($result);
		// REMEMBER:
		// if no rows are returned, fetch_array will return false
        // echo $query;
        $price_single = sqlsrv_fetch_array($result);
        $price = $price_single["price"];
        return $price;
    }

    function get_doc_name($doctor_id) {
        global $conn;
		$query = "SELECT TOP 1 name ";
		$query .= "FROM doctor ";
		$query .= "WHERE id_number='{$doctor_id}' ";
		$result_set = sqlsrv_query($conn, $query);
		confirm_query($result_set);
		// REMEMBER:
		// if no rows are returned, fetch_array will return false
		if ($subject = sqlsrv_fetch_array($result_set)) {
			return $subject['name'];
		} else {
			return NULL;
		}
    }

    function get_patient_name($patient_id) {
        global $conn;
		$query = "SELECT TOP 1 name ";
		$query .= "FROM patient ";
		$query .= "WHERE id_number='{$patient_id}' ";
		$result_set = sqlsrv_query($conn, $query);
		confirm_query($result_set);
		// REMEMBER:
		// if no rows are returned, fetch_array will return false
		if ($subject = sqlsrv_fetch_array($result_set)) {
			return $subject['name'];
		} else {
			return NULL;
		}
    }

    function get_patients_all_docs()
    {
        global $conn;
        $query = "SELECT p.name, p.id_number 
                    FROM patient p
                    WHERE NOT EXISTS 
                            (SELECT * 
                             FROM doctor d 
                             WHERE NOT EXISTS
                                 (SELECT * 
                                  FROM visit v
                                  WHERE v.doctor = d.id_number AND v.patient = p.id_number))";
        $result_set = sqlsrv_query($conn, $query);
		confirm_query($result_set);
        return $result_set;
    }

        function get_docs_all_patients()
    {
        global $conn;
        $query = "SELECT d.name, d.id_number 
                    FROM doctor d
                    WHERE NOT EXISTS 
                            (SELECT * 
                             FROM patient p 
                             WHERE NOT EXISTS
                                 (SELECT * 
                                  FROM visit v
                                  WHERE v.doctor = d.id_number AND v.patient = p.id_number))";
        $result_set = sqlsrv_query($conn, $query);
		confirm_query($result_set);
        return $result_set;
    }

    function get_visit_today()
    {
        global $conn;
        $query = "SELECT COUNT(*) AS count
                    FROM visit v
                    WHERE CONVERT(VARCHAR(25),v.datetime,112) = CONVERT(VARCHAR(25),getdate(),112)";
        // echo $query;
        $result_set = sqlsrv_query($conn, $query);
		confirm_query($result_set);

        $result = sqlsrv_fetch_array($result_set);
        return $result['count'];
    }

    function get_drug_today()
    {
        global $conn;
        $query = "SELECT COUNT(DISTINCT p.drug) AS count
                    FROM prescribe p
                    WHERE CONVERT(VARCHAR(25),p.datetime,112) = CONVERT(VARCHAR(25),getdate(),112)";
        // echo $query;
        $result_set = sqlsrv_query($conn, $query);
		confirm_query($result_set);

        $result = sqlsrv_fetch_array($result_set);
        return $result['count'];
    }

    function get_doctor_today()
    {
        global $conn;
        $query = "SELECT COUNT(DISTINCT v.doctor) AS count
                    FROM visit v
                    WHERE CONVERT(VARCHAR(25),v.datetime,112) = CONVERT(VARCHAR(25),getdate(),112)";
        // echo $query;
        $result_set = sqlsrv_query($conn, $query);
		confirm_query($result_set);

        $result = sqlsrv_fetch_array($result_set);
        return $result['count'];
    }

    function get_patients_today() {
        global $conn;
        $query = "SELECT v.doctor AS doctor_name, COUNT(DISTINCT v.patient) AS count
                    FROM visit v
                    WHERE CONVERT(VARCHAR(25),v.datetime,112) = CONVERT(VARCHAR(25),getdate(),112)
                    GROUP BY v.doctor";
        // echo $query;
        $result_set = sqlsrv_query($conn, $query);
		confirm_query($result_set);
        return $result_set;
    }
?>
