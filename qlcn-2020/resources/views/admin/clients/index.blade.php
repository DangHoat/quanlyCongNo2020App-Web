@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
{{trans('__title.clients.list')}}
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
{{-- <link rel="stylesheet" href="{{ asset('assets/vendors/animate/animate.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/pages/only_dashboard.css') }}"/> --}}
@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>{{trans('__title.layouts.statistical_table')}}</h1>
    <ol class="breadcrumb">
        <li><a href="#">{{trans('__title.layouts.statistical_table')}}</a></li>
        <li class="active">{{trans('__title.clients.list')}}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
        <div class="panel panel-primary ">
            <div class="panel-heading">
                <h4 class="panel-title pull-left">
                    <i class="livicon" data-name="user" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    {{trans('__title.clients.list')}}
                </h4>
                <div class="panel-title pull-right">
                    <div style="display: flex;">
                        <button class="btn btn-success" onclick="window.location='{{route('export_client')}}'">
                            {{trans('__title.layouts.export_excel')}}
                        </button>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 margin_10 animated fadeInUpBig">
                    <!-- Trans label pie charts strats here-->
                    <div class="redbg no-radius">
                        <div class="panel-body squarebox square_boxs">
                            <div class="col-xs-12 pull-left nopadmar">
                                <div class="row">
                                    <div class="square_box col-xs-7 pull-left">
                                        <div><b>{{trans('__title.clients.the_total_amount')}}</b></div>
                                        <div class="number" id="myTargetElement2">{{$total_amount}}</div>
                                    </div>
                                    <i class="livicon pull-right" data-name="piggybank" data-l="true" data-c="#fff"
                                       data-hc="#fff" data-s="60"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 margin_10 animated fadeInRightBig">
                    <!-- Trans label pie charts strats here-->
                    <div class="palebluecolorbg no-radius">
                        <div class="panel-body squarebox square_boxs">
                            <div class="col-xs-12 pull-left nopadmar">
                                <div class="row">
                                    <div class="square_box col-xs-10 pull-left">
                                        <div class="row">
                                            <div><b>{{trans('__title.clients.clients')}} ({{$resolved + $pending + $danger}})</b></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-4">
                                                <div>{{trans('__title.clients.resolved')}}</div>
                                                <h4 id="myTargetElement1.1">{{$resolved}}</h4>
                                            </div>
                                            <div class="col-xs-4">
                                                <div>{{trans('__title.clients.pending')}}</div>
                                                <h4 id="myTargetElement1.2">{{$pending}}</h4>
                                            </div>
                                            <div class="col-xs-4">
                                                <div>{{trans('__title.clients.danger')}}</div>
                                                <h4 id="myTargetElement1.3">{{$danger}}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <i class="livicon pull-right" data-name="users" data-l="true" data-c="#fff"
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
                            <th>{{trans('__title.clients.code')}}</th>
                            <!-- <th>{{trans('__title.clients.name')}}</th> -->
                            <th>{{trans('__title.clients.address')}}</th>
                            <th>{{trans('__title.clients.telephone')}}</th>
                            <th>{{trans('__title.clients.total')}}</th>
                            <!-- <th>{{trans('__title.clients.date_limit')}}</th> -->
                            <!-- <th>{{trans('__title.clients.money_limit')}}</th> -->
                            <!-- <th>{{trans('__title.clients.status')}}</th> -->
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
    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();
        if (month.length < 2)
            month = '0' + month;
        if (day.length < 2)
            day = '0' + day;
        return [year, month, day].join('-');
    }
    $(function() {
        var table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.clients.data') !!}',
            columns: [
                { data: 'id', defaultContent: '', orderable: false, searchable: false },
                { data: 'code', name: 'code' },
                // { data: 'name', name: 'name' },
                { data: 'address', name: 'address' },
                { data: 'telephone', name: 'telephone' },
                { data: 'total', name:'total' },
                // { data: 'date_limit', name:'date_limit', "width": "75px" },
                // { data: 'money_limit', name:'money_limit' },
                // { data: 'status', name:'status' },
                { data: 'actions', name: 'actions', "width": "81px", orderable: false, searchable: false },
                { data: 'note', name:'note' },
            ],
            order: [[6, 'desc']],
            rowCallback: function( row, data, index ) {
                if(data.status == '{{trans('__title.clients.pending')}}'){
                    if(data.date_limit){
                        if(formatDate(new Date()) >= data.date_limit){
                            $('td', row).css('background-color', '#EF6F6C');
                            $('td', row).css('color', 'white');
                            $('i.livicon',row).attr('data-c','white');
                            $('i.livicon',row).attr('data-hc','white');
                        }
                    }
                    if(data.money_limit){
                        if(parseInt(data.total.split(".").join('')) >= parseInt(data.money_limit.split(".").join(''))){
                            $('td', row).css('background-color', '#EF6F6C');
                            $('td', row).css('color', 'white');
                            $('i.livicon',row).attr('data-c','white');
                            $('i.livicon',row).attr('data-hc','white');
                        }
                    }
                }
            }
            // columnDefs: [
            //     {className: "dt-body-center", targets: "8"}
            // ],
        });
        table.on( 'draw', function () {
            $('.livicon').each(function(){
                $(this).updateLivicon();
            });
            $(".status_btn").parent().css("text-align","center")
        });
        // table.on( 'order.dt search.dt', function () {
        //     table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        //         cell.innerHTML = i+1;
        //     });
        // }).draw();
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
