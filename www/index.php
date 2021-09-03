<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML//EN">
<html>
<head><title>Database test page</title>
<style>
th { text-align: left; }

table, th, td {
  border: 2px solid grey;
  border-collapse: collapse;
}

th, td {
  padding: 0.2em;
}
</style>
</head>

<body>
<h1>Haiku Generator</h1>

<p>How to use: Enter the theme of your haiku, currently there are 6 to chose from. Give your haiku a unique title then enter your stanzas and submit.</p>
<p>THEMES: Happy | Sad | Animal | Coding | Angry | Gibberish</p>

<style>


th { text-align: left; }

table, th, td {
  border: 2px solid grey;
  border-collapse: collapse;
}

th, td {
  padding: 0.2em;
}
</style>
<table border="1">
<tr><th>Theme</th><th>Title</th><th>Stanza</th></tr>
<form method="POST">
Theme: <input type="text" name="Database"  Required>
  <br/>
Title: <input type="text" name="Label"  Required>
  <br/>
  First Stanza : <input type="text" name="first"  Required>
  <br/>
  Second Stanza: <input type="text" name="second"  Required>
  <br/>
Third Stanza: <input type="text" name="third"  Required>
  <br/>
  <input type="submit" name="submit" value="Submit">
</form>





<?php
if (!isset($_SESSION)) {
    session_start();

}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);




$db_host   = '192.168.2.12';
$db_user   = 'webuser';
$db_passwd = 'insecure_db_pw';


$input = array("Happy", "Sad", "Angry", "Coding", "Animal", "Gibberish", "dsf");

$rand_keys = array_rand($input, 2);
$db_name   = $input[$rand_keys[0]];
$pdo_dsn = "mysql:host=$db_host;dbname=$db_name";
$pdo = new PDO($pdo_dsn, $db_user, $db_passwd);
$q = $pdo->query("SELECT * FROM First ORDER BY RAND() LIMIT 1;");
while($row = $q->fetch()){
  echo "<tr><td>$db_name</td><td>".$row["code"]."</td><td>".$row["name"]."</td></tr>\n";
}

$rand_keys = array_rand($input, 2);
$db_name   = $input[$rand_keys[0]];
$pdo_dsn = "mysql:host=$db_host;dbname=$db_name";
$pdo = new PDO($pdo_dsn, $db_user, $db_passwd);
$q2 = $pdo->query("SELECT * FROM Second ORDER BY RAND() LIMIT 1;");

while($row = $q2->fetch()){
  echo "<tr><td>$db_name</td><td>".$row["code"]."</td><td>".$row["name"]."</td></tr>\n";
}

$rand_keys = array_rand($input, 2);
$db_name   = $input[$rand_keys[0]];
$pdo_dsn = "mysql:host=$db_host;dbname=$db_name";
$pdo = new PDO($pdo_dsn, $db_user, $db_passwd);
$q3 = $pdo->query("SELECT * FROM Third ORDER BY RAND() LIMIT 1;");

while($row = $q3->fetch()){
  echo "<tr><td>$db_name</td><td>".$row["code"]."</td><td>".$row["name"]."</td></tr>\n";
}







if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$_SESSION['postdata'] = $_POST;
if(isset($_SESSION['postdata']['first']))
{	
	

$db_host   = '192.168.2.12';
$db_name   = $_SESSION['postdata']['Database'];
$Label = $_SESSION['postdata']['Label'];

$db_user   = 'webuser';
$db_passwd = 'insecure_db_pw';

$pdo_dsn = "mysql:host=$db_host;dbname=$db_name";
try{
$pdo = new PDO($pdo_dsn, $db_user, $db_passwd);
}catch(Exception $e) {
   echo "<br>"."<br>";
    echo "Database does not exist"."<br>";
}
    $first = $_SESSION['postdata']['first'];
    $first = str_replace("'", "\'", $first);

$firstF = $_SESSION['postdata']['first'];
    $firstF = str_replace("'", '', $firstF);
    
$second = $_SESSION['postdata']['second'];
 $second = str_replace("'", "\'", $second);

$secondF = $_SESSION['postdata']['second'];
$secondF = str_replace("'", '', $secondF);
    
$third = $_SESSION['postdata']['third'];
 $third = str_replace("'", "\'", $third);

$thirdF = $_SESSION['postdata']['third'];
$thirdF = str_replace("'", '', $thirdF);

    

  $username = posix_getpwuid(posix_geteuid())['name'];
    $ssh_conn = ssh2_connect('192.168.2.13', 22);
    ssh2_auth_pubkey_file($ssh_conn, 'vagrant', '/opt/www-files/id_rsa.pub', '/opt/www-files/id_rsa', 'Null');
    $stream = ssh2_exec($ssh_conn, "sudo python3 /vagrant/SyllableCounter.py '{$firstF}' '{$secondF}' '{$thirdF}'");
    stream_set_blocking($stream, true);
    $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
    $output = stream_get_contents($stream_out);
    $q2 = $pdo->query("SELECT * FROM Second WHERE code = '{$Label}';"); 

$count = count($q2->fetch());



    if(strcmp(strval($output), "575") == 0 and $count != 4)
    {

    $pdo->query("INSERT INTO First VALUES ('{$Label}','{$first}');");
     $pdo->query("INSERT INTO Second VALUES ('{$Label}','{$second}');");
    $pdo->query("INSERT INTO Third VALUES ('{$Label}','{$third}');");
$pdo->query("INSERT INTO Third VALUES ('{$Label}','{$third}');");
$pdo->query("\! mysqldump -u root -pinsecure_mysqlroot_pw > /vagrant/dump.sql");
    unset($_POST);
    unset($output);
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
    }elseif(strcmp(strval($output), "575") != 0 and $count != 4){
       
       echo "<br>";
       echo "Syllables do not follow 5, 7, 5. Your syllabe sequence was: ".strval($output[0])." ".strval($output[1])." ".strval($output[2]).". Please try again.";
      
      
    }elseif(strcmp(strval($output), "575") == 0 and $count == 4){
       
       echo "<br>";
      echo "The title "."{$Label}"." is already used. Please use a different title.";
      
      
    
    }else{

 echo "<br>";
       echo "The title "."{$Label}"." is already used. Please use a different title.";
 echo "<br>";
       echo "Syllables do not follow 5, 7, 5. Your syllable sequence was: ".strval($output[0])." ".strval($output[1])." ".strval($output[2]).". Please try again.";

}
   
   
} 
 
    
}
?>
</br>
</br>
<form method="POST2">
<br/>
<br/>
<input type="submit" name="submit" value="Generate">

</form>



</body>
</html>