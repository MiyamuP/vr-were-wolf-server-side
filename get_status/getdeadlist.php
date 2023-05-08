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

    $sql_get_dead = 'SELECT * FROM '.$roomname.' WHERE Alive=0';
    $result = $pdo -> prepare($sql_get_dead);
    $result -> execute();

    $count = 0;

    foreach($result as $res) {
        $dead_list[$count] = array(
            'ID' => $res['ID'],
            'UserName' => $res['UserName'],
            'Job' => $res['Job'],
            'Alive' => $res['Alive'],
            'TimeSkip' => $res['TimeSkip'],
            'TimeExtension' => $res['TimeExtension'],
            'VotingTarget' => $res['VotingTarget'],
            'AttackTarget' => $res['AttackTarget'],
            'ProtectTarget' => $res['ProtectTarget'],
            'DivineTarget' => $res['DivineTarget'],
            'OKflag' => $res['OKflag']
        );
        $count++;
    }

    echo json_encode($dead_list, JSON_UNESCAPED_UNICODE);