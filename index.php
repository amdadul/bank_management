<?php

session_start();



if(!isset($_SESSION['id']) || isset($_GET['logout']))
{
	$_SESSION['role'] = "";
	$_SESSION['id'] = "";
}
if (isset($_POST['login'])) {

	$cn = mysqli_connect("localhost","root","","bankmanagement");
	$sqllog = "select account_no,role from client where phone_no = '".$_POST['phone']."' and password = password('".$_POST['password']."') ";
	if ($result=mysqli_query($cn,$sqllog))
	  			{
	  				$row = mysqli_fetch_row($result);
	  				if($row > 0)
	  				{
	  					$_SESSION['id'] = $row[0];
	  					$_SESSION['role'] = $row[1];
	  				}
	  				else
	  				{
	  					print 'Phone No / Password Not Match';
	  				}
	  			}
}
print '<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="top">
		<h1>Welcome to Pro Security Bank </h1>
		<span>Reduce Cost and Keep money to bank</span>
	</div>
	<div class="content">
	<div class="left">';
	if($_SESSION['role'] =='ADMIN')
	{
		print '
			<a href="?p=create_account" class="btn block">Create Account</a>
			<a href="?p=deposit" class="btn block">Deposit</a>
			<a href="?p=balance_withdraw" class="btn block">Balance Withdraw</a>
			<a href="?p=check_balance" class="btn block">Check Balance</a>
			<a href="?p=balance_transfer" class="btn block">Balance Transfer</a>
			<a href="?p=manage_account" class="btn block">Manage Account</a>
			<a href="?logout=true" class="btn block">Log-Out</a>
		';
	}

	if($_SESSION['role'] =='USER')
	{
		print '
			<a href="?p=check_balance" class="btn block">Check Balance</a>
			<a href="?p=balance_transfer" class="btn block">Balance Transfer</a>
			<a href="?p=manage_account" class="btn block">Manage Account</a>
			<a href="?logout=true" class="btn block">Log-Out</a>
		';
	}
	print '</div>

		<div class="right">';
		if($_SESSION['role'] !='')
		{
			 
					if(isset($_GET['p']))
					{

						if(file_exists($_GET['p'].".php"))
							{
								print '<h2 class="head">'.ucwords(str_replace("_", " ", $_GET['p'])).'</h2><br>';
								include($_GET['p'].".php");
							}
							else
							{
								print '<h2 class="head">Error Found</h2><br>';
								include("404.php");
							}
					}
					else
					{
						include("home.php");
					}
		}
		else
		{
			print '<div class="loginc">';
			include("login.php");
			print '</div>';
		}
		?>
		</div>

	</div>

</body>
</html>