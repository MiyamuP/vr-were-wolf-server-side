<?php
    $roomname = urldecode($_GET['roomname']);
    $targetid = (int)$_GET['targetid'];
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

    $sql_protect = 'UPDATE '.$roomname.' SET protecttarget = :targetid WHERE Job = :job';
    $stmn = $pdo -> prepare($sql_protect);
    $params = array(':targetid' => $targetid, ':job' => 'knight');
    $stmn -> execute($params);
?>