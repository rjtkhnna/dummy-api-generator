<?php 
//This file will be providing a valid JSON Token
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
$data = array();

//Validating the POST
if(isset($_POST) && !empty($_POST)){
    $post = $_POST;
}else{
    http_response_code(404);
    echo(json_encode(array('data'=>$data,'error'=>true, 'message' =>'Insufficient Parameters')));
    exit();
}
//For JWT Libraries
require "vendor/autoload.php";
use \Firebase\JWT\JWT;

//Reading Primary Configuration
include("config.php");
$config_array = json_decode($config_json, true);

//Validating users
if(isset($post['uname']) && $post['uname'] == $config_array['creds']['uname']){
    if(isset($post['upwd']) && $post['upwd'] == $config_array['creds']['upwd']){
        //JWT
        $token = array(
            "iss" => $config_array['jwt']['issuer-claim'],
            "aud" => $config_array['jwt']['audience-claim'],
            "iat" => time(),
            "nbf" => time()+$config_array['jwt']['pre-issue-time'],
            "exp" => time()+$config_array['jwt']['post-issue-time'],
            "data" => array(
                "id"=>time(),
                "uname"=>$config_array['creds']['uname'],
                "upwd"=>$config_array['creds']['upwd'],
                "ufname"=>$config_array['creds']['ufname'],
                "ulname"=>$config_array['creds']['ulname']
        ));
        http_response_code(200);
        $jwt = JWT::encode($token, $config_array['jwt']['secret-key']);
        echo json_encode(
            array(
                "data" => array('jwt'=>$jwt,"uname" => $config_array['creds']['uname'],"expireAt" => time()+$config_array['post-issue-time']),
                "error"=>false,
                "message" => "Success",
            ));
            exit;
    }else{
        http_response_code(404);
        echo(json_encode(array('data'=>$data,'error'=>true, 'message' =>'Invalid Password')));
        exit();
    }
}else{
    http_response_code(404);
    echo(json_encode(array('data'=>$data,'error'=>true, 'message' =>'Invalid Username')));
    exit();
}
?>