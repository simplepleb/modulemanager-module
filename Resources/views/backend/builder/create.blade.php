@extends('backend.layouts.app')

@section('title') Module Builder @endsection

@section('breadcrumbs')
    <x-backend-breadcrumbs>
        <x-backend-breadcrumb-item route='{{route("backend.$module_name.index")}}' icon='{{ $module_icon }}' >
            {{ $module_title }}
        </x-backend-breadcrumb-item>
        <x-backend-breadcrumb-item type="active">{{ __($module_action) }}</x-backend-breadcrumb-item>
    </x-backend-breadcrumbs>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-8">
                    <h4 class="card-title mb-0">
                        <i class="{{ $module_icon }}"></i> {{ $module_title }} <small class="text-muted">{{ __($module_action) }}</small>
                    </h4>
                    <div class="small text-muted">
                        @lang(":module_name Management Dashboard", ['module_name'=>Str::title($module_name)])
                    </div>
                </div>
                <!--/.col-->
                <div class="col-4">
                    <div class="btn-toolbar float-right" role="toolbar" aria-label="Toolbar with button groups">
                        <a href="{{ route("backend.$module_name.index") }}" class="btn btn-secondary btn-sm ml-1" data-toggle="tooltip" title="{{ $module_title }} List"><i class="fas fa-list-ul"></i> List</a>
                    </div>
                </div>
                <!--/.col-->
            </div>
            <!--/.row-->

            <hr>
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
                                    <th>Default</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><input name="field_name[]" type="text" class="form-control"></td>
                                    <td>
                                        <select name="field_type[]" class="form-control">
                                            {{--<option value="text">String</option>
                                            <option value="text">Text</option>
                                            <option value="text">tinyInteger</option>
                                            <option value="text">Integer</option>
                                            <option value="text">DateTime</option>--}}
                                            <option title="Only Once Per Table" value="text">bigIncrements</option>
                                            <option value="text">bigInteger</option>
                                            <option value="text">binary</option>
                                            <option value="text">boolean</option>
                                            <option value="text">char</option>
                                            <option value="text">dateTimeTz</option>
                                            <option value="text">dateTime</option>
                                            <option value="text">date</option>
                                            <option value="text">decimal</option>
                                            <option value="text">double</option>
                                            <option value="text">enum</option>
                                            <option value="text">float</option>
                                            <option value="text">foreignId</option>
                                            <option value="text">geometryCollection</option>
                                            <option value="text">geometry</option>
                                            <option value="text">id</option>
                                            <option value="text">increments</option>
                                            <option value="text">integer</option>
                                            <option value="text">ipAddress</option>
                                            <option value="text">json</option>
                                            <option value="text">jsonb</option>
                                            <option value="text">lineString</option>
                                            <option value="text">longText</option>
                                            <option value="text">macAddress</option>
                                            <option value="text">mediumIncrements</option>
                                            <option value="text">mediumInteger</option>
                                            <option value="text">mediumText</option>
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
                                            <option value="text">smallIncrements</option>
                                            <option value="text">smallInteger</option>
                                            <option value="text">softDeletesTz</option>
                                            <option value="text">softDeletes</option>
                                            <option value="text">string</option>
                                            <option value="text">text</option>
                                            <option value="text">timeTz</option>
                                            <option value="text">time</option>
                                            <option value="text">timestampTz</option>
                                            <option value="text">timestamp</option>
                                            <option value="text">timestampsTz</option>
                                            <option value="text">timestamps</option>
                                            <option value="text">tinyIncrements</option>
                                            <option value="text">tinyInteger</option>
                                            <option value="text">unsignedBigInteger</option>
                                            <option value="text">unsignedDecimal</option>
                                            <option value="text">unsignedInteger</option>
                                            <option value="text">unsignedMediumInteger</option>
                                            <option value="text">unsignedSmallInteger</option>
                                            <option value="text">unsignedTinyInteger</option>
                                            <option value="text">uuidMorphs</option>
                                            <option value="text">uuid</option>
                                            <option value="text">year</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="field_size[]">
                                    </td>
                                    <td>
                                        <input name="field_nullable[]" value="1" type="checkbox" class="form-control">
                                    </td>
                                    <td><input name="field_default[]" type="text" class="form-control"></td>
                                </tr>

                                </tbody>
                            </table>
                            <div class="pull-right">
                                <button id="add" class="btn btn-sm btn-success float-right">Add Field</button>
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

@stop
@push('after-scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $("#add").click(function() {
                $('#fields_tbl tbody>tr:last').clone(true).insertAfter('#fields_tbl tbody>tr:last');
                $('#fields_tbl tbody>tr:last input').val('');
                /*$("#fields_tbl tbody>tr:last").each(function() {this.reset();});*/
                return false;
            });
        });
    </script>
@endpush
