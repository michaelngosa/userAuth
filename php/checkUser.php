<?php 
$storage = "../storage/users.csv";
function db() {
    $data = array();
    if(($csv_file = fopen($GLOBALS['storage'], "r")) !== FALSE) { // csv directory
        while (($datas = fgetcsv($csv_file, 1000, ",")) !== FALSE) {
            $data[] = $datas;
        }
        fclose($csv_file);
    }

    return $data;
}

function checkUserLogin(array $users) {
    [$email, $password ] = $users;
    $db = db();
    $error = '';
    foreach($db as $kdb => $datas) {
        // echo "<pre>";print_r($datas);echo "</pre>";
        foreach($datas as $key => $data) {
            if($datas[1] == $email && $datas[2] == $password) {
                return ["Auth" =>true,"message"=>$datas];

            }else {
                $error = ["Auth" =>false,"message"=> "Incorrect credentials"];
            }
        }
    }
    return $error;
}

function checkUser_Exist(string $email) {
    $db = db();
    foreach($db as $kdb => $datas) {
        foreach($datas as $key => $data) {
            if($datas[1] == $email) {
                return true;
            }else {
                return false;
            }
        }
    }
}

function getUserRegistered(array $users) {
    $db = db();
    [$username, $email, $password] = $users;
    
    array_push($users, date('d-m-Y'),1);
    array_push($db,$users);
    // echo "<pre>";print_r($db);die;
    if (checkUser_Exist($email)) {
        return ["Auth" =>false,"message"=> "Users with \'$email\' is already exist"];
    }else {
        // unlink($GLOBALS['storage']);
        $fh = fopen($GLOBALS['storage'],"w");    
        
        foreach($db as $fields) {
            fputcsv($fh, $fields);
        }
        fclose($fh);
        return ["Auth" =>true,"message"=>$users];
    }
}


function updateUser(array $users) {
    [$email, $password ] = $users;
    $db = db();
    $error = '';
    foreach($db as $kdb => $datas) {
        foreach($datas as $key => $data) {
            if($datas[1] == $email) {
                $datas[1] = $email;
                $datas[2] = $password;
                $db[$kdb] = $datas;
                $fh = fopen($GLOBALS['storage'],"w");    
        
                foreach($db as $fields) {
                    fputcsv($fh, $fields);
                }
                fclose($fh);
                return ["Auth" =>true,"message"=>$datas];

            }else {
                $error = ["Auth" =>false,"message"=> "Email not found"];
            }
        }
    }

    return $error;
}

?>