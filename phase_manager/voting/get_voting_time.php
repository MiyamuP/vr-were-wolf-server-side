<?php
    $roomname = urldecode($_GET['roomname']);
    $change_time = 30;
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

    $sql_getphase = 'SELECT * FROM phase_manager WHERE RoomName = :roomname';
    $stmn = $pdo -> prepare($sql_getphase);
    $stmn -> execute(array(':roomname' => $roomname));

    foreach($stmn as $res) {
        $starttime = $res['VotingTimekeeper'];
        $nowphase = $res['Phase'];
    }

    $nowtime = time();

    if($starttime != NULL) {

        $time = $nowtime - $starttime;

        $return_nowtime = array('voting_time' => $time);
    
        echo json_encode($return_nowtime);

        if($time >= $change_time) {

            $sql_get_inf = 'SELECT * FROM '.$roomname.' WHERE VotingTarget!=0';
            $gi_stmn = $pdo -> prepare($sql_get_inf);
            $gi_stmn -> execute();

            $count_max = 0;
            $id = array();

            $test_id = array();

            $count = 0;

            $id_keep = array();

            $id_max=0;

            foreach($gi_stmn as $gi_res) {
                $id_keep = array_merge($id_keep, array($count => (int)$gi_res['VotingTarget']));
                $count+=1;
            }

            $count = 0;

            foreach($id_keep as $vares_2) {
                $id[(string)$vares_2] += 1;
            }

            foreach($id as $vares_3) {
                if($count_max<$vares_3) {
                    $count_max = $vares_3;
                    
                }
            }

            $count=0;

            $votemax_id = array();

            foreach($id as $vares_4 => $vaval) {
                if($count_max==$vaval && $vaval != 0) {
                    $votemax_id[$count] = (int)$vares_4;
                    $count++;
                }
            }

            if(count($votemax_id)>=2) {
                $targetid = rand(0, $count-1);
                $sql_hang = 'UPDATE '.$roomname.' SET Alive=0 WHERE ID=:id';
                $ha_stmn = $pdo -> prepare($sql_hang);
                $ha_stmn -> execute(array(':id' => $votemax_id[$targetid]));

                $sql_dead_add = 'UPDATE room_manager SET DeadID=:deadid WHERE RoomName=:roomname';
                $da_stmn = $pdo -> prepare($sql_dead_add);
                $da_stmn -> execute(array(':deadid' => $votemax_id[$targetid], ':roomname' => $roomname));

                $hang_return = array('ID' => $votemax_id[$targetid]);
            } else {
                $sql_hang = 'UPDATE '.$roomname.' SET Alive=0 WHERE ID=:id';
                $ha_stmn = $pdo -> prepare($sql_hang);
                $ha_stmn -> execute(array(':id' => $votemax_id[0]));

                $sql_dead_add = 'UPDATE room_manager SET DeadID=:deadid WHERE RoomName=:roomname';
                $da_stmn = $pdo -> prepare($sql_dead_add);
                $da_stmn -> execute(array(':deadid' => $votemax_id[0], ':roomname' => $roomname));
            }

            $sql_phase_change = 'UPDATE phase_manager SET Phase = :phase WHERE RoomName=:roomname';
            $ch_stmn = $pdo -> prepare($sql_phase_change);
            $ch_params = array(':phase' => 'votingresult', ':roomname' => $roomname);
            $ch_stmn -> execute($ch_params);

            $rt_sql = 'UPDATE phase_manager SET VotingTimekeeper = NULL WHERE RoomName=:roomname';
            $rt_stmn = $pdo -> prepare($rt_sql);
            $rt_stmn -> execute(array(':roomname' => $roomname));
        }

    } else {

        if(strcmp($nowphase, 'votingresult') == 0) {

            $return_nowtime = array('voting_time' => $change_time);
            echo json_encode($return_nowtime);
        } else {
            $return_nowtime = array('voting_time' => 0);
            echo json_encode($return_nowtime);
        }
    }
?>