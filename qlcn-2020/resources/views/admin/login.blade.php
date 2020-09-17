<!DOCTYPE html>
<html>

<head>
    <title>{{trans('__title.auth.login')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- global level css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
    <!-- end of global level css -->
    <!-- page level css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/pages/login.css') }}" />
    <link href="{{ asset('assets/vendors/iCheck/css/square/blue.css') }}" rel="stylesheet"/>
    <!-- end of page level css -->

</head>

<body>
    <div class="container">
        <div class="row vertical-offset-100">
            <!-- Notifications -->
           <div id="notific">
               @include('notifications')
           </div>

            <div class="col-sm-6 col-sm-offset-3  col-md-5 col-md-offset-4 col-lg-4 col-lg-offset-4">
                <div id="container_demo">
                    <a class="hiddenanchor" id="tologin"></a>
                    <a class="hiddenanchor" id="toforgot"></a>
                    <div id="wrapper">
                        <div id="login" class="animate form">
                            <form action="{{ route('signin') }}" autocomplete="on" method="post" role="form" id="login_form">
                                <h3 class="black_bg">{{ trans('__title.auth.login') }}</h3>
                                    <!-- CSRF Token -->
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <div class="form-group {{ $errors->first('email', 'has-error') }}">
                                    <label style="margin-bottom:0px;" for="email" class="uname control-label"> <i class="livicon" data-name="mail" data-size="16" data-loop="true" data-c="#3c8dbc" data-hc="#3c8dbc"></i>
                                        {{trans('__title.auth.email')}}
                                    </label>
                                    <input id="email" name="email" type="email" placeholder="{{trans('__title.auth.email')}}"
                                           value="{!! old('email') !!}"/>
                                    <div class="col-sm-12">
                                        {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->first('password', 'has-error') }}">
                                    <label style="margin-bottom:0px;" for="password" class="youpasswd"> <i class="livicon" data-name="key" data-size="16" data-loop="true" data-c="#3c8dbc" data-hc="#3c8dbc"></i>
                                        {{trans('__title.auth.pw')}}
                                    </label>
                                    <input id="password" name="password" type="password" placeholder="{{trans('__title.auth.enter_pw')}}" />
                                    <div class="col-sm-12">
                                        {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                {{-- <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="remember-me" id="remember-me" value="remember-me"
                                               class="square-blue"/>
                                        Keep me logged in
                                    </label>
                                </div> --}}
                                <p class="login button">
                                    <input type="submit" value="{{trans('__title.auth.login')}}" class="btn btn-success" />
                                </p>
                                <p class="change_link">
                                    <a href="#toforgot">
                                        <button type="button" class="btn btn-responsive botton-alignment btn-warning btn-sm">
                                            {{trans('__title.auth.forgot')}}
                                        </button>
                                    </a>
                                </p>
                            </form>
                        </div>
                        
                        <div id="forgot" class="animate form">
                            <form action="{{ url('forgot-password') }}" autocomplete="on" method="post" role="form" id="reset_pw">
                                <h3 class="black_bg">{{trans('__title.auth.forgot')}}</h3>
                                <p>
                                    {{trans('__title.auth.content_forgor')}}
                                </p>

                                <!-- CSRF Token -->
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                                <div class="form-group {{ $errors->first('email_forgot', 'has-error') }}">
                                    <label style="margin-bottom:0px;" for="email_forgot" class="youmai">
                                        <i class="livicon" data-name="mail" data-size="16" data-loop="true" data-c="#3c8dbc" data-hc="#3c8dbc"></i>
                                        {{trans('__title.auth.email')}}
                                    </label>
                                    <input id="email_forgot" name="email_forgot" required type="email_forgot" placeholder="abcxyz@mail.com"
                                           value="{!! old('email_forgot') !!}"/>
                                    <div class="col-sm-12">
                                        {!! $errors->first('email_forgot', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <p class="login button">
                                    <input type="submit" value="{{trans('__title.auth.reset_pw')}}" class="btn btn-success" />
                                </p>
                                <p class="change_link">
                                    <a href="#tologin" class="to_register">
                                        <button type="button" class="btn btn-responsive botton-alignment btn-warning btn-sm">{{trans('__title.auth.back')}}</button>
                                    </a>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- global js -->
    {{-- <script src="{{ asset('assets/js/jquery-1.11.1.min.js') }}" type="text/javascript"></script> --}}
    <!-- Bootstrap -->
    {{-- <script src="{{ asset('assets/js/bootstrap.min.js') }}" type="text/javascript"></script> --}}
    {{-- <script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script> --}}
    <!--livicons-->
    <script src="{{ asset('assets/js/raphael-min.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/livicons-1.4.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}" type="text/javascript"></script> --}}
    <script src="{{ asset('assets/js/pages/login.js') }}" type="text/javascript"></script>
    <!-- end of global js -->
</body>
</html>