<?
include "../_h.php";
if(!empty($_FILES)){
    $file = $Mem->data["post"]."/".mktime().".png";
    $post_location = "../".$file;
    $server_location = "http://".$_SERVER["HTTP_HOST"]."/".$file;
    $json->path = $server_location;
    $json = json_encode($json);
    move_uploaded_file($_FILES['file']['tmp_name'],$post_location);
    echo $json;
    if(empty($_SESSION["post_file"])){
        $_SESSION["post_file"]=array();
    }
    $_FILES["file"]["real_name"] = $file;
    $_FILES["file"]["real_path"] = $post_location;
    array_push($_SESSION["post_file"],$_FILES["file"]);
}
?>