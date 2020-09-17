@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    {{ trans('__title.users.profile') }}
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/vendors/x-editable/css/bootstrap-editable.css') }}" rel="stylesheet"/>

    <link href="{{ asset('assets/css/pages/user_profile.css') }}" rel="stylesheet"/>
@stop


{{-- Page content --}}
@section('content')
    <section class="content-header">
        <!--section starts-->
        <h1>{{ trans('__title.layouts.users') }}</h1>
        <ol class="breadcrumb">
            <li>
                <a href="#">{{ trans('__title.users.profile') }}</a>
            </li>
            <li class="active">{{ trans('__title.users.profile') }}</li>
        </ol>
    </section>
    <!--section ends-->
    <section class="content">
        <div class="row">
            <div class="col-lg-12">
                <ul class="nav  nav-tabs ">
                    <li class="active">
                        <a href="#tab1" data-toggle="tab">
                            <i class="livicon" data-name="user" data-size="16" data-c="#000" data-hc="#000" data-loop="true"></i>
                            {{ trans('__title.users.profile') }}</a>
                    </li>
                    <li>
                        <a href="#tab2" data-toggle="tab">
                            <i class="livicon" data-name="key" data-size="16" data-loop="true" data-c="#000" data-hc="#000"></i>
                            {{ trans('__title.users.change_pw') }}</a>
                    </li>
                </ul>
                <div  class="tab-content mar-top">
                    <div id="tab1" class="tab-pane fade active in">
                        <div class="row">
                        <form id="commentForm" action="{{ route('admin.users.update_profile') }}"
                              method="POST" enctype="multipart/form-data" class="form-horizontal">
                        {!! method_field('PUT') !!}
                        {{ csrf_field() }}
                            <div class="form-group {{ $errors->first('name', 'has-error') }}">
                                <label class="col-lg-2 control-label">
                                    {{ trans('__title.users.name') }}:<span class='require'>*</span>
                                </label>
                                <div class="col-lg-6">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="livicon" data-name="user" data-size="16" data-loop="true" data-c="#418bca" data-hc="#418bca"></i>
                                        </span>
                                        <input type="text" placeholder=" " name="name" id="uf-name"
                                               class="form-control" value="{!! old('name',$user->name) !!}">
                                    </div>
                                    <span class="help-block">{{ $errors->first('first_name', ':message') }}</span>
                                </div>
                            </div>

                            <div class="form-group {{ $errors->first('email', 'has-error') }}">
                                <label class="col-lg-2 control-label">
                                    {{ trans('__title.users.email') }}:<span class='require'>*</span>
                                </label>
                                <div class="col-lg-6">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="livicon" data-name="mail" data-size="16" data-loop="true" data-c="#418bca" data-hc="#418bca"></i>
                                        </span>
                                        <input type="text" placeholder=" " id="email" name="email" class="form-control"
                                               value="{!! old('email',$user->email) !!}" disabled>
                                    </div>
                                    <span class="help-block">{{ $errors->first('email', ':message') }}</span>
                                </div>
                            </div>

                            {{-- <div class="form-group">
                                <label class="col-lg-2 control-label">Gender: </label>
                                <div class="col-lg-6">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="gender" value="male" @if($user->gender === "male") checked="checked" @endif />
                                            Male
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="gender" value="female" @if($user->gender === "female") checked="checked" @endif />
                                            Female
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="gender" value="other" @if($user->gender === "other") checked="checked" @endif />
                                            Other
                                        </label>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-10">
                                    <button class="btn btn-primary" type="submit">{{ trans('__title.layouts.save') }}</button>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>

                    <div id="tab2" class="tab-pane fade">
                        <div class="row">
                            <div class="col-md-12 pd-top">
                                <form class="form-horizontal">
                                    {{ csrf_field() }}
                                    <div class="form-body">
                                        <div class="form-group">
                                            <label for="current_pw" class="col-md-3 control-label">
                                                {{ trans('__title.users.current_pw')}}
                                                <span class='require'>*</span>
                                            </label>
                                            <div class="col-md-9">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="livicon" data-name="key" data-size="16" data-loop="true" data-c="#000" data-hc="#000"></i>
                                                    </span>
                                                    <input type="password" id="current_pw" placeholder="{{ trans('__title.users.current_pw')}}" name="current_pw"
                                                           class="form-control"/>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="password" class="col-md-3 control-label">
                                                {{ trans('__title.users.pw') }}
                                                <span class='require'>*</span>
                                            </label>
                                            <div class="col-md-9">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="livicon" data-name="key" data-size="16" data-loop="true" data-c="#000" data-hc="#000"></i>
                                                    </span>
                                                    <input type="password" id="password" placeholder="{{ trans('__title.users.pw') }}" name="password"
                                                           class="form-control"/>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="password-confirm" class="col-md-3 control-label">
                                                {{ trans('__title.users.confirm_pw') }}
                                                <span class='require'>*</span>
                                            </label>
                                            <div class="col-md-9">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="livicon" data-name="key" data-size="16" data-loop="true" data-c="#000" data-hc="#000"></i>
                                                    </span>
                                                    <input type="password" id="password-confirm" placeholder="{{ trans('__title.users.confirm_pw') }}" name="confirm_password"
                                                           class="form-control"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="submit" class="btn btn-primary" id="change-password">{{ trans('__title.layouts.submit') }}
                                            </button>
                                            &nbsp;
                                            <input type="reset" class="btn btn-default" value="{{ trans('__title.layouts.reset') }}"></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- Bootstrap WYSIHTML5 -->
    <script  src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#change-password').click(function (e) {
                e.preventDefault();
                var check = false;
                if ($('#password').val() ===""){
                    alert('{{trans('__title.users.alert_error_pw_enter')}}');
                }
                else if  ($('#password').val().length < 6) {
                    alert('{{trans('__title.users.alert_error_min_pw')}}');
                }
                else if  ($('#password').val() !== $('#password-confirm').val()) {
                    alert('{{trans('__title.users.alert_error_pw_confirm')}}');
                }
                else if  ($('#password').val() === $('#password-confirm').val()) {
                    check = true;
                }

                if(check == true){
                    $.ajax({
                        url: "passwordreset",
                        type: "post",
                        data: {
                            _token: $("input[name='_token']").val(),
                            password: $('#password').val(),
                            current_pw : $('#current_pw').val()
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                        },
                        success: function (data) {
                            $('#password, #password-confirm, #current_pw').val('');
                            alert(data.message);
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert('{{trans('__title.users.alert_error_pw')}}');
                        }
                    });
                }
            });
        });
    </script>

@stop
