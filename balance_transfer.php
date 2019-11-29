<?php

$cn = mysqli_connect("localhost","root","","bankmanagement");

$trans_id = "";
$account = "";
$account_to = "";
$amount = "";
$date = "";

$erraccount_from = "";
$erraccount_to = "";
$erramount = "";


if(mysqli_connect_errno())
{
	print 'Database Connection Error';
}
else
{
	$date = date("Y-m-d");
	if(isset($_POST['submit']))
	{
		$err = 0;

		if($_SESSION['role']=='ADMIN')
		{
			if($_POST['account']=="")
			{
				$erraccount_from = '<span style="color:red;">* Required</span>';
				$err++;
			}
		}

		if($_POST['account_to']=="")
		{
			$erraccount_to = '<span style="color:red;">* Required</span>';
			$err++;
		}

		if($_POST['amount']=="" || $_POST['amount'] < 1)
		{
			$erramount = '<span style="color:red;">* Required</span>';
			$err++;
		}


		if($_SESSION['role']=='ADMIN')
		{
			$acc = $_POST['account'];
		}
		else
		{
			$acc = $_SESSION['id'];
		}


		$sqlb = "select balance from balance where account_no = ".$acc;
			if ($result=mysqli_query($cn,$sqlb))
	  			{
	  				$row = mysqli_fetch_row($result);
	  				if($row > 0)
	  				{
	  					$bl = $row[0]-$_POST['amount'];
	  					if($bl<0)
	  					{
	  						print '<h2 class="balancee">Insufficient Balance In your Account<h2>';
	  						$err++;
	  					}
	  				}
	  			}


		if($err==0)
		{

			$sqlt = "select trans_id from transection order by trans_id DESC limit 1 ";
			if ($result=mysqli_query($cn,$sqlt))
	  			{
	  				$row = mysqli_fetch_row($result);
	  				if($row > 0)
	  				{
	  					$trans_id = $row[0]+1;
	  				}
	  			}

	  	if($_SESSION['role']=='ADMIN')
		{
			$account = $_POST['account'];
		}
		else
		{
			$account = $_SESSION['id'];
		}

			$sql = "insert into transfer (trans_id, account_from, account_to, amount, date) values ('".$trans_id."','".$account."','".$_POST['account_to']."','".$_POST['amount']."','".$date."')";

			if(mysqli_query($cn,$sql))
			{
				print '<p class="balance">Balance Transfer Successfully<br>';
				print 'Your Transection ID : '.$trans_id.'</p>';

			}
			else
			{
				print 'Error';
			}
		}
	}
	

	if(isset($_GET['id']))
	{
		$sqls = "select trans_id,account_from,account_to,amount,date from transfer where trans_id =".$_GET['id'];
		if ($result=mysqli_query($cn,$sqls))
  			{
  				$row = mysqli_fetch_row($result);
  				if($row > 0)
  				{
  					$trans_id = $row[0];
  					$account = $row[1];
  					$account_to = $row[2];
  					$amount = $row[3];
  					$date = $row[4];
  				}
  			}
	}

	if(isset($_POST['update']))
	{

		$err = 0;

		if($_POST['account']=="")
		{
			$erraccount_from = '<span style="color:red;"> * Required</span>';
			$err++;
		}

		if($_POST['account_to']=="")
		{
			$erraccount_to = '<span style="color:red;"> * Required</span>';
			$err++;
		}

		if($_POST['amount']=="")
		{
			$erramount = '<span style="color:red;"> * Required</span>';
			$err++;
		}

		if($_SESSION['role']=='ADMIN')
		{
			$acc = $_POST['account'];
		}
		else
		{
			$acc = $_SESSION['id'];
		}

		$sqlb = "select balance from balance where account_no = ".$acc;
			if ($result=mysqli_query($cn,$sqlb))
	  			{
	  				$row = mysqli_fetch_row($result);
	  				if($row > 0)
	  				{
	  					$bl = $row[0]-($_POST['amount']-$amount);
	  					if($bl<0)
	  					{
	  						print '<h2 class="balancee">Insufficient Balance In your Account<h2>';
	  						$err++;
	  					}
	  				}
	  			}


		if($err==0)
		{


			if($_SESSION['role']=='ADMIN')
			{
				$account = $_POST['account'];
			}
			else
			{
				$account = $_SESSION['id'];
			}

				$sqlu = "update transfer set account_from ='".$account."', account_to = '".$_POST['account_to']."', amount = '".$_POST['amount']."' where trans_id =".$trans_id;

				if(mysqli_query($cn,$sqlu))
				{
					print '<p class="balance">Balance Transfer Updated Successfully</p>';
					$trans_id = "";
					$account = "";
					$account_to = "";
					$amount = "";
					$date = "";
				}
				else
				{
					print 'Error '.mysqli_error($cn);
				}
		}
	}
}


print '

<!DOCTYPE html>
<html>
<head>
	<title>Blance Transfer from Account</title>
</head>
<body>
	<form method="post" action="">
		<fieldset>
				<legend>Balance Transfer Form</legend>';
		if($_SESSION['role']=='ADMIN')
		{
			print '<input type="text" name="account" value="'.$account.'" placeholder="Account From" autocomplete="off">'.$erraccount_from.'
		<br>';
		}
		print '<input type="text" name="account_to" value="'.$account_to.'" placeholder="Account To" autocomplete="off">'.$erraccount_to.'
		<br>
		<input type="text" name="amount" value="'.$amount.'" placeholder="Transfer Amount" autocomplete="off">'.$erramount.'
		<br>';
		
		if(isset($_GET['id']))
		{
			print '<input type="submit" class="submit" name="update" value="Update Transfer">';
		}
		else
		{
			print '<input type="submit" class="submit" name="submit" value="Transfer">';
		}
	print '</fieldset></form>
</body>
</html>

';


?>

