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
    $gp_stmn = $pdo -> prepare($sql_getphase);
    $gp_params = array(':roomname' => $roomname);
    $gp_stmn -> execute($gp_params);

    foreach($gp_stmn as $res) {
        $starttime = $res['JobdisplayTimekeeper'];
    }

    $nowtime = time();

    if ($starttime!=NULL){
        $time = $nowtime - $starttime;
        $return_nowtime = array('jobdisplay_time' => $time);
        echo json_encode($return_nowtime);

        if($time>=20 ) {
            $cp_sql = 'UPDATE phase_manager SET Phase=:phase WHERE RoomName=:roomname';
            $cp_stmn = $pdo -> prepare($cp_sql);
            $cp_stmn -> execute(array(':phase' => 'firstevening', ':roomname' => $roomname));

            $rt_sql = 'UPDATE phase_manager SET JobdisplayTimekeeper = NULL WHERE RoomName=:roomname';
            $rt_stmn = $pdo -> prepare($rt_sql);
            $rt_stmn -> execute(array(':roomname' => $roomname));
        }
    } else {
        $return_nowtime = array('jobdisplay_time' => 0);
        echo json_encode($return_nowtime);
    }
?>