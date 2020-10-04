<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Army;
use App\Http\Controllers\SessionController;

session_start();


class StartGame extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */



    public function __invoke(Request $request)
    {
      
   
        if (!isset($_SESSION['army']) || $_SESSION['army'] >= Army::select('id')->where('gameID', $_SESSION['game'])->where('status', '1')->count()-1) {
            $_SESSION['army'] = 0;
        } else {
            $_SESSION['army']++;
        }

        

        if (Army::select('id')->where('gameID', $_SESSION['game'])->where('status', '1')->count() != 1) {
            $toAttack = "";

            $value = (Army::all()->where('gameID', $_SESSION['game'])->where('status', '1')->skip($_SESSION['army'])->first());

            if ($value['status'] != 0) {
                $w = $this->whoToAttack($value);

                if (Army::find($w) != null) {
                    $toAttack .= $value['id'] . 'vs' . $w . ";";
                    $this->attack($value, Army::find($w));
                }
            }
            //return ['message'=>$_SESSION['game']];
            return ["message" => $toAttack];
        } else {

           return ["message" => Army::select('name')->where('gameID', $_SESSION['game'])->where('status', '=', '1')->value('name') . " won. Game done!"];
        }
    }

    public function whoToAttack($value)
    {


        $toAttack = 1;
        if ($value['strategy'] == 1) { //RANDOM
            
            $toAttack=Army::where('gameID', $_SESSION['game'])->where('id', '<>', $value['id'])->where('status', '<>', '0')->inRandomOrder()->first()->id;
            
        } else if ($value['strategy'] == 2) { //WEAKEST
            $toAttack = Army::where('id', '<>', $value['id'])->where('gameID', $_SESSION['game'])->where('units', '=', (Army::select('units')->where('gameID', $_SESSION['game'])->where('id', '<>', $value['id'])->where('status', '<>', '0')->min('units')))->first()->id;
            
        } else if ($value['strategy'] == 3) { //STRONGEST
            $toAttack = Army::where('id', '<>', $value['id'])->where('gameID', $_SESSION['game'])->where('units', '=', (Army::select('units')->where('gameID', $_SESSION['game'])->where('id', '<>', $value['id'])->where('status', '<>', '0')->max('units')))->first()->id;
        }
        return $toAttack;
    }

    public function attack($attacker, $attacked)
    {
        if ($attacked['units'] != 1) {
            $damage = $attacker['units'] * 0.5;
            if ($attacked['units'] - $damage > 0) {
                $attacked->update(['units' => $attacked['units'] - $damage]);
            } else {
                $attacked->update(['units' => 0]);
            }
        } else {
            $attacked->update(['units' => 0]);
        }

        if ($attacked['units'] == 0) {
            $attacked->update(['status' => 0]);
        }

        if (Army::select('id')->where('status', '1')->count() == 1) {
            return Army::select('name')->where('gameID', $_SESSION['game'])->where('status', '=', '1')->value('name') . " won. Game done!";
        }
    }


}
