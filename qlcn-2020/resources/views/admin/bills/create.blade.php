@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    {{trans('__title.layouts.add_bill')}}
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/iCheck/css/all.css') }}"  rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/pages/wizard.css') }}" rel="stylesheet">
    <!--end of page level css-->
@stop


{{-- Page content --}}
@section('content')
    <section class="content-header">
        <h1>{{trans('__title.layouts.statistical_table')}}</h1>
        <ol class="breadcrumb">
            <li><a href="#">{{trans('__title.layouts.statistical_table')}} </a></li>
            <li class="active">{{trans('__title.layouts.add_bill')}}</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div id="create_bill" class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title pull-left">
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            {{trans('__title.layouts.add_bill')}}
                        </h3>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        <!--main content-->
                        <form id="commentForm" action="{{ route('admin.bills.store') }}"
                              method="POST" enctype="multipart/form-data" class="form-horizontal">
                            <!-- CSRF Token -->
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                            <div id="rootwizard">
                                <ul>
                                    <div class="form-group {{ $errors->first('client', 'has-error') }}">
                                        <label for="client" class="col-sm-2 control-label">{{trans('__title.clients.client')}}: </label>
                                        <div class="col-sm-4">
                                            {!! Form::select('client', $code_name_client, isset($idClient) ? $idClient : null, ['class' => 'form-control select2', 'id' => 'client']) !!}
                                        </div>
                                        <span class="help-block">{{ $errors->first('client', ':message') }}</span>
                                    </div>
                                </ul>

                                <div id="record_of_bill" class="tab-content">
                                    <input type="hidden" id="counter" value="0">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <h4 class="clickbill panel-title">
                                                <a data-toggle="collapse" href="#" style="color: white !important;">
                                                    <span class="glyphicon glyphicon-minus"></span>
                                                    <span class=".ID_record">ID 1</span>
                                                </a>
                                            </h4>
                                            <span class="del_bill pull-right" hidden>
                                                 <i class="glyphicon glyphicon-remove" style="cursor: pointer;"></i>
                                            </span>
                                        </div>
                                        <div id="0" class="panel-collapse collapse in">
                                            <div class="panel-body">

                                                <div class="form-group {{ $errors->first('date.*', 'has-error') }}">
                                                    <label for="date" class="col-sm-2 control-label">{{trans('__title.clients.date')}} *</label>
                                                    <div class="col-sm-10">
                                                        <input id="date" name="date[0]" type="date" class="form-control" 
                                                        value="{!! old('date.0', date("Y-m-d")) !!}" required />
                                                    </div>
                                                </div>

                                                <div class="form-group {{ $errors->first('construction.*', 'has-error') }}">
                                                    <label for="construction" class="col-sm-2 control-label">{{trans('__title.clients.construction_address')}}</label>
                                                    <div class="col-sm-10">
                                                        <input id="construction" name="construction[0]" type="text" class="form-control"
                                                        value="{!! old('construction.0') !!}">
                                                    </div>
                                                </div>

                                                <div class="form-group {{ $errors->first('categories.*', 'has-error') }}">
                                                    <label for="categories" class="col-sm-2 control-label">{{trans('__title.clients.categories')}} *</label>
                                                    <div class="col-sm-10">
                                                        <input id="categories" name="categories[0]" type="text" class="form-control" value="{!! old('categories.0') !!}" required>
                                                        {!! $errors->first('categories.*', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                                <div class="form-group {{ $errors->first('types.*', 'has-error') }}">
                                                    <label for="types" class="col-sm-2 control-label">{{trans('__title.clients.types')}}</label>
                                                    <div class="col-sm-10">
                                                        <input id="types" name="types[0]" type="text" class="form-control" value="{!! old('types.0') !!}">
                                                    </div>
                                                </div>

                                                <div class="form-group {{ $errors->first('unit.*', 'has-error') }}">
                                                    <label for="unit" class="col-sm-2 control-label">{{trans('__title.clients.unit')}}</label>
                                                    <div class="col-sm-10">
                                                        <input id="unit" name="unit[0]" type="text" class="form-control" value="{!! old('unit.0') !!}">
                                                    </div>
                                                </div>

                                                <div class="form-group {{ $errors->first('quantity.*', 'has-error') }}">
                                                    <label for="quantity" class="col-sm-2 control-label">{{trans('__title.clients.quantity')}}</label>
                                                    <div class="col-sm-10">
                                                        <input id="quantity" name="quantity[0]" type="number" class="form-control" 
                                                            min="0" step="0.001" value="{!! old('quantity.0') !!}">
                                                    </div>
                                                </div>

                                                <div class="form-group {{ $errors->first('unit_price.*', 'has-error') }}">
                                                    <label for="unit_price" class="col-sm-2 control-label">{{trans('__title.clients.unit_price')}}</label>
                                                    <div class="col-sm-10">
                                                        <input id="unit_price" name="unit_price[0]" type="text" class="form-control" value="{!! old('unit_price.0') !!}">
                                                    </div>
                                                </div>

                                                <div class="form-group {{ $errors->first('total_amount.*', 'has-error') }}">
                                                    <label for="total_amount" class="col-sm-2 control-label">{{trans('__title.clients.total_amount')}} *</label>
                                                    <div class="col-sm-10">
                                                        <input id="total_amount" name="total_amount[0]" type="text" class="form-control" value="{!! old('total_amount.0') !!}" required>
                                                    </div>
                                                </div>

                                                <div class="form-group {{ $errors->first('note.*', 'has-error') }}">
                                                    <label for="note" class="col-sm-2 control-label">{{trans('__title.clients.note')}}</label>
                                                    <div class="col-sm-10">
                                                        <textarea id="note" name="note[0]" class="form-control resize_vertical" rows="1">{!! old('note.0') !!}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <ul class="pager wizard">
                                    <li id="add_bill" style="float: right;"><a href="javascript:;">{{trans('__title.layouts.add_record')}}</a></li>
                                    <li class="next finish" style="display:none;"><a href="javascript:;">{{trans('__title.layouts.finish')}}</a></li>
                                </ul>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--row end-->
    </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}"></script>
    <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" ></script>
    <script src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"  type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/bootstrapwizard/jquery.bootstrap.wizard.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/adduser.js') }}"></script>
    <script>
    $(document).ready(function(){
        //start add search client to select input
        function formatState (state) {
            if (!state.id) { return state.text; }
            var $state = $(
                '<span>' + state.text + '</span>'
            );
            return $state;
        }
        $("#client").select2({
            templateResult: formatState,
            templateSelection: formatState,
            theme:"bootstrap"
        });
        //end add search client to select input

        //array contains the ID of the row
        var array_id_record = [0];

        //start click add new bill
        $("div#create_bill").on('click','#add_bill',function(){
            //check validate input
            var $validator = $('#commentForm').data('bootstrapValidator').validate();

            //check validate input
            if ($validator.isValid()) {
                
                //id current record
                var id_record = parseInt($('#counter').val());

                //id next record
                var nextID = id_record + 1;

                //add new record ID to array
                array_id_record.push(nextID);

                //start click add bill to create new record and close other records
                $('div#record_of_bill').find('.collapse').removeClass('in');
                $('div#record_of_bill').find('.clickbill').children().children('.glyphicon').removeClass('glyphicon-minus').addClass('glyphicon-plus');
                //end click add bill to create new record and close other records

                //function to write some input field
                function field_html(tag, id, name, type, required, trans, form_err, option){
                    var field = '<div class="form-group '+form_err+'">';
                    field += '<label for="'+name+'" class="col-sm-2 control-label">'+trans+' '+(required ? '*' : '')+'</label>';
                    field += '<div class="col-sm-10">';
                    if(tag == 'textarea')
                    field += '<textarea id="'+name+'" name="'+name+'['+id+']" class="form-control resize_vertical" rows="1"></textarea>'
                    else //tag = input
                    field += '<input id="'+name+'" name="'+name+'['+id+']" type="'+type+'" class="form-control" '+option+' '+(required ? 'required' : '')+' />';

                    field += '</div></div>';
                    return field;
                }

                //start new record html
                var html = '<div class="panel panel-primary"><div class="panel-heading">';
                html +='<h4 class="clickbill panel-title">';
                html +='<a data-toggle="collapse" href="#'+nextID+'" style="color: white !important;">';
                html +='<span class="glyphicon glyphicon-minus"></span><span class="ID_record">ID '+(nextID+1)+'</span>';
                html +='</a></h4>';
                html +='<span class="del_bill pull-right" hidden>';
                html +='<i class="glyphicon glyphicon-remove" style="cursor: pointer;"></i>';
                html +='</span></div>';
                html +='<div id="'+nextID+'" class="panel-collapse collapse in"><div class="panel-body">';
                html +=field_html('input',
                            nextID,
                            'date',
                            'date',
                            true,
                            '{{trans('__title.clients.date')}}',
                            '{{ $errors->first('date.*', 'has-error') }}',
                            '');

                html +=field_html('input',
                            nextID,
                            'construction',
                            'text',
                            false,
                            '{{trans('__title.clients.construction_address')}}',
                            '{{ $errors->first('construction.*', 'has-error') }}',
                            '');

                html +=field_html('input',
                            nextID,
                            'categories',
                            'text',
                            true,
                            '{{trans('__title.clients.categories')}}',
                            '{{ $errors->first('categories.*', 'has-error') }}',
                            '');

                html +=field_html('input',
                            nextID,
                            'types',
                            'text',
                            false,
                            '{{trans('__title.clients.types')}}',
                            '{{ $errors->first('types.*', 'has-error') }}',
                            '');

                html +=field_html('input',
                            nextID,
                            'unit',
                            'text',
                            false,
                            '{{trans('__title.clients.unit')}}',
                            '{{ $errors->first('unit.*', 'has-error') }}',
                            '');

                html +=field_html('input',
                            nextID,
                            'quantity',
                            'number',
                            false,
                            '{{trans('__title.clients.quantity')}}',
                            '{{ $errors->first('quantity.*', 'has-error') }}',
                            'min="0" step="0.001"');

                html +=field_html('input',
                            nextID,
                            'unit_price',
                            'text',
                            false,
                            '{{trans('__title.clients.unit_price')}}',
                            '{{ $errors->first('unit_price.*', 'has-error') }}',
                            '');

                html +=field_html('input',
                            nextID,
                            'total_amount',
                            'text',
                            true,
                            '{{trans('__title.clients.total_amount')}}',
                            '{{ $errors->first('total_amount.*', 'has-error') }}',
                            '');

                html +=field_html('textarea',
                            nextID,
                            'note',
                            'date',
                            false,
                            '{{trans('__title.clients.note')}}',
                            '{{ $errors->first('note.*', 'has-error') }}',
                            '');
                //end new record html

                //append new record to the bottom
                $('div#record_of_bill').append(html);

                //start show remove icon if we have more than 1 record 
                if(array_id_record.length > 1)
                    $('#record_of_bill').find('.del_bill').show();
                else
                    $('#record_of_bill').find('.del_bill').hide();
                //end show remove icon if we have more than 1 record

                //increase counter to distinguish the rows
                $('#counter').val(nextID);

                //refesh ID record 
                $(".ID_record").empty();
                for(i = 0; i < array_id_record.length; i++){
                    $('div#'+array_id_record[i]).parent().find('.ID_record').append('ID '+(i+1));
                }

                //start add current date to new row
                var now = new Date();
                var year = now.getFullYear();
                if(now.getMonth()<9)    var month = '0'+(now.getMonth()+1);
                else var month = now.getMonth()+1;
                if(now.getDate()<10)    var date = '0'+now.getDate();
                else var date = now.getDate();
                var current_date = year + '-' + month + '-' + date;
                $('div#record_of_bill').find('#'+nextID).find('#date').val(current_date);
                //end add current date to new row
                
                //start auto take old construction field to new row
                var old_construction = $('div#record_of_bill').find('#'+id_record).find('#construction').val();
                $('div#record_of_bill').find('#'+nextID).find('#construction').val(old_construction);
                //end auto take old construction field to new row

                //call validate input again (from the 2nd row onwards)
                $('#commentForm').data('bootstrapValidator', null);
                $('#commentForm').bootstrapValidator();
            }
        });
        //end click add new bill

        //start click delete some bills
        $("div#record_of_bill").on('click','.del_bill',function(){
            var id_del = parseInt($(this).parent().parent().children('.collapse').attr('id'));
            //remove this record
            $(this).parent().parent().remove();
            //delete id_del in array_id_record
            array_id_record.splice(array_id_record.indexOf(id_del),1);

            //start show remove icon if we have more than 1 record 
            if(array_id_record.length > 1)
                $('#record_of_bill').find('.del_bill').show();
            else
                $('#record_of_bill').find('.del_bill').hide();
            //end show remove icon if we have more than 1 record 
           
            //refesh ID record 
            $(".ID_record").empty();
            for(i = 0; i < array_id_record.length; i++){
                $('div#'+array_id_record[i]).parent().find('.ID_record').append('ID '+(i+1));
            }
        })
        //end click delete some bills

        //start change icon up down each tab
        $("div#record_of_bill").on('click','.clickbill', function(){
            //check validate input
            var $validator = $('#commentForm').data('bootstrapValidator').validate();
            var id_select = parseInt($(this).parent().parent().children('.collapse').attr('id'));
            //if passing validate input in container have id=id_select, we set href=#x to a tag, otherwise, set href=# to prevent collapsed
            if (!$validator.isValidContainer('#'+id_select)) {
                $(this).children('a').attr('href', '#');
            }
            if ($validator.isValidContainer('#'+id_select)) {
                $(this).children('a').attr('href', '#'+id_select);
                if($(this).parent().parent().children('.collapse').hasClass('in')){
                    $(this).children().children('.glyphicon').removeClass('glyphicon-minus').addClass('glyphicon-plus');
                }
                else{
                    $(this).children().children('.glyphicon').removeClass('glyphicon-plus').addClass('glyphicon-minus');
                }
            }
        })
        //end change icon up down each tab

        //start auto calculate total amount
        $("div#record_of_bill").on('change','#quantity, #unit_price',function(){
            var id_change = parseInt($(this).parent().parent().parent().parent().attr('id'));
            var total = Math.round(
                parseFloat($('div#'+id_change).find('#quantity').val())*
                parseFloat($('div#'+id_change).find('#unit_price').val().replace(/,/gi,""))
            )
            //get value of total_amount and split .
            var num_origin = total.toString().replace(/,/gi, "").split(".");
            //add commas to wholes (not fractions)
            num_origin[0] = num_origin[0].split(/(?=(?:\d{3})+$)/).join(",");
            //when we done, add fractions to the back
            var num_commas = num_origin.join(".")

            if(num_commas != "NaN")
                $('div#'+id_change).find('#total_amount').val(num_commas);

            //reset validate to fix total_amount
            //appear error when click add new bill first, then enter value to quantity and unit_price but enter to total_amount
            //this case, we can't click add new bill because of validation
            $('#commentForm').data('bootstrapValidator', null);
            $('#commentForm').bootstrapValidator();
        })
        //end auto calculate total amount

        //start auto add commas number input unit_price
        $("div#record_of_bill").on('change keyup','#unit_price',function(event){
            //get id record
            var id_change = parseInt($(this).parent().parent().parent().parent().attr('id'));
            //get value of unit_price and split .
            var num_origin = $('div#'+id_change).find('#unit_price').val().replace(/,/gi, "").split(".");
            var index = "";
            //add commas to wholes (not fractions)
            if(num_origin[0].indexOf("-") != -1){
                index = "-";
                num_origin[0] = index+num_origin[0].replace("-","").split(/(?=(?:\d{3})+$)/).join(",")
            }
            else if(num_origin[0].indexOf("+") != -1){
                num_origin[0] = num_origin[0].replace("+","").split(/(?=(?:\d{3})+$)/).join(",")
            }
            else{
                num_origin[0] = num_origin[0].split(/(?=(?:\d{3})+$)/).join(",");
            }
            //when we done, add fractions to the back
            var num_commas = num_origin.join(".")
            //show to the input field
            $('div#'+id_change).find('#unit_price').val(num_commas);
        })
        //end auto add commas number input unit_price
        
        //start auto add commas number input total_amount
        $("div#record_of_bill").on('change keyup','#total_amount',function(event){
            //get id record
            var id_change = parseInt($(this).parent().parent().parent().parent().attr('id'));
            //get value of total_amount and split .
            var num_origin = $('div#'+id_change).find('#total_amount').val().replace(/,/gi, "").split(".");
            var index = "";
            //add commas to wholes (not fractions)
            if(num_origin[0].indexOf("-") != -1){
                index = "-";
                num_origin[0] = index+num_origin[0].replace("-","").split(/(?=(?:\d{3})+$)/).join(",")
            }
            else if(num_origin[0].indexOf("+") != -1){
                num_origin[0] = num_origin[0].replace("+","").split(/(?=(?:\d{3})+$)/).join(",")
            }
            else{
                num_origin[0] = num_origin[0].split(/(?=(?:\d{3})+$)/).join(",");
            }
            //when we done, add fractions to the back
            var num_commas = num_origin.join(".")
            //show to the input field
            $('div#'+id_change).find('#total_amount').val(num_commas);
            //reset validate to fix total_amount
            //appear error when click add new bill first, then enter value to quantity and unit_price but enter to total_amount
            //this case, we can't click add new bill because of validation
            $('#commentForm').data('bootstrapValidator', null);
            $('#commentForm').bootstrapValidator();
        })
        //end auto add commas number input total_amount
    })
    </script>
@stop