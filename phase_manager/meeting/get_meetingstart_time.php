<?php
    $roomname = urldecode($_GET['roomname']);
    $change_time = 10;

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
        $starttime = $res['MeetingstartTimekeeper'];
        $nowphase = $res['Phase'];
    }

    $nowtime = time();

    if($starttime!=NULL) {
        $time = $nowtime - $starttime;

        $return_nowtime = array('meetingstart_time' => $time);
    
        echo json_encode($return_nowtime);

        if($nowtime - $starttime >= $change_time) {

            $sql_get_aliver = 'SELECT * FROM '.$roomname.' WHERE Alive=1';
            $gts_stmn = $pdo -> prepare($sql_get_aliver);
            $gts_stmn -> execute();

            $wolf_count = 0;
            $citizenside_count = 0;

            foreach($gts_stmn as $res) {
                if(strcmp($res['Job'], 'wolf') == 0) {
                    $wolf_count++;
                } else {
                    $citizenside_count++;
                }
            }

            if($wolf_count >= $citizenside_count) {

                $sql_phase_change = 'UPDATE phase_manager SET Phase = :phase WHERE RoomName=:roomname';
                $ch_stmn = $pdo -> prepare($sql_phase_change);
                $ch_params = array(':phase' => 'end_wolfwin', ':roomname' => $roomname);
                $ch_stmn -> execute($ch_params);

            } elseif($wolf_count == 0) {

                $sql_phase_change = 'UPDATE phase_manager SET Phase = :phase WHERE RoomName=:roomname';
                $ch_stmn = $pdo -> prepare($sql_phase_change);
                $ch_params = array(':phase' => 'end_humanwin', ':roomname' => $roomname);
                $ch_stmn -> execute($ch_params);

            } else {
                $sql_phase_change = 'UPDATE phase_manager SET Phase = :phase WHERE RoomName=:roomname';
                $ch_stmn = $pdo -> prepare($sql_phase_change);
                $ch_params = array(':phase' => 'meeting', ':roomname' => $roomname);
                $ch_stmn -> execute($ch_params);

                $rt_sql = 'UPDATE phase_manager SET MeetingstartTimekeeper=NULL WHERE RoomName=:roomname';
                $rt_stmn = $pdo -> prepare($rt_sql);
                $rt_stmn -> execute(array(':roomname' => $roomname));

                $sql_reset_protect = 'UPDATE '.$roomname.' SET ProtectTarget = 0 WHERE Job = :job';
                $rp_stmn = $pdo -> prepare($sql_reset_protect);
                $rp_params = array(':job' => 'knight');
                $rp_stmn -> execute($rp_params);

                $sql_reset_attack = 'UPDATE '.$roomname.' SET AttackTarget = 0 WHERE Job = :job';
                $ra_stmn = $pdo -> prepare($sql_reset_attack);
                $ra_params = array(':job' => 'wolf');
                $ra_stmn -> execute($ra_params);
            }
        }
    } else {
        if(strcmp($nowphase, 'meeting') == 0 || strcmp($nowphase, 'end_humanwin') == 0 || strcmp($nowphase, 'end_wolfwin') == 0) {
            $return_nowtime = array('meetingstart_time' => $change_time);
            echo json_encode($return_nowtime);
        } else {
            $return_nowtime = array('meetingstart_time' => 0);
            echo json_encode($return_nowtime);
        }
    }
    
?>