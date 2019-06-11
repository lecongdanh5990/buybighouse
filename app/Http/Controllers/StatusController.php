<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Status;
use Carbon\Carbon;
use App\Target;
use Illuminate\Support\Facades\Auth;
use AuthenticatesUsers;

class StatusController extends Controller{

    private $authUser;

    public function __construct(){
        $this->middleware('auth:api');
        $this->authUser = auth('api')->user();
    }

    public function index(){
         return Status::all();
    }

    public function store(Request $request){
        //get list of target which completed today
        $targets=Target::whereDate('last_completed_day', '=', date("Y-m-d"))
            ->get(['purpose_id'])
            ->unique('purpose_id');
        
        
        $targetArr=array();
        $i=0;
        //get all purpose id of this targert arr to attach purpose id
        foreach($targets as $key=>$value){
            $targetArr[$i++]=$value['purpose_id'];
        }

        $status= Status::create([
            'content' => $request->content,
            'user_id'=>$this->authUser['id'],
            'rating' => $request->rating,
            'tomato'=>$request->tomato,
        ]);
        $status->purposes()->attach($targetArr);
        return $status;
    }

    public function show($id){
        // $mission=Mission::find($id);
        // return $mission;
    }

    public function edit($id){

    }

    public function update(Request $request, $id){
        //get all purpose was complete in today to attah with status
        $targets=Target::whereDate('created_at', '=', date("Y-m-d"))
            ->get(['purpose_id'])
            ->unique('purpose_id');
        $targetsArr=array(); 
        $i=0;
        foreach($targets as  $key=>$value){ 
              $targetsArr[$i++]=$value['purpose_id'];
        }
        $status=Status::find($id);

        $status->fill($request-> only([
            'content',
            'rating',
            'tomato'
        ]));

        //can not update by this way
        // $status->content=$request->content;
        // $status->rating = $request->rating;
        $status->save();
        $status->purposes()->sync($targetsArr);
        return $status;
    }

    public function updateTomato(Request $request, $id){
        $status=Status::find($id);
        $status->fill($request->only([
            'tomato',
        ]));
        $status->save();
        return $status;
    }

    public function destroy($id){
        // $mission=Mission::find($id);
        // $mission->delete();
    }
    public function getToday(){

        $statuses=$this->authUser->statuses()
            ->whereDate('created_at', '=', date("Y-m-d"))
            ->get(['id', 'tomato', 'content', 'rating']);
        // $statuses = Status::whereDate('created_at', '=', date("Y-m-d"))
        //     ->get(['id','tomato','content','rating']);
        // $statuses[0]['newid']=$id;
        return $statuses;
    }
    public function getInWeek(){
        //$startDay=Carbon::now()->subweek()->format('Y-m-d');

        //get startday and finish day in week
        $startDay=Carbon::now()->startOfWeek()->format('Y-m-d');
        $finishDay=Carbon::now()->endOfWeek()->format('Y-m-d');


        $statuses = $this->authUser->statuses()
            ->whereBetween('created_at', array($startDay,$finishDay))
            ->get(['tomato']);
        $statusesArr=array();
        $i=0;
        foreach($statuses as $key=>$value){
            $statusesArr[$i++]=$value['tomato'];
        }
        return $statusesArr;
    }

    public function getInMonth(){
       //$startDay=Carbon::now()->subweek()->format('Y-m-d');
        $startDay=Carbon:: now()->startOfMonth()->format('Y-m-d');
        $finishDay=Carbon:: now()->endOfMonth()->format('Y-m-d');
        $statuses = Status::whereBetween('created_at', array($startDay,$finishDay))
            ->get(['tomato']);
        $statusesArr=array() ; 
        $i=0;
          foreach($statuses as $key=>$value ){ 
             $statusesArr[$i++]=$value['tomato'];
        }
        return $statusesArr;
    }
    public function getThisMonth($month,$year){
        $time = Carbon::create($year, $month, 1, 0, 0, 0);
        $startDay=$time->startOfMonth()->format('Y-m-d');
        $finishDay=$time->endOfMonth()->format('Y-m-d');
        $statuses = Status::whereBetween('created_at', array($startDay,$finishDay))
            ->get(['tomato']);
        $statusesArr=array ( );
        $i=0;
          foreach($statuses as $key=>$value ){
             $statusesArr[$i++]=$value ['tomato'];
        }
        return $statusesArr;
    }
    public function getLimitedMonth() {
        $dateArr=array();
        $max = Status::orderBy('created_at','desc')->first(['created_at']);
        $dateArr['maxYear']=$max->created_at->format('Y');
        $dateArr['maxMonth']=$max->created_at->format('m');
        $min = Status::orderBy('created_at','asc')->first(['created_at']);
        $dateArr['minYear']=$min->created_at->format('Y');
        $dateArr['minMonth']=$min->created_at->format('m');
        return $dateArr;
    }
}
