@extends('layouts.app', [
    'title' => $targetModule. __(' Settings'),
    'parentSection' => 'app-settings',
    'elementName' => 'feature-manager'
])

@section('title') {{ __($module_action) }} {{ $module_title }} @endsection


@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('Manage settings for '). $targetModule }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('backend.roles.index') }}">{{ __('Role Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('List') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h2 class="h3 mb-0">{{ $targetModule }} <small class="text-muted">{{ __($module_action) }}</small></h2>
                            </div>
                            <div class="col-auto">
                                <x-buttons.return-back route='{{ route("backend.$module_name.index") }}' title="{{__('Bacl')}}" small="true"/>
                                {{--<a href="{{ route("backend.$module_name.index") }}" class="btn btn-secondary btn-sm ml-1" data-toggle="tooltip" title="{{ $module_title }} List"><i class="fas fa-list-ul"></i> Feature List</a>--}}
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="row mt-4">
                            <div class="col-12">
                                {{ html()->modelForm($settings, 'PATCH', route("backend.$module_name.update", $$module_name_singular))->class('form')->open() }}

                                @include ("modulemanager::backend.settings_form")

                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            {{ html()->submit($text = icon('fas fa-save')." Save")->class('btn btn-success') }}
                                        </div>
                                    </div>

                                    <div class="col-8">
                                        <div class="float-right">
                                            {{--@can('delete_'.$module_name)
                                            <a href="{{route("backend.$module_name.destroy", $$module_name_singular)}}" class="btn btn-danger" data-method="DELETE" data-token="{{csrf_token()}}" data-toggle="tooltip" title="{{__('labels.backend.delete')}}"><i class="fas fa-trash-alt"></i></a>
                                            @endcan--}}
                                            <a href="{{ route("backend.$module_name.index") }}" class="btn btn-warning" data-toggle="tooltip" title="{{__('labels.backend.cancel')}}"><i class="fas fa-reply"></i> Cancel</a>
                                        </div>
                                    </div>
                                </div>

                                {{ html()->form()->close() }}

                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col">
                                <small class="float-right text-muted">
                                    Updated: {{$$module_name_singular->updated_at->diffForHumans()}},
                                    Created at: {{$$module_name_singular->created_at->isoFormat('LLLL')}}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth')
    </div>

@stop
