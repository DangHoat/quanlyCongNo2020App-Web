@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    {{trans('__title.layouts.add_client')}}
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
            <li><a href="#">{{trans('__title.layouts.statistical_table')}}</a></li>
            <li class="active">{{trans('__title.layouts.add_client')}}</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            {{trans('__title.layouts.add_client')}}
                        </h3>
                        <span class="pull-right">
                             <i class="glyphicon glyphicon-chevron-up clickable"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <!--main content-->
                        <form id="commentForm" action="{{ route('admin.clients.store') }}"
                              method="POST" enctype="multipart/form-data" class="form-horizontal">
                            <!-- CSRF Token -->
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                            <div id="rootwizard">
                                <ul>
                                    <li><a href="#tab1" data-toggle="tab">{{trans('__title.clients.client_info')}}</a></li>
                                    {{-- <li><a href="#tab2" data-toggle="tab">Invoice</a></li> --}}
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane" id="tab1">
                                        <h2 class="hidden">&nbsp;</h2>

                                        <div class="form-group {{ $errors->first('code', 'has-error') }}">
                                            <label for="code" class="col-sm-2 control-label">{{trans('__title.clients.code')}} *</label>
                                            <div class="col-sm-10">
                                                <input id="code" name="code" type="text"
                                                       placeholder="{{trans('__title.clients.code')}}" class="form-control required"
                                                       value="{!! old('code') !!}"/>
                                                {!! $errors->first('code', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group {{ $errors->first('name', 'has-error') }}">
                                            <label for="name" class="col-sm-2 control-label">{{trans('__title.clients.name')}} *</label>
                                            <div class="col-sm-10">
                                                <input id="name" name="name" type="text" placeholder="{{trans('__title.clients.name')}}"
                                                       class="form-control required" value="{!! old('name') !!}"/>
                                                {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="address" class="col-sm-2 control-label">{{trans('__title.clients.address')}}</label>
                                            <div class="col-sm-10">
                                                <input id="address" name="address" type="text" class="form-control"
                                                       value="{!! old('address') !!}"/>
                                            </div>
                                            <span class="help-block">{{ $errors->first('address', ':message') }}</span>
                                        </div>

                                        <div class="form-group {{ $errors->first('telephone', 'has-error') }}">
                                            <label for="telephone" class="col-sm-2 control-label">{{trans('__title.clients.telephone')}}</label>
                                            <div class="col-sm-10">
                                                <input id="telephone" name="telephone" type="text" class="form-control" 
                                                        value="{!! old('telephone') !!}"/>
                                                {!! $errors->first('telephone', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group {{ $errors->first('status', 'has-error') }}">
                                            <label for="email" class="col-sm-2 control-label">{{trans('__title.clients.status')}} *</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" title="Select Status..." name="status">
                                                    <option value="pending"
                                                            @if(old('status') === 'pending') selected="selected" @endif >
                                                            {{trans('__title.clients.pending')}}
                                                    </option>
                                                    <option value="resolved"
                                                            @if(old('status') === 'resolve') selected="selected" @endif >
                                                            {{trans('__title.clients.resolved')}}
                                                    </option>
                                                </select>
                                            </div>
                                            <span class="help-block">{{ $errors->first('status', ':message') }}</span>
                                        </div>

                                        <div class="form-group">
                                            <label for="date_limit" class="col-sm-2 control-label">{{trans('__title.clients.date_limit')}}</label>
                                            <div class="col-sm-10">
                                                <input id="date_limit" name="date_limit" type="date" class="form-control"
                                                       data-date-format="YYYY-MM-DD" value="{!! old('date_limit') !!}"/>
                                            </div>
                                            <span class="help-block">{{ $errors->first('date_limit', ':message') }}</span>
                                        </div>

                                        <div class="form-group">
                                            <label for="money_limit" class="col-sm-2 control-label">{{trans('__title.clients.money_limit')}}</label>
                                            <div class="col-sm-10">
                                                <input id="money_limit" name="money_limit" type="number" min="0" class="form-control"
                                                       value="{!! old('money_limit') !!}"/>
                                            </div>
                                            <span class="help-block">{{ $errors->first('money_limit', ':message') }}</span>
                                        </div>

                                        <div class="form-group">
                                            <label for="note" class="col-sm-2 control-label">{{trans('__title.clients.note')}}</label>
                                            <div class="col-sm-10">
                                                <textarea name="note" id="note" class="form-control resize_vertical"
                                            rows="4">{!! old('note') !!}</textarea>
                                            </div>
                                            <span class="help-block">{{ $errors->first('note', ':message') }}</span>
                                        </div>

                                    </div>

                                    <ul class="pager wizard">
                                        <li class="next finish" style="display:none;"><a href="javascript:;">{{trans('__title.layouts.finish')}}</a></li>
                                    </ul>
                                </div>
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
@stop