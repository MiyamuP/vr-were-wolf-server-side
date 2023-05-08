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

    $sql_divine = 'SELECT * FROM '.$roomname.' WHERE ID=:targetid';
    $stmt = $pdo -> prepare($sql_divine);
    $params = array(':targetid' => $targetid);
    $stmt -> execute($params);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if($result['Job'] == 'wolf') {
        $return_color = array('divineresult' => 'black');
    } else {
        $return_color = array('divineresult' => 'white');
    }

    echo json_encode($return_color);
?>