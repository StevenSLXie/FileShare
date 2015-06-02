<?php


require_once("./include/membersite_config.php");

if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("login.php");
    exit;
}

if(isset($_GET['id']))
{

    try {
        $id = $_GET['id'];
        $conn = new PDO("mysql:host=$fgmembersite->db_host;dbname=$fgmembersite->database", $fgmembersite->username, $fgmembersite->pwd);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("SELECT name, type, size, content FROM upload WHERE id= '$id'");
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $row = $stmt->fetch();

        header("Content-length:" . $row['size']);
        header("Content-type:" . $row['type']);
        header("Content-Disposition: attachment; filename=" . $row['name']);
        echo $row['content'];
    }
    catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $conn = null;

}

?>