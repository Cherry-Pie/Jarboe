@extends('jarboe::layouts.auth')

@section('header')
    <header id="header">

        <div id="logo-group">
            <span id="logo">
                @include('jarboe::inc.auth.logo')
            </span>
        </div>

        @if (config('jarboe.admin_panel.registration_enabled'))
            <span id="extr-page-header-space">
                <span class="hidden-mobile hiddex-xs">{{ __('jarboe::auth.login.need_account') }}</span>
                <a href="{{ admin_url('register') }}" class="btn btn-danger">{{ __('jarboe::auth.login.create_account') }}</a>
            </span>
        @endif
    </header>
@endsection

@section('content')
<div id="main" role="main">

    <!-- MAIN CONTENT -->
    <div id="content" class="container">

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-8">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
                <div class="well no-padding">
                    <form action="{{ admin_url('login') }}" method="post" id="login-form" class="smart-form client-form">
                        <header>
                            {{ __('jarboe::auth.login.sign_in_header') }}
                        </header>

                        <fieldset>

                            {{ csrf_field() }}

                            <section>
                                <label class="label">{{ __('jarboe::auth.login.email') }}</label>
                                <label class="input"> <i class="icon-append fa fa-user"></i>
                                    <input type="email" name="email">
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> {{ __('jarboe::auth.login.email_tooltip') }}</b></label>
                            </section>

                            <section>
                                <label class="label">{{ __('jarboe::auth.login.password') }}</label>
                                <label class="input"> <i class="icon-append fa fa-lock"></i>
                                    <input type="password" name="password">
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> {{ __('jarboe::auth.login.password_tooltip') }}</b></label>
                            </section>

                            @if ($shouldOTP)
                                <section>
                                    <label class="label">{{ __('jarboe::auth.login.otp_password') }}</label>
                                    <label class="input"> <i class="icon-append fa fa-key"></i>
                                        <input type="text" name="otp">
                                        <b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> {{ __('jarboe::auth.login.otp_password_tooltip') }}</b></label>
                                </section>
                            @endif

                            <section>
                                <label class="checkbox">
                                    <input type="checkbox" name="remember" checked="">
                                    <i></i>{{ __('jarboe::auth.login.remember_me') }}</label>
                            </section>
                        </fieldset>
                        <footer>
                            <button type="submit" class="btn btn-primary">
                                {{ __('jarboe::auth.login.sign_in_button') }}
                            </button>
                        </footer>
                    </form>

                </div>

                {{--
                <h5 class="text-center"> - {{ __('jarboe::auth.login.or_sign_in_using') }} -</h5>

                <ul class="list-inline text-center">
                    <li>
                        <a href="javascript:void(0);" class="btn btn-primary btn-circle disabled"><i class="fa fa-facebook"></i></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="btn btn-info btn-circle disabled"><i class="fa fa-twitter"></i></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="btn btn-warning btn-circle disabled"><i class="fa fa-linkedin"></i></a>
                    </li>
                </ul>
                --}}

            </div>
        </div>
    </div>

</div>
@endsection


@section('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '<?= csrf_token() ?>'
            }
        });

        runAllForms();

        $(function() {
            // Validation
            $("#login-form").validate({
                // Rules for form validation
                rules : {
                    email : {
                        required : true,
                        email : true
                    },
                    password : {
                        required : true,
                        minlength : 3,
                        maxlength : 20
                    },
                    @if ($shouldOTP)
                    otp : {
                        required : true,
                        minlength : 6,
                        maxlength : 6
                    },
                    @endif
                },

                // Messages for form validation
                messages : {
                    email : {
                        required : '{{ __('jarboe::auth.login.email_required_message') }}',
                        email : '{{ __('jarboe::auth.login.email_email_message') }}'
                    },
                    password : {
                        required : '{{ __('jarboe::auth.login.password_required_message') }}'
                    },
                    otp : {
                        required : '{{ __('jarboe::auth.login.otp_password_required_message') }}'
                    },
                },

                // Do not change code below
                errorPlacement : function(error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });
    </script>
@endsection
