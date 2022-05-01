@extends('layouts.app', [
    'title' => __('Feature Manager'),
    'parentSection' => 'app-settings',
    'elementName' => 'feature-manager'
])


@section('title') {{ __($module_action) }} Modules @stop
@section('page-title') {{ __($module_action) }} Modules @stop

{{--@section('breadcrumbs')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item type="active" icon='{{ $module_icon }}'>{{ $module_title }}</x-backend-breadcrumb-item>
</x-backend-breadcrumbs>
@stop--}}

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('Feature Management') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('backend.modulemanager.index') }}">{{ __('Feature Management') }}</a></li>

        @endcomponent
    @endcomponent
    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h2 class="h3 mb-0">@lang(":module_name Management Dashboard", ['module_name'=>Str::title('Features')])</h2>
                            </div>
                            <div class="col-auto">
                                <x-buttons.create route='{{ route("backend.module_builder.builder.create") }}' title="{{__('Create')}} Module" small="true"/>
                                <x-buttons.refresh route='{{ route("backend.$module_name.refresh") }}' title="{{__('Refresh List')}}" icon="fas fa-sync" small="true"/>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if( $mmodules )
                            <div class="row ">
                                @php $cnt = 0; @endphp
                                @foreach($mmodules as $mmodule)
                                    @php
                                        $settings = json_decode($mmodule->settings);
                                       $settings = $settings[0];
                                    @endphp
                                    @if ( !in_array($settings->name, $protected_modules ))
                                        <div class="col-4">
                                            <div class="card shadow-lg">
                                                {{--<img class="card-img-top" src="{{ $img_src }}" alt="Theme Name">--}}
                                                <div class="card-body">
                                                    <h5 class="card-title">{{ ucwords($settings->name) }}
                                                        @if ( in_array($settings->name, $protected_modules ))
                                                            <small class="float-right"><i class="fas fa-info-circle text-blue"  data-toggle="tooltip" data-placement="right" title="" data-original-title="{{ __('Protected Module') }}"></i> </small>
                                                            &nbsp;@endif
                                                    </h5>
                                                    {{--<div class="card-body">--}}
                                                        <span class="text-sm">{{ $settings->description }}</span>
                                                        @if($settings->keywords)
                                                            <ul class="text-sm mt-2">
                                                                @foreach( $settings->keywords as $keyword )
                                                                    <li>{{ $keyword }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    {{--</div>--}}
                                                    <div class="card-footer" >
                                                        {{--<div class="btn-toolbar bg-secondary m-0 flex-column flex-sm-row" role="toolbar" aria-label="Module Settings Controller" >--}}
                                                        {{--<div class="btn-toolbar justify-content-between btn-group-sm" role="toolbar" aria-label="Module Actions">--}}
                                                            <div class="btn-group btn-group-sm bg-secondary ml-0" role="group" aria-label="Module Control">
                                                                @if( in_array($settings->name, $active ) )
                                                                    <a href="{{route("backend.$module_name.disable_module", $settings->name)}}" >
                                                                        <button class="btn btn-sm btn-outline-warning mr-2">{{ __('Disable') }}</button>
                                                                    </a>
                                                                @else
                                                                    <a href="{{route("backend.$module_name.enable_module", $settings->name)}}" >
                                                                        <button class="btn btn-sm btn-success ml-2" >{{ __('Enable') }}</button>
                                                                    </a>
                                                                @endif

                                                                {{--
                                                                <a href="{{route("backend.$module_name.update_module", $settings->name)}}" ><button class="btn btn-sm btn-secondary" >{{ __ ('Update') }}</button></a>
                                                                <button onclick="deleteConfirmation( '{{route("backend.$module_name.delete_module", $settings->name)}}' )"  class="btn btn-sm btn-secondary" >{{ __('Delete') }}</button>
                                                                --}}
                                                                @if ( !in_array($settings->name, $protected_modules ))
                                                                    <a href="{{route("backend.$module_name.settings", $settings->name)}}" >
                                                                        <button class="btn btn-sm btn-outline-info" >{{ __('Settings') }}</button>
                                                                    </a>
                                                                @endif
                                                            {{--</div>--}}
                                                            {{--</div>--}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @php($cnt++)
                                        @if($cnt == 3)@php( $cnt = 0)</div><div class="row mt-4"> @endif
                                    @endif
                                @endforeach
                            </div>
                        @endif

                    </div>

                    {{--<div class="card-footer">
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
                    </div>--}}
                </div>
            </div>
        </div>
        @include('layouts.footers.auth')
    </div>


@stop
@push('css')
    <style>

        #control_module .btn-sm{
            position: relative;
            vertical-align: center;
            margin: 0px;
            height: 100px;
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
@endpush
@push('js')
    <script type="text/javascript">
        function deleteConfirmation(url) {
            swal({
                title: "{{ __('Delete Module') }}?",
                text: "{{ __('Are you sure you want to delete, this cannot be undone!') }}",
                type: "warning",
                showCancelButton: !0,
                confirmButtonText: "{{ __('Yes, delete it') }}!",
                cancelButtonText: "{{ __('No, cancel') }}!",
                reverseButtons: !0
            }).then(function (e) {
                if (e.value === true) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {_token: CSRF_TOKEN},
                        dataType: 'JSON',
                        success: function (results) {
                            if (results.success === true) {
                                swal("{{ __('Done') }}!", results.message, "success");
                                location.reload();
                            } else {
                                swal("{{ __('Error') }}!", results.message, "error");
                            }
                        }
                    });
                } else {
                    e.dismiss;
                }
            }, function (dismiss) {
                return false;
            })
        }
    </script>
@endpush

