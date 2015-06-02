<html>
 <head>
  <title>PHP Test</title>
 </head>
 <body>

 <?php
    $servername = "localhost";
    $username = "sxie2";
    $password = "2xiexing";
    $dbname = "php";

    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $email = htmlspecialchars($_POST['email']);

     try {
         $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $sql = "INSERT INTO People (firstname, lastname, email)
        VALUES ('$firstname', '$lastname', '$email')";
         // use exec() because no results are returned
         $conn->exec($sql);
         echo "New record created successfully";
     }
     catch(PDOException $e)
     {
         echo $sql . "<br>" . $e->getMessage();
     }

     $conn = null;
 ?>

 <table style='border: solid 1px black;'>
     <tr><th>Id</th><th>Firstname</th><th>Lastname</th></tr>
     <?php

     class TableRows extends RecursiveIteratorIterator {
         function __construct($it) {
             parent::__construct($it, self::LEAVES_ONLY);
         }

         function current() {
             return "<td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
         }

         function beginChildren() {
             echo "<tr>";
         }

         function endChildren() {
             echo "</tr>" . "\n";
         }
     }

     try {
         $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $stmt = $conn->prepare("SELECT id, firstname, lastname FROM People");
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



 </body>
</html>