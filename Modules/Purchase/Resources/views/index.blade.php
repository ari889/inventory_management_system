@extends('layouts.app')

@section('title')
    {{$page_title}}
@endsection

@push('stylesheets')
<link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" type="text/css" />
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
                @if (permission('purchase-add'))
                    <a href="{{ route('purchase.add') }}" style="font-size: 1.4rem" class="btn btn-primary btn-sm"><i class="fas fa-plus-square mr-2"></i>Add New</a>
                @endif

            </div>
            <!-- /entry header -->

            <!-- Card -->
            <div class="dt-card">

                <!-- Card Body -->
                <div class="dt-card__body">

                    <form id="form-filter">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="purchase_no">Purchase No</label>
                                <input type="text" name="purchase_no" id="purchase_no" class="form-control" placeholder="Enter purchase number">
                            </div>
                            <x-form.selectbox labelName="Supplier" name="supplier_id" col="col-md-3" class="selectpicker">
                                @if(!$suppliers->isEmpty())
                                    @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name.' - '.$supplier->phone }}</option>
                                    @endforeach
                                @endif
                            </x-form.selectbox>
                            <div class="form-group col-md-3">
                                <label for="from_date">From Date</label>
                                <input type="text" name="from_date" id="from_date" class="form-control date" placeholder="Enter from date">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="to_date">To Date</label>
                                <input type="text" name="to_date" id="to_date" class="form-control date" placeholder="Enter to date">
                            </div>
                            <x-form.selectbox labelName="Purchase Status" name="purchase_status" col="col-md-3" class="selectpicker">
                                @foreach(PURCHASE_STATUS as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </x-form.selectbox>
                            <x-form.selectbox labelName="Payment Status" name="payment_status" col="col-md-3" class="selectpicker">
                                @foreach(PAYMENT_STATUS as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </x-form.selectbox>
                            <div class="form-group col-md-12">
                                <button type="button" class="btn btn-danger btn-sm float-right" id="btn-reset" data-toggle="tooltip" data-placement="top" data-original-title="Reset Data"><i class="fas fa-redo-alt"></i></button>
                                <button type="button" class="btn btn-primary btn-sm float-right mr-2" id="btn-filter" data-toggle="tooltip" data-placement="top" data-original-title="Finter Data"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>

                    <!-- Tables -->
                    {{-- <div class="table-responsive"> --}}
                        <div class="col-md-12 table-responsive">
                            <table id="dataTable" class="table table-striped table-bordered table-hover">
                                <thead class="bg-primary">
                                    <tr>
                                        @if (permission('purchase-bulk-delete'))
                                        <th>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="select_all" onchange="select_all()">
                                                <label class="custom-control-label" for="select_all"></label>
                                            </div>
                                        </th>
                                        @endif
                                        <th>SL</th>
                                        <th>Purchase No</th>
                                        <th>Supplier</th>
                                        <th>Total Items</th>
                                        <th>Total Qty</th>
                                        <th>Total Discount</th>
                                        <th>Total Tax</th>
                                        <th>Total Cost</th>
                                        <th>Tax Rate</th>
                                        <th>Total Order Tax</th>
                                        <th>Total Order Discount</th>
                                        <th>Shipping Cost</th>
                                        <th>Grand Total</th>
                                        <th>Paid Amount</th>
                                        <th>Due Amount</th>
                                        <th>Purchase Status</th>
                                        <th>Payment Status</th>
                                        <th>Created By</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>

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
    @include('purchase::payment.add')
    <!-- start payment view modal -->
    <div class="modal fade show" id="payment_view_modal" tabindex="-1" role="dialog" aria-labelledby="model-1" aria-modal="true">
        <div class="modal-dialog modal-xl" role="document">

            <!-- Modal Content -->
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header bg-primary">
                    <h3 class="modal-title text-white" id="model-1"></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">??</span>
                    </button>
                </div>
                <!-- /modal header -->

                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered" id="payment-list">
                                <thead class="bg-primary">
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Paid Amount</th>
                                    <th class="text-center">Change Amount</th>
                                    <th class="text-center">Payment Method</th>
                                    <th>Account</th>
                                    <th>Payment No</th>
                                    <th>Note</th>
                                    <th class="text-center">Action</th>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /modal body -->

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                </div>
                <!-- /modal footer -->

            </div>
            <!-- /modal content -->

        </div>
    </div>
    <!-- end payment view modal -->

</div>
@endsection

@push('scripts')
<script src="js/moment.min.js"></script>
<script src="js/bootstrap-datetimepicker.min.js"></script>
<script>
var table;
$(document).ready(function(){
    $('.date').datetimepicker({
        format : "YYYY-MM-DD"
    });
    table = $('#dataTable').DataTable({
        "processing": true, //Feature control the processing indicator
        "serverSide": true, //Feature control DataTable server side processing mode
        "order": [], //Initial no order
        "responsive": false, //Make table responsive in mobile device
        "bInfo": true, //TO show the total number of data
        "bFilter": false, //For datatable default search box show/hide
        "lengthMenu": [
            [5, 10, 15, 25, 50, 100, 1000, 10000, -1],
            [5, 10, 15, 25, 50, 100, 1000, 10000, "All"]
        ],
        "pageLength": 25, //number of data show per page
        "language": {
            processing: `<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i> `,
            emptyTable: '<strong class="text-danger">No Data Found</strong>',
            infoEmpty: '',
            zeroRecords: '<strong class="text-danger">No Data Found</strong>'
        },
        "ajax": {
            "url": "{{route('purchase.datatable.data')}}",
            "type": "POST",
            "data": function (data) {
                data.purchase_no     = $("#form-filter #purchase_no").val();
                data.supplier_id     = $("#form-filter #supplier_id").val();
                data.from_date       = $("#form-filter #from_date").val();
                data.to_date         = $("#form-filter #to_date").val();
                data.purchase_status = $("#form-filter #purchase_status").val();
                data.payment_status  = $("#form-filter #payment_status").val();
                data._token          = _token;
            }
        },
        "columnDefs": [{
                @if (permission('purchase-bulk-delete'))
                "targets": [0,20],
                @else
                "targets": [19],
                @endif
                "orderable": false,
                "className": "text-center"
            },
            {
                @if (permission('purchase-bulk-delete'))
                "targets": [1,4,5,16,17,18,19],
                @else
                "targets": [0,3,4,15,16,18],
                @endif
                "className": "text-center"
            },
            {
                @if (permission('purchase-bulk-delete'))
                "targets": [6,7,8,9,10,,11,12,13,14,15],
                @else
                "targets": [5,6,7,8,9,10,11,12,13,14],
                @endif
                "className": "text-right"
            }
        ],
        "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",
        "buttons": [
            @if (permission('purchase-report'))
            {
                'extend':'colvis','className':'btn btn-secondary btn-sm text-white','text':'Column'
            },
            {
                "extend": 'print',
                'text':'Print',
                'className':'btn btn-secondary btn-sm text-white',
                "title": "Menu List",
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
                'text':'CSV',
                'className':'btn btn-secondary btn-sm text-white',
                "title": "Menu List",
                "filename": "purchase-list",
                "exportOptions": {
                    columns: function (index, data, node) {
                        return table.column(index).visible();
                    }
                }
            },
            {
                "extend": 'excel',
                'text':'Excel',
                'className':'btn btn-secondary btn-sm text-white',
                "title": "Menu List",
                "filename": "purchase-list",
                "exportOptions": {
                    columns: function (index, data, node) {
                        return table.column(index).visible();
                    }
                }
            },
            {
                "extend": 'pdf',
                'text':'PDF',
                'className':'btn btn-secondary btn-sm text-white',
                "title": "Menu List",
                "filename": "purchase-list",
                "orientation": "landscape", //portrait
                "pageSize": "A4", //A3,A5,A6,legal,letter
                "exportOptions": {
                    columns: [1, 2, 3]
                },
            },
            @endif
            @if (permission('purchase-bulk-delete'))
            {
                'className':'btn btn-danger btn-sm delete_btn d-none text-white',
                'text':'Delete',
                action:function(e,dt,node,config){
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

    // payment add modal show
    $(document).on('click', '.add_payment', function () {
        let id = $(this).data('id');
        let due = $(this).data('due');
        if(id && due){
            $('#payment_form')[0].reset();
            $('#payment_form').find('.is-invalid').removeClass('is-invalid');
            $('#payment_form').find('.error').remove();
            $('.payment_no').addClass('d-none');
            $('.selectpicker').selectpicker('refresh');
            if (id) {
                $('#payment_modal #payment_id').val('');
                $('#payment_modal #purchase_id').val(id);
                $('#payment_modal #paying_amount').val(due);
                $('#payment_modal #amount').val(due);
                $('#payment_modal #balance').val(due);
                $('#payment_modal').modal({
                    keyboard: false,
                    backdrop: 'static',
                });
                $('#payment_modal .modal-title').html(
                    '<i class="fas fa-plus-square"></i> <span>Add Payment</span>');
            }
        }
    });
    //Payment Add Modal Show
    $(document).on('click', '.edit-payment', function () {
        let id = $(this).data('id');
        let purchase_id = $(this).data('purchaseid');
        let amount = $(this).data('amount');
        let change = $(this).data('change');
        let payment_method = $(this).data('paymentmethod');
        let account_id = $(this).data('accountid');
        let payment_no = $(this).data('paymentno');
        let payment_note = $(this).data('note');
        let paying_amount = amount + change;
        if(id)
        {
            $('#payment_form')[0].reset();
            $('#payment_form').find('.is-invalid').removeClass('is-invalid');
            $('#payment_form').find('.error').remove();
            $('.payment_no').addClass('d-none');
            $('.selectpicker').selectpicker('refresh');
            if (id) {
                $('#payment_modal #payment_id').val(id);
                $('#payment_modal #purchase_id').val(purchase_id);
                $('#payment_modal #paying_amount').val(paying_amount);
                $('#payment_modal #amount').val(amount);
                $('#payment_modal #change_amount').val(change);
                $('#payment_modal #payment_method').val(payment_method);
                $('#payment_modal #account_id').val(account_id);
                $('#payment_modal #payment_note').val(payment_note);
                if(payment_method != 1){
                    $('.payment_no').removeClass('d-none');
                    $('#payment_no').val(payment_no);
                }else{
                    $('.payment_no').addClass('d-none');
                    $('#payment_no').val('');
                }
                $('.selectpicker').selectpicker('refresh');
                $('#payment_view_modal').modal('hide');
                $('#payment_modal').modal({
                    keyboard: false,
                    backdrop: 'static',
                });
                $('#payment_modal .modal-title').html(
                    '<i class="fas fa-edit"></i> <span>Edit Payment</span>');
            }
        }
    });

    $(document).on('click', '#payment-save-btn', function(e){
        e.preventDefault();
        let id = $('#payment_id').val();
        let method;
        if (id) {
            method = 'update';
        } else {
            method = 'add';
        }
        let form = document.getElementById('payment_form');
        let formData = new FormData(form);
        $.ajax({
            url: "{{route('purchase.payment.store.or.update')}}",
            type: "POST",
            data: formData,
            dataType: "JSON",
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: function(){
                $('#payment-save-btn').addClass('kt-spinner kt-spinner--md kt-spinner--light');
            },
            complete: function(){
                $('#payment-save-btn').removeClass('kt-spinner kt-spinner--md kt-spinner--light');
            },
            success: function (data) {
                $('#payment_form').find('.is-invalid').removeClass('is-invalid');
                $('#payment_form').find('.error').remove();
                if (data.status == false) {
                    $.each(data.errors, function (key, value) {
                        var key = key.split('.').join('_');
                        $('#payment_form input#' + key).addClass('is-invalid');
                        $('#payment_form textarea#' + key).addClass('is-invalid');
                        $('#payment_form select#' + key).parent().addClass('is-invalid');
                        $('#payment_form #' + key).parent().append(
                            '<small class="error text-danger">' + value + '</small>');
                    });
                } else {
                    notification(data.status, data.message);
                    if (data.status == 'success') {
                    if (method == 'update') {
                        table.ajax.reload(null, false);
                    } else {
                        table.ajax.reload();
                    }
                    $('#payment_modal').modal('hide');
                }
                }
            },
            error: function (xhr, ajaxOption, thrownError) {
                console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
            }
        });
    });

    //view payment list
    $(document).on('click', '.view_payment_list', function () {
        let id = $(this).data('id');
        if (id) {
            payment_list(id);
            $('#payment_view_modal').modal({
                keyboard: false,
                backdrop: 'static',
            });
            $('#payment_view_modal .modal-title').html(
                '<i class="fas fa-file-invoice-dollar"></i> <span>payment Lists</span>');
        }
    });

    // delete payment
    $(document).on('click', '.delete-payment', function(e){
        e.preventDefault();
        let id = $(this).data('id');
        let purchase_id = $(this).data('purchaseid');
        Swal.fire({
            title: 'Are you sure to delete  data?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "{{ route('purchase.payment.delete') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: _token
                    },
                    dataType: "JSON",
                }).done(function (response) {
                    if (response.status == "success") {
                        Swal.fire("Deleted", response.message, "success").then(function () {
                            payment_list(purchase_id);
                            table.ajax.reload(null, false);
                        });
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

    $(document).on('keyup', '#paying_amount', function () {
        $('#change_amount').val(parseFloat($(this).val() - $('#amount').val()).toFixed(2));
    });

    $(document).on('keyup', '#amount', function () {
        var amount = parseFloat($(this).val());
        var paying_amount = parseFloat($('#paying_amount').val());
        var change_amount = paying_amount - amount;
        if(amount > paying_amount){
            notification('error','Paying amount cannot be bigger than received amount');
        }
        if($('#payment_id').val() == '')
        {
            var balance = parseFloat($('#balance').val());
            if(amount > balance){
                notification('error','Paying amount cannot be bigger than due amount');
            }
        }
        $('#change_amount').val(parseFloat(change_amount).toFixed(2));
    });

    $(document).on('change', '#payment_method', function () {
        if($('#payment_method option:selected').val() != 1){
            var method = $('#payment_method option:selected').val() == 2 ? 'Cheque' : 'Mobile';
            $('#method-name').text(method);
            $('.payment_no').removeClass('d-none');
        }else{
            $('.payment_no').addClass('d-none');
        }
    });

    $(document).on('click', '.delete_data', function () {
        let id   = $(this).data('id');
        let name = $(this).data('name');
        let row  = table.row($(this).parent('tr'));
        let url  = "{{ route('purchase.delete') }}";
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
            let url = "{{route('purchase.bulk.delete')}}";
            bulk_delete(ids,url,table,rows);
        }
    }

});

function payment_list(id)
{
    $.ajax({
        url: "{{route('purchase.payment.show')}}",
        type: "POST",
        data: { id: id,_token: _token},
        success: function (data) {
            $('#payment_view_modal #payment-list tbody').html();
            $('#payment_view_modal #payment-list tbody').html(data);

        },
        error: function (xhr, ajaxOption, thrownError) {
            console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
        }
    });
}

</script>
@endpush
