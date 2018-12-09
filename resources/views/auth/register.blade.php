@extends('layouts.master')
@section('title', 'Register')
@section('content')
    <section id="page-title">
        <div class="container clearfix">
            <h1>Register Webservice</h1>
        </div>
    </section>

    <section id="content">
        <div class="content-wrap">
            <div class="container clearfix">
                <div class="tabs divcenter nobottommargin clearfix" id="tab-login-register" style="max-width: 500px;">
                    <div class="tab-container" id="vue">
                        <div class="tab-content clearfix" id="tab-register">
                            <register-component></register-component>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
