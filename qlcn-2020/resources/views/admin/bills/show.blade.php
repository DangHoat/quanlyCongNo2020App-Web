@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
{{trans('__title.clients.client_detail')}}
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>{{trans('__title.layouts.statistical_table')}}</h1>
    <ol class="breadcrumb">
        <li><a href="#"> {{trans('__title.layouts.statistical_table')}}</a></li>
        <li class="active">{{trans('__title.clients.client_detail')}}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
        <div class="panel panel-primary ">
            <div class="panel-heading">
                <h4 class="panel-title pull-left">
                    <i class="livicon" data-name="user" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    {{trans('__title.clients.client_detail')}}: {!! $client->name !!} - {!! $client->code !!} ({!! $client->status == 'pending' ? trans('__title.clients.pending') : trans('__title.clients.resolved') !!})
                </h4>
                <div class="panel-title pull-right">
                    <div style="display: flex;">
                        <button class="btn btn-danger" style="margin-right: 5px;" onclick="window.location='{{route('admin.bills.create', $client->id)}}'">{{trans('__title.layouts.add_bill')}}</button>
                        <button class="btn btn-success" onclick="window.location='{{route('export_bill', $client->id)}}'">{{trans('__title.layouts.export_excel')}}</button>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 margin_10 animated fadeInUpBig">
                    <!-- Trans label pie charts strats here-->
                    <div class="redbg no-radius">
                        <div class="panel-body squarebox square_boxs">
                            <div class="col-xs-12 pull-left nopadmar">
                                <div class="row">
                                    <div class="square_box col-xs-7 pull-left">
                                        <div><b>{{trans('__title.clients.the_total_amount')}}</b></div>
                                        <div class="number" id="myTargetElement2">{{$sum}}</div>
                                    </div>
                                    <i class="livicon pull-right" data-name="piggybank" data-l="true" data-c="#fff"
                                       data-hc="#fff" data-s="60"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                <table class="table table-bordered width100" id="table">
                    <thead>
                        <tr class="filters">
                            <th>{{trans('__title.clients.id')}}</th>
                            <th>{{trans('__title.clients.last_update')}}</th>
                            <th>{{trans('__title.clients.date')}}</th>
                            <th>{{trans('__title.clients.construction_address')}}</th>
                            <th>{{trans('__title.clients.categories')}}</th>
                            <th>{{trans('__title.clients.types')}}</th>
                            <th>{{trans('__title.clients.unit')}}</th>
                            <th>{{trans('__title.clients.quantity')}}</th>
                            <th>{{trans('__title.clients.unit_price')}}</th>
                            <th>{{trans('__title.clients.total_amount')}}</th>
                            <th>{{trans('__title.clients.actions')}}</th>
                            <th>{{trans('__title.clients.note')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>    <!-- row-->
</section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>
<script>
    $(function() {
        var table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.bills.data',$client->id) !!}',
            columns: [
                { data: 'id', defaultContent: '', orderable: false, searchable: false },
                { data: 'updated_at', name: 'updated_at'},
                { data: 'date', name: 'date' },
                { data: 'construction_address', name: 'construction_address' },
                { data: 'categories', name:'categories'},
                { data: 'types', name:'types'},
                { data: 'unit', name:'unit'},
                { data: 'quantity', name:'quantity'},
                { data: 'unit_price', name: 'unit_price' },
                { data: 'total_amount', name: 'total_amount' },
                { data: 'actions', name: 'actions', "width": "81px", orderable: false, searchable: false },
                { data: 'note', name: 'note' }
            ],
            order:[[1,'desc']]
        });
        table.on( 'draw', function () {
            $('.livicon').each(function(){
                $(this).updateLivicon();
            });
            $(".status_btn").parent().css("text-align","center")
        });
        table.on('draw.dt', function () {
            var info = table.page.info();
            table.column(0, { search: 'applied', order: 'applied', page: 'applied' }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1 + info.start;
            });
        });
    });
</script>

<div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="user_delete_confirm_title" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content"></div>
  </div>
</div>
<script>
$(function () {
	$('body').on('hidden.bs.modal', '.modal', function () {
		$(this).removeData('bs.modal');
	});
});
</script>
@stop
