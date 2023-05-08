<?php
    $id = (int)$_GET['id'];
    $roomname = $_GET['roomname'];

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

    $sql_leave = 'DELETE FROM '.$roomname.' WHERE id = :id';
    $result = $pdo -> prepare($sql_leave);
    $result -> execute(array(':id' => $id));

    $sql_player_cnt = 'SELECT COUNT(*) AS cnt FROM '.$roomname;
    $pcstmn = $pdo -> prepare($sql_player_cnt);
    $pcstmn -> execute();
    $playercnt = $pcstmn->fetch(PDO::FETCH_ASSOC);

    if($playercnt['cnt'] == 0) {
        $sql_del_phase = 'DELETE FROM phase_manager WHERE RoomName = :roomname';
        $dropres = $pdo -> prepare($sql_del_phase);
        $dropres -> execute(array(':roomname' => $roomname));

        $sql_del_room = 'DELETE FROM room_manager WHERE RoomName = :roomname';
        $dropres = $pdo -> prepare($sql_del_room);
        $dropres -> execute(array(':roomname' => $roomname));

        $sql_drop_room = 'DROP TABLE '.$roomname;
        $dropres = $pdo -> prepare($sql_drop_room);
        $dropres -> execute();
    }
?>