<?PHP
require_once("./include/membersite_config.php");

if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("login.php");
    exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
      <title>An Access Controlled Page</title>
      <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css">
</head>
<body>
<div id='fg_membersite_content'>
    <form method="post" enctype="multipart/form-data">
        <table width="350" border="0" cellpadding="1" cellspacing="1" class="box">
            <tr>
                <td width="246">
                    <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
                    <input name="userfile" type="file" id="userfile">
                </td>
                <td width="80"><input name="upload" type="submit" class="box" id="upload" value=" Upload "></td>
            </tr>
        </table>
    </form>

    <p>
Logged in as: <?= $fgmembersite->UserFullName() ?>
</p>
<p>
<a href='login-home.php'>Home</a>
</p>
</div>

<?php
if(isset($_POST['upload']) && $_FILES['userfile']['size'] > 0)
{
    $fileName = $_FILES['userfile']['name'];
    $tmpName  = $_FILES['userfile']['tmp_name'];
    $fileSize = $_FILES['userfile']['size'];
    $fileType = $_FILES['userfile']['type'];

    $fp      = fopen($tmpName, 'r');
    $content = fread($fp, filesize($tmpName));
    $content = addslashes($content);
    fclose($fp);

    if(!get_magic_quotes_gpc())
    {
        $fileName = addslashes($fileName);
    }


    try {
        $conn = new PDO("mysql:host=$fgmembersite->db_host;dbname=$fgmembersite->database", $fgmembersite->username, $fgmembersite->pwd);
        $username = $fgmembersite->UserFullName();
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO Upload (name, size, type, content, pid ) ".
            "VALUES ('$fileName', '$fileSize', '$fileType', '$content', (SELECT id_user from User where name='$username'))";
        // use exec() because no results are returned
        $conn->exec($sql);
        echo "<br>File $fileName uploaded<br>";
    }
    catch(PDOException $e)
    {
        echo $sql . "<br>" . $e->getMessage();
    }

    $conn = null;

}
?>

</body>
</html>
