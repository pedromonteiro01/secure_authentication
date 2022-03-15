<?php
    session_start();
    //$challenge_response = $_SESSION['generated_challenge'];
    include('connection.php');
    //$cURLConnection = curl_init();
//
    //curl_setopt($cURLConnection, CURLOPT_URL, 'http://172.18.0.4:5000/base');
    //curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
//
    //$phoneList = curl_exec($cURLConnection);
    //curl_close($cURLConnection);
//
    //$jsonArrayResponse - json_decode($phoneList);

    //echo "<p>".$jsonArrayResponse."</p>";



    //ini_set("allow_url_fopen", true);
    //$json = file_get_contents('php://input');
    //$d = json_decode($json);
    //print_r($d);



    function send_data($post){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, '172.18.0.4:5000/get_auth/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        $response = curl_exec($ch);
        curl_close ($ch);
    }

    if (isset($_GET['user'])){
        $user = $_GET['user'];
    }
    else{
        $auth = $_GET['auth'];
    }

    if (isset($user)) {
        $sql = "SELECT * FROM users ";
        $result = $conn->query($sql);
        $bol = true;
        while($row = $result->fetch_assoc()){
            if($row["email"]==$user){
                $password = $row["password"];
                $data = rand();
                $hashed = hash('sha256', $data);

                $post = [
                    'challenge' => $hashed,
                ];

                $str = $password.$hashed;
                $fp = fopen("global_variables.txt", "w");
                $res = hash('sha256', $str);
                fwrite($fp, $res);
                fclose($fp);

                $fp = fopen("auth_counter.txt", "w");
                $res = 0;
                fwrite($fp, $res);
                fclose($fp);
            
                send_data($post);
                //$challenge_response = hash('sha256', $str);
                $bol=false;
            }
        }
        if ($bol) {
            sleep(1);
            $post = [
                'auth' => "failed",
            ];
            send_data($post);
        }
    }
    elseif (isset($auth)) {

        //$challenge_response = readfile("global_variables.txt");
        $fp = fopen("global_variables.txt", "r");
        $challenge_response = fread($fp, filesize("global_variables.txt"));
        fclose($fp);

        $fp = fopen("auth_counter.txt", "r");
        $auth_counter = intval(fread($fp, filesize("auth_counter.txt")));
        fclose($fp);
        
        if ($auth == $challenge_response[$auth_counter]) { // se correto, enviar proximo bit
            if ($auth_counter == 10) {
                // mandar mensagem de sucesso
                $post = [
                    'auth' => "success",
                ];
                $auth_counter = 0;
                $fp = fopen("auth_counter.txt", "w");
                fwrite($fp, $auth_counter);
                fclose($fp);
                send_data($post);
            }
            else{
                $post = [
                    'auth' => $challenge_response[$auth_counter + 1],
                ];

                $auth_counter += 2;
                $fp = fopen("auth_counter.txt", "w");
                fwrite($fp, $auth_counter);
                fclose($fp);

                send_data($post);
            }
        }
        else { // se falso, enviar bit aleatorio
            if ($auth_counter != 10) {
                $post = [
                    'auth' => random_bytes(1),
                ];
                send_data($post);
            }
            else{
                $post = [
                    'auth' => 'failed',
                ];
                $auth_counter = 0;
                $fp = fopen("auth_counter.txt", "w");
                fwrite($fp, $auth_counter);
                fclose($fp);
                send_data($post);
                header("Location: http://172.18.0.2");
                exit();
            }
        }

    }

?>