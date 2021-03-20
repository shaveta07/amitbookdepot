<?php
$html="<table width='100%' border='1'><thead><tr style='border-top:1px solid #ccc'>
<th>S.No.</th>
<th>Invoive Number</th>
<th>Date</th>
<th>Status</th>
<th>AP Invoice Amount</th>
<th>Paid Amount</th>
<th>Description</th>
</tr></thead>";
$html .= "<tbody>";$sts = '';$total_dr=0;
	$total_cr=0;
for($i=0;$i<count($data);$i++){
	
	if($data[$i]['Status']=='P')
				{
				$sts = "<span style='color:green'>Paid</span>";
				$total_cr+=$data[$i]['Total'];
				}
				else if($data[$i]['Status']=='C')
				{
				$sts = "<span style='color:red'>Canceled</span>";
				}
				else
				{
				$sts = "<span style='color:orange'>Unpaid</span>";
				$total_dr+=$data[$i]['Total'];
				}
$html .= "<tr style='border-top:1px solid #ccc'>
<td>$i</td>
<td>".$data[$i]['InvoiceNumber']."</td>
<td>".$data[$i]['Date']."</td>
<td>".$sts."</td>
<td>".$data[$i]['Total']."</td>
<td></td>
<td>".$data[$i]['Description']."</td>
</tr>";
}
$html .= "<tr rowspan='3' style='border-top:1px solid #ccc'><td colspan='7'>&nbsp;</td></tr>";
$html .= "<tr style='border-top:1px solid #ccc'><td colspan='7'>&nbsp;</td></tr>";
$html .= "<tr style='border-top:1px solid #ccc'><td colspan='7'>&nbsp;</td></tr>";
$html .= "<tr style='border-top:1px solid #ccc'><td colspan='7'>Total Credit: ".$total_cr."</td></tr>";
$html .= "<tr style='border-top:1px solid #ccc;border-bottom:1px solid #ccc'><td colspan='7'>Total Debit: ".$total_dr."</td></tr>";
$html .= "</tbody></table>";
echo $html;
?>