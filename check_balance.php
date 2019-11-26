<?php
$cn = mysqli_connect("localhost","root","","bankmanagement");

$balance = "";
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
				$balance = '<h3 class="balance">Your Balance is : '.$row[0].'</h3>';
			}
			else
			{
				$balance = '<h3 class="balancee">Account Not Found</h3>';
			}

			print $balance;
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Check Balance</title>
</head>
<body>
	<?php
	print '<form method="post" action="">';
	if($_SESSION['role']=='ADMIN')
	{
		print '<input type="text" name="account_no" placeholder="Account No/Phone No" autocomplete="off">'.$erraccount.'<br>';
	}
	else
	{
		print '<input type="hidden" name="account_no" value="'.$_SESSION['id'].'" placeholder="Account No/Phone No" autocomplete="off">';
	}
		print '<input type="submit" name="submit" class="submit" value="Check">
	</form>';
	?>
</body>
</html>