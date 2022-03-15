<?php 
	session_start();
    $GLOBALS['generated_challenge'] = 0;
    header("Location: http://172.18.0.4:5000");
	include('includes/header.php');
	include('connection.php'); 
    $post = [
        'dns' => '172.18.0.2',
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, '172.18.0.4:5000/get-origin/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    $response = curl_exec($ch);
    curl_close ($ch);

    # echo $response;
?> 