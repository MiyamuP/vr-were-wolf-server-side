<?php
    $id = (int)$_GET['id'];
    $username = urldecode($_GET['username']);
    $roomname = urldecode($_GET['roomname']);
    try{
        $pdo=new PDO('mysql:host=mysql1.php.xdomain.ne.jp;dbname=vrwerewolf_data;charset=utf8', 'vrwerewolf_host', 'rhpVfYYnK2R96yH');
    } catch (Exception $e) {
        /*接続に失敗*/
        $arrayFailure = array('joinresult' => false);
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($arrayFailure);
        die();
    }

    try{
        /*接続に成功*/

        $sql_user_add = 'INSERT INTO '.$roomname.'(ID, UserName, Job, Alive, TimeSkip, TimeExtension, VotingTarget, AttackTarget, ProtectTarget, DivineTarget) VALUES (:id, :username, null, 1, 0, 0, 0, 0, 0, 0)';
        $add_stmn = $pdo -> prepare($sql_user_add);
        $params = array(':id' => $id, ':username' => $username);
        $add_stmn -> execute($params);

        $stmt = $pdo->prepare('SELECT * FROM room_manager WHERE RoomName = :roomname');
        $stmt->execute(array(':roomname' => $roomname));

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $sql_player_cnt = 'SELECT COUNT(*) AS cnt FROM '.$roomname;
        $pcstmn = $pdo -> prepare($sql_player_cnt);
        $pcstmn -> execute();
        $playercnt = $pcstmn->fetch(PDO::FETCH_ASSOC);

        //echo $row['cnt'];

        $job_array = array();

        if($result['PlayerNum'] == $playercnt['cnt']) {
            switch($result['PlayerNum']) {
                case 5:
                    $job_array = array("wolf", "knight", "diviner", "psychic", "madman");
                break;
                case 6:
                    $job_array = array("wolf", "wolf", "knight", "diviner", "psychic", "madman");
                break;
                case 7:
                    $job_array = array("wolf", "wolf", "citizen", "knight", "diviner", "psychic", "madman");
                break;
                case 8:
                    $job_array = array("wolf", "wolf", "citizen", "knight", "diviner", "psychic", "madman", "citizen");
                break;
                case 9:
                    $job_array = array("wolf", "wolf", "citizen", "knight", "diviner", "psychic", "madman", "citizen", "citien");
                break;
                case 10:
                    $job_array = array("wolf", "wolf", "citizen", "knight", "diviner", "psychic", "madman", "citizen", "citizen", "citizen");
                break;
                case 11:
                    $job_array = array("wolf", "wolf", "citizen", "knight", "diviner", "psychic", "madman", "citizen", "citizen", "citizen", "citizen");
                break;
                case 12:
                    $job_array = array("wolf", "wolf", "citizen", "knight", "diviner", "psychic", "madman", "citizen", "citizen", "citizen", "citizen", "citizen");
                break;
            }

            shuffle($job_array);

            $sql_get_player = 'SELECT * FROM '.$roomname;
            $gp_result = $pdo -> prepare($sql_get_player);
            $gp_result -> execute();

            $count = 0;

            foreach($gp_result as $gp_res) {
                $sql_job_decide = 'UPDATE '.$roomname.' SET Job = :job WHERE ID = :id';
                $dec_stmn = $pdo -> prepare($sql_job_decide);
                $dec_params = array(':job' => $job_array[$count], ':id' => (int)$gp_res['ID']);
                $dec_stmn -> execute($dec_params);
                $count++;
            }

            $sql_cp = 'UPDATE phase_manager SET phase=:phase WHERE RoomName=:roomname';
            $cp_stmn = $pdo -> prepare($sql_cp);
            $cp_stmn -> execute(array(':phase' => 'jobdisplay', ':roomname' => $roomname));
        }
    
        echo json_encode(array('joinresult' => true));
    } catch (Exception $e) {
        /*接続に失敗*/
        $arrayFailure = array('joinresult' => false);
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($arrayFailure);
        die();
    } 

?>