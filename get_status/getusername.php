<?php
    $id = (int)$_GET['id'];
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

    $sql_getusername = 'SELECT * FROM '.$roomname.' WHERE ID=:id';
    $user_stmt = $pdo -> prepare($sql_getusername);
    $user_stmt -> execute(array(':id' => $id));

    $result = $user_stmt->fetch(PDO::FETCH_ASSOC);

    $username = array('username' => $result['UserName']);

    echo json_encode($username, JSON_UNESCAPED_UNICODE);
?>