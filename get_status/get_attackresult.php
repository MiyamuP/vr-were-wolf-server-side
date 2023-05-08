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

    $sql_get_id = 'SELECT * FROM room_manager WHERE RoomName=:roomname';
    $gi_stmt = $pdo -> prepare($sql_get_id);
    $gi_stmt -> execute(array(':roomname' => $roomname));

    $gi_res = $gi_stmt->fetch(PDO::FETCH_ASSOC);

    $biteid = (int)$gi_res['BitedID'];

    $sql_get_ar = 'SELECT * FROM '.$roomname.' WHERE ID=:id';
    $ar_stmt = $pdo -> prepare($sql_get_ar);
    $ar_stmt -> execute(array(':id' => $biteid));

    $ar_list = array();

    $attack_F = 0;

    foreach($ar_stmt as $ar_res) {
        $ar_list = array(
            'id' => $ar_res['ID'],
            'username' => $ar_res['UserName'],
            'job' => $ar_res['Job'],
            'alive' => $ar_res['Alive'],
            'votingtarget' => $ar_res['VotingTarget'],
            'attacktarget' => $ar_res['AttackTarget'],
            'protecttarget' => $ar_res['ProtectTarget'],
            'divinetarget' => $ar_res['DivineTarget']
        );
        $attack_F = 1;
    }

    if($attack_F == 0) {
        $ar_list = array('id' => -1);
    }

    echo json_encode($ar_list);
?>