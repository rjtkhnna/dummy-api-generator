<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
$data = array();
$jwt= getallheaders()['Authorization'];
//For JWT Libraries
require "vendor/autoload.php";
use \Firebase\JWT\JWT;

if(!$jwt){
    http_response_code(404);
    echo json_encode(array( "data"=>$data, "message" => "Invalid Headers", "error" =>true));
}else{
    //Reading Primary Configuration
    include("config.php");
    $config_array = json_decode($config_json, true);
    try {
        $decoded = JWT::decode($jwt, $config_array['jwt']['secret-key'], array('HS256'));
        echo(json_encode(array('data'=>$data,'error'=>false, 'message' =>'Valid Access')));
    }catch (Exception $e){
        http_response_code(401);
        echo(json_encode(array('data'=>$data,'error'=>true, 'message' =>$e->getMessage())));
    }
}
?>



