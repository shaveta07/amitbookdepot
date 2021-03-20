<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use Session;
use Auth;
use Hash;
use App\BusinessSetting;
use App\Http\Controllers\SearchController;
use ImageOptimizer;
use Cookie;
use DB;
use DateTime;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sts = '';
        $startdate = isset($request->startdate)? date("Y-m-d", strtotime($request->startdate)) : '';
        $enddate = isset($request->enddate) ? date("Y-m-d", strtotime($request->enddate)) : '';
        $search = isset($request->search)?$request->search:'';
        $users = isset($request->users)?$request->users:'';
        if(Auth::user()->user_type == 'admin'){
                $rrr = DB::table('tasks')
                ->join('taskissues','taskissues.taskid', '=', 'tasks.id' )
                ->select('tasks.id', 'tasks.title','tasks.status','tasks.isrecursive','tasks.duedate','tasks.createdby', 'tasks.createddate','tasks.startdate','tasks.store_id', 'taskissues.assignfrom', 'taskissues.assignto')
                ->where ('tasks.status', '=', 'open')
                ->orwhere('tasks.status','=','pending')
                ->groupBy('tasks.id')
                ->orderBy('tasks.id', 'desc')
                ->get();
        }
        else{
            $rrr =  DB::select(DB::raw("select * from tasks where createdby = '".Auth::user()->id."' and id IN (SELECT taskid FROM `taskissues` where assignfrom='".Auth::user()->id."' OR assignto='".Auth::user()->id."' ORDER BY `tasks`.`id` DESC )"));
        }
      
        //$attributes = Attribute::all();
        return view('task.tasklist',compact('sts','rrr','startdate','enddate','search','users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sts = '';
       
        //$attributes = Attribute::all();
        return view('task.createtask',compact('sts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
       // $target_dir = "task/";
       $sts ='';
        $startdate = isset($request->startdate)? date("Y-m-d", strtotime($request->startdate)) : '';
        $enddate = isset($request->enddate) ? date("Y-m-d", strtotime($request->enddate)) : '';
        $search = isset($request->search)?$request->search:'';
        $users = isset($request->users)?$request->users:'';
        if(Auth::user()->user_type == 'admin'){
                $rrr = DB::table('tasks')
                ->join('taskissues','taskissues.taskid', '=', 'tasks.id' )
                ->select('tasks.id', 'tasks.title','tasks.status','tasks.isrecursive','tasks.duedate','tasks.createdby', 'tasks.createddate','tasks.startdate','tasks.store_id', 'taskissues.assignfrom', 'taskissues.assignto')
                ->where ('tasks.status', '=', 'open')
                ->orwhere('tasks.status','=','pending')
                ->groupBy('tasks.id')
                ->orderBy('tasks.id', 'desc')
                ->get();
        }
        else{
            $rrr =  DB::select(DB::raw("select * from tasks where createdby = '".Auth::user()->id."' and id IN (SELECT taskid FROM `taskissues` where assignfrom='".Auth::user()->id."' OR assignto='".Auth::user()->id."' ORDER BY `tasks`.`id` DESC )"));
        }

        $arr = array();
        
        $arr['title'] = $request->title;
        $arr['description'] = $request->description;
        //$arr['images'] = $target_file ;
        $arr['status'] = $request->status;
        $arr['isrecursive'] = $request->recursive;
        $arr['recursivetype'] = $request->recursivetype;
        $arr['rdate'] = $request->rdate;
        $arr['createdby'] = Auth::user()->id;
        $arr['createddate'] = date('Y-m-d H:i:s');
        $arr['startdate'] = $request->startdate;
        $arr['duedate'] = $request->duedate;
        $arr['assignto'] = $request->assignto;
        $arr['assignby'] = Auth::user()->id;
        //print_r($arr);
        
        $date1=date_create($request->startdate);
        $date2=date_create($request->duedate);
        $diff=date_diff($date1,$date2);
        if($diff->format("%R%a") < 0){
            //header("location:createTask.php?sts=overdue");
            return redirect()->route('Task.create')->with('sts','overdue');
            }
        
          $INs =  DB::table('tasks')->insertGetId($arr);
        if($INs){
            $last_id = $INs;
            $attachment = array();
            $imagesdata = array();
            if($request->hasfile('images'))

            {
   
               foreach($request->file('images') as $file)
   
               {
   
                   $name = time().rand(1,100).'.'.$file->extension();
   
                   $file->move(public_path('task'), $name);  
   
                   $imagesdata[] = $name;  
   
               }
   
            }
           // return json_encode($imagesdata);
            $attachment['taskid'] = $last_id;
            $attachment['filename'] = json_encode($imagesdata);
           // print_r($attachment); die();
            $attch =  DB::table('tasks_attachment')->insert($attachment);
          
        // return json_encode($attch);
        
            $issue = array();
            $issue['taskid'] = $last_id ;
            $issue['assignfrom'] = Auth::user()->id;
            $issue['assignto'] = $request->assignto;
            $issue['issuedate'] = date('Y-m-d H:i:s');
            
            if(DB::table('taskissues')->insert($issue)){
                $sts = 'success';
                return redirect()->route('Task.index',compact('sts','rrr','startdate','enddate','search','users'));
                //return view('task.tasklist',compact('sts','rrr','startdate','enddate','search','users'));          
              }
              //  return view('task.tasklist',compact('sts','rrr','startdate','enddate','search','users'));
            }
           // return view('task.tasklist',compact('sts','rrr','startdate','enddate','search','users'));
    }


    public function taskdetail($id)
    {
        $task = DB::table('tasks')->where('id',$id)->first();
       
        
      return view('task.taskdetail',compact('task','id'));
        

    }


    public function subform(Request $request)
    {
        //return json_encode($request->all());
        $id = $request->id;
        $count=0;
     
       
        $assign = DB::table('taskissues')->select('assignfrom','assignto')->where('taskid',$id)->orderBy('id','desc') ->skip(0)->take(1)->first();
        if($assign)
        {
            $assignfrom = $assign->assignfrom;
            $assignto = $assign->assignto;
        
        if(Auth::user()->id == $assignfrom || Auth::user()->id == $assignto || Auth::user()->id == 1){
            ;
            }else{
                $sts = 'unauth';
          //  $str = "/task/taskdetail/".$id."&sts=unauth";
           return redirect("/task/taskdetail/".$id."")->with('sts');
            }

        }

            $arr = array();

            $arr['taskid'] = $request->id;
            $arr['comments'] = $request->comment;
            $arr['commentedby'] = Auth::user()->id;
            $arr['commenteddate'] = date('Y-m-d H:i:s');
            $arr['taskstatus'] = $request->status;
            $x = DB::table('taskscomment')->insertGetId($arr);

            if($x){
                $last_id = $x;
                $attachment = array();
                //// upload file //////////////////
                $imagesdata = array();
                if($request->hasfile('filetoupload'))

                {
       
                   foreach($request->file('filetoupload') as $file)
       
                   {
       
                       $name = time().rand(1,100).'.'.$file->extension();
       
                       $file->move(public_path('task'), $name);  
       
                       $imagesdata[] = $name;  
       
                   }
       
                }
                $attachment['taskid'] = $request->id;
                $attachment['commentsid'] = $last_id;
                $attachment['attachment'] =json_encode($imagesdata);
                DB::table('taskscomment_attachment')->insert($attachment);
               
                // return $id;
                 $task = DB::table('tasks')->where('id',$request->id)->first();
                 return view('task.taskdetail',compact('task','id'));
                }
                
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task,$id)
    { 
        $sts = '';
       
        $r = DB::table('tasks')->find($id);

        return view('task.updatetask',compact('sts','r','id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    { $sts ='';
        $startdate = isset($request->startdate)? date("Y-m-d", strtotime($request->startdate)) : '';
        $enddate = isset($request->enddate) ? date("Y-m-d", strtotime($request->enddate)) : '';
        $search = isset($request->search)?$request->search:'';
        $users = isset($request->users)?$request->users:'';
        if(Auth::user()->user_type == 'admin'){
                $rrr = DB::table('tasks')
                ->join('taskissues','taskissues.taskid', '=', 'tasks.id' )
                ->select('tasks.id', 'tasks.title','tasks.status','tasks.isrecursive','tasks.duedate','tasks.createdby', 'tasks.createddate','tasks.startdate','tasks.store_id', 'taskissues.assignfrom', 'taskissues.assignto')
                ->where ('tasks.status', '=', 'open')
                ->orwhere('tasks.status','=','pending')
                ->groupBy('tasks.id')
                ->orderBy('tasks.id', 'desc')
                ->get();
        }
        else{
            $rrr =  DB::select(DB::raw("select * from tasks where createdby = '".Auth::user()->id."' and id IN (SELECT taskid FROM `taskissues` where assignfrom='".Auth::user()->id."' OR assignto='".Auth::user()->id."' ORDER BY `tasks`.`id` DESC )"));
        }

        $arr = array();
        $id = $request->id;
        $arr['title'] = $request->title;
        $arr['description'] = $request->description;
        //$arr['images'] = $target_file ;
        $arr['status'] = $request->status;
        $arr['isrecursive'] = $request->recursive;
        $arr['recursivetype'] = $request->recursivetype;
        $arr['rdate'] = $request->rdate;
        $arr['createdby'] = Auth::user()->id;
        $arr['createddate'] = date('Y-m-d H:i:s');
        $arr['startdate'] = $request->startdate;
        $arr['duedate'] = $request->duedate;
        $arr['assignto'] = $request->assignto;
        $arr['assignby'] = Auth::user()->id;
        //print_r($arr);
        
        $date1=date_create($request->startdate);
        $date2=date_create($request->duedate);
        $diff=date_diff($date1,$date2);
        if($diff->format("%R%a") < 0){
            //header("location:createTask.php?sts=overdue");
            return redirect()->route('Task.edit',compact('id'))->with('sts','overdue');
            }
        
          $INs =  DB::table('tasks')->where('id',$id)->update($arr);
        if($INs){
            //$last_id = $INs;
            $attachment = array();
            $imagesdata = array();
            if($request->hasfile('images'))

            {
   
               foreach($request->file('images') as $file)
   
               {
   
                   $name = time().rand(1,100).'.'.$file->extension();
   
                   $file->move(public_path('task'), $name);  
   
                   $imagesdata[] = $name;  
   
               }
   
            }
           // return json_encode($imagesdata);
            $attachment['taskid'] = $id;
            $attachment['filename'] = json_encode($imagesdata);
           // print_r($attachment); die();
            $attch =  DB::table('tasks_attachment')->where('taskid',$id)->update($attachment);
          
        // return json_encode($attch);
        
            $issue = array();
            $issue['taskid'] = $id ;
            $issue['assignfrom'] = Auth::user()->id;
            $issue['assignto'] = $request->assignto;
            $issue['issuedate'] = date('Y-m-d H:i:s');
            
            if(DB::table('taskissues')->where('taskid',$id)->update($issue)){
                $sts ='success';
        //$attributes = Attribute::all();
        return redirect()->route('Task.index',compact('sts','rrr','startdate','enddate','search','users'));
        //return view('task.tasklist',compact('sts','rrr','startdate','enddate','search','users'));
    }
    return redirect()->route('Task.index',compact('sts','rrr','startdate','enddate','search','users'));      
    //return view('task.tasklist',compact('sts','rrr','startdate','enddate','search','users'));
            }
          //  return view('task.tasklist',compact('sts','rrr','startdate','enddate','search','users'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request,$id)
    {
        $task = DB::table('tasks')->where('id',$id)->delete();
        $taskscomment = DB::table('taskscomment')->where('taskid',$id)->delete();
        $taskscomment_attachment = DB::table('taskscomment_attachment')->where('taskid',$id)->delete();
        $tasks_attachment = DB::table('tasks_attachment')->where('taskid',$id)->delete();
        $taskissues = DB::table('taskissues')->where('taskid',$id)->delete();
        // $leave->delete();
        $sts = '';
        $startdate = isset($request->startdate)? date("Y-m-d", strtotime($request->startdate)) : '';
        $enddate = isset($request->enddate) ? date("Y-m-d", strtotime($request->enddate)) : '';
        $search = isset($request->search)?$request->search:'';
        $users = isset($request->users)?$request->users:'';
        if(Auth::user()->user_type == 'admin'){
                $rrr = DB::table('tasks')
                ->join('taskissues','taskissues.taskid', '=', 'tasks.id' )
                ->select('tasks.id', 'tasks.title','tasks.status','tasks.isrecursive','tasks.duedate','tasks.createdby', 'tasks.createddate','tasks.startdate','tasks.store_id', 'taskissues.assignfrom', 'taskissues.assignto')
                ->where ('tasks.status', '=', 'open')
                ->orwhere('tasks.status','=','pending')
                ->groupBy('tasks.id')
                ->orderBy('tasks.id', 'desc')
                ->get();
        }
        else{
            $rrr =  DB::select(DB::raw("select * from tasks where createdby = '".Auth::user()->id."' and id IN (SELECT taskid FROM `taskissues` where assignfrom='".Auth::user()->id."' OR assignto='".Auth::user()->id."' ORDER BY `tasks`.`id` DESC )"));
        }
      
        //$attributes = Attribute::all();
        return view('task.tasklist',compact('sts','rrr','startdate','enddate','search','users'));
    }

    public function ClosedTask()
    {
        $sts = '';
        $store_id = isset($request->store_id)?$request->store_id:'';
        $startdate = isset($request->startdate)? date("Y-m-d", strtotime($request->startdate)) : '';
        $enddate = isset($request->enddate) ? date("Y-m-d", strtotime($request->enddate)) : '';
        $search = isset($request->search)?$request->search:'';
        $users = isset($request->users)?$request->users:'';
        if(Auth::user()->user_type == 'admin'){
                $rrr = DB::table('tasks')
                ->join('taskissues','taskissues.taskid', '=', 'tasks.id' )
                ->select('tasks.id', 'tasks.title','tasks.status','tasks.isrecursive','tasks.duedate','tasks.createdby', 'tasks.createddate','tasks.startdate','tasks.store_id', 'taskissues.assignfrom', 'taskissues.assignto')
                ->where ('tasks.status', '!=', 'open')
                ->where('tasks.status','!=','pending')
                ->groupBy('tasks.id')
                ->orderBy('tasks.id', 'desc')
                ->get();
                //return json_encode($rrr);
        }
        else{
            $rrr =  DB::select(DB::raw("select * from tasks where createdby = '".Auth::user()->id."' and id IN (SELECT taskid FROM `taskissues` where assignfrom='".Auth::user()->id."' OR assignto='".Auth::user()->id."' ORDER BY `tasks`.`id` DESC )"));
        }
        //$attributes = Attribute::all();
        return view('task.closedtask',compact('rrr','sts','startdate','search','users','enddate','store_id'));
    }

    public function warningmessage()
    {
        $sts = '';
        $startdate = isset($request->startdate)?$request->startdate:'';
        $enddate = isset($request->enddate)?$request->enddate:'';
        $status = isset($request->status)?$request->status:'';
        $searchkey = isset($request->searchkey)?$request->searchkey:'';
        $users = isset($request->user)?$request->user:'';
        $arr = DB::table('warning')->get();
        //return json_encode($rrr);
        //$attributes = Attribute::all();
        return view('task.warningmessage',compact('sts','startdate','enddate','status','searchkey','users','arr'));
    }

    public function search(Request $request)
    {
        //return json_encode($request->all());
        $sts = '';
        $startdate = isset($request->startdate)? date("Y-m-d", strtotime($request->startdate)) : '';
        $enddate = isset($request->enddate) ? date("Y-m-d", strtotime($request->enddate)) : '';
        $search = isset($request->search)?$request->search:'';
        $users = isset($request->users)?$request->users:'';
	
	if(isset($search) && $startdate != '' && $enddate != '' && $users == ''){
        $rrr = DB::table('tasks')
        ->join('taskissues','taskissues.taskid', '=', 'tasks.id' )
        ->select('tasks.id', 'tasks.title','tasks.status','tasks.isrecursive','tasks.duedate','tasks.createdby', 'tasks.createddate','tasks.startdate','tasks.store_id', 'taskissues.assignfrom', 'taskissues.assignto')
        ->where ('tasks.status', '=', 'open')
        ->orwhere('tasks.status','=','pending')
        ->wherebetween('tasks.duedate',[$startdate,$enddate])
        ->groupBy('tasks.id')
        ->orderBy('tasks.id', 'desc')
        ->get();
       
	}else if(isset($search) && $startdate != '' && $enddate != '' && $users != ''){
        $rrr = DB::table('tasks')
        ->join('taskissues','taskissues.taskid', '=', 'tasks.id' )
        ->select('tasks.id', 'tasks.title','tasks.status','tasks.isrecursive','tasks.duedate','tasks.createdby', 'tasks.createddate','tasks.startdate','tasks.store_id', 'taskissues.assignfrom', 'taskissues.assignto')
        ->where ('tasks.status', '=', 'open')
        ->orwhere('tasks.status','=','pending')
        ->wherebetween('tasks.duedate',[$startdate,$enddate])
        ->where('taskissues.assignto',$users)
        ->groupBy('tasks.id')
        ->orderBy('tasks.id', 'desc')
        ->get();
       
	}else if(isset($request->search) && $request->users != ''){
        $rrr = DB::table('tasks')
        ->join('taskissues','taskissues.taskid', '=', 'tasks.id' )
        ->select('tasks.id', 'tasks.title','tasks.status','tasks.isrecursive','tasks.duedate','tasks.createdby', 'tasks.createddate','tasks.startdate','tasks.store_id', 'taskissues.assignfrom', 'taskissues.assignto')
        ->where ('tasks.status', '=', 'open')
        ->orwhere('tasks.status','=','pending')
        ->where('taskissues.assignto',$users)
        ->groupBy('tasks.id')
        ->orderBy('tasks.id', 'desc')
        ->get();
		
    }else {
            if(Auth::user()->user_type == 'admin'){
                $rrr = DB::table('tasks')
                ->join('taskissues','taskissues.taskid', '=', 'tasks.id' )
                ->select('tasks.id', 'tasks.title','tasks.status','tasks.isrecursive','tasks.duedate','tasks.createdby', 'tasks.createddate','tasks.startdate','tasks.store_id', 'taskissues.assignfrom', 'taskissues.assignto')
                ->where ('tasks.status', '=', 'open')
                ->orwhere('tasks.status','=','pending')
                ->groupBy('tasks.id')
                ->orderBy('tasks.id', 'desc')
                ->get();
                }
                else{
                    $rrr =  DB::select(DB::raw("select * from tasks where createdby = '".Auth::user()->id."' and id IN (SELECT taskid FROM `taskissues` where assignfrom='".Auth::user()->id."' OR assignto='".Auth::user()->id."' ORDER BY `tasks`.`id` DESC )"));
                }
    }
    return view('task.tasklist',compact('sts','rrr','startdate','enddate','search','users'));
    } 



    public function closesearch(Request $request)
    {
        //return json_encode($request->all());
        $sts = '';
        $startdate = isset($request->startdate)? date("Y-m-d", strtotime($request->startdate)) : '';
        $enddate = isset($request->enddate) ? date("Y-m-d", strtotime($request->enddate)) : '';
        $search = isset($request->search)?$request->search:'';
        $users = isset($request->users)?$request->users:'';
	
	if(isset($search) && $startdate != '' && $enddate != '' && $users == ''){
        $rrr = DB::table('tasks')
        ->join('taskissues','taskissues.taskid', '=', 'tasks.id' )
        ->select('tasks.id', 'tasks.title','tasks.status','tasks.isrecursive','tasks.duedate','tasks.createdby', 'tasks.createddate','tasks.startdate','tasks.store_id', 'taskissues.assignfrom', 'taskissues.assignto')
        ->where ('tasks.status', '!=', 'open')
        ->where('tasks.status','!=','pending')
        ->wherebetween('tasks.duedate',[$startdate,$enddate])
        ->groupBy('tasks.id')
        ->orderBy('tasks.id', 'desc')
        ->get();
        
     	
	}else if(isset($search) && $startdate != '' && $enddate != '' && $users != ''){
        $rrr = DB::table('tasks')
        ->join('taskissues','taskissues.taskid', '=', 'tasks.id' )
        ->select('tasks.id', 'tasks.title','tasks.status','tasks.isrecursive','tasks.duedate','tasks.createdby', 'tasks.createddate','tasks.startdate','tasks.store_id', 'taskissues.assignfrom', 'taskissues.assignto')
        ->where ('tasks.status', '=', 'open')
        ->where('tasks.status','=','pending')
        ->wherebetween('tasks.duedate',[$startdate,$enddate])
        ->where('taskissues.assignto',$users)
        ->groupBy('tasks.id')
        ->orderBy('tasks.id', 'desc')
        ->get();
        
	}else if(isset($request->search) && $request->users != ''){
        $rrr = DB::table('tasks')
        ->join('taskissues','taskissues.taskid', '=', 'tasks.id' )
        ->select('tasks.id', 'tasks.title','tasks.status','tasks.isrecursive','tasks.duedate','tasks.createdby', 'tasks.createddate','tasks.startdate','tasks.store_id', 'taskissues.assignfrom', 'taskissues.assignto')
        ->where ('tasks.status', '!=', 'open')
        ->where('tasks.status','!=','pending')
        ->where('taskissues.assignto',$users)
        ->groupBy('tasks.id')
        ->orderBy('tasks.id', 'desc')
        ->get();
		
            }
            else{
                

                if(Auth::user()->user_type == 'admin'){
                    $rrr = DB::table('tasks')
                    ->join('taskissues','taskissues.taskid', '=', 'tasks.id' )
                    ->select('tasks.id', 'tasks.title','tasks.status','tasks.isrecursive','tasks.duedate','tasks.createdby', 'tasks.createddate','tasks.startdate','tasks.store_id', 'taskissues.assignfrom', 'taskissues.assignto')
                    ->where ('tasks.status', '!=', 'open')
                    ->where('tasks.status','!=','pending')
                    ->groupBy('tasks.id')
                    ->orderBy('tasks.id', 'desc')
                    ->get();
                    //return json_encode($rrr);
            }
            else{
                $rrr =  DB::select(DB::raw("select * from tasks where createdby = '".Auth::user()->id."' and id IN (SELECT taskid FROM `taskissues` where assignfrom='".Auth::user()->id."' OR assignto='".Auth::user()->id."' ORDER BY `tasks`.`id` DESC )"));
            }
                
				}
           // return json_encode($rrr);
            return view('task.closedtask',compact('sts','rrr','startdate','enddate','search','users'));
      
    
    } 

    public function wrnmsgcreate()
    {
        $sts = '';
       
        //$attributes = Attribute::all();
        return view('task.warnmsgcreate',compact('sts'));
    }

    public function wrnmsgstore(Request $request)
    {
        

        $arr1 = array();
        $sts = 'success';
        $startdate = isset($request->startdate)?$request->startdate:'';
        $enddate = isset($request->enddate)?$request->enddate:'';
        $status = isset($request->status)?$request->status:'';
        $searchkey = isset($request->searchkey)?$request->searchkey:'';
        $users = isset($request->user)?$request->user:'';

        $arr1['Warning_msg'] = $request->warnmsg;
        //$arr['images'] = $target_file ;
        $arr1['createddate'] = date('Y-m-d H:i:s');
        $arr1['createdby'] = Auth::user()->id;
        $arr1['assignto'] = $request->assignto;
        $arr1['status'] = $request->status;
        $arr1['publishdate'] = $request->publishdate;

        $x = DB::table('warning')->insert($arr1);

        if($x){
            $arr = DB::table('warning')->get();
                //header("location:warnmsgcreate.php?sts=success");
                return view('task.warningmessage',compact('arr','sts','startdate','enddate','status','searchkey','users'));
            
            }
    }

    public function warningmessageupdate($id)
    { 
        $sts = '';
       
        $r = DB::table('warning')->find($id);

        return view('task.warnmsgupdate',compact('sts','r'));
    }

    public function wrnuptstore(Request $request)
    {
        $sts = 'success';
        $startdate = isset($request->startdate)?$request->startdate:'';
        $enddate = isset($request->enddate)?$request->enddate:'';
        $status = isset($request->status)?$request->status:'';
        $searchkey = isset($request->searchkey)?$request->searchkey:'';
        $users = isset($request->user)?$request->user:'';
        $arr1 = array();


        $arr1['Warning_msg'] = $request->warnmsg;
        $arr1['assignto'] = $request->assignto;
        $arr1['status'] = $request->status;
        $arr1['publishdate'] = $request->publishdate;

        $updt = DB::table('warning')->where('id',$request->id)->update($arr1);

        $arr = DB::table('warning')->get();

        if($updt){
            return view('task.warningmessage',compact('sts','startdate','enddate','status','searchkey','users','arr'));
            
            }

    }

    public function searchwrnmsg(Request $request)
    {
        $sts = '';
        $startdate = isset($request->startdate)?$request->startdate:'';
        $enddate = isset($request->enddate)?$request->enddate:'';
        $status = isset($request->status)?$request->status:'';
        $searchkey = isset($request->searchkey)?$request->searchkey:'';
        $users = isset($request->user)?$request->user:'';
       
			if($startdate != '' and $enddate != ''){
                $arr = DB::table('warning')->wherebetween('createddate',[$startdate,$enddate])->get();
				
				}
			if($status != ''){
                $arr = DB::table('warning')->where('status',$status)->get();
			
				}
			if($searchkey != ''){
                $arr = DB::table('warning')->where('Warning_msg','like','%'.$searchkey.'%')->get();
				
				}
				
			if($users != ''){
                $arr = DB::table('warning')->where('createdby',$users)->get();
				
				}
				
                return view('task.warningmessage',compact('arr','sts','startdate','enddate','status','searchkey','users'));	

    }
}

