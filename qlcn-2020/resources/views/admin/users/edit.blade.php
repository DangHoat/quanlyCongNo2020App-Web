@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    {{ trans('__title.layouts.edit_user') }}
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
        <h1>{{ trans('__title.layouts.edit_user') }}</h1>
        <ol class="breadcrumb">
            <li>{{ trans('__title.layouts.users') }}</li>
            <li class="active">{{ trans('__title.layouts.edit_user') }}</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="users" data-size="16" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            {{ trans('__title.layouts.edit_user') }}: <p class="user_name_max">{!! $user->name!!}</p>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <!--main content-->
                        <div class="row">
                            <div class="col-md-12">
                                <form id="commentForm" action="{{ route('admin.users.update',$user->id) }}"
                              method="POST" enctype="multipart/form-data" class="form-horizontal">
                                    {!! method_field('PUT') !!}
                                    {{ csrf_field() }}

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
                                                        <input id="name" name="name" type="text"
                                                               placeholder="{{ trans('__title.users.name') }}" class="form-control required"
                                                               value="{!! old('name', $user->name) !!}"/>
                                                    </div>
                                                    {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                                                </div>

                                                <div class="form-group {{ $errors->first('email', 'has-error') }}">
                                                    <label for="email" class="col-sm-2 control-label">{{ trans('__title.users.email') }} *</label>
                                                    <div class="col-sm-10">
                                                        <input id="email" name="email" placeholder="{{ trans('__title.users.email') }}" type="text"
                                                               class="form-control required email"
                                                               value="{!! old('email', $user->email) !!}" disabled />

                                                    {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <p class="text-danger"><strong>{{ trans('__title.users.content_alert_role') }}</strong></p>
                                                <div class="form-group {{ $errors->first('roles', 'has-error') }}">
                                                    <label for="roles" class="col-sm-2 control-label">Role *</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control " title="{{ trans('__title.layouts.select') }}..." name="roles[]" id="roles" required>
                                                            <option value="">{{ trans('__title.layouts.select') }}</option>
                                                            @foreach($roles as $role)
                                                                <option value="{!! $role->id !!}" {{ (array_key_exists($role->id, $userRoles) ? ' selected="selected"' : '') }}>{{ $role->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div
                                                            {!! $errors->first('roles', '<span class="help-block">:message</span>') !!}>
                                                </div>

                                                {{-- <div class="form-group">
                                                    <label for="activate" class="col-sm-2 control-label"> Activate User</label>
                                                    <div class="col-sm-10">
                                                        <input id="activate" name="activate" type="checkbox" class="pos-rel p-l-30 custom-checkbox" value="1" @if($status) checked="checked" @endif  >
                                                        <span>To activate your account click the check box</span>
                                                    </div>
                                                </div> --}}
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
                        <!--main content end-->
                    </div>
                </div>
            </div>
        </div>
        <!--row end-->
    </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" ></script>
    <script src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}"></script>
    <script src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"  type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/bootstrapwizard/jquery.bootstrap.wizard.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/edituser.js') }}"></script>
@stop
