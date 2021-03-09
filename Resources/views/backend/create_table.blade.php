
<table id="example" class="display" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th></th>
        <th>First name</th>
        <th>Last name</th>
        <th>Position</th>
        <th>Office</th>
        <th width="18%">Start date</th>
        <th>Salary</th>
    </tr>
    </thead>
</table>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.1/css/select.dataTables.min.css">
<link rel="stylesheet" href="{{asset('css/editor.dataTables.css')}}">


<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
<script src="{{asset('/js/dataTables.editor.js')}}"></script>

<script type="text/javascript">
    var editor; // use a global for the submit and return data rendering in the examples

    $(document).ready(function() {
        editor = new $.fn.dataTable.Editor( {
            ajax: "/staff.json",
            table: "#example",
            fields: [ {
                label: "First name:",
                name: "first_name"
            }, {
                label: "Last name:",
                name: "last_name"
            }, {
                label: "Position:",
                name: "position"
            }, {
                label: "Office:",
                name: "office"
            }, {
                label: "Extension:",
                name: "extn"
            }, {
                label: "Start date:",
                name: "start_date",
                type: "datetime"
            }, {
                label: "Salary:",
                name: "salary"
            }
            ]
        } );

        $('#example').on( 'click', 'tbody td:not(:first-child)', function (e) {
            editor.inline( this, {
                onBlur: 'submit'
            } );
        } );

        $('#example').DataTable( {
            dom: "Bfrtip",
            ajax: "../php/staff.php",
            columns: [
                {
                    data: null,
                    defaultContent: '',
                    className: 'select-checkbox',
                    orderable: false
                },
                { data: "first_name" },
                { data: "last_name" },
                { data: "position" },
                { data: "office" },
                { data: "start_date" },
                { data: "salary", render: $.fn.dataTable.render.number( ',', '.', 0, '$' ) }
            ],
            order: [ 1, 'asc' ],
            select: {
                style:    'os',
                selector: 'td:first-child'
            },
            buttons: [
                { extend: "create", editor: editor },
                { extend: "edit",   editor: editor },
                { extend: "remove", editor: editor }
            ]
        } );
    } );
</script>
