<?php

if(isset($_GET['id']))
{
	$cn = mysqli_connect("localhost","root","","bankmanagement");
	$sql = "select trans_id,cheque_no,amount,withdraw_type,date from withdraw where account_no = '".$_GET['id']."' order by trans_id DESC";

			if($result = mysqli_query($cn,$sql))
			{
				print '<table class="table">';
				if($_SESSION['role']=='ADMIN')
					{
						print '<tr class="th"><th>Date</th><th>Transection Id</th><th>Cheque/Card No</th><th>Withdraw From</th><th>Amount</th><th>Action</th></tr>';
					}
					else
					{
						print '<tr class="th"><th>Date</th><th>Transection Id</th><th>Cheque/Card No</th><th>Withdraw From</th><th>Amount</th></tr>';
					}

				while ($row = mysqli_fetch_array($result)) 
				{
					if($_SESSION['role']=='ADMIN')
					{
						print '<tr class="tr"><td>'.$row['date'].'</td><td>'.$row['trans_id'].'</td><td>'.$row['cheque_no'].'</td><td>'.$row['withdraw_type'].'</td><td>'.$row['amount'].'</td><td><a class="btn" href="?p=balance_withdraw&id='.$row['trans_id'].'">Edit</a></td></tr>';
					}
					else
					{
						print '<tr class="tr"><td>'.$row['date'].'</td><td>'.$row['trans_id'].'</td><td>'.$row['cheque_no'].'</td><td>'.$row['withdraw_type'].'</td><td>'.$row['amount'].'</td></tr>';
					}
				}

				print '</table>';
			}
			else
			{
				print '<h2 class="balancee">Data Not Found</h2>';
			}
}
else
{
	print '<h2 class="balancee">Enter valid Account No</h2>';
}