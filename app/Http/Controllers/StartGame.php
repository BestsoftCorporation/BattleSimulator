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
        $toAttack = array();
        foreach(Army::all() as $key => $value){
           if ($value['strategy']==1){ //RANDOM
                $rand=rand(1,count(Army::all()));
                while ($rand==$value['id']){
                    $rand=rand(1,count(Army::all()));    
                }
                 $toAttack[$value['id']]=$rand;
           }else if ($value['strategy']==2){ //WEAKEST
                 $toAttack[$value['id']]=Army::select('id')->where('units', Army::min('units'))->where('id', '<>', $value['id'])->value('id');
           }else if ($value['strategy']==3){ //STRONGEST
                 $toAttack[$value['id']]=Army::select('id')->where('units', Army::max('units'))->where('id', '<>', $value['id'])->value('id');
           }
            
        }


        
        return $toAttack;
    }
}
