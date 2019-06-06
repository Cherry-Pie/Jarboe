@extends('jarboe::layouts.auth')

@section('header')
    <header id="header">
        <!--<span id="logo"></span>-->

        <div id="logo-group">
        <span id="logo">
            @include('jarboe::inc.auth.logo')
        </span>

            <!-- END AJAX-DROPDOWN -->
        </div>

        <span id="extr-page-header-space">
        <span class="hidden-mobile hiddex-xs">{{ __('jarboe::auth.registration.already_registered') }}</span>
        <a href="{{ admin_url('login') }}" class="btn btn-danger">{{ __('jarboe::auth.registration.sign_in') }}</a>
    </span>

    </header>
@endsection

@section('content')
    <div id="main" role="main">

        <!-- MAIN CONTENT -->
        <div id="content" class="container">

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">

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
                <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                    <div class="well no-padding">

                        <form action="{{ admin_url('register') }}" method="post" id="smart-form-register" class="smart-form client-form">
                            <header>
                                {{ __('jarboe::auth.registration.register_header') }}
                            </header>

                            @csrf

                            <fieldset>
                                <section>
                                    <label class="input"> <i class="icon-append fa fa-user"></i>
                                        <input type="text" name="name" placeholder="{{ __('jarboe::auth.registration.name_placeholder') }}">
                                        <b class="tooltip tooltip-bottom-right">{{ __('jarboe::auth.registration.name_tooltip') }}</b> </label>
                                </section>

                                <section>
                                    <label class="input"> <i class="icon-append fa fa-envelope"></i>
                                        <input type="email" name="email" placeholder="{{ __('jarboe::auth.registration.email_placeholder') }}">
                                        <b class="tooltip tooltip-bottom-right">{{ __('jarboe::auth.registration.email_tooltip') }}</b> </label>
                                </section>

                                <section>
                                    <label class="input"> <i class="icon-append fa fa-lock"></i>
                                        <input type="password" name="password" placeholder="{{ __('jarboe::auth.registration.password_placeholder') }}" id="password">
                                        <b class="tooltip tooltip-bottom-right">{{ __('jarboe::auth.registration.password_tooltip') }}</b> </label>
                                </section>

                                <section>
                                    <label class="input"> <i class="icon-append fa fa-lock"></i>
                                        <input type="password" name="password_confirmation" placeholder="{{ __('jarboe::auth.registration.confirm_password_placeholder') }}">
                                        <b class="tooltip tooltip-bottom-right">{{ __('jarboe::auth.registration.confirm_password_tooltip') }}</b> </label>
                                </section>
                            </fieldset>

                            <footer>
                                <button type="submit" class="btn btn-primary">
                                    {{ __('jarboe::auth.registration.register_button') }}
                                </button>
                            </footer>

                        </form>

                    </div>

                    {{--
                    <h5 class="text-center">- {{ __('jarboe::auth.registration.or_sign_in_using') }} -</h5>
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


        // Validation
        $(function() {
            // Validation
            $("#smart-form-register").validate({

                // Rules for form validation
                rules : {
                    name : {
                        required : true
                    },
                    email : {
                        required : true,
                        email : true
                    },
                    password : {
                        required : true,
                        minlength : 3,
                        maxlength : 20
                    },
                    password_confirmation : {
                        required : true,
                        minlength : 3,
                        maxlength : 20,
                        equalTo : '#password'
                    },
                },

                // Messages for form validation
                messages : {
                    name : {
                        required : '{{ __('jarboe::auth.registration.name_required_message') }}'
                    },
                    email : {
                        required : '{{ __('jarboe::auth.registration.email_required_message') }}',
                        email : '{{ __('jarboe::auth.registration.email_email_message') }}'
                    },
                    password : {
                        required : '{{ __('jarboe::auth.registration.password_required_message') }}'
                    },
                    password_confirmation : {
                        required : '{{ __('jarboe::auth.registration.password_confirmation_required_message') }}',
                        equalTo : '{{ __('jarboe::auth.registration.password_confirmation_equal_to_message') }}'
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

