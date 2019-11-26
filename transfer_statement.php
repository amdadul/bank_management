<?php

if(isset($_GET['id']))
{
	$cn = mysqli_connect("localhost","root","","bankmanagement");
	$sql = "select trans_id,account_to,amount,date from transfer where account_from = '".$_GET['id']."' order by trans_id ASC";

			if($result = mysqli_query($cn,$sql))
			{
				print '<table class="table">
	<tr class="th"><th>Date</th><th>Transection Id</th><th>Account To</th><th>Amount</th></tr>';

				while ($row = mysqli_fetch_array($result)) 
				{
					print '<tr class="tr"><td>'.$row['date'].'</td><td>'.$row['trans_id'].'</td><td>'.$row['account_to'].'</td><td>'.$row['amount'].'</td></tr>';
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