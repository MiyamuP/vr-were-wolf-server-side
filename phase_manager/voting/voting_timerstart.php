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
    $starttime = time();
    
    $sql_starttime = 'UPDATE phase_manager SET VotingTimekeeper = :starttime WHERE RoomName=:roomname';
    $stmn = $pdo -> prepare($sql_starttime);
    $params = array(':starttime' => $starttime, ':roomname' => $roomname);
    $stmn -> execute($params);
?>