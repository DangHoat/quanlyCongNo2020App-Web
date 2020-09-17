<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CoverController;
use App\Http\Requests\BillRequest;
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

class BillsController extends CoverController
{
	public function show($idClient){
        //findOrFail()
        $client = Client::find($idClient);
        $sum = 0;
        foreach ($client->bill as $bill) {
            $sum += $bill->total_amount;
        }
        $sum = number_format($sum, 0, '.', ',');
        // Show the page
        return view('admin.bills.show', compact('client','sum'));
    }

    public function data($idClient){
        $bills = Client::find($idClient)->bill()
            ->get(['id','date','construction_address','categories','types','unit','quantity','unit_price','total_amount','note','updated_at']);
        return DataTables::of($bills)
            ->editColumn('quantity',function($bill) {
                if($bill->quantity == 0) return null;
                else return $bill->quantity;
            })
            ->editColumn('unit_price',function($bill) {
                if($bill->unit_price == 0) return null;
                else return number_format($bill->unit_price, 0, '.', ',');
            })
            ->editColumn('total_amount',function($bill) {
                return number_format($bill->total_amount, 0, '.', ',');
            })
            ->editColumn('date',function($bill) {
                return '<div style="min-width:75px;">'.$bill->date.'</div>';
            })
            ->editColumn('updated_at',function($bill) {
                $time = '';
                if(isset($bill->updated_at)){
                    $time_display = $bill->updated_at->diffForHumans();
                    $time_title = $bill->updated_at;
                    $time = '<div title="'.$time_title.'">'.$time_display.'</div>';
                }
                return $time;
            })
            ->addColumn('actions',function($bill) {
                $actions = '
                <div style="max-width:48px;">
                    <a href='. route('admin.bills.destroy', $bill->id) .'>
                        <i class="livicon" data-name="trash" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="'.trans('__title.clients.title_delete_bill').'"></i>
                    </a>
                </div>';
                return $actions;
            })
            ->rawColumns(['actions'])
            ->escapeColumns([])
            ->make(true);
    }

    public function create($idClient){
        $clients = Client::get(['id','code','name'])->toArray();
        if(!$clients){
            return Redirect::back()->withInput()->with('error', trans('__title.clients.not_have_anyone'));
        }
        foreach ($clients as $key => $value) {
            $code_name_client[$value['id']] = $value['code'].'-'.$value['name'];
        }
        // Show the page
        return view('admin.bills.create', compact('code_name_client','idClient'));
    }

    public function store(BillRequest $request){
        $date = $request->date;
        $construction = $request->construction;
        $categories = $request->categories;
        $types = $request->types;
        $unit = $request->unit;
        $quantity = $request->quantity;
        $unit_price = $request->unit_price;
        $total_amount = $request->total_amount;
        $note = $request->note;
        $client = $request->client;
        foreach ($categories as $key => $value) {
            $data = array(
                'idClient' => $client,
                'date' => $date[$key],
                'construction_address' => $construction[$key],
                'categories' => $categories[$key],
                'types' => $types[$key],
                'unit'  => $unit[$key],
                'quantity' => $quantity[$key],
                'unit_price' => $unit_price[$key],
                'total_amount' => $total_amount[$key],
                'note'  =>  $note[$key],
            );
            $bill = new Bill;
            $bill->idClient = $data['idClient'];
            $bill->date = $data['date'];
            $bill->construction_address = $data['construction_address'];
            $bill->categories = $data['categories'];
            $bill->types = $data['types'];
            $bill->unit = $data['unit'];
            $bill->quantity = $data['quantity'];
            $bill->unit_price = (float) str_replace(",", "", $data['unit_price']);
            $bill->total_amount = (float) str_replace(",", "", $data['total_amount']);
            $bill->note = $data['note'];
            $bill->save();
        }
        Client::find($client)->increment('number_update_bills');
        $this_client = Client::find($client)->bill;
        $total_money_client = 0;
        foreach ($this_client as $bill_client) {
            $total_money_client += $bill_client->total_amount;
        }
        $update_client = Client::find($client);
        $update_client->total = $total_money_client;
        if($total_money_client == 0)
            $update_client->status = 'resolved';
        else $update_client->status = 'pending';
        $update_client->save();
        $success = trans('__title.notifications.add_bill_success');
        return Redirect::route('admin.bills.show', $client)->with('success', $success);
    }

    public function destroy($id){
        $idClient = Bill::find($id)->idClient;
        Bill::find($id)->delete();
        $this_client = Client::find($idClient)->bill;
        $total_money_client = 0;
        foreach ($this_client as $bill_client) {
            $total_money_client += $bill_client->total_amount;
        }
        $update_client = Client::find($idClient);
        $update_client->total = $total_money_client;
        $update_client->save();
        $success = trans('__title.notifications.delete_bill_success');
        return Redirect::back()->withInput()->with('success', $success);
    }


}
