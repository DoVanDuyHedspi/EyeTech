@extends('layouts.master')
@section('title', 'Branch Cameras')
@section('content')
    <section id="page-title">
        <div class="container clearfix">
            <h1>List Cameras</h1>
        </div>
    </section>
    <section id="content">
        <div class="content-wrap" id="vue">
            <div class="container clearfix">
                <div class="row clearfix">
                    <div class="col-md-8">
                        <div class="clear"></div>
                        <div class="row clearfix">
                            <div class="col-lg-12">
                                <div class="tabs tabs-alt clearfix" id="tabs-profile">
                                    <div class="tab-container">
                                        <list-cameras-component></list-cameras-component>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-100 line d-block d-md-none"></div>
                    <div class="col-md-4 clearfix">
                        <h3>Create New Camera</h3>
                        <create-camera-component></create-camera-component>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
