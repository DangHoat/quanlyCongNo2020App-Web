@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    {{ trans('__title.layouts.add_user') }}
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
        <h1>{{ trans('__title.layouts.add_user') }}</h1>
        <ol class="breadcrumb">
            <li><a href="#"> {{ trans('__title.layouts.users') }}</a></li>
            <li class="active">{{ trans('__title.layouts.add_user') }}</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            {{ trans('__title.layouts.add_user') }}
                        </h3>
                    </div>
                    <div class="panel-body">
                        <!--main content-->
                        <form id="commentForm" action="{{ route('admin.users.store') }}"
                              method="POST" enctype="multipart/form-data" class="form-horizontal">
                            <!-- CSRF Token -->
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                            <div id="rootwizard">
                                <ul>
                                    <li><a href="#tab1" data-toggle="tab">{{ trans('__title.users.profile') }}</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane" id="tab1">
                                        <h2 class="hidden">&nbsp;</h2>

                                        <div class="form-group {{ $errors->first('name', 'has-error') }}">
                                            <label for="name" class="col-sm-2 control-label">{{ trans('__title.users.name') }} *</label>
                                            <div class="col-sm-10">
                                                <input id="name" name="name" type="text" placeholder="{{ trans('__title.users.name') }}"
                                                       class="form-control required" value="{!! old('name') !!}"/>

                                                {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group {{ $errors->first('email', 'has-error') }}">
                                            <label for="email" class="col-sm-2 control-label">{{ trans('__title.users.email') }} *</label>
                                            <div class="col-sm-10">
                                                <input id="email" name="email" placeholder="{{ trans('__title.users.email') }}" type="text"
                                                       class="form-control required email" value="{!! old('email') !!}"/>
                                                {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group {{ $errors->first('password', 'has-error') }}">
                                            <label for="password" class="col-sm-2 control-label">{{ trans('__title.users.pw') }} *</label>
                                            <div class="col-sm-10">
                                                <input id="password" name="password" type="password" minlength="6" placeholder="{{ trans('__title.users.pw') }}"
                                                       class="form-control required" value="{!! old('password') !!}"/>
                                                {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group {{ $errors->first('password_confirm', 'has-error') }}">
                                            <label for="password_confirm" class="col-sm-2 control-label">{{ trans('__title.users.confirm_pw') }} *</label>
                                            <div class="col-sm-10">
                                                <input id="password_confirm" name="password_confirm" minlength="6" type="password"
                                                       placeholder="{{ trans('__title.users.confirm_pw') }} " class="form-control required"/>
                                                {!! $errors->first('password_confirm', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <p class="text-danger"><strong>{{ trans('__title.users.content_alert_role') }}</strong></p>
                                        <div class="form-group required">
                                            <label for="group" class="col-sm-2 control-label">{{ trans('__title.users.role') }} *</label>
                                            <div class="col-sm-10">
                                                <select class="form-control required" title="{{ trans('__title.layouts.select') }}..." name="role"
                                                        id="role">
                                                    <option value="">{{ trans('__title.layouts.select') }}</option>
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->id }}"
                                                                @if($role->id == old('role')) selected="selected" @endif >{{ $role->name}}</option>
                                                    @endforeach
                                                </select>
                                                {!! $errors->first('role', '<span class="help-block">:message</span>') !!}
                                            </div>
                                            <span class="help-block">{{ $errors->first('role', ':message') }}</span>
                                        </div>
                                    </div>                                    
                                    <ul class="pager wizard">
                                        {{-- <li class="previous"><a href="#">Previous</a></li>
                                        <li class="next"><a href="#">Next</a></li> --}}
                                        <li class="next finish" style="display:none;"><a href="javascript:;">{{ trans('__title.layouts.finish') }}</a></li>
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
