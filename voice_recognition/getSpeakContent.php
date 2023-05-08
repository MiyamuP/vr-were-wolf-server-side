<?php
    $id = (int)$_GET['id'];
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

    $speak_array[] = array();

    if($id == 0) {
        $sql_speak_info = 'SELECT * FROM speak_content';
        $result = $pdo -> query($sql_speak_info);

        $count = 0;
        foreach($result as $res) {
            $speak_array[$count] = array('ID' => (int)$res['ID'], 'content' => $res['Content'], 'speaker_id' => (int)$res['Speaker']);
            $count++;
        }
    } else {
        $sql_speak_info = 'SELECT * FROM speak_content WHERE Speaker='.$id;
        $result = $pdo -> query($sql_speak_info);

        $count = 0;
        foreach($result as $res) {
            $speak_array[$count] = array('ID' => (int)$res['ID'], 'content' => $res['Content'], 'speaker_id' => (int)$res['Speaker']);
            $count++;
        }
    }

    echo json_encode($speak_array, JSON_UNESCAPED_UNICODE);

?>