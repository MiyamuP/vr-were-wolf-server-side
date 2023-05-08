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
        $starttime = $res['EveningTimekeeper'];
        $nowphase = $res['Phase'];
    }

    $nowtime = time();

    if ($starttime != NULL){
        $time = $nowtime - $starttime;
        if($time >= $change_time) {

            $sql_get_inf = 'SELECT * FROM '.$roomname.' WHERE ProtectTarget!=0 OR AttackTarget!=0';
            $gi_stmn = $pdo -> prepare($sql_get_inf);
            $gi_stmn -> execute();

            $protect_id = 0;

            $count = 0;

            $attack_id = 0;

            foreach ($gi_stmn as $res) {
                if($res['AttackTarget'] != 0 && $count == 0) {
                    $attack_id = (int)$res['AttackTarget'];
                    $count+=1;
                } else if($res['AttackTarget'] != 0 && $count != 0) {
                    $return_id = rand(0, $count);
                    $count+=1;
                    if($return_id == 0) {
                        $attack_id = (int)$res['AttackTarget'];
                    }
                } else if($res['ProtectTarget'] != 0) {
                    $protect_id = (int)$res['ProtectTarget'];
                }
            }

            if($attack_id == $protect_id) {

                $sql_death_regi = 'UPDATE room_manager SET BitedID=:targetid WHERE RoomName=:roomname';
                $at_stmn = $pdo -> prepare($sql_death_regi);
                $at_stmn -> execute(array(':targetid' => 0, ':roomname' => $roomname));

            } else {

                $sql_attack = 'UPDATE '.$roomname.' SET Alive=0 WHERE ID=:targetid';
                $at_stmn = $pdo -> prepare($sql_attack);
                $at_params = array(':targetid' => $attack_id);
                $at_stmn -> execute($at_params);

                $sql_death_regi = 'UPDATE room_manager SET BitedID=:targetid WHERE RoomName=:roomname';
                $dr_stmn = $pdo -> prepare($sql_death_regi);
                $dr_params = array(':targetid' => $attack_id, ':roomname' => $roomname);
                $dr_stmn -> execute($dr_params);

            }

            $sql_phase_change = 'UPDATE phase_manager SET Phase = :phase WHERE RoomName=:roomname';
            $ch_stmn = $pdo -> prepare($sql_phase_change);
            $ch_params = array(':phase' => 'meetingstart', ':roomname' => $roomname);
            $ch_stmn -> execute($ch_params);

            $rt_sql = 'UPDATE phase_manager SET EveningTimekeeper=NULL WHERE RoomName=:roomname';
            $rt_stmn = $pdo -> prepare($rt_sql);
            $rt_stmn -> execute(array(':roomname' => $roomname));

            $sql_get_inf = 'SELECT * FROM '.$roomname.' WHERE ProtectTarget!=0 OR AttackTarget!=0';

            $gi_stmn = $pdo -> prepare($sql_get_inf);
            $gi_stmn -> execute();
        }
        $return_nowtime = array('evening_time' => $time);
        echo json_encode($return_nowtime);
    } else {
        if(strcmp($nowphase, 'meetingstart') == 0) {
            $return_nowtime = array('evening_time' => $change_time);
            echo json_encode($return_nowtime);
        } else {
            $return_nowtime = array('evening_time' => 0);
            echo json_encode($return_nowtime);
        }
    }
?>