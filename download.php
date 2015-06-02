<?php
if(isset($_GET['id']))
{
// if id is set then get the file with the id from database

    $servername = "localhost";
    $username = "sxie2";
    $password = "2xiexing";
    $dbname = "php";

    //$conn = new mysqli($servername, $username, $password);

    //$db = mysqli_select_db($conn, $dbname);
    //if (!$db) {
    //    die ('Can\'t use the name : ' . mysql_error());
    //}

    //$id    = $_GET['id'];
    //$query = "SELECT name, type, size, content " .
    //    "FROM upload WHERE id = '$id'";

    //$result = $conn->query($query);

    //$row = $result->fetch_assoc();

    $id = $_GET['id'];
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT name, type, size, content FROM upload WHERE id= '$id'");
    $stmt->execute();

    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    $row = $stmt->fetch();

    header("Content-length:". $row['size']);
    header("Content-type:". $row['type']);
    header("Content-Disposition: attachment; filename=".$row['name']);
    echo $row['content'];

    $conn = null;
    //$conn->close();
    // exit;
}

?>