<?php

$cn = mysqli_connect("localhost","root","","bankmanagement");

$trans_id = "";
$account = "";
$slip = "";
$wtype = "";
$amount = "";
$date = "";

$erraccount = "";
$errslip = "";
$errwtype = "";
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

		if($_POST['account']=="")
		{
			$erraccount = '<span style="color:red;">* Required</span>';
			$err++;
		}

		if($_POST['cheque']=="")
		{
			$errslip = '<span style="color:red;">* Required</span>';
			$err++;
		}

		if($_POST['amount']=="" || $_POST['amount'] < 1)
		{
			$erramount = '<span style="color:red;">* Required</span>';
			$err++;
		}

		if($_POST['wtype']=="")
		{
			$errwtype = '<span style="color:red;">* Required</span>';
			$err++;
		}


		$sqlb = "select balance from balance where account_no = ".$_POST['account'];
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


			$sql = "insert into withdraw (trans_id, account_no, withdraw_type, amount, cheque_no, date) values ('".$trans_id."','".$_POST['account']."','".$_POST['wtype']."','".$_POST['amount']."','".$_POST['cheque']."','".$date."')";

			if(mysqli_query($cn,$sql))
			{
				print '<p class="balance">Withdraw Successful<br>';
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
		$sqls = "select trans_id,account_no,withdraw_type,amount, cheque_no,date from withdraw where trans_id =".$_GET['id'];
		if ($result=mysqli_query($cn,$sqls))
  			{
  				$row = mysqli_fetch_row($result);
  				if($row > 0)
  				{
  					$trans_id = $row[0];
  					$account = $row[1];
  					$wtype = $row[2];
  					$amount = $row[3];
  					$slip = $row[4];
  					$date = $row[5];
  				}
  			}
	}

	if(isset($_POST['update']))
	{

		$err = 0;

		if($_POST['account']=="")
		{
			$erraccount = '<span style="color:red;">* Required</span>';
			$err++;
		}

		if($_POST['cheque']=="")
		{
			$errslip = '<span style="color:red;">* Required</span>';
			$err++;
		}

		if($_POST['amount']=="")
		{
			$erramount = '<span style="color:red;">* Required</span>';
			$err++;
		}

		if($_POST['wtype']=="")
		{
			$errwtype = '<span style="color:red;">* Required</span>';
			$err++;
		}


		$sqlb = "select balance from balance where account_no = ".$_POST['account'];
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

			$sqlu = "update withdraw set account_no ='".$_POST['account']."', withdraw_type = '".$_POST['wtype']."', amount = '".$_POST['amount']."', cheque_no = '".$_POST['cheque']."' where trans_id =".$trans_id;

			if(mysqli_query($cn,$sqlu))
			{
				print '<p class="balance">Withdraw Updated Successfully</p>';
				$trans_id = "";
				$account = "";
				$slip = "";
				$wtype = "";
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
	<title>Withdraw from Account</title>
</head>
<body>
	<form method="post" action="">
		<fieldset>
				<legend>Withdraw Form</legend>
		<input type="text" name="account" value="'.$account.'" placeholder="Account No" autocomplete="off">'.$erraccount.'
		<br>
		<input type="text" name="cheque" value="'.$slip.'" placeholder="Cheque No/ Card No" autocomplete="off">'.$errslip.'
		<br>
		<select name="wtype">
		<option value="">Select Withdraw Type</option>';
			if ($wtype == 'ATM' )
			{
				print '<option value="ATM" selected>ATM</option>
						<option value="BANK">BANK</option>
						<option value="AGENT">AGENT</option>';
			}
			else if($wtype == 'BANK')
			{
				print '<option value="ATM">ATM</option>
						<option value="BANK" selected>BANK</option>
						<option value="AGENT">AGENT</option>';
			}
			elseif ($wtype == 'AGENT') {
				print '<option value="ATM">ATM</option>
						<option value="BANK">BANK</option>
						<option value="AGENT" selected>AGENT</option>';
			}
			else
			{
				print '<option value="ATM">ATM</option>
						<option value="BANK">BANK</option>
						<option value="AGENT">AGENT</option>';
			}

		print '</select>'.$errwtype.'
		<br>
		<input type="text" name="amount" value="'.$amount.'" placeholder="Withdraw Amount" autocomplete="off">'.$erraccount.'
		<br>';
		
		if(isset($_GET['id']))
		{
			print '<input type="submit" class="submit" name="update" value="Update Withdraw">';
		}
		else
		{
			print '<input type="submit" class="submit" name="submit" value="Withdraw">';
		}
	print '</fieldset></form>
</body>
</html>

';


?>

