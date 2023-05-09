<?php
    session_start();
    // error_reporting(E_ALL);
    // ini_set('display_errors', 1);

    // include ('codebase/connector/gantt_connector.php');
    
    // $res = new PDO("mysql =>host=localhost;dbname=mmb-basic", "root", "835f4df86047416c588c1dbf8d322f0dc7b68be129d712b5");
    // // // $res = new PDO("mysql =>host=localhost;dbname=ibex", "root", "");
    
    // $programme_id = $_SESSION['gantt_id'];

    // // Error trap


        

    // $gantt = new JSONGanttConnector($res);
    // $gantt->event->attach("onDBError",doOnDBError);
    // $gantt->enable_order("sortorder");

    // $gantt->render_links("SELECT * FROM gantt_links WHERE programme_id='$programme_id' AND active='1'","id","source,target,type,programme_id,source_guid,target_guid,offset_minutes,offset_type,color,created");
    // $gantt->render_sql("SELECT * FROM gantt_tasks WHERE programme_id='$programme_id' AND active='1' ORDER BY order_ui ASC", "id","start_date,duration,text,progress,sortorder,color,parent,reference_number,baseline_start,baseline_end,deadline,type,calendar_id,programme_id,duration_worked,comments,guid,position,order_ui,end_date,completed,resource_count,resources_listed,opened,is_summary,baseline_duration_worked,parent_guid,duration_unit,non_working_periods,manually_delayed,budget_at_completion,baseline_resource_cost,resource_id,custom_permission_groups,files,timing_overriden,status,actual_costs,workload_quantity,workload_quantity_unit,resource_group_id,budget_cost_completion,actual_cost_completion,baseline_progress,price");


    // function doOnDBError($action, $exception)
    // {
    //     echo json_encode("DBError", $action);
    // }
    // $data = array(
    //     "data" => array(
    //         "id" => "1", "text" => "Project #2", "start_date" => "01-04-2023", "duration" => "18", "progress" => "0.4", "open" => true, "resource_group_id" => "0"
    //     ),
    //     "links" => array(
    //         "id" => "1", "source" => "1", "target" => "2", "type" => "1"
    //     )
    // );
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "mmb_basic2";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve data from the database
    $sql = "SELECT * FROM gantt_tasks";
    $result = $conn->query($sql);

    // Convert the data to a JSON object
    $data = array();
    if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    }
    echo json_encode($data);
    // print_r($data);

    // Close the database connection
    $conn->close();
?>