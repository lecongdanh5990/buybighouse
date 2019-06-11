<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Purpose;
class PurposeTargetsController extends Controller{
    private $authUser;

    public function __construct(){
        $this->middleware('auth:api');
        $this->authUser = auth('api')->user();
    }
    public function index(Purpose $purpose){
        // $statuses = $purpose->steps()
        //     ->whereHas('statuses')
        //     ->with('statuses')
        //     ->get()
        //     ->pluck('statuses')
        //     ->collapse();
        $listOfTargets = $this->authUser->purposes()
            ->where('id',$purpose->id)
            ->with('targets')
            ->get()
            ->pluck('targets')
            ->collapse();
        return $listOfTargets;
    }

}
