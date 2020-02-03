<?php

//Specifying the Headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');  

//Reading Header Params
$headers = getallheaders();

//Reading Primary Configuration
include("config.php");
$config_array = json_decode($config_json, true);

//JWT validation if enabled true
$validate = $config_array['auth-enabled']?false:true;
if($config_array['auth-enabled']){
    $jwt = getallheaders()['Authorization']; 
    if(!$jwt){
        http_response_code(404);
        echo json_encode(array( "data"=>$data, "message" => "Invalid Headers", "error" =>true));
        exit;
    }else{
        //setup the request, you can also use CURLOPT_URL
        $ch = curl_init($config_array['jwt']['validate-url']);
        // Returns the data/output as a string instead of raw data
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //Set your auth headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization:'.$jwt));
        // get stringified data/output. See CURLOPT_RETURNTRANSFER
        $response = curl_exec($ch);
        // get info about the request
        $info = curl_getinfo($ch);
        // close curl resource to free up system resources
        curl_close($ch);
        if(!json_decode($response,'true')['error']){
            $validate=true;
        }else{
            echo json_encode(array( "data"=>json_decode($response,'true')['data'], "message" => "Invalid Headers", "error" =>json_decode($response,'true')['error']));
            exit;
        }
    }
}

//Data Fetching
$url = explode('/',$_SERVER['REQUEST_URI']);
$req_key = empty($url[count($url)-1])?$url[count($url)-2]:$url[count($url)-1]; //handling trailing slashes
$file_data = json_decode(file_get_contents($config_array['data-file']),true);

//Actions to be performed
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(empty($file_data[$req_key])){
        echo(json_encode(array('data'=>$file_data[$req_key],'error'=>false, 'message' => 'No Data Found')));
        exit;
    }else{
        echo(json_encode(array('data'=>$file_data[$req_key],'error'=>false, 'message' => 'Data Found')));
        exit;
    }
}
elseif($_SERVER['REQUEST_METHOD'] === 'POST') {
    parse_str(file_get_contents('php://input'), $req_params); 
    if(empty($file_data[$req_key])){
        $file_data[$req_key]=array($req_params);
    }else{
        array_push($file_data[$req_key],$req_params);
    }
    updateContent($file_data,$config_array['data-file']);
    echo(json_encode(array('data'=>$file_data[$req_key],'error'=>false, 'message' => 'Data Created')));
    exit;
}
elseif($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $found = false;
    parse_str(file_get_contents('php://input'), $req_params);
    if($req_params['id']==''){
        echo(json_encode(array('data'=>'','error'=>true, 'message' => 'ID Empty')));
        exit;
    }else{
        foreach($file_data[$req_key] as $k=>$lists){
            if($lists['id'] == $req_params['id']){
                $file_data[$req_key][$k] = $req_params;
                $found = true;
            }
        }
    }
    if($found){
        updateContent($file_data,$config_array['data-file']);
        echo(json_encode(array('data'=>$file_data[$req_key],'error'=>false, 'message' => 'Data Updated')));
        exit;
    }else{
        echo(json_encode(array('data'=>$file_data[$req_key],'error'=>true, 'message' => 'Data Not Found')));
        exit;
    }
}
elseif($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $found = false;
    parse_str(file_get_contents('php://input'), $req_params);
    if($req_params['id']==''){
        echo(json_encode(array('data'=>'','error'=>true, 'message' => 'ID Empty')));
        exit;
    }else{
        foreach($file_data[$req_key] as $k=>$lists){
            if($lists['id'] == $req_params['id']){
                unset($file_data[$req_key][$k]);
                $found = true;
            }
        }
    }
    if($found){
        updateContent($file_data,$config_array['data-file']);
        echo(json_encode(array('data'=>$file_data[$req_key],'error'=>false, 'message' => 'Data Deleted')));
        exit;
    }else{
        echo(json_encode(array('data'=>$file_data[$req_key],'error'=>true, 'message' => 'Data Not Found')));
        exit;
    }
}
function updateContent($file_data,$fileName){
    //Writing to File
    $myfile = fopen($fileName, "w") or die("Unable to open file!");
    fwrite($myfile, json_encode($file_data));
    fclose($myfile);
    //Ends
}
?>