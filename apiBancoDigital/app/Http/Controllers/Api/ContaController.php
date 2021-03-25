<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\All\Conta;
use App\Models\All\Extrato;

use DB;

class ContaController extends Controller
{
    public function saldo($conta)
    {
        $data = [];
         try{
            $objConta = Conta::where('conta',$conta)->first();

            if($objConta){
                 $data = [
                   'message' => 'Saldo',
                   'saldo' => $objConta->saldo,
                ];
            }else{
                $data = [
                   'message' => 'Conta incorreta',
                   'saldo' => 0,
                ];
            }
        }
        catch(\Exception $e){
            $data = [
               'message' => $e->getMessage()
            ];
        }

        return response()->json($data);
    }

    //realizo o saque na conta existente e com saldo
    public function sacar($conta,$valor)
    {
        $data = [];
         try{

            DB::beginTransaction(); 
    
            $objConta = Conta::where('conta',$conta)->first();

            if($objConta){

                //se o saldo for maior ou igual ao valor solicitado, realizo o saque
                if($objConta->saldo >= $valor){

                    $saldoAtual = ($objConta->saldo - $valor);

                    $update = $objConta->update([
                           'saldo' => $saldoAtual,
                    ]);

                    if($update){
                            
                            //gravando a movimentação da conta
                            $this->movimentacoes($objConta->id,$valor,'saque');
                            DB::commit();

                            $data = [
                               'message' => 'Saque realizado',
                               'conta' => $conta,
                               'saldo' => $saldoAtual,
                            ];
                    }


                }else{
                    //saldo insuficiente para saque
                   
                    $data = [
                       'message' => 'Saldo insuficiente',
                       'conta' => $conta,
                       'saldo' => $objConta->saldo,
                    ];
                }

            }else{
                $data = [
                   'message' => 'Conta incorreta',
                ];
            }
        } 
        catch(\Exception $e){
            $data = [
               'message' => $e->getMessage()
            ];
        }

        return response()->json($data);
    }

    //realizo o deposito na conta existente
    public function depositar($conta,$valor)
    {
        $data = [];
         try{
            DB::beginTransaction(); 

            $objConta = Conta::where('conta',$conta)->first();

            if($objConta){

                $saldoAtual = ($objConta->saldo + $valor);

                $update = $objConta->update([
                       'saldo' => $saldoAtual,
                ]);

                if($update){                        

                        //gravando a movimentação da conta
                        $this->movimentacoes($objConta->id,$valor,'deposito');
                        DB::commit();

                        $data = [
                           'message' => 'Desposito realizado',
                           'conta' => $conta,
                           'saldo' => $saldoAtual,
                        ];
                }

            }else{
                $data = [
                   'message' => 'Conta incorreta',
                ];
            }
        } 
        catch(\Exception $e){
            $data = [
               'message' => $e->getMessage()
            ];
        }

        return response()->json($data);
    }


    //gravo as movimentações da conta
    public function movimentacoes($conta_id,$valor,$transacao)
    {
        $data = [];
        try{
            DB::beginTransaction(); 

            $insert = Extrato::create([
                       'conta_id' => $conta_id,
                       'valor' => $valor,
                       'transacao' => $transacao,
            ]);

            if($insert){
                DB::commit();
                $data = [
                    'message' => true,
                ];
            }else{
                DB::rollBack();
                $data = [
                    'message' => false,
                ];
            }

        } 
        catch(\Exception $e){
            $data = [
               'message' => $e->getMessage()
            ];
        }

        return $data;

    }

}