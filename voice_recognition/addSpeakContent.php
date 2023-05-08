<?php
    $id = (int)$_GET['id'];
    $content = urldecode($_GET['content']);

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

    $sql = 'SELECT COUNT(*) AS cnt FROM speak_content';
    $res = $pdo -> query($sql);
    $row = $res -> fetch(PDO::FETCH_ASSOC);

    $sql_addcontent = 'INSERT INTO speak_content(ID, Content, Speaker) VALUE (:ID, :Content, :Speaker)';
    $stmn = $pdo -> prepare($sql_addcontent);
    $params = array(':ID' => (int)$row['cnt']+1, ':Content' => $content, ':Speaker' => (int)$id);
    $stmn -> execute($params);
    
?>