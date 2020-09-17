<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ClientRequest;
use App\User;
use App\Client;
use App\Bill;
use JWTAuth;
use JWTAuthException;
use Hash;
use DB;

class APIBillController extends Controller
{
    
    private $user;
    /**
    * Create a new AuthController instance.
    *
    * @return void
    */
   public function __construct(User $user)
   {
       $this->middleware('auth:api', ['except' => ['login','resgiter']]);
       $this->user = $user;
   }

   public function getAllBills(){
       return Bill::all();

   }

   public function CreateBill(Request $request){
    $bills = json_decode( $request->bill);
    $ClientInfo = Client::where("code",$request->code)->first();
    if(!$ClientInfo){
        $client = new Client;
        $client ->name =    $request->name ;
        $client ->address = $request->address;
        $client ->telephone =  $request->telephone;
        $client ->code =    $request->code;
        $client ->status =  $request->"resolved";
        // $client ->number_update_invoices =  $req->number_update_invoices;
        $client ->save();
    } 
    if(count($bills)>0){
     $NewClient = Client::where("code",$request->code)->first();
     $newTotal = $NewClient->total;
    for($i = 0;$i<count($bills);$i++){
            $newTotal = $newTotal + (double)$bills[$i]->total_amount;
            $newBills=  new Bill;
            $newBills ->unit_price = $bills[$i]->unit_price;
            $newBills ->quantity = $bills[$i]->quantity;
            $newBills ->unit = $bills[$i]->unit;
            $newBills ->types = $bills[$i]->types;
            $newBills ->construction_address = $bills[$i]->construction_address;
            $newBills ->date = $bills[$i]->date;
            $newBills ->note = $bills[$i]->note;
            $newBills ->categories = $bills[$i]->categories;
            $newBills ->total_amount = $bills[$i]->total_amount;
            $newBills ->idClient = $NewClient->id;
            $newBills -> save();
    }
     if($newTotal == 0){
         $NewClient->status = "resolved";
         $NewClient->total = $newTotal;
         $NewClient->save();
          $bills = Bill::where('idClient',$NewClient->id)->delete();
         return response()->json(['message' => 'pay_enough']);
     }
     $NewClient->status = "pending";
     $NewClient->total = $newTotal;
     $NewClient->save();
 }
    return response()->json(['message' => 'Successfully']);
}

   public function getSomeBill($start,$end){
       if($start<=$end){
           return DB::table('bills')
                           ->skip($start-1)
                           ->take($end-$start)
                           ->get();
       }else{
          
           return DB::table('bills')
                           ->skip($end)
                           ->take($start-$end)
                           ->get();
       }   
   }
   
   public function getBillOfClient(Request $request){
       $code = $request->code;
       $idClient =  Client::where("code",$code)->first()->id;
       return Bill::where("idClient",$idClient)->get();  
   }

   public function getSomeBillOfClient($code,$start,$end){
       $idClient =  Client::where("code",$code)->first()->id;
       if($start<=$end){
           return DB::table('bills')
                           ->where("idClient",$idClient)
                           ->skip($start-1)
                           ->take($end-$start)
                           ->get();
       }else{
          
           return DB::table('bills')
                           ->where("idClient",$idClient)
                           ->skip($end-1)
                           ->take($start-$end)
                           ->get();
       } 
   }
   public function deleteBill($id){
       $bill = Bill::find($id);
       $client = Client::where("id",$bill->idClient)->first();
       $newTotal = (double)$client->total - (double)$bill->total_amount;
       if($newTotal == 0){
           $client->status = 'resolved';
       }
       $client->total = $newTotal;
       $client->save();
       $bill->delete();
       return response()->json(['message' => 'Successfully',"total"=>$client->total]);
   }
   public function updateBill(Request $request)
      {
       $role = DB::table('role_users')->where("user_id",auth()->user()->id)->first();
           if($role->role_id == 1 ){
               $deta_money= 0;
               $bill=  Bill::find($request->id);
               if($bill ->total_amount != $request->total_amount){
                   $deta_money = (double) $request->total_amount - (double) $bill ->total_amount;
               }
               $bill ->unit_price = $request->unit_price;
               $bill ->quantity = $request->quantity;
               $bill ->unit = $request->unit;
               $bill ->types = $request->types;
               $bill ->construction_address = $request->construction_address;
               $bill ->date = $request->date;
               $bill ->categories = $request->categories;
               $bill ->total_amount = $request->total_amount;
               $bill ->note = $request->note;
               $bill -> save();
               $client = Client::where("id",$bill->idClient)->first();
               $client ->total =(double)$client->total + (double) $deta_money;
               $client->save();
               return response()->json(['message' => 'Successfully',"total"=>$client ->total]);
           }
           return response()->json(['message' => 'Fails']);
      }

}
