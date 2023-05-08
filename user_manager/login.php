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

    $stmt = $pdo->prepare('SELECT * FROM user_manager WHERE UserName = :username');
    $stmt->execute(array(':username' => $username));

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if(password_verify($password, $result['PassWord'])){
        $array = array('passresult' => true);
        echo json_encode($array);
    }else{
        $array = array('passresult' => false);
        echo json_encode($array);
    } 
?>