<?php
header('Content-Type: application/json; charset=utf-8');
include('../config.php');
if(!empty($_GET['username']))
{
    $stmt = $conn->prepare("SELECT T1_userid FROM T1_user WHERE T1_username = ?");
    $stmt->bind_param("s",$_GET['username']);
    $stmt->execute();
    $filename = "data/".mysqli_fetch_assoc($stmt->get_result())['T1_userid'].".user";
    $response["file"] = $filename;
    
}
echo json_encode($response);
?>