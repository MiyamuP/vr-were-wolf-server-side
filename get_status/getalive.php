<?php
    $id = (int)$_GET['id'];
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

    $sql_getalive = 'SELECT * FROM '.$roomname.' WHERE ID=:id';
    $alive_stmt = $pdo -> prepare($sql_getalive);
    $alive_stmt -> execute(array(':id' => $id));

    $result = $alive_stmt->fetch(PDO::FETCH_ASSOC);

    $alive = array('alive' => $result['Alive']);

    echo json_encode($alive);
?>