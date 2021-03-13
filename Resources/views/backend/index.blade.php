@extends('backend.layouts.app')

@section('title') {{ __($module_action) }} Modules @stop

@section('breadcrumbs')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item type="active" icon='{{ $module_icon }}'>{{ $module_title }}</x-backend-breadcrumb-item>
</x-backend-breadcrumbs>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-8">
                <h4 class="card-title mb-0">
                    <i class="{{ $module_icon }}"></i> Modules <small class="text-muted">{{ __($module_action) }}</small>
                </h4>
                <div class="small text-muted" >
                    @lang(":module_name Management Dashboard", ['module_name'=>Str::title('Modules')])
                </div>
            </div>
            <!--/.col-->
            <div class="col-4">
                <div class="float-right">
                    <x-buttons.create route='{{ route("backend.module_builder.builder.create") }}' title="{{__('Create')}} Module"/>
                    <x-buttons.refresh route='{{ route("backend.$module_name.refresh") }}' title="{{__('Refresh List')}}"/>
                </div>
            </div>
            <!--/.col-->
        </div>
        <!--/.row-->
@if( $mmodules )
        <div class="row mt-4">
            @php $cnt = 0; @endphp
            @foreach($mmodules as $mmodule)
                @php
                 $settings = json_decode($mmodule->settings);
                $settings = $settings[0];
                @endphp
            <div class="col-4">
                <div class="card">
                    {{--<img class="card-img-top" src="{{ $img_src }}" alt="Theme Name">--}}
                    <div class="card-body">
                        <h5 class="card-title">{{ ucwords($settings->name) }}&nbsp;{{--<small>by: @if($settings->web)<a href="{{ $settings->web }}" target="_blank">@endif {{ $settings->author }}@if($settings->web)</a> @endif</small>--}}</h5>
                        <p class="card-text">{{ $settings->description }}</p>
                        <div class="row">

                            <div class="col-2"></div>
                            <div class="col">

                                    <div class="btn-toolbar mb-3 float-right" role="group" aria-label="Module Controls">
                                        <div class="btn-group " role="group" aria-label="Manage">
                                            @if( in_array($settings->name, $active ) )
                                                <a href="{{route("backend.$module_name.disable_module", $settings->name)}}" ><button class="btn btn-warning" type="button"><i data-toggle="tooltip" title="Disable" class="fas fa-ban"></i></button></a>
                                            @else
                                                <a href="{{route("backend.$module_name.enable_module", $settings->name)}}" ><button class="btn btn-success" type="button"><i data-toggle="tooltip" title="Enable"  class="fas fa-check-double"></i></button></a>
                                            @endif
                                            <a href="{{route("backend.$module_name.update_module", $settings->name)}}" ><button class="btn btn-primary" type="button"><i data-toggle="tooltip" title="Update Module"  class="fas fa-upload"></i> </button></a>
                                            <a href="{{route("backend.$module_name.delete_module", $settings->name)}}" ><button class="btn btn-danger" type="button"><i data-toggle="tooltip" title="Delete Module"  class="fas fa-stop-circle"></i> </button></a>
                                            <a href="{{route("backend.$module_name.settings", $settings->name)}}" ><button class="btn btn-primary" type="button"><i data-toggle="tooltip" title="Manage Module"  class="fas fa-user-cog"></i> </button></a>
                                        </div>
                                    </div>
                            </div>
                            <div class="col-2"></div>


                            {{--<div class="col">
                                @if( in_array($settings->name, $active ) )
                                    <a href="{{route("backend.$module_name.disable_module", $settings->name)}}" class="btn btn-sm btn-warning "><i class="fas fa-ban"></i> &nbsp;Disable</a>
                                @else
                                    <a href="{{route("backend.$module_name.enable_module", $settings->name)}}" class="btn btn-sm btn-success "><i class="fas fa-check-double"></i> &nbsp;Enable</a>
                                @endif
                            </div>
                            <div class="col">
                                <a href="{{route("backend.$module_name.update_module", $settings->name)}}" class="btn btn-sm btn-primary">Update &nbsp;<i class="fas fa-upload"></i></a>
                                <a href="{{route("backend.$module_name.delete_module", $settings->name)}}" class="btn btn-sm btn-danger">Delete &nbsp;<i class="fas fa-remove"></i></a>
                            </div>
                            <div class="col">
                                <a href="#" class="btn btn-sm btn-primary">Manage &nbsp;<i class="fas fa-user-cog"></i></a>
                            </div>--}}
                        </div>


                    </div>
                </div>
            </div>
                @php($cnt++)
                @if($cnt == 3)@php( $cnt = 0)</div><div class="row mt-4"> @endif
            @endforeach
        </div>
 @endif

    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col-7">
                <div class="float-left">
                    Total {{ $$module_name->total() }} Modules
                </div>
            </div>
            <div class="col-5">
                <div class="float-right">
                    {!! $$module_name->render() !!}
                </div>
            </div>
        </div>
    </div>
</div>

    <style>

        #control_module .btn-sm{
            position: relative;
            vertical-align: center;
            margin: 0px;
            height: 100x;
            padding: 20px 20px;
            font-size: 4px;
            color: white;
            text-align: center;
            text-shadow: 0 3px 2px rgba(0, 0, 0, 0.3);
            background: #62b1d0;
            border: 0;
            border-bottom: 3px solid #9FE8EF;
            cursor: pointer;
            -webkit-box-shadow: inset 0 -3px #9FE8EF;
            box-shadow: inset 0 -3px #9FE8EF;
        }

        #control_module .btn-sm:active {
            top: 2px;
            outline: none;
            -webkit-box-shadow: none;
            box-shadow: none;
        }
        #control_module .btn-sm:hover {
            background: #45E1E8;
        }
    </style>
@stop
