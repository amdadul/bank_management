<?php

$cn = mysqli_connect("localhost","root","","bankmanagement");

$trans_id = "";
$account = "";
$slip = "";
$amount = "";
$date = "";

$erraccount = "";
$errslip = "";
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

		if($_POST['slip']=="")
		{
			$errslip = '<span style="color:red;">* Required</span>';
			$err++;
		}

		if($_POST['amount']=="" || $_POST['amount'] < 1)
		{
			$erramount = '<span style="color:red;">* Required</span>';
			$err++;
		}

		if($err == 0)
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


			$sql = "insert into deposit (trans_id, account_no, slip_no, amount, date) values (".$trans_id.",".$_POST['account'].",'".$_POST['slip']."',".$_POST['amount'].",'".$date."')";

			if(mysqli_query($cn,$sql))
			{
				print '<p class="balance">Cash Deposit Successfully<br>';
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
		$sqls = "select trans_id,account_no,slip_no,amount,date from deposit where trans_id =".$_GET['id'];
		if ($result=mysqli_query($cn,$sqls))
  			{
  				$row = mysqli_fetch_row($result);
  				if($row > 0)
  				{
  					$trans_id = $row[0];
  					$account = $row[1];
  					$slip = $row[2];
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
			$erraccount = '<span style="color:red;">* Required</span>';
			$err++;
		}

		if($_POST['slip']=="")
		{
			$errslip = '<span style="color:red;">* Required</span>';
			$err++;
		}

		if($_POST['amount']=="" || $_POST['amount'] < 1)
		{
			$erramount = '<span style="color:red;">* Required</span>';
			$err++;
		}

		if($err == 0)
		{

			$sqlu = "update deposit set account_no ='".$_POST['account']."', slip_no = '".$_POST['slip']."', amount = '".$_POST['amount']."' where trans_id =".$trans_id;

			if(mysqli_query($cn,$sqlu))
			{
				print '<p class="balance">Deposit Updated Successfully</p>';
				$trans_id = "";
				$account = "";
				$slip = "";
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
	<title>Deposit to Account</title>
</head>
<body>
	<form method="post" action="">
		<fieldset>
				<legend>Deposit Form</legend>
		<input type="text" name="account" value="'.$account.'" placeholder="Account No" autocomplete="off" >'.$erraccount.'
		<br>
		<input type="text" name="slip" value="'.$slip.'" placeholder="Deposit Slip No" autocomplete="off">'.$errslip.'
		<br>
		<input type="text" name="amount" value="'.$amount.'" placeholder="Deposit Amount" autocomplete="off">'.$erramount.'
		<br>';
		
		if(isset($_GET['id']))
		{
			print '<input type="submit" class="submit" name="update" value="Update Deposit">';
		}
		else
		{
			print '<input type="submit" class="submit" name="submit" value="Deposit">';
		}
	print '</fieldset></form>
</body>
</html>

';


?>

