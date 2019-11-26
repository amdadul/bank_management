<?php
$cn = mysqli_connect("localhost","root","","bankmanagement");

$balance = "";
$account_no = "";
$name = "";
$address = "";
$phone_no = "";
$branch = "";
$account_type = "";
$erraccount = "";

if(mysqli_connect_errno())
{
	print 'Database Connection Error';
}
else
{
	if (isset($_POST['submit']))
	{

		$err = 0;
		if($_POST['account_no']=="")
		{
			$erraccount = '<span style="color:red;">* Required</span>';
			$err++;
		}

		if($err == 0)
		{

			$sql = "select balance from balance,client where balance.account_no = client.account_no and 
			(client.account_no = '".$_POST['account_no']."' or client.phone_no = '".$_POST['account_no']."')";
			$result = mysqli_query($cn,$sql);
			$row = mysqli_fetch_row($result);
			if($row > 0)
			{
				$balance = $row[0];
			}
			else
			{
				$balance = "";
			}

			$sql = "select account_no,name,address,phone_no,branch,account_type from client where account_no = '".$_POST['account_no']."' or phone_no = '".$_POST['account_no']."'";
			$result = mysqli_query($cn,$sql);
			$row = mysqli_fetch_row($result);
			if($row > 0)
			{
				$account_no = $row[0];
				$name = $row[1];
				$address = $row[2];
				$phone_no = $row[3];
				$branch = $row[4];
				$account_type = $row[5];
			}
			else
			{
				$account_no = "";
				$name = "";
				$address = "";
				$phone_no = "";
				$branch = "";
				$account_type = "";
			}
		}
	}
	else
	{
		if($_SESSION['role']!='ADMIN')
		{
			$sql = "select balance from balance,client where balance.account_no = client.account_no and 
			(client.account_no = '".$_SESSION['id']."')";
			$result = mysqli_query($cn,$sql);
			$row = mysqli_fetch_row($result);
			if($row > 0)
			{
				$balance = $row[0];
			}
			else
			{
				$balance = "";
			}

			$sql = "select account_no,name,address,phone_no,branch,account_type from client where account_no = '".$_SESSION['id']."' ";
			$result = mysqli_query($cn,$sql);
			$row = mysqli_fetch_row($result);
			if($row > 0)
			{
				$account_no = $row[0];
				$name = $row[1];
				$address = $row[2];
				$phone_no = $row[3];
				$branch = $row[4];
				$account_type = $row[5];
			}
			else
			{
				$account_no = "";
				$name = "";
				$address = "";
				$phone_no = "";
				$branch = "";
				$account_type = "";
			}
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Manage Account</title>
</head>
<body>
	<?php
	if($_SESSION['role'] =='ADMIN')
	{
		print '<form method="post" action="">
		<input type="text" name="account_no" placeholder="Account No/Phone No" autocomplete="off"><?php print $erraccount; ?>
		<input type="submit" name="submit" class="submit" value="Show">
	</form>';
	}
?>

<?php
if($account_no !="")
{
	print '
	<table class="table">
	<tr class="trm"><td>Account No : '.$account_no.'</td><td>';
		if($_SESSION['role']=='ADMIN')
			{
				print '<a class="btn" href="?p=create_account&id='.$account_no.'">Update Account Info</a></td></tr>';
			}
	print '<tr class="trm"><td>Name : '.$name.'</td><td>Phone No: '.$phone_no.'</td></tr>
	<tr class="trm"><td>Address: '.$address.'</td><td>Branch: '.$branch.'</td></tr>
	<tr class="trm"><td>Account Type: ';
	if($account_type == "SV")
	{
		print 'Savings';
	}
	else
	{
		print 'Current';
	}
	print '</td><td>Balance: '.$balance.'</td></tr>
	<tr class="trm"><td><a class="btn" href="?p=deposit_statement&id='.$account_no.'">Deposit Statement</a></td><td><a class="btn" href="?p=withdraw_statement&id='.$account_no.'">Withdraw Statement</a></td></tr>
	<tr class="trm"><td><a class="btn" href="?p=transfer_statement&id='.$account_no.'">Transfer Statement</a></td><td><a class="btn" href="?p=transection_statement&id='.$account_no.'">Transection Statement</a></td></tr>
	</table>
	</body>
</html>
	';
}
else
{
	print '<h2 class="balancee">Data Not Found</h2>';
}
?>