<?php
    $password = $_GET['password'];
    $username = urldecode($_GET['username']);
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

    $sql = 'SELECT COUNT(*) AS cnt FROM user_manager WHERE UserName=:username';
    $res = $pdo -> prepare($sql);
    $res -> execute(array(':username' => $username));
    $row = $res -> fetch(PDO::FETCH_ASSOC);

    if($row['cnt'] == 0) {
        $stmn = $pdo -> prepare('INSERT INTO user_manager(UserName, PassWord, Rating) VALUES (:username, :password, 0)');
        $stmn -> execute(array(':username' => $username, ':password' => password_hash($password, PASSWORD_DEFAULT)));
        $array = array('addresult' => true);
        echo json_encode($array);
    } else {
        $array = array('addresult' => false);
        echo json_encode($array);
    }
?>