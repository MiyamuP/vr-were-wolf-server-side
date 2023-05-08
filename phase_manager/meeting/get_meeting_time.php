<?php
    $roomname = urldecode($_GET['roomname']);

    $change_time = 60;
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

    $sql_getphase = 'SELECT * FROM phase_manager WHERE RoomName=:roomname';
    $stmn = $pdo -> prepare($sql_getphase);
    $stmn -> execute(array(':roomname' => $roomname));

    foreach($stmn as $res) {
        $starttime = $res['MeetingTimekeeper'];
        $nowphase = $res['Phase'];
    }

    $nowtime = time();

    if($starttime!=NULL) {
        $time = $nowtime - $starttime;

        $return_nowtime = array('meeting_time' => $time);
    
        echo json_encode($return_nowtime);

        if($time >= $change_time) {
            $sql_phase_change = 'UPDATE phase_manager SET Phase = :phase WHERE RoomName=:roomname';
            $ch_stmn = $pdo -> prepare($sql_phase_change);
            $ch_params = array(':phase' => 'voting', ':roomname' => $roomname);
            $ch_stmn -> execute($ch_params);

            $rt_sql = 'UPDATE phase_manager SET MeetingTimekeeper = NULL WHERE RoomName=:roomname';
            $rt_stmn = $pdo -> prepare($rt_sql);
            $rt_stmn -> execute(array(':roomname' => $roomname));
    
            $sql_reset_dt = 'UPDATE '.$roomname.' SET TimeSkip = 0, TimeExtension = 0';
            $dtstmn = $pdo -> prepare($sql_reset_dt);
            $dtstmn -> execute();
        }
    } else {
        if(strcmp($nowphase, 'voting') == 0) {
            $return_nowtime = array('meeting_time' => $change_time);
            echo json_encode($return_nowtime);
        } else {
            $return_nowtime = array('meeting_time' => 0);
            echo json_encode($return_nowtime);
        }
        
    }
?>