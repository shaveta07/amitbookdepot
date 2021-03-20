<?php

$totalpaid = 0;$totalbal = 0;$totalbill=0;
$html="<h2>Invoice Report</h2><br/><table width='100%' border='1'><thead><tr style='border-top:1px solid #ccc'>
<th>S.No.</th>
<th>Invoive Number</th>
<th>Date</th>
<th>Status</th>
<th>AR Invoice Amount</th>
<th>Paid Amount</th>
<th>Balance</th>


</tr></thead>";

$html .= "<tbody>";$sts = '';$total_dr=0;
	$total_cr=0;$i=0;
foreach($rrr as $r){
	$amt = DB::table('arcredit')->where('invoiceid',$r->invoiceid)->sum('amount');
	$balance = $r['Amount'] - $recieved;
	if($r['Status']=='P')
				{
				$sts = "<span style='color:green'>Paid</span>";
				//$total_cr+=$data[$i]['Total'];
				$recieved = $r['Amount'];
				
				}
				else if($r['Status']=='C')
				{
				$sts = "<span style='color:red'>Canceled</span>";
				}
				else
				{
				$sts = "<span style='color:orange'>Unpaid</span>";
				//$total_dr+=$data[$i]['Total'];
				//$recieved = $r['Amount'];
				}
$totalpaid = $totalpaid+$recieved;
$totalbal = $totalbal+$balance;
$totalbill = $totalbill+$r['Amount'];

$html .= "<tr style='border-top:1px solid #ccc;background-color:#ddd'>

<td>".++$i."</td>
<td>".$r['InvoiceNumber']."</td>
<td>".$r['InvoiceDate']."</td>
<td>".$sts."</td>
<td>".$r['Amount']."</td>
<td>".$recieved."</td>
<td>".$balance."</td>

</tr>";

$rrr2 = array();
$sql2 = DB::table('arcredit')->where('invoiceid',$rrr['InvoiceID'])->get();
foreach($sql2 as $sq)
{
   $items = array(
       'invoiceid' => $sq->invoiceid,
       'amount'=> $sq->amount,
       'paymentdate' => $sq->paymentdate,
       'clearancedate' => $sq->clearancedate,
       'ModeOfPayment' => $sq->ModeOfPayment,
       'cheque' => $sq->cheque,
       'description' => $sq->description
   );
   array_push($rrr2,$items);
}
if(count($rrr2)>0){
$html .= "<tr>";
$html .= "<td>&nbsp;</td>";
$html .= "<td colspan='6'>";
$html .= "<table width='100%' border='1'><thead><tr style='border-top:1px solid #ccc'>
<th>S.No.</th>
<th>credit ID</th>
<th>Paid Amount</th>
<th>Paid date</th>
<th>Clearance date</th>
<th>ModeOfPayment</th>
<th>cheque</th>
<th>Description</th>

</tr></thead>";
$html .= "<tbody>";
$j=0;
foreach($rrr2 as $r2):
$html .= "<tr>";
$html .= "<td>".++$j."</td>";
$html .= "<td>".$r2['invoiceid']."</td>";
$html .= "<td>".$r2['amount']."</td>";
$html .= "<td>".$r2['paymentdate']."</td>";
$html .= "<td>".$r2['clearancedate']."</td>";
$html .= "<td>".$r2['ModeOfPayment']."</td>";
$html .= "<td>".$r2['cheque']."</td>";
$html .= "<td>".$r2['description']."</td>";
$html .= "</tr>";
endforeach;
$html .= "</tbody></table>";
$html .= "</td>";

$html .= "</tr>";	
	}
}
//$html .= "<tr rowspan='3' style='border-top:1px solid #ccc'><td colspan='7'>&nbsp;</td></tr>";
$html .= "<tr style='border-top:1px solid #ccc'><td colspan='7'>&nbsp;</td></tr>";
$html .= "<tr style='border-top:1px solid #ccc'><td colspan='7'>&nbsp;</td></tr>";
$html .= "<tr style='border-top:1px solid #ccc'><td>Total Billed: ".$totalbill."</td><td></td>
<td>Total Recieved: ".$totalpaid."</td><td></td>
<td>Total Balance: ".$totalbal."</td><td></td>
<td></td>
</tr>";

$html .= "</tbody></table>";
echo $html;
?>
