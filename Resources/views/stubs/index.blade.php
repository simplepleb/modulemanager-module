@extends('backend.layouts.app')
@section('content')

    <h1>
        %%moduleName%% <span>SimplePleb</span>
    </h1>
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="pleb-table" width="100%">
        <thead>
        <tr>
            %%field_headers%%
        </tr>
        </thead>
    </table>


@stop

@push ('after-styles')


    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jqc-1.12.4/moment-2.18.1/dt-1.10.23/b-1.6.5/sl-1.3.1/datatables.min.css">
    <link rel="stylesheet" type="text/css" href="/editor/css/generator-base.css">
    <link rel="stylesheet" type="text/css" href="/editor/css/editor.dataTables.min.css">


    <!-- DataTables Core and Extensions -->
    {{--<link rel="stylesheet" href="{{ asset('vendor/datatable/datatables.min.css') }}">--}}

@endpush

@push ('after-scripts')


    <script type="text/javascript" charset="utf-8" src="https://cdn.datatables.net/v/dt/jqc-1.12.4/moment-2.18.1/dt-1.10.23/b-1.6.5/sl-1.3.1/datatables.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/vendor/editor/js/dataTables.editor.min.js?id={{ time() }}"></script>
    {{--<script type="text/javascript" charset="utf-8" src="/editor/js/table.crypto_currencies.js?id={{ time() }}"></script>--}}
    <script type="text/javascript">

        (function($){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).ready(function() {
                var editor = new $.fn.dataTable.Editor( {
                    // ajax: '/admin/cryptocurrencies/editor',
                    "ajax": {
                        "url": "/%%moduleNameLower%%/editor",
                        "type": "POST"
                    },
                    table: '#pleb-table',
                    idSrc: 'id',
                    title:  'Edit',
                    fields: %%json_fields%%
                } );

                editor.on('open', function (e, mode, action) {
                    if ( action === 'edit' ) {
                        editor.title('Edit Item');
                    }
                    if ( action === 'create' ) {
                        editor.title('Add Item');
                    }

                });

                var table = $('#pleb-table').DataTable( {
                    dom: 'Bfrtip',
                    "ajax": {
                        "url": "/%%moduleNameLower%%/dtable",
                        "type": "GET"
                    },
                    columns: %%json_columns%%,
                    select: true,
                    lengthChange: false,
                    buttons: [
                        { extend: 'create', editor: editor },
                        { extend: 'edit',   editor: editor },
                        { extend: 'remove', editor: editor }
                    ]
                } );
            } );

        }(jQuery));

    </script>
@endpush
