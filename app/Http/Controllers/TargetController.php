<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Target;
use App\Purpose;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TargetController extends Controller{

    private $authUser;

    public function __construct(){
        $this->middleware('auth:api');
        $this->authUser = auth('api')->user();
    }

    public function index(){
        $targets = DB ::table('targets')
            ->join('purposes', 'targets.purpose_id', '=', 'purposes.id')
            ->where('purposes.user_id','=', $this->authUser->id)
            ->select('targets.*','purposes.name as purpose_name')
            ->get();
        
        return $targets;
    }

    public function store(Request $request){
        $purpose_id = $request->purpose_id;
        $purpose = Purpose::find($purpose_id);
        $now = Carbon::now();
       
        //if target dont repeate calculate the last day to do this
        if( !$request['repeat']){
            
            switch($request['frequency']){
                case 'One time':
                    $last_date_to_do=$purpose->endday;
                    break;
                case 'Today':
                    $last_date_to_do = Carbon::today();
                    break;
                case 'Tomorrow':
                    $last_date_to_do = Carbon::tomorrow()->format('Y-m-d');
                    break;
                case 'This week':
                    $last_date_to_do = $now->endOfWeek()->format('Y-m-d');
                    break;
                case 'This month':
                    $last_date_to_do = $now->endOfMonth()->format('Y-m-d');
                    break;
            }
            
        }
        else{

            $last_date_to_do=null;
        }
        
        $target = Target::create([
            'name' => $request['name'],
            'purpose_id' => $request['purpose_id'],
            'frequency' => $request['frequency'],
            'repeat' => $request['repeat'],
            'complete' => 0,
            'time_has_completed' => 0,
            'last_date_to_do'=>$last_date_to_do,
        ]);
        return $target;
    }

    public function show($id)
    { }

    public function update(Request $request, $id){
        $target = Target::find($id);
        $purpose_id = $request->purpose_id;
        $purpose = Purpose::find($purpose_id);
        $now = Carbon::now();

        if (!$request['repeat']) {

            switch ($request['frequency']) {
                case 'One time':
                    $request['last_date_to_do'] = $purpose->endday;
                    break;
                case 'Today':
                    $request['last_date_to_do'] = Carbon::today();
                    break;
                case 'Tomorrow':
                    $request['last_date_to_do'] = Carbon::tomorrow()->format('Y-m-d');
                    break;
                case 'This week':
                    $request['last_date_to_do'] = $now->endOfWeek()->format('Y-m-d');
                    break;
                case 'This month':
                    $request['last_date_to_do'] = $now->endOfMonth()->format('Y-m-d');
                    break;
            }
        }
        else{

        }

        $target->fill($request->only([
            'name',
            'frequency',
            'complete',
            'last_date_to_do',
            'repeat'
        ]));
        $target->save();
        return $target;
    }

    public function destroy($id){
        // $this->authorize('isAdmin');

        $target = Target::find($id);
        $target->delete();
    }

    //find the target
    //update last complete equal to today
    //increate time hascomplted
    public function makeComplete(Request $request,$id){
        $target = Target::find($id);
        
        $target->last_completed_day = date('Y-m-d');
        
        if($target->repeat==0){
            $target->complete = 1;
        }

        // $target->time_has_completed=$request->time_has_completed+1;
        // if($target->time_has_completed==$target->time_to_complete){
        //     $target->complete=1;
        // }
        $target->save();
        return $target;
    }

    public function getCompleted(){
        $targets = DB::table('targets')
            ->join('purposes', 'purpose_id', '=', 'purposes.id')
            ->where('purposes.user_id', '=', $this->authUser->id)
            ->select(
                'targets.name','targets.complete',
                'targets.repeat','targets.frequency',
                'targets.last_completed_day',
                'purposes.name as purpose_name'
            )
            ->get();
        //change this become an array to spice array
        $targets = $targets->toArray();
       
        for ($i = 0; $i < count($targets); $i++){
            if ($targets[$i]->last_completed_day === NULL) {
                array_splice($targets, $i, 1);
                $i--;
                continue;
            }
            $lastCompletedDay = $targets[$i]->last_completed_day;

            //create an object from lastcomplete day to compare with now
            $lastCompletedDay = Carbon::createFromFormat('Y-m-d', $lastCompletedDay);
            
            //determiner if target is complete in today
            //get end of day to compasision
            $today = Carbon::now()->endOfDay();
        
            $diff = $lastCompletedDay->diffInDays($today);
            if($diff){
                array_splice($targets, $i, 1);
                $i--;
            }

        }

        return $targets;
    }

    public function today(){
        $targets = DB::table('targets')
            ->join('purposes', 'purpose_id', '=', 'purposes.id')
            ->where('purposes.user_id', '=', $this->authUser->id)
            ->where('targets.complete', 0)
            ->select('targets.id', 'targets.name', 'targets.purpose_id',
                'targets.complete', 'targets.repeat', 'targets.frequency',
                'targets.time_has_completed', 'targets.last_completed_day',
                'targets.last_date_to_do','targets.created_at',
                'purposes.name as purpose_name')
            ->get();
            
        //must change to array to use array_spice funtion
        
        $targets = $targets->toArray();

        for ($i = 0; $i < count($targets); $i++) {
            //if user dont complete this target one time show for user
            if ($targets[$i]->last_completed_day === NULL) {
                continue;
            }
            //if target not repeat show this for user
            //because when user make this completed this
            //complete is equal to 1
            if ($targets[$i]->repeat == 0) {
                continue;
            }
            $lastCompletedDay = $targets[$i]->last_completed_day;
            $lastCompletedDay = Carbon::createFromFormat('Y-m-d', $lastCompletedDay);
            $frequency = $targets[$i]->frequency;
            //determine if target was complete in today or not
            //diffDay==0 so this target is completed in to day
            //if frequency equal everday we should derterminie
            //it completed in this day or not
            if (strcmp($frequency, 'Every day')) {
                $comp = Carbon::now()->endOfDay();
                $diffDay = $comp->diffInDays($lastCompletedDay);
                if ($diffDay == 0) {
                    array_splice($targets, $i, 1);
                    $i--;
                    continue;
                }
            }
            
            //determine this targert is complete or not
            //if not completed, we should change time to show for use

            if(!$this->determineIsCompleted($targets, $targets[$i], $i)){
                $targets[$i]->last_completed_day = date('d-m-Y', strtotime($lastCompletedDay));
            }

            //change format to show for user
            
            
        }
        return $targets;
    }

    public function tomorrow(){
        $targets =  DB::table('targets')
            ->join('purposes', 'purpose_id', '=', 'purposes.id')
            ->where('purposes.user_id', '=', $this->authUser->id)
            ->where('targets.complete', 0)
            ->select('targets.*', 'purposes.name as purpose_name')
            ->get();

        $targets = $targets->toArray();

        //check to this target are complete or not
        for ($i = 0; $i < count($targets); $i++) {
            
            //if user dont complete this target one time show for user
            if ($targets[$i]->last_completed_day === NULL) {
                continue;
            }
            //if repeat==0 and this target is uncomplete, you must do this in
            //tomorrow
            if($targets[$i]->repeat==0){
                continue;
            }
            //change format to show for user
            $lastCompletedDay = $targets[$i]->last_completed_day;

            //determine id target is complete or not
            //if not complete, change the format of date to show for the user
            //echo $targets[$i]->name;

           // return $this->determineIsCompleted($targets, $targets[$i], $i);
            if(!$this->determineIsCompleted($targets, $targets[$i], $i)){
                $lastCompletedDay = Carbon::createFromFormat('Y-m-d', $lastCompletedDay);
                $targets[$i]->last_completed_day = date('d-m-Y', strtotime($lastCompletedDay));
            };
                   
        }
        return $targets;
    }

    public function inWeek(){
        $targets =  DB::table('targets')
            ->join('purposes', 'purpose_id', '=', 'purposes.id')
            ->where('purposes.user_id', '=', $this->authUser->id)
            ->where('targets.complete', 0)
            ->select('targets.*', 'purposes.name as purpose_name')
            ->get();

        $targets = $targets->toArray();

        //check to this target are complete or not
        for ($i = 0; $i < count($targets); $i++) {

           
            //remove target don repeat and frequency not in this week
            if($targets[$i]->repeat==0){
                // if user dont complete this target one time show for user
                
                if(strcmp($targets[$i]->frequency,'This week')!=0){
                    array_splice($targets, $i, 1);
                    $i--;
                    
                }
            }
            else{
                if ($targets[$i]->last_completed_day === NULL) {
                    continue;
                }
                //determine id target is complete or not
                //if not complete, change the format of date to show for the user
                //echo $targets[$i]->name;

                // return $this->determineIsCompleted($targets, $targets[$i], $i);
                if (!$this->determineIsCompleted($targets, $targets[$i], $i)) {
                    $lastCompletedDay = $targets[$i]->last_completed_day;
                    $lastCompletedDay = Carbon::createFromFormat('Y-m-d', $lastCompletedDay);
                    $targets[$i]->last_completed_day = date('d-m-Y', strtotime($lastCompletedDay));
                };
            }
            
        }
        return $targets;
    }

    public function inMonth(){
        $targets =  DB::table('targets')
            ->join('purposes', 'purpose_id', '=', 'purposes.id')
            ->where('purposes.user_id', '=', $this->authUser->id)
            ->where('targets.complete', 0)
            ->select('targets.*', 'purposes.name as purpose_name')
            ->get();

        $targets = $targets->toArray();

        //check to this target are complete or not
        for ($i = 0; $i < count($targets); $i++) {


            //remove target don repeat and frequency not in this week
            if ($targets[$i]->repeat == 0) {
                // if user dont complete this target one time show for user

                if (strcmp($targets[$i]->frequency, 'This month') != 0) {
                    array_splice($targets, $i, 1);
                    $i--;
                }
            } else {
                if ($targets[$i]->last_completed_day === NULL) {
                    continue;
                }
                //determine id target is complete or not
                //if not complete, change the format of date to show for the user
                //echo $targets[$i]->name;

                // return $this->determineIsCompleted($targets, $targets[$i], $i);
                if (!$this->determineIsCompleted($targets, $targets[$i], $i)) {
                    $lastCompletedDay = $targets[$i]->last_completed_day;
                    $lastCompletedDay = Carbon::createFromFormat('Y-m-d', $lastCompletedDay);
                    $targets[$i]->last_completed_day = date('d-m-Y', strtotime($lastCompletedDay));
                };
            }
        }
        return $targets;
    }


    //get time has completed, calculate time has completed until mow
    //and then comparision two value, if this equal , target completed and reverse
    public function determineIsCompleted(&$targets, $target, &$index){
        $frequency = $target->frequency;
        $lastCompletedDay = $target->last_completed_day;
        $timeHasCompleted = $target->time_has_completed;

        //$targetCreated =Carbon::parse( $target->created_at);
        //it return wrong, I don't know how to fix that
        //only create with Y-m-d time format'
        
        $targetCreated= date('Y-m-d', strtotime( $target->created_at));
        $targetCreated = Carbon::createFromFormat('Y-m-d', $targetCreated );
        
        //if you get start of month here, you will get some error when you 
        //get diff in week
        //$startMonthOfTarget = $targetCreated->startOfMonth();
        
       
        $frequencyNum=$this->calculateFrequency($frequency);

        $lastCompletedDay=Carbon::createFromFormat('Y-m-d',$lastCompletedDay);

        $now = Carbon::now();
        
        if ($frequency == 'Every month') {
            //use time now and start of month when target was created
            //to get diff month
            $startMonthOfTarget = $targetCreated->startOfMonth();
            $diffMonth = $now->diffInMonths(  $startMonthOfTarget);
            // //comparision last complete day with now
            if ($timeHasCompleted>=$diffMonth*$frequencyNum) {

                array_splice($targets, $index, 1);
                $index--;
                return true;
            }
        } 
        else{
           //because when we make complete for one target
           //if it have one period like 5 times or 4 times 
           //we must update last complete day fopr thuis
           //so now we simple compasision diff in week or diff in month
           //to derrtermine if last week or last month we completed this
           //target and delete it or show it for user
           
            $startWeekOfTarget = $targetCreated->startOfWeek();
            $diffWeek= $now->diffInWeeks($startWeekOfTarget);
            
            //completed
            if($timeHasCompleted>=$diffWeek*$frequencyNum){
                array_splice($targets,$index,1);
                $index--;
                return true;
            } 
            
        }
        return false;
    }

    

    //change frequency from string to num to calculate
    public function calculateFrequency($frequency){
        switch($frequency){
            case '2 times a week': 
                return 2;
                break;
            case '3 times a week':
                return 3;
                break;
            case '4 times a week':
                return 4;
                break;
            case '5 times a week':
                return 5;
                break;
            case '6 times a week':
                return 6;
                break;
            case 'Every week':
                return 7;
                break;
            default:
                return 1;
                break;
        }
    }
}
