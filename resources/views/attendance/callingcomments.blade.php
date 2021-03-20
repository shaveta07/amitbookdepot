
 <form method="post" id="commentform">
 
	@csrf		   
 <div class="col-sm-12">
    <div class="form-group col-sm-6">
        <div class="row">
            <div class="form-group col-sm-12">
            
                <label>Status :</label>
                    <select required name="status" id="reminder_status" class="form-control">
                        <option value="Do not pick">Do not pick</option>
                        <option value="Switched Off">Switched Off</option>
                        <option value="out of covarage area">Out of covarage area</option>
                        <option value="call later">Call later</option>
                        <option value="unknown">Unknown</option>
                        <option value="do not desturb">Do not desturb</option>
                        <option value="done">Done</option>
                        <option value="busy">Busy</option>
                        <option value="wrong number">Wrong number</option>
                        <option value="not interested">Not Interested</option>
                        <option value="interested">Interested</option>
                        <!--
                        //'Do not pick','Switched Off','out of covarage area','call later','unknown','do not desturb'
                        -->
                    
                    </select>
            </div>
        </div>
    </div>
                            
    <div class="form-group col-sm-6">
        <div class="row">
            <div class="form-group col-sm-12">
                <label>Reminder :</label>
                <input type="date" required name="reminder" class="form-control" id="reminder_comment" />
                
            </div>
        </div>
    </div>
             
</div>


<div class="col-sm-12">
    <div class="form-group col-sm-12">
        <div class="row">
             <div class="form-group col-sm-12">
                <label>Comments :</label>
                <textarea required name="comment" id="comment_reminder"  class="form-control ckeditor"></textarea>
            </div>
        </div>
     </div>	
     </div>
 <?php

//print_r($allopen); InvoiceLookupType
?>	
     
    <div class="form-group col-sm-12">
        <div class="row">
            <div class="form-group col-sm-12">
                <input type="hidden" name="caller_id" value="<?= $id ?>" />
                <input type="submit" class="btn btn-default" value="Save" name="save_comment" />
             
            </div>
        </div>
    </div>	
                 


</form>	

<div class="col-sm-12"> 
    <div class="table-responsive">
    <table id="invtbl" style= "border-color: rgb(17, 2, 1);" class="table table-bordered" >
        <thead>
        <tr>
        <th>Status</th>
        <th>Entry Date</th>
        <th>Reminder Date</th>
        <th>Entry By</th>
        <th>comments</th>
        </tr>
        </thead>
        <tfoot>
        <tr>

        <th>Status</th>
        <th>Entry Date</th>
        <th>Reminder Date</th>
        <th>Entry By</th>
        <th>comments</th>
        </tr>
        </tfoot>
        <tbody>
        <?php
        //$rrr = get_all_array($con,"select * from calling_comment where calling_id = '$id' order by id desc");
        //echo "select * from calling_comment where calling_id = '$id' order by id desc";
        //print_r($rrr);
        $i=0;
        foreach($rrr as $r):
        ?>
        <tr>

        <td><?= $r->status ?></td>
        <td><?= $r->comment_date ?></td>
        <td><?= $r->reminder_date ?></td>
        <td><?= $r->entry_by ?></td>
        <td><?= $r->comment ?></td>

        </tr>
        <?php endforeach; ?>
        </tbody>
        </table>
    </div>
</div>
