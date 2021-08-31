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
<h1>Test page</h1>

<p>Showing contents of papers table:</p>
<form method="POST">
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


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$_SESSION['postdata'] = $_POST;
if(isset($_SESSION['postdata']['first']))
{	
	
    ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    $first = $_SESSION['postdata']['first'];

    $second = $_SESSION['postdata']['second'];
    $third = $_SESSION['postdata']['third'];

    $firstSyllable = shell_exec('ssh 192.168.2.13 -l vagrant -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no -o LogLevel=quiet');


  $username = posix_getpwuid(posix_geteuid())['name'];
    $ssh_conn = ssh2_connect('192.168.2.13', 22);
    ssh2_auth_pubkey_file($ssh_conn, 'vagrant', '/opt/www-files/id_rsa.pub', '/opt/www-files/id_rsa', 'Null');
    $stream = ssh2_exec($ssh_conn, "sudo python3 /home/vagrant/test.py '{$first}' '{$second}' '{$third}'");
    stream_set_blocking($stream, true);
    $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
    $output = stream_get_contents($stream_out);

    if(strcmp(strval($output), "575") == 0)
    {
     
    unset($_POST);
    unset($output);
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
    }else{
    
       echo 'Syllables do not follow 5, 7, 5. Please try again.';
       
    }
   
   
} 
 
    
}
?>





</body>
</html>