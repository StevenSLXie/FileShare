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
      <title>File Sharing</title>
      <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css">
</head>
<body>
<div id='fg_membersite_content'>
<h2>我的文件</h2>
    <table style='border: solid 1px black;'>
        <tr><th>序号</th><th>文件名</th><th>大小</th></tr>
        <?php

        class TableRows extends RecursiveIteratorIterator {
            var $index = 0;

            function __construct($it) {
                parent::__construct($it, self::LEAVES_ONLY);
            }

            function current() {
                if (parent::key() == 'name'){
                    return "<td style='width:150px;border:1px solid black;'><a href='download.php?id=". $this->index ."'>" . parent::current() . "</a></td>";
                }
                elseif(parent::key() == 'id'){
                    return "<td style='width:150px;border:1px solid black;'>" . parent::current() . "</td>";
                }
                else {
                    return "<td style='width:150px;border:1px solid black;'>" . parent::current() . "</td>";
                }
            }

            function beginChildren() {
                echo "<tr>";
                $this->index = $this->index+1;
            }

            function endChildren() {
                echo "</tr>" . "\n";
            }
        }

        try {
            $conn = new PDO("mysql:host=$fgmembersite->db_host;dbname=$fgmembersite->database", $fgmembersite->username, $fgmembersite->pwd);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $username = $fgmembersite->UserFullName();
            $stmt = $conn->prepare("SELECT id, f.name, size FROM Upload f INNER JOIN User u ON f.pid = u.id_user WHERE u.name='$username' ");
            $stmt->execute();

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
                echo $v;
            }

        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
        ?>
    </table>


    <p><a href='change-pwd.php'>Change password</a></p>

<p><a href='access-controlled.php'>Upload a new file</a></p>
<br><br><br>
<p><a href='logout.php'>Logout</a></p>
</div>
</body>
</html>
