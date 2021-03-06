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
                @if (permission('unit-add'))
                    <button type="button" style="font-size: 1.4rem" class="btn btn-primary btn-sm" onclick="showFormModal('Add Unit', 'Save')"><i class="fas fa-plus-square mr-2"></i>Add New</button>
                @endif

            </div>
            <!-- /entry header -->

            <!-- Card -->
            <div class="dt-card">

                <!-- Card Body -->
                <div class="dt-card__body">

                    <form id="form-filter">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="unit_name">Unit Name</label>
                                <input type="text" name="unit_name" id="unit_name" class="form-control" placeholder="Enter unit Name">
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
                                    @if (permission('unit-bulk-delete'))
                                    <th>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="select_all" onchange="select_all()">
                                            <label class="custom-control-label" for="select_all"></label>
                                        </div>
                                    </th>
                                    @endif
                                    <th>SL</th>
                                    <th>Unit Name</th>
                                    <th>Unit Code</th>
                                    <th>Base Unit</th>
                                    <th>Operator</th>
                                    <th>Operation Value</th>
                                    <th>Status</th>
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
@include('system::unit.modal')
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
                "url": "{{route('unit.datatable.data')}}",
                "type": "POST",
                "data": function (data) {
                    data.unit_name = $('#form-filter #unit_name').val();
                    data._token = _token;
                }
            },
            "columnDefs": [{
                    @if (permission('unit-bulk-delete'))
                    "targets": [0,8],
                    @else
                    "targets": [7],
                    @endif
                    "orderable": false,
                    "className": "text-center"
                },
                {
                    @if (permission('unit-bulk-delete'))
                    "targets": [1,3,5,6,7],
                    @else
                    "targets": [0,2,4,5,6,7],
                    @endif
                    "className": "text-center"
                }
            ],
            "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7' <'float-right'p>>>",

            "buttons": [
                @if (permission('unit-report'))
                {
                    'extend' : 'colvis',
                    'className' : 'btn btn-secondary btn-sm text-white',
                    'text' : 'Column'
                },
                {
                    "extend": 'print',
                    "text" : "Print",
                    'className' : 'btn btn-secondary btn-sm text-white',
                    "title": "Unit List",
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
                    "title": "Unit List",
                    "filename": "unit-list",
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
                    "title": "Unit List",
                    "filename": "unit-list",
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
                    "title": "Unit List",
                    "filename": "unit-list",
                    "orientation": "landscape", //portrait
                    "pageSize": "A4", //A3,A5,A6,legal,letter
                    "exportOptions": {
                        columns: [1,2,3]
                    }
                },
                @endif
                @if (permission('unit-report'))
                {
                    'className' : 'btn btn-danger btn-sm delete_btn text-white',
                    'text' : 'Delete',
                    action : function(e, dt, node, config){
                        multi_delete();
                    }
                }
                @endif
            ],
        });

        $('#btn-filter').click(function () {
            table.ajax.reload();
        });

        $('#btn-reset').click(function () {
            $('#form-filter')[0].reset();
            table.ajax.reload();
        });

        $(document).on('click', '#save-btn', function () {
            let form = document.getElementById('store_or_update_form');
            let formData = new FormData(form);
            formData.append('_token', _token);
            let url = "{{route('unit.store.or.update')}}";
            let id = $('#update_id').val();
            let method;
            if (id) {
                method = 'update';
            } else {
                method = 'add';
            }
            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function(){
                    $('#save-btn').addClass('kt-spinner kt-spinner-md kt-spinner--light')
                },
                complete: function(){
                    $('#save-btn').removeClass('kt-spinner kt-spinner-md kt-spinner--light')
                },
                success: function (data) {
                    $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
                    $('#store_or_update_form').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            $('#store_or_update_form input#' + key).addClass('is-invalid');
                            $('#store_or_update_form textarea#' + key).addClass('is-invalid');
                            $('#store_or_update_form select#' + key).parent().addClass('is-invalid');
                            $('#store_or_update_form #' + key).parent().append(
                                '<small class="error text-danger">' + value + '</small>');
                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') {
                            base_unit();
                            if (method == 'update') {
                                table.ajax.reload(null, false);
                            } else {
                                table.ajax.reload();
                            }
                            $('#store_or_update_modal').modal('hide');
                        }
                    }

                },
                error: function (xhr, ajaxOption, thrownError) {
                    console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                }
            });
        });

        $(document).on('click', '.edit_data', function () {
            let id = $(this).data('id');
            $('#store_or_update_form')[0].reset();
            $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
            $('#store_or_update_form').find('.error').remove();
            if (id) {
                $.ajax({
                    url: "{{route('unit.edit')}}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: _token
                    },
                    dataType: "JSON",
                    success: function (data) {
                        $('#store_or_update_form #update_id').val(data.id);
                        $('#store_or_update_form #unit_name').val(data.unit_name);
                        $('#store_or_update_form #unit_code').val(data.unit_code);
                        $('#store_or_update_form #base_unit').val(data.base_unit);
                        $('#store_or_update_form #operator').val(data.operator);
                        $('#store_or_update_form #operation_value').val(data.operation_value);
                        $('#store_or_update_form .selectpicker').selectpicker('refresh');

                        $('#store_or_update_modal').modal({
                            keyboard: false,
                            backdrop: 'static',
                        });
                        $('#store_or_update_modal .modal-title').html(
                            '<i class="fas fa-edit"></i> <span>Edit ' + data.unit_name + '</span>');
                        $('#store_or_update_modal #save-btn').text('Update');

                    },
                    error: function (xhr, ajaxOption, thrownError) {
                        console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                    }
                });
            }
        });

        $(document).on('click', '.delete_data', function () {
            let id   = $(this).data('id');
            let name = $(this).data('name');
            let row  = table.row($(this).parent('tr'));
            let url  = "{{ route('unit.delete') }}";
            Swal.fire({
                title: 'Are you sure to delete ' + name + ' data?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            id: id,
                            _token: _token
                        },
                        dataType: "JSON",
                    }).done(function (response) {
                        if (response.status == "success") {
                            Swal.fire("Deleted", response.message, "success").then(function () {
                                table.row(row).remove().draw(false);

                            });
                            base_unit();
                        }

                        if (response.status == "error") {
                            Swal.fire('Oops...', response.message, "error");
                        }
                    }).fail(function () {
                        Swal.fire('Oops...', "Somthing went wrong with ajax!", "error");
                    });
                }
            });
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
                let url = "{{route('unit.bulk.delete')}}";
                Swal.fire({
                    title: 'Are you sure to delete all checked data?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete all!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: url,
                            type: "POST",
                            data: {
                                ids: ids,
                                _token: _token
                            },
                            dataType: "JSON",
                        }).done(function (response) {
                            if (response.status == "success") {
                                Swal.fire("Deleted", response.message, "success").then(function () {

                                    table.rows(rows).remove().draw(false);
                                    $('#select_all').prop('checked',false);
                                    $('.delete_btn').addClass('d-none')
                                });
                                base_unit();
                            }
                        }).fail(function () {
                            Swal.fire('Oops...', "Somthing went wrong with ajax!", "error");
                        });
                    }
                });
            }
        }

        $(document).on('click', '.change_status', function () {
            let id     = $(this).data('id');
            let status = $(this).data('status');
            let name   = $(this).data('name');
            let row    = table.row($(this).parent('tr'));
            let url    = "{{ route('unit.change.status') }}";

            change_status(id, status, name, table, url);
        });

        base_unit();
        function base_unit(){
            $.ajax({
                url: "{{route('unit.base.unit')}}",
                type: "POST",
                data: {_token: _token},
                success: function (data) {
                    if(data){
                        $('#store_or_update_form #base_unit').html('');
                        $('#store_or_update_form #base_unit').html(data);
                    }else{
                        $('#store_or_update_form #base_unit').html('');
                    }
                    $('#store_or_update_form .selectpicker').selectpicker('refresh');
                },
                error: function (xhr, ajaxOption, thrownError) {
                    console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                }
            });
        }

});
</script>
@endpush
