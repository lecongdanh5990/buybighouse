<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Purpose;
use Carbon\Carbon;

class PurposeStatusController extends Controller{

    private $authUser;

    public function __construct(){
        $this->middleware('auth:api');
        $this->authUser = auth('api')->user();
    }

    public function index(Purpose $purpose){
        $statuses = $this->authUser-> purposes()
            ->where('id',$purpose->id)
            ->with('statuses')
            ->get()
            ->pluck('statuses')
            ->collapse();
        
        for($i=0;$i<count($statuses);$i++){
            $date=$statuses[$i]['created_at'];
            $statuses[$i]['date'] = Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y');
        }

        

        return $statuses;
    }

    
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
