<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CoverController;
use App\Http\Requests\ClientRequest;
use App\Mail\Register;
use App\Mail\Restore;
use App\Client;
use App\Bill;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use File;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Redirect;
use Sentinel;
use URL;
use View;
use Yajra\DataTables\DataTables;


class ClientsController extends CoverController
{

    /**
     * Show a list of all the users.
     *
     * @return View
     */

    public function index(){
        $clients = Client::where('total','!=',0)->get(['id','status','total','date_limit','money_limit']);
        $sum = 0;
        $resolved = 0;
        $pending = 0;
        $danger = 0;
        foreach ($clients as $client) {
            $sum += $client->total;
            if($client->status == 'resolved') $resolved++;
            if($client->status == 'pending'){
                $pending++;
                if($client->date_limit){
                    if(date("Y-m-d") >= $client->date_limit){
                        $danger++;
                    }
                }
                elseif($client->money_limit){
                    if($client->total >= $client->money_limit){
                        $danger++;
                    }
                }
            }
        }
        $total_amount = number_format($sum, 0, '.', ',');   //format $sum
        // Show the page
        return view('admin.clients.index', compact('total_amount','resolved','pending','danger'));
    }

    /*
     * Pass data through ajax call
     */
    /**
     * @return mixed
     */
    public function data(){
        // $clients = Client::where('total','!=',0)->get();
        $clients = Client::where('total','!=',0)->get(['id','code','name','address','telephone', 'total', 'status','note','date_limit','money_limit']);
        return DataTables::of($clients)
            // ->editColumn('status',function($client) {
            //     if($client->status === 'pending') return trans('__title.clients.pending');
            //     if($client->status === 'resolved') return trans('__title.clients.resolved');
            // })
            // ->editColumn('money_limit',function($client) {
            //     if($client->money_limit == 0) return null;
            //     else return number_format($client->money_limit, 0, '.', ',');
            // })
            ->editColumn('total',function($client){
                return number_format($client->total, 0, '.', ',');
            })
            ->addColumn('actions',function($client) {
                $actions = '
                    <div style="min-width:58px;">
                        <a href='. route('admin.bills.show', $client->id) .'>
                            <i class="livicon" data-name="info" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="'.trans('__title.clients.title_detail').'"></i>
                        </a>
                    </div>';
                if(Sentinel::inRole('admin')){
                    $actions = '
                    <div style="min-width:58px;">
                        <a href='. route('admin.bills.show', $client->id) .'>
                            <i class="livicon" data-name="info" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="'.trans('__title.clients.title_detail').'"></i>
                        </a>
                        <a href='. route('admin.clients.edit', $client->id) .'>
                            <i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="'.trans('__title.clients.title_edit').'"></i>
                        </a>
                        <a href='. route('admin.bills.create', $client->id) .'>
                            <i class="livicon" data-name="plus" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="'.trans('__title.clients.title_add_bill').'"></i>
                        </a>
                    </div>';
                    if ($client->status == 'resolved') {
                        $actions = '
                        <div style="min-width:81px;">
                            <a href='. route('admin.bills.show', $client->id) .'>
                                <i class="livicon" data-name="info" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="'.trans('__title.clients.title_detail').'"></i>
                            </a>
                            <a href='. route('admin.clients.edit', $client->id) .'>
                                <i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="'.trans('__title.clients.title_edit').'"></i>
                            </a>
                            <a href='. route('admin.bills.create', $client->id) .'>
                                <i class="livicon" data-name="plus" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="'.trans('__title.clients.title_add_bill').'"></i>
                            </a>
                            <a href='. route('admin.clients.confirm-delete', $client->id) .' data-toggle="modal" data-target="#delete_confirm">
                                <i class="livicon" data-name="user-remove" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="'.trans('__title.clients.title_delete_client').'"></i>
                            </a>
                        </div>';
                    }
                }
                return $actions;
            })
            ->rawColumns(['actions'])
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Create new user
     *
     * @return View
     */
    public function create(){
        // Show the page
        return view('admin.clients.create');
    }

    /**
     * User create form processing.
     *
     * @return Redirect
     */
    public function store(ClientRequest $request){
        $client = new Client;
        $client->code = str_replace(" ","_",$request->code);
        $client->name = $request->name;
        $client->address = $request->address;
        $client->telephone = $request->telephone;
        $client->status = $request->status;
        $client->date_limit = $request->date_limit;
        $client->money_limit = $request->money_limit;
        $client->note = $request->note;
        $client->save();
        $success = trans('__title.notifications.add_client_success');
        return Redirect::route('admin.clients.index')->with('success',$success);
    }

    /**
     * User update.
     *
     * @param  int $id
     * @return View
     */
    public function edit($id){
        $client = Client::find($id);
        // Show the page
        return view('admin.clients.edit', compact('client'));
    }

    /**
     * User update form processing page.
     *
     * @param  User $user
     * @param ClientRequest $request
     * @return Redirect
     */
    public function update(ClientRequest $request, $id){
        $client = Client::find($id);
        $client->code = str_replace(" ","_",$request->code);
        $client->name = $request->name;
        $client->address = $request->address;
        $client->telephone = $request->telephone;
        $client->status = $request->status;
        $client->date_limit = $request->date_limit;
        $client->money_limit = $request->money_limit;
        $client->note = $request->note;
        $client->save();
        $client_name = $client->name;
        $success = trans('__title.notifications.edit_client_success', compact('client_name'));
        return Redirect::route('admin.clients.index')->with('success', $success);
    }

    /**
     * Delete the given user.
     *
     * @param  int $id
     * @return Redirect
     */
    public function destroy($id){
        Bill::where('idClient',$id)->delete();
        $client = Client::find($id)->delete();
        $success = trans('__title.notifications.delete_client_success');
        return Redirect::route('admin.clients.index')->with('success', $success);
    }
    public function getModalDelete($id){
        $model = 'clients';
        $method = 'delete';
        $confirm_route = $error = null;
        $client = Client::find($id);
        $confirm_route = route('admin.clients.destroy', ['id' => $client->id]);
        return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route', 'method'));
    }
}
