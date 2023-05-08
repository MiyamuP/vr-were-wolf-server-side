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

    $sql_get_alive = 'SELECT * FROM '.$roomname.' WHERE Alive=1';
    $result = $pdo -> prepare($sql_get_alive);
    $result -> execute();

    $count = 0;

    foreach($result as $res) {
        $alive_list[$count] = array(
            'id' => $res['ID'],
            'username' => $res['UserName'],
            'job' => $res['Job'],
            'alive' => $res['Alive'],
            'votingtarget' => $res['VotingTarget'],
            'attacktarget' => $res['AttackTarget'],
            'protecttarget' => $res['ProtectTarget'],
            'divinetarget' => $res['DivineTarget'],
        );
        $count++;
    }

    echo json_encode($alive_list, JSON_UNESCAPED_UNICODE);