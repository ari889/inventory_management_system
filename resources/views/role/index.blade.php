@extends('layouts.app')

@section('title')
    {{$page_title}}
@endsection

@push('stylesheets')

@endpush

@section('content')
<div class="dt-content">

    <!-- Grid -->
    <div class="row">
        <div class="col-xl-12 pb-3">
            <ol class="breadcrumb bg-white">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="active breadcrumb-item">{{ $sub_title }}</li>
              </ol>
        </div>

        <!-- Grid Item -->
        <div class="col-xl-12">

            <!-- Entry Header -->
            <div class="dt-entry__header">

                <!-- Entry Heading -->
                <div class="dt-entry__heading">
                    <h2 class="dt-entry__title mb-0 text-primary"><i class="{{ $page_icon }} mr-2"></i>{{ $sub_title }}</h2>
                </div>
                <!-- /entry heading -->

                <a href="{{ route('role.create') }}" style="font-size: 1.4rem" class="btn btn-primary btn-sm"><i class="fas fa-plus-square mr-2"></i>Add New</a>

            </div>
            <!-- /entry header -->

            <!-- Card -->
            <div class="dt-card">

                <!-- Card Body -->
                <div class="dt-card__body">

                    <form id="form-filter">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="role_name">Role Name</label>
                                <input type="text" name="role_name" id="role_name" class="form-control" placeholder="Enter role name">
                            </div>
                            <div class="form-group col-md-8">
                                <button type="button" class="btn btn-danger btn-sm float-right" id="btn-reset" data-toggle="tooltip" data-placement="top" data-original-title="Reset Data"><i class="fas fa-redo-alt"></i></button>
                                <button type="button" class="btn btn-primary btn-sm float-right mr-2" id="btn-filter" data-toggle="tooltip" data-placement="top" data-original-title="Finter Data"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>

                    <!-- Tables -->
                    {{-- <div class="table-responsive"> --}}

                        <table id="dataTable" class="table table-striped table-bordered table-hover">
                            <thead class="bg-primary">
                                <tr>
                                    <th>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="select_all" onchange="select_all()">
                                            <label class="custom-control-label" for="select_all"></label>
                                        </div>
                                    </th>
                                    <th>SL</th>
                                    <th>Role Name</th>
                                    <th>Deletable</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>

                    {{-- </div> --}}
                    <!-- /tables -->

                </div>
                <!-- /card body -->

            </div>
            <!-- /card -->

        </div>
        <!-- /grid item -->

    </div>
    <!-- /grid -->

</div>
@endsection

@push('scripts')
<script>
var table;
$(document).ready(function(){
    table = $('#dataTable').DataTable({
            "processing": true, //Feature control the processing indicator
            "serverSide": true, //Feature control DataTable server side processing mode
            "order": [], //Initial no order
            "responsive": true, //Make table responsive in mobile device
            "bInfo": true, //TO show the total number of data
            "bFilter": false, //For datatable default search box show/hide
            "lengthMenu": [
                [5, 10, 15, 25, 50, 100, 1000, 10000, -1],
                [5, 10, 15, 25, 50, 100, 1000, 10000, "All"]
            ],
            "pageLength": 25, //number of data show per page
            "language": {
                processing: `<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i>`,
                emptyTable: '<strong class="text-danger">No Data Found</strong>',
                infoEmpty: '',
                zeroRecords: '<strong class="text-danger">No Data Found</strong>'
            },
            "ajax": {
                "url": "{{route('role.datatable.data')}}",
                "type": "POST",
                "data": function (data) {
                    data.role_name = $('#form-filter #role_name').val();
                    data._token = _token;
                }
            },
            "columnDefs": [{
                    "targets": [0,4],
                    "orderable": false,
                    "className": "text-center"
                },
                {
                    "targets": [1,3],
                    "className": "text-center"
                }
            ],
            "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7' <'float-right'p>>>",

            "buttons": [
                {
                    'extend' : 'colvis',
                    'className' : 'btn btn-secondary btn-sm text-white',
                    'text' : 'Column'
                },
                {
                    "extend": 'print',
                    "text" : "Print",
                    'className' : 'btn btn-secondary btn-sm text-white',
                    "title": "Role List",
                    "orientation": "landscape", //portrait
                    "pageSize": "A4", //A3,A5,A6,legal,letter
                    "exportOptions": {
                        columns: function (index, data, node) {
                            return table.column(index).visible();
                        }
                    },
                    customize: function (win) {
                        $(win.document.body).addClass('bg-white');
                    },
                },
                {
                    "extend": 'csv',
                    "text" : "CSV",
                    'className' : 'btn btn-secondary btn-sm text-white',
                    "title": "Role List",
                    "filename": "role-list",
                    "exportOptions": {
                        columns: function (index, data, node) {
                            return table.column(index).visible();
                        }
                    }
                },
                {
                    "extend": 'excel',
                    "text" : "Excel",
                    'className' : 'btn btn-secondary btn-sm text-white',
                    "title": "Role List",
                    "filename": "role-list",
                    "exportOptions": {
                        columns: function (index, data, node) {
                            return table.column(index).visible();
                        }
                    }
                },
                {
                    "extend": 'pdf',
                    "text" : "PDF",
                    'className' : 'btn btn-secondary btn-sm text-white',
                    "title": "Role List",
                    "filename": "role-list",
                    "orientation": "landscape", //portrait
                    "pageSize": "A4", //A3,A5,A6,legal,letter
                    "exportOptions": {
                        columns: [1,2,3]
                    }
                },
                {
                    'className' : 'btn btn-danger btn-sm delete_btn text-white',
                    'text' : 'Delete',
                    action : function(e, dt, node, config){
                        multi_delete();
                    }
                }
            ],
        });

        $('#btn-filter').click(function () {
            table.ajax.reload();
        });

        $('#btn-reset').click(function () {
            $('#form-filter')[0].reset();
            table.ajax.reload();
        });

        $(document).on('click', '.delete_data', function () {
            let id   = $(this).data('id');
            let name = $(this).data('name');
            let row  = table.row($(this).parent('tr'));
            let url  = "{{ route('role.delete') }}";
            delete_data(id, url, table, row, name);
        });

        function multi_delete(){
            let ids = [];
            let rows;
            $('.select_data:checked').each(function(){
                ids.push($(this).val());
                rows = table.rows($('.select_data:checked').parents('tr'));
            });
            if(ids.length == 0){
                Swal.fire({
                    type:'error',
                    title:'Error',
                    text:'Please checked at least one row of table!',
                    icon: 'warning',
                });
            }else{
                let url = "{{route('role.bulk.delete')}}";
                bulk_delete(ids,url,table,rows);
            }
        }

});
</script>
@endpush
