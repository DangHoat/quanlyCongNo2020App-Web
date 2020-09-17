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

class APIClientController extends Controller
{
    
    private $user;
     /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->middleware('auth:api', ['except' => ['login']]);
        $this->user = $user;
    }

    public function update(ClientRequest $request, $id){
        if(auth()->user()->permissions == "1"){
            $client = Client::find($id);
            $client->code = $request->code;
            $client->name = $request->name;
            $client->address = $request->address;
            $client->telephone = $request->telephone;
            $client->worker = $request->worker;
            $client->status = $request->status;
            $client->date_limit = $request->date_limit;
            $client->money_limit = $request->money_limit;
            $client->note = $request->note;
            $client->save();
            $message = 'Successfully';
            return response()->json(['message'=> $message]);
        }else{
            return response()->json(['message'=> "No"]);
        }
       
    }

    public function makeClient(Request $request){ 
        try{
            $client = new Client;
            $client->code = $request->code;
            $client->name = $request->name;
            $client->address = $request->address;
            $client->telephone = $request->telephone;
            $client->status = $request->status;
            $client->date_limit = $request->date_limit;
            $client->money_limit = $request->money_limit;
            $client->note = $request->note;
            $client->save();
       return response()->json([
           'message' => 'Successfully']);
        
        }catch(ModelNotFoundException $exception){
            return response()->json([
                'message' => 'Fails']);          
            }  
    }

    public function deleteClient(Request $request){

        $role = DB::table('role_users')->where("user_id",auth()->user()->id)->first();
        if($role->role_id == 1 ){
            $client = Client::where("code",$request->code)->first();
            if($client){
                $bills = Bill::where("idClient",$client->id)->delete();
                $client->delete();
            }else{
                return response()->json(['message' => 'Undefined']);
            }
            return response()->json(['message' => 'Successfully']);
        }else{
            return response()->json(['message'=> "Fails"]);
        }
    }
    public function getAllClient(){
        return Client::all();
    }

    public function getSomeClient($start,$end){
        if($start<=$end){
            return DB::table('clients')
                            ->skip($start-1)
                            ->take($end-$start)
                            ->get();
        }else{
           
            return DB::table('clients')
                            ->skip($end-1)
                            ->take($start-$end)
                            ->get();
        }     
    }
    public function updateClient(Request $request)
    {
        $role = DB::table('role_users')->where("user_id",auth()->user()->id)->first();
        if($role->role_id == 1 ){
            DB::table('clients')
            ->where('code', $request->code)
            ->update([
                'name' => $request->name,
                'address' => $request->address,
                'telephone' => $request->telephone,
                'date_limit' => $request->date_limit,
                'money_limit' => $request->money_limit    
            ]);
            return response()->json(['message' => 'Successfully']);
        }
    }
    public function pay($code)
    {
        $role = DB::table('role_users')->where("user_id",auth()->user()->id)->first();
        if($role->role_id == 1 ){
            $client = Client::where('code',$code)->first();
            $client->status = "resolved";
            $client->total = 0;
            $client->save();
            $bills = Bill::where('idClient',$client->id)->delete();
            return response()->json(['message' => 'Successfully']);
        }
    }

     public function paySomeMoney(Request $request)
        {
        $role = DB::table('role_users')->where("user_id",auth()->user()->id)->first();
        if($role->role_id == 1){
            $client = Client::where("code",$request->code)->first();
            $total = $client->total;
            if((double)$total == 0){
                   return response()->json(['message' => 'pay_enough']);
            }
            $newTotal = (double)$total - (double)$request->money;
            $client->total = $newTotal;

            if($newTotal == 0){
             $bills = Bill::where("idClient",$client->id)->delete();
                DB::table('clients')
                    ->where('code', $request->code)
                    ->update([
                        'status' => 'resolved',
                        'total' => 0
                    ]);
            }
            $client ->save();
            return response()->json(['message' => 'Successfully',"total"=>$client->total]);
        }
        return response()->json(['message' => 'Error']);
     }

    public function getClientByCode(Request $request){
        $user = Client::where("code",$request->code)->first();
        if($user){
            return response()->json(['message' => 'Successfully',
                                            'info' => $user,
                                            'total'=>$user->total,]);
        }
         return response()->json(['message' => 'Undefined']);
    }
}
