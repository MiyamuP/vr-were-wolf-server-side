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

    $sql_getphase = 'SELECT * FROM phase_manager WHERE RoomName=:roomname';
    $stmn = $pdo -> prepare($sql_getphase);
    $stmn -> execute(array(':roomname' => $roomname));

    foreach($stmn as $res) {
        $starttime = $res['FirsteveningTimekeeper'];
        $nowphase = $res['Phase'];
    }

    $nowtime = time();

    if ($starttime!=NULL){
        $time = $nowtime - $starttime;

        $return_nowtime = array('firstevening_time' => $time);
    
        echo json_encode($return_nowtime);

        if($time >= 30) {
            $sql_phase_change = 'UPDATE phase_manager SET Phase = :phase WHERE RoomName=:roomname';
            $ch_stmn = $pdo -> prepare($sql_phase_change);
            $ch_params = array(':phase' => 'meeting', ':roomname' => $roomname);
            $ch_stmn -> execute($ch_params);

            $rt_sql = 'UPDATE phase_manager SET FirsteveningTimekeeper = NULL WHERE RoomName=:roomname';
            $rt_stmn = $pdo -> prepare($rt_sql);
            $rt_stmn -> execute(array(':roomname' => $roomname));
        }
    } else {
        if(strcmp($nowphase, 'meeting') == 0) {

            $return_nowtime = array('firstevening_time' => 30);
            echo json_encode($return_nowtime);
        } else {
            $return_nowtime = array('firstevening_time' => 0);
            echo json_encode($return_nowtime);
        }
    }
?>