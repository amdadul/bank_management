<?php

if(isset($_GET['id']))
{
	$cn = mysqli_connect("localhost","root","","bankmanagement");
	$sql = "select trans_id,trans_type,amount,trans_date from transection where account_no = '".$_GET['id']."' order by trans_date ASC";

	$sqlt = "select trans_id,amount,date from transfer where account_to = '".$_GET['id']."' order by trans_id ASC";

			if($result = mysqli_query($cn,$sql))
			{
				print '<table class="table">
	<tr class="th"><th>Date</th><th>Transection Id</th><th>Transection Type</th><th>Amount</th></tr>';

				$balance = 0;
				$resultf = mysqli_query($cn,$sqlt);

				while ($row = mysqli_fetch_array($result)) 
				{
					if($row['trans_type']=='deposit')
					{
						$balance+=$row['amount'];
					}
					else
					{
						$balance-=$row['amount'];
					}

					print '<tr class="tr"><td>'.$row['trans_date'].'</td><td>'.$row['trans_id'].'</td><td>'.$row['trans_type'].'</td><td>'.$row['amount'].'</td></tr>';
				}

				while ($row = mysqli_fetch_array($resultf)) 
				{
					$balance+=$row['amount'];

					print '<tr class="tr"><td>'.$row['date'].'</td><td>'.$row['trans_id'].'</td><td>Cash-in</td><td>'.$row['amount'].'</td></tr>';

				}

				print '<tr class="th"><td colspan="3">Ending Balance</td><td>'.$balance.'</td></tr>';
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