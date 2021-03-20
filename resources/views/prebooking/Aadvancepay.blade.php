<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Advance Pay Invoice</title>
	
</head>
<style>

</style>
<body onLoad="window.print();">
    <div id="wrapper">


<?php
                $payHistory = \App\PrebookingPayment::where('invoiceid',$invoice_id)->get();
              
                        $paid=0;
                  
                ?>
                <p>Payment History</p>
					
                <div class="row">
                    <ul class="payhistory">
                    <?php
                            
                        foreach($payHistory as $payh):
                            echo "<li>";
                            $emailid = $user->email;
                            
                            echo $payh->paid.'INR on '.$payh->paiddate .' Using '.$payh->modeofpayment.' By '.$emailid;
                            echo "</li>";
                            $paid = $paid+$payh->paid;
                        endforeach;
                    
                    ?>
                    </ul>
                
                </div>
                </div>
    <!-- /#wrapper -->

</body>
</html>
                