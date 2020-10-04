<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Army;

class ArmyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Army::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        if (isset($_SESSION['game'])){
            $data['gameID'] = $_SESSION['game'];
        }
        return Army::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $army = Army::find($id);
        return $army;
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
        $army = Army::find($id);
        $army->update($request->all());
        return $army;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Army::destroy($id);
    }

    public function getGameArmy($id){
        $army = Army::select('*')->where('gameID',$id)->get();
        return $army;
    }
}
