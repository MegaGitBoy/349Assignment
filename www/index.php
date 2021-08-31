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


<?php


if(isset($_POST['submit']))
{		
    ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    $username = posix_getpwuid(posix_geteuid())['name'];
    $ssh_conn = ssh2_connect('192.168.2.13', 22);
    ssh2_auth_pubkey_file($ssh_conn, 'vagrant', '/opt/www-files/id_rsa.pub', '/opt/www-files/id_rsa', 'Null');
    $stream = ssh2_exec($ssh_conn, 'pwd');
    stream_set_blocking($stream, true);
    $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
    echo stream_get_contents($stream_out);
    
    $first = $_POST['first'];
    $second = $_POST['second'];
    $third = $_POST['third'];
    echo $stream ;
    $firstSyllable = shell_exec('ssh 192.168.2.13 -l vagrant -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no -o LogLevel=quiet');
    echo "<pre>$firstSyllable</pre>";
    echo "<br>";
    echo $second;
    echo "<br>";
    echo $third;
    echo "<br>";
}
?>



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
</body>
</html>