<?php
    $roomname = urldecode($_GET['roomname']);
    try{
        $pdo=new PDO('mysql:host=mysql1.php.xdomain.ne.jp;dbname=vrwerewolf_data;charset=utf8', 'vrwerewolf_host', 'rhpVfYYnK2R96yH');
    } catch (Exception $e) {
        /*接続に失敗*/
        $arrayFailure = [
            'state' => 'FAILURE'
        ];
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($arrayFailure);
        die();
    }

    $sql_dead_id = 'SELECT * FROM room_manager WHERE RoomName=:roomname';
    $id_stmt = $pdo -> prepare($sql_dead_id);
    $id_stmt -> execute(array(':roomname' => $roomname));

    $id_result = $id_stmt->fetch(PDO::FETCH_ASSOC);

    $sql_getjob = 'SELECT * FROM '.$roomname.' WHERE ID=:id';
    $job_stmt = $pdo -> prepare($sql_getjob);
    $job_stmt -> execute(array(':id' => $id_result['DeadID']));

    $result = $job_stmt->fetch(PDO::FETCH_ASSOC);

    if($result['Job'] == 'wolf') {
        $return_color = array('divineresult' => 'black');
    } else {
        $return_color = array('divineresult' => 'white');
    }

    echo json_encode($return_color);
?>