<?php
header('Content-Type: application/json; charset=utf-8');
include('../config.php');
if(!empty($_GET['username']))
{
    $stmt = $conn->prepare("SELECT * FROM T1_user WHERE T1_name = ?");
    $stmt->bind_param("s",$_GET['username']);
    $stmt->execute();
    if($stmt->execute())
    {
        $r = mysqli_fetch_assoc($stmt->get_result());
        $response["status"] = "success";
        $response['data'] = ["id"=>$r['T1_id'],"name" => $r['T1_name'],"file"=>$r['T1_id'].".user"];
    }
    else
    {
        $response["status"] = "empty";
    }
    
    //$filename = "data/".mysqli_fetch_assoc($stmt->get_result())['T1_id'].".user";
    //$response["file"] = $filename;
    
}
echo json_encode($response);
?>