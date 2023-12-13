<?php  
	if(isset($_GET['client']) && !empty($_GET['client'])){
		$client =  $_GET['client'] ;
	}
	elseif (isset($_POST['client']) && !empty($_POST['client'])){
		$client = $_POST['client'];
	}
	else{
		$client = 'no client';
	}			
			
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Control Server</title>
  <style>
    body {
      font-family: 'Courier New', monospace;
      background-color: #282828;
      color: #f0f0f0;
      padding: 40px;
      margin: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    #clienttable {
      width: 80%;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    img {
      border-radius: 50%;
    }

    h2 {
      font-size: 2rem;
      color: #a8a8a8;
      margin: 20px 0;
    }

    table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0 15px;
      margin-top: 20px;
    }

    td, th {
      background-color: #383838;
      color: #ffffff;
      border: none;
      text-align: center;
      padding: 10px;
      font-size: 1rem;
    }

    th {
      background-color: #505050;
    }

    tr {
      border-radius: 8px;
    }

    a {
      color: #4caf50;
      text-decoration: none;
    }

    a:hover {
      color: #66ff66;
    }

    button {
      background-color: #4caf50;
      color: #ffffff;
      border: none;
      padding: 10px 20px;
      margin: 10px;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background-color: #66ff66;
    }

  </style>
</head>
<body>
  <div id="clienttable">

    <img src="serverIcon.png" alt="Server Icon" width="130px">
        <h2>Control Panel</h2>
	<?php
	$db = new mysqli('localhost', 'root','', 'controlserver');
	if(mysqli_connect_errno()) exit;
	echo '<p>Connected to Command and Control Server</p>';
	$query = "SELECT id, name, ip FROM clients";
	$stmt = $db->prepare($query);
	$stmt->execute();
	$stmt->bind_result($id, $name, $ip);

	
	
	echo '<table>';
	echo '<tr><th>id</th><th>name</th><th>ip</th><th>select</th><th>Delete</th></tr>';
	while($stmt->fetch()){
		echo '<tr>';
		$administer = "<a href='/openClient.php?client=" .$name. "'>administer</a>";
		$delete = "<a href='/index.php?client=" .$name. "'>delete</a>";
		echo '<td>'.$id.'</td><td>'.$name.'</td><td>'.$ip.'</td><td>'.$administer.'</td>'.'</td><td>'.$delete.'</td>';
		echo '</tr>';
	}
	echo '</table>';
	?> 
 
  </div>
	
</body>
</html>


<?php
if(isset($_GET['client']) && !empty($_GET['client'])){
	$client =  $_GET['client'] ;

	$db = new mysqli('localhost', 'root','', 'controlserver');
	if(mysqli_connect_errno()) exit;

	$query = "DELETE FROM clients WHERE name=?";
	$stmt = $db->prepare($query);
	$stmt->bind_param('s',$client); 
	$stmt->execute();
	$db->close();
	echo "Client deleted";
	header("Location: /index.php");
}
?>