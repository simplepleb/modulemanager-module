@extends('layouts.app')

@section('title') Module Builder @endsection

{{--@section('breadcrumbs')
    <x-backend-breadcrumbs>
        <x-backend-breadcrumb-item route='{{route("backend.$module_name.index")}}' icon='{{ $module_icon }}' >
            {{ $module_title }}
        </x-backend-breadcrumb-item>
        <x-backend-breadcrumb-item type="active">{{ __($module_action) }}</x-backend-breadcrumb-item>
    </x-backend-breadcrumbs>
@endsection--}}

@section('content')
    @include('partials.header_space', [
'title' => __('Client List') ,
'description' => __('Manage all of your clients from here'),
'class' => 'col-lg-12'
])
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h2 class="h3 mb-0">{{ $module_title }} <small class="text-muted">{{ __($module_action) }}</small></h2>
                                {{--<div class="small text-muted">
                                    @lang(":module_name Management Dashboard", ['module_name'=>Str::title($module_name)])
                                </div>--}}
                            </div>
                            <div class="col-auto">
                                <a href="{{ route("backend.$module_name.index") }}" class="btn btn-secondary btn-sm ml-1" data-toggle="tooltip" title="{{ $module_title }} List"><i class="fas fa-list-ul"></i> List</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        {{ html()->form('POST', route("backend.module_builder.builder.store"))->class('form')->open() }}
                        <div class="row mt-4">
                            <div class="col">
                                <div class="card">
                                    <div class="card-header"><i class="fa fa-info"></i> Module Details</div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label class="form-label">Module Name</label>
                                                    <input class="form-control" type="text" name="module_name">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label class="form-label">Description</label>
                                                    <input class="form-control" type="text" name="module_description">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col">

                                <div class="card">
                                    <div class="card-header"><i class="fa fa-align-justify"></i> Module Database</div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label class="form-label">Table Name</label>
                                                    <input class="form-control" type="text" name="table_name">
                                                </div>
                                            </div>
                                        </div>


                                        <table id="fields_tbl" class="table table-responsive-sm table-striped">
                                            <thead>
                                            <tr>
                                                <th>Field Name</th>
                                                <th>Type</th>
                                                <th>Size</th>
                                                <th>Nullable</th>
                                                <th>Fillable</th>
                                                <th>DataTable</th>
                                                <th>Default</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr id="1">
                                                <td><input name="field_name[]" type="text" class="form-control"></td>
                                                <td>
                                                    <select name="field_type[]" class="form-control">
                                                        {{--<option value="text">String</option>
                                                        <option value="text">Text</option>
                                                        <option value="text">tinyInteger</option>
                                                        <option value="text">Integer</option>
                                                        <option value="text">DateTime</option>--}}
                                                        <option title="Only Once Per Table" value="text">bigIncrements</option>
                                                        <option value="bigInteger">bigInteger</option>
                                                        <option value="binary">binary</option>
                                                        <option value="boolean">boolean</option>
                                                        <option value="char">char</option>
                                                        <option value="dateTimeTz">dateTimeTz</option>
                                                        <option value="dateTime">dateTime</option>
                                                        <option value="date">date</option>
                                                        <option value="decimal">decimal</option>
                                                        <option value="double">double</option>
                                                        <option value="enum">enum</option>
                                                        <option value="float">float</option>
                                                        <option value="foreignId">foreignId</option>
                                                        <option value="geometryCollection">geometryCollection</option>
                                                        <option value="geometry">geometry</option>
                                                        <option value="increments">increments</option>
                                                        <option value="integer">integer</option>
                                                        <option value="ipAddress">ipAddress</option>
                                                        <option value="json">json</option>
                                                        <option value="jsonb">jsonb</option>
                                                        <option value="lineString">lineString</option>
                                                        <option value="longText">longText</option>
                                                        <option value="macAddress">macAddress</option>
                                                        <option value="mediumIncrements">mediumIncrements</option>
                                                        <option value="mediumInteger">mediumInteger</option>
                                                        <option value="mediumText">mediumText</option>
                                                        {{--<option value="text">morphs</option>
                                                        <option value="text">multiLineString</option>
                                                        <option value="text">multiPoint</option>
                                                        <option value="text">multiPolygon</option>
                                                        <option value="text">nullableMorphs</option>
                                                        <option value="text">nullableTimestamps</option>
                                                        <option value="text">nullableUuidMorphs</option>
                                                        <option value="text">point</option>
                                                        <option value="text">polygon</option>--}}
                                                        {{--<option value="text">rememberToken</option>--}}
                                                        {{--<option value="text">set</option>--}}
                                                        <option value="smallIncrements">smallIncrements</option>
                                                        <option value="smallInteger">smallInteger</option>
                                                        <option value="softDeletesTz">softDeletesTz</option>
                                                        <option value="softDeletes">softDeletes</option>
                                                        <option value="string">string</option>
                                                        <option value="text">text</option>
                                                        <option value="timeTz">timeTz</option>
                                                        <option value="time">time</option>
                                                        <option value="timestampTz">timestampTz</option>
                                                        <option value="timestamp">timestamp</option>
                                                        <option value="timestampsTz">timestampsTz</option>
                                                        <option value="tinyIncrements">tinyIncrements</option>
                                                        <option value="tinyInteger">tinyInteger</option>
                                                        <option value="unsignedBigInteger">unsignedBigInteger</option>
                                                        <option value="unsignedDecimal">unsignedDecimal</option>
                                                        <option value="unsignedInteger">unsignedInteger</option>
                                                        <option value="unsignedMediumInteger">unsignedMediumInteger</option>
                                                        <option value="unsignedSmallInteger">unsignedSmallInteger</option>
                                                        <option value="unsignedTinyInteger">unsignedTinyInteger</option>
                                                        <option value="uuidMorphs">uuidMorphs</option>
                                                        <option value="uuid">uuid</option>
                                                        <option value="year">year</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input name="field_size[]" type="number" class="form-control" >
                                                </td>
                                                <td>
                                                    <input name="field_nullable[]" value="1" type="checkbox" class="form-control">
                                                </td>
                                                <td>
                                                    <input name="field_fillable[]" value="1" type="checkbox" class="form-control">
                                                </td>
                                                <td>
                                                    <input name="field_datatable[]" value="1" type="checkbox" class="form-control">
                                                </td>
                                                <td><input name="field_default[]" type="text" class="form-control"></td>
                                            </tr>

                                            </tbody>
                                        </table>
                                        <div class="pull-right">
                                            <button id="add_field" class="btn btn-sm btn-success float-right mt-2">Add Field</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{ html()->button($text = "<i class='fas fa-plus-circle'></i> " . ucfirst($module_action) . "", $type = 'submit')->class('btn btn-success') }}
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="float-right">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-warning" onclick="history.back(-1)"><i class="fas fa-reply"></i> Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        {{ html()->form()->close() }}
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth')
    </div>

@stop
@push('js')
    <script type="text/javascript">
        $(document).ready(function() {

            let cnt = 1;
            $("#add_field").click(function(e) {

                e.preventDefault();
                cnt++
                $('#fields_tbl tbody>tr:last').clone(true).insertAfter('#fields_tbl tbody>tr:last');
                $('#fields_tbl tbody>tr:last input').val('');
                $('#fields_tbl tbody>tr:last').attr('id', cnt);
                // $("#fields_tbl tbody>tr:last input[name='field_size[]']").val('999');
                /*$("#fields_tbl tbody>tr:last input[name='field_name[]']").attr('name', 'field_name['+cnt+']').val('');
                $("#fields_tbl tbody>tr:last input[name='field_type[]']").attr('name', 'field_type['+cnt+']').val('');
                $("#fields_tbl tbody>tr:last input[name='field_size[]']").attr('name', 'field_size['+cnt+']').val('');
                $("#fields_tbl tbody>tr:last input[name='field_nullable[]']").attr('name', 'field_nullable['+cnt+']').val('');
                $("#fields_tbl tbody>tr:last input[name='field_fillable[]']").attr('name', 'field_fillable['+cnt+']').val('');
                $("#fields_tbl tbody>tr:last input[name='field_datatable[]']").attr('name', 'field_datatable['+cnt+']').val('');
                $("#fields_tbl tbody>tr:last input[name='field_default[]']").attr('name', 'field_default['+cnt+']').val('');*/
                // $("#fields_tbl tbody>tr:last").each(function() {this.reset();});
                //$('tbody>tr:last')
                return false;
            });
        });
    </script>
@endpush
