<?php
    $playernum = $_GET['playernum'];
    $roomname = $_GET['roomname'];
    try{
        $pdo=new PDO('mysql:host=mysql1.php.xdomain.ne.jp;dbname=vrwerewolf_data;charset=utf8', 'vrwerewolf_host', 'rhpVfYYnK2R96yH');
    } catch (Exception $e) {
        /*接続に失敗*/
        $arrayFailure = array('createroom' => false);
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($arrayFailure);
        die();
    }

    try{
        $sql_make_room = 'CREATE TABLE '.$roomname.' (ID INT(11), UserName VARCHAR(255), Job VARCHAR(255), Alive TINYINT(1), TimeSkip TINYINT(1), TimeExtension TINYINT(1), VotingTarget INT(11), AttackTarget INT(11), ProtectTarget INT(11), DivineTarget INT(11))engine=innodb default charset=utf8';
        $cr_result = $pdo -> prepare($sql_make_room);
        $cr_result -> execute();

        $sql_user_add = 'INSERT INTO room_manager (RoomName, PlayerNum) VALUES (:roomname, :playernum)';
        $add_stmn = $pdo -> prepare($sql_user_add);
        $params = array(':roomname' => $roomname, ':playernum' => $playernum);
        $add_stmn -> execute($params);

        $sql_phase_add = "INSERT INTO phase_manager (RoomName, Phase, JobdisplayTimekeeper, FirsteveningTimekeeper, MeetingstartTimekeeper, MeetingTimekeeper, VotingTimekeeper, VotingresultTimekeeper, EveningTimekeeper) VALUES (:roomname, :phase, NULL, NULL, NULL, NULL, NULL, NULL, NULL)";
        $pa_stmn = $pdo -> prepare($sql_phase_add);
        $phase_params = array(':roomname' => $roomname, ':phase' => 'initialize');
        $pa_stmn -> execute($phase_params);

        echo json_encode(array('createroom' => true));
    } catch (Exception $e) {
        /*接続に失敗*/
        $arrayFailure = array('createroom' => false);
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($arrayFailure);
        die();
    }
?>