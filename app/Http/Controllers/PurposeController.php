<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Purpose;
use App\User;
use Faker\Provider\zh_TW\DateTime;
use Illuminate\Support\Facades\Auth;


class PurposeController extends Controller{

    private $authUser;

    public function __construct(){
        $this->middleware('auth:api');
        $this->authUser= auth('api')->user();
    }

    public function index(){
        return $purposes=$this->authUser->purposes()
            ->orderBy('id','asc')
            ->get(['id','name','complete','endday','startday','realendday']);
    }

    public function getAllPurpose(){
        return $purposes = $this->authUser->purposes()
            ->get(['id', 'name']);
        // for ($i = 0; $i < count($purposes); $i++) {
        //     $dateEndPurpose = strtotime($purposes[$i]->endday);
        //     $date = strtotime(date("Y-m-d"));
        //     $purposes[$i]['Purposedayleft'] = ($dateEndPurpose - $date) / 86400;
        // }
        return $purposes;
    }

    public function store(Request $request){
        $purpose= Purpose::create([
            'startday' => $request->startday,
            'endday' => $request->endday,
            'name'=>$request['name'],
            'user_id'=>$this->authUser['id']
        ]);
        return $purpose;
    }

    public function show($id){
        $purpose=Purpose::find($id);
        return $purpose;
    }

    public function edit($id){
        //
    }

    
    public function update(Request $request, $id){
        $purpose=Purpose::find($id);
        $purpose->fill($request->only([
            'name',
            'endday',
            'realendday',
            'startday',
            'iscomplete'
        ]));
        // $Purpose->name=$request->name;
        // $Purpose->endday = $request->endday;
        // $Purpose->realendday = $request->realendday;
        // $Purpose->startday = $request->startday;
        // $Purpose->iscomplete = $request->iscomplete;
        $purpose->save();
        return $purpose;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        
        $purpose=purpose::find($id);
        $purpose->delete();
    }

    public function today(){
        return $purposes=$this->authUser->purposes()
            ->where('complete', '=', 0)
            ->orderBy('name', 'desc')
            ->get(['id', 'name']);
        // for ($i = 0; $i < count($purposes); $i++) {
        //     $dateEndPurpose = strtotime($purposes[$i]->endday);
        //     $date = strtotime(date("Y-m-d"));
        //     $purposes[$i]['Purposedayleft'] = ($dateEndPurpose - $date) / 86400;
        // }
        return $purposes;
    }

    public function getStatuses($id){
        $purpose=Purpose::find($id);
        $statuses=$purpose->steps()
            ->whereHas('statuses')
            ->with('statuses')
            ->get()
            ->pluck('statuses')
            ->collapse();
        
        $ratings=array('excellent'=>'','good'=>'','normal'=>'','bad'=>'','terrible'=>'');
        $total=count($statuses);
        foreach($statuses as $status){
            if($status['rating']==5) $ratings['excellent']++;
            else if( $status['rating'] == 4) $ratings['good']++;
            else if($status['rating'] == 3) $ratings['normal']++;
            else if($status['rating'] == 2) $ratings['bad']++;
            else $ratings['terrible']++;
        }

        $ratings['excellent']= round( $ratings['excellent'] / $total * 100,2);
        $ratings['good']= round( $ratings['good'] / $total * 100,2);
        $ratings['normal']= round( $ratings['normal'] / $total * 100,2);
        $ratings['bad']= round( $ratings['bad'] / $total * 100,2);
        $ratings['terrible']= round( $ratings['terrible'] / $total * 100,2);
        return $ratings;
    }

    public function getUncompletedPurposes(){
        $uncompletedPurposes = $this->authUser->purposes()
            ->where('complete', '=', 0)
            ->orderBy('name', 'desc')
            ->get();
        return $uncompletedPurposes;
    }
}
