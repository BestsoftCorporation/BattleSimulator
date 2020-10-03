<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Army;

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

        if (Army::select('id')->where('status', '1')->count() != 1) {
            $toAttack = array();
            foreach (Army::all() as $key => $value) {
                if ($value['status'] != 0) {
                    $w = $this->whoToAttack($value);

                    if (Army::find($w) != null) {
                        $toAttack[$value['id']] = $w;
                        $this->attack($value, Army::find($w));
                    }
                }
            }


            return $toAttack;
        }else{
         ;
             return Army::select('name')->where('status','=', '1')->value('name'). " won. Game done!";
        }
    }

    public function whoToAttack($value)
    {

       
        $toAttack = 1;
        if ($value['strategy'] == 1) { //RANDOM
            $rand = rand(1, count(Army::all()));
            while ($rand == $value['id'] || (Army::find($rand)['status']) == 0) {
                $rand = rand(1, count(Army::all()));
            }
            $toAttack = $rand;
        } else if ($value['strategy'] == 2) { //WEAKEST
            $toAttack = Army::where('units', '=', (Army::select('units')->where('id', '<>', $value['id'])->where('status', '<>', '0')->min('units')))->first()->id;
        } else if ($value['strategy'] == 3) { //STRONGEST
            $toAttack = Army::where('units', '=', (Army::select('units')->where('id', '<>', $value['id'])->where('status', '<>', '0')->max('units')))->first()->id;
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
            return Army::select('name')->where('status','=', '1')->value('name'). " won. Game done!";
        }
    }

    public function startRound()
    {
        return (Army::all()[3]['strategy']);
    }
}
