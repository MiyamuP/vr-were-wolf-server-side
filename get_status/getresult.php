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

    $sql_gettimeskip = 'SELECT * FROM '.$roomname.' WHERE Alive=1';
    $gts_stmn = $pdo -> prepare($sql_gettimeskip);
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
        $result = array('result' => 'wolf');
        echo json_encode($result);
    } elseif($wolf_count == 0) {
        $result = array('result' => 'human');
        echo json_encode($result);
    } else {
        $result = array('result' => 'continue');
        echo json_encode($result);
    }
?>