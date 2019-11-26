<?php

if(isset($_GET['id']))
{
	$cn = mysqli_connect("localhost","root","","bankmanagement");
	$sql = "select trans_id,slip_no,amount,date from deposit where account_no = '".$_GET['id']."' order by trans_id DESC";

			if($result = mysqli_query($cn,$sql))
			{
				print '<table class="table">
	<tr class="th"><th>Date</th><th>Transection Id</th><th>Deposit Slip No</th><th>Amount</th></tr>';

				while ($row = mysqli_fetch_array($result)) 
				{
					print '<tr class="tr"><td>'.$row['date'].'</td><td>'.$row['trans_id'].'</td><td>'.$row['slip_no'].'</td><td>'.$row['amount'].'</td></tr>';
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