<?php
    $id = (int)$_GET['id'];
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

    $sql_gettimeex = 'SELECT * FROM player_manager WHERE ID = '.$id;
    $gte_stmn = $pdo -> query($sql_gettimeex);

    foreach($gte_stmn as $res) {
        $timeex = array('timeex' => (boolean)$res['TimeExtension']);
    }

    echo json_encode($timeex);
?>