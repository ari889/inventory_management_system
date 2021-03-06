@extends('layouts.app')

@section('title')
    {{$page_title}}
@endsection

@push('stylesheets')
<link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css">
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
                @if (permission('attendance-add'))
                    <button type="button" style="font-size: 1.4rem" class="btn btn-primary btn-sm" onclick="showFormModal('Add New Attendance', 'Save')"><i class="fas fa-plus-square mr-2"></i>Add New</button>
                @endif

            </div>
            <!-- /entry header -->

            <!-- Card -->
            <div class="dt-card">

                <!-- Card Body -->
                <div class="dt-card__body">

                    <form id="form-filter">
                        <div class="row">
                            <x-form.selectbox labelName="Employee" name="employee_id" col="col-md-3" class="selectpicker">
                                @if (!$employees->isEmpty())
                                @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endforeach
                                @endif
                            </x-form.selectbox>
                            <x-form.textbox labelName="From Date" name="from_date" required="required" col="col-md-3" class="date" />
                            <x-form.textbox labelName="To Date" name="to_date" required="required" col="col-md-3" class="date" />
                            <div class="form-group col-md-3 pt-24">
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
                                    @if (permission('attendance-bulk-delete'))
                                    <th>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="select_all" onchange="select_all()">
                                            <label class="custom-control-label" for="select_all"></label>
                                        </div>
                                    </th>
                                    @endif
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Date</th>
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
@include('hrm::attendance.modal')
@endsection

@push('scripts')
<script src="js/moment.min.js"></script>
<script src="js/bootstrap-datetimepicker.min.js"></script>
<script>
var table;
$(document).ready(function(){

    $('.time').datetimepicker({format: 'hh:mm A', ignoreReadonly:true});
    $('.date').datetimepicker({format:'YYYY-MM-DD',ignoreReadonly:true});

    table = $('#dataTable').DataTable({
            "processing": true, //Feature control the processing indicator
            "serverSide": true, //Feature control DataTable server side processing mode
            "order": [], //Initial no order
            "responsive": true, //Make table responsive in mobile device
            "bInfo": true, //TO show the total number of data
            "bFilter": false, //For datatable default search box show/hide
            "lengthDepartment": [
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
                "url": "{{route('attendance.datatable.data')}}",
                "type": "POST",
                "data": function (data) {
                    data.employee_id = $('#form-filter #employee_id').val();
                    data.from_date = $('#form-filter #from_date').val();
                    data.to_date = $('#form-filter #to_date').val();
                    data._token = _token;
                }
            },
            "columnDefs": [{
                    @if (permission('attendance-bulk-delete'))
                    "targets": [0,7],
                    @else
                    "targets": [6],
                    @endif
                    "orderable": false,
                    "className": "text-center"
                },
                {
                    @if (permission('attendance-bulk-delete'))
                    "targets": [1,3,4,5,6],
                    @else
                    "targets": [0,2,3,4,5],
                    @endif
                    "className": "text-center"
                }
            ],
            "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7' <'float-right'p>>>",

            "buttons": [
                @if (permission('attendance-report'))
                {
                    'extend' : 'colvis',
                    'className' : 'btn btn-secondary btn-sm text-white',
                    'text' : 'Column'
                },
                {
                    "extend": 'print',
                    "text" : "Print",
                    'className' : 'btn btn-secondary btn-sm text-white',
                    "title": "Attendance List",
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
                    "title": "Attendance List",
                    "filename": "attendance-list",
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
                    "title": "Attendance List",
                    "filename": "attendance-list",
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
                    "title": "Attendance List",
                    "filename": "attendance-list",
                    "orientation": "landscape", //portrait
                    "pageSize": "A4", //A3,A5,A6,legal,letter
                    "exportOptions": {
                        columns: [1,2,3]
                    }
                },
                @endif
                @if (permission('attendance-report'))
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
            $('#form-filter .selectpicker').selectpicker('refresh');
            table.ajax.reload();
        });

        $(document).on('click', '#save-btn', function () {
            let form = document.getElementById('store_or_update_form');
            let formData = new FormData(form);
            formData.append('_token', _token);
            let url = "{{route('attendance.store.or.update')}}";
            let id = $('#update_id').val();
            let method;
            if (id) {
                method = 'update';
            } else {
                method = 'add';
            }
            store_or_update_data(table, method, url, formData);
        });

        $(document).on('click', '.edit_data', function () {
            let id = $(this).data('id');
            $('#store_or_update_form')[0].reset();
            $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
            $('#store_or_update_form').find('.error').remove();
            if (id) {
                $.ajax({
                    url: "{{route('attendance.edit')}}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: _token
                    },
                    dataType: "JSON",
                    success: function (data) {
                        $('#store_or_update_form #update_id').val(data.id);
                        $('#store_or_update_form #employee_id').val(data.employee_id);
                        $('#store_or_update_form #date').val(data.date);
                        $('#store_or_update_form #check_in').val(data.check_in);
                        $('#store_or_update_form #check_out').val(data.check_out);
                        $('#store_or_update_form #check_out').val(data.check_out);
                        $('#store_or_update_form .selectpicker').selectpicker('refresh');

                        $('#store_or_update_modal').modal({
                            keyboard: false,
                            backdrop: 'static',
                        });
                        $('#store_or_update_modal .modal-title').html(
                            '<i class="fas fa-edit"></i> <span>Edit ' + data.name + '</span>');
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
            let url  = "{{ route('attendance.delete') }}";
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
                let url = "{{route('attendance.bulk.delete')}}";
                bulk_delete(ids,url,table,rows);
            }
        }

});
</script>
@endpush
