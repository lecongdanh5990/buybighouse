<?php

namespace App\Http\Controllers;

use Faker\Generator as Faker;
use App\Mission;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Faker\Provider\zh_TW\DateTime;

class TestController extends Controller
{
    public function index(Faker $faker )
    {
        $mission_id = Mission::all()->random()->id;
        $test = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null);
        
        $lastStep = Mission::find(77)->steps()
            ->orderBy('id', 'desc')
            ->take(1)
            ->get(['endday']);
        
        if (count($lastStep)) {
            $startday = $lastStep[0]->endday;
            $date=new \DateTimeImmutable($startday);
            $date=$date->modify('+1 day');
            $startday=$date->format('Y-m-d');
        } 
        else {
           $startday = $faker->date($format = 'Y-m-d',  $max = Mission::find(77)                           ->endday);
            }
        
        $temps = $faker->dateTimeBetween($startDate = $startday, $endDate = Mission::find                   (77)->endday,$timezone = null);
        $endday=$temps->format('m/d/Y');
        return [
            'name' => $faker->name,
            'mission_id' => 77,
            'iscomplete' => $faker->randomElement($array = array(1, 0)),
            'startday' => $startday,
            'endday' => $endday,
        ];
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
