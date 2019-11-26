<?php

$cn = mysqli_connect("localhost","root","","bankmanagement");

$name = "";
$address = "";
$phone = "";
$branch = "";
$acctype = "";
$account_no = "";
$password = "";
$role = "";

$errname = "";
$erraddress = "";
$errphone ="";
$errbranch = "";
$erracctype = "";

if(mysqli_connect_errno())
{
	print 'Database Connection Error';
}
else
{
	$date = date("Y-m-d");
	if(isset($_POST['submit']))
	{


		$name = $_POST['name'];
		$address = $_POST['address'];
		$phone = $_POST['phone'];
		$branch = $_POST['branch'];
		$acctype = $_POST['acctype'];

		$err = 0;

		if($name=="")
		{
			$errname = '<span style="color:red;">* Required</span>';
			$err++;
		}

		if($address=="")
		{
			$erraddress = '<span style="color:red;">* Required</span>';
			$err++;
		}

		if($phone =="")
		{
			$errphone = '<span style="color:red;">* Required</span>';
			$err++;
		}

		if($branch =="")
		{
			$errbranch = '<span style="color:red;">* Required</span>';
			$err++;
		}

		if($acctype =="")
		{
			$erracctype = '<span style="color:red;">* Required</span>';
			$err++;
		}

		if($err == 0)
		{

			$sql = "insert into client (name, address, phone_no, branch, account_type, role, password,  create_date) values ('".$_POST['name']."','".$_POST['address']."','".$_POST['phone']."','".$_POST['branch']."','".$_POST['acctype']."','".$_POST['role']."', password('".$_POST['password']."') ,'".$date."')";

			if(mysqli_query($cn,$sql))
			{
				print '<p class="balance">Account Created Successfully<br>';
				print 'Your Account No : '.mysqli_insert_id($cn).'</p>';

				$name = "";
				$address = "";
				$phone = "";
				$branch = "";
				$acctype = "";
				$account_no = "";

			}
			else
			{
				print 'Error';
			}
		}
	}
	

	if(isset($_GET['id']))
	{
		$sqls = "select account_no,name,address,phone_no,branch,role,password,account_type from client where account_no =".$_GET['id'];
		if ($result=mysqli_query($cn,$sqls))
  			{
  				$row = mysqli_fetch_row($result);
  				if($row > 0)
  				{
  					$account_no = $row[0];
  					$name = $row[1];
  					$address = $row[2];
  					$phone = $row[3];
  					$branch = $row[4];
  					$role = $row[5];
  					$password = "";
  					$acctype = $row[7];
  				}
  			}
	}

	if(isset($_POST['update']))
	{

		$name = $_POST['name'];
		$address = $_POST['address'];
		$phone = $_POST['phone'];
		$branch = $_POST['branch'];
		$acctype = $_POST['acctype'];
		$role = $_POST['role'];
		$password = $_POST['password'];

		$err = 0;

		if($name=="")
		{
			$errname = '<span style="color:red;">* Required</span>';
			$err++;
		}

		if($address=="")
		{
			$erraddress = '<span style="color:red;">* Required</span>';
			$err++;
		}

		if($phone =="")
		{
			$errphone = '<span style="color:red;">* Required</span>';
			$err++;
		}

		if($branch =="")
		{
			$errbranch = '<span style="color:red;">* Required</span>';
			$err++;
		}

		if($acctype =="")
		{
			$erracctype = '<span style="color:red;">* Required</span>';
			$err++;
		}

		if($err == 0)
		{

			$sqlu = "update client set name ='".$_POST['name']."', address = '".$_POST['address']."', phone_no = '".$_POST['phone']."', branch = '".$_POST['branch']."', account_type = '".$_POST['acctype']."',role = '".$_POST['role']."',password = password('".$_POST['password']."')  where account_no =".$account_no;

			if(mysqli_query($cn,$sqlu))
			{
				print '<p class="balance">Account Updated Successfully</p>';
				$name = "";
				$address = "";
				$phone = "";
				$branch = "";
				$acctype = "";
				$account_no = "";
				$role = "";
				$password = "";
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
	<title>Create Account</title>
</head>
<body>
	<form method="post" action="">
		<fieldset>
				<legend>Account Create Form</legend>
		<input type="text" class="input" name="name" value="'.$name.'" placeholder="Name" autocomplete="off">'.$errname.'
		<br>
		<textarea name="address" placeholder="Address">'.$address.'</textarea>'.$erraddress.'
		<br>
		<input type="text" name="phone" value="'.$phone.'" placeholder="Phone Number" autocomplete="off">'.$errphone.'
		<br>
		<input type="text" name="branch" value="'.$branch.'" placeholder="Branch Name" autocomplete="off">'.$errbranch.'
		<br>
		<select name="acctype">
			<option value="">Select Account Type</option>';
			if ($acctype == 'SV' )
			{
				print '<option value="SV" selected>Savings</option>';
			}
			else
			{
				print '<option value="SV">Savings</option>';
			}

			if ($acctype == 'CR' )
			{
				print '<option value="CR" selected>Current</option>';
			}
			else
			{
				print '<option value="CR">Current</option>';
			}
			print '
		</select>'.$erracctype.'<br>
		<select name="role">
			<option value="">Select User Role</option>';
			if ($role == 'USER' )
			{
				print '<option value="USER" selected>User</option>';
				print '<option value="ADMIN">Admin</option>';
			}
			else if($role == 'ADMIN' )
			{
				print '<option value="USER" >User</option>';
				print '<option value="ADMIN" selected>Admin</option>';
			}
			else
			{
				print '<option value="USER" >User</option>';
				print '<option value="ADMIN" >Admin</option>';
			}
			print '
		</select>
		<br>
		<input type="password" name="password" value="'.$password.'" placeholder="password" autocomplete="off">
		<br>';
		if(isset($_GET['id']))
		{
			print '<input type="submit" class="submit" name="update" value="Update Account">';
		}
		else
		{
			print '<input type="submit" class="submit" name="submit" value="Create Account">';
		}
	print '</fieldset></form>
</body>
</html>

';


?>

