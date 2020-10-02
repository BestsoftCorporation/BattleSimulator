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
              if ($value['units']>0){
                 $w=$this->whoToAttack($value);
                 $toAttack[$value['id']]=$w;
                 $this->attack($value,Army::find($w));
              }
        }


        
        return $toAttack;
    }

    public function whoToAttack($value){
        $toAttack=0;
        if ($value['strategy']==1){ //RANDOM
            $rand=rand(1,count(Army::all()));
            while ($rand==$value['id']){
                $rand=rand(1,count(Army::all()));    
            }
             $toAttack=$rand;
       }else if ($value['strategy']==2){ //WEAKEST
             $toAttack=Army::select('id')->where('units', Army::min('units'))->where('id', '<>', $value['id'])->value('id');
       }else if ($value['strategy']==3){ //STRONGEST
             $toAttack=Army::select('id')->where('units', Army::max('units'))->where('id', '<>', $value['id'])->value('id');
       }
       return $toAttack;
    }

    public function attack($attacker,$attacked){
         if ($attacked['units']!=1){
            $damage=$attacker['units']*0.5;
            if ($attacked['units']-$damage>0){
                $attacked->update(['units'=>$attacked['units']-$damage]);
            }else{
                $attacked->update(['units'=>0]);
            }
            
         }else{

         }
    }

    public function startRound(){
         return (Army::all()[3]['strategy']);
    }

}
