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
                    <h2 class="dt-entry__title mb-0 text-primary"><i class="{{ $page_icon }} mr-2"></i>{{ $sub_title }}
                    </h2>
                </div>
                <!-- /entry heading -->
            </div>
            <!-- /entry header -->

            <!-- Card -->
            <div class="dt-card">

                <!-- Card Body -->
                <div class="dt-card__body">

                    <form id="store_or_update_form" method="POST">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="update_id" value="{{ $data ? $data->id : '' }}">
                            <div class="form-group col-md-4">
                                <label for="check_in">Check In Time</label>
                                <input type="text" class="form-control time" name="check_in" id="check_in"
                                    class="form-control" value="{{ $data ? $data->check_in : '' }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="check_out">Check Out Time</label>
                                <input type="text" class="form-control time" name="check_out" id="check_out"
                                    class="form-control" value="{{ $data ? $data->check_out : '' }}">
                            </div>

                            <div class="form-group col-md-4 text-center" style="padding-top: 24px;">
                                <button class="btn btn-danger btn-sm" id="reset-btn" type="button">Reset</button>
                                <button class="btn btn-primary btn-sm" id="save-btn" type="button">Save</button>
                            </div>
                        </div>
                    </form>

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
<script src="js/moment.min.js"></script>
<script src="js/bootstrap-datetimepicker.min.js"></script>
<script>
    $(document).ready(function () {

        $('.time').datetimepicker({format: 'hh:mm A', ignoreReadonly:true});


        $(document).on('click', '#save-btn', function(e){
            e.preventDefault();
            let form = document.getElementById('store_or_update_form');
            let formData = new FormData(form);
            $.ajax({
                url: "{{route('hrm.store')}}",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function(){
                    $('#save-btn').addClass('kt-spinner kt-spinner--md kt-spinner--light');
                },
                complete: function(){
                    $('#save-btn').removeClass('kt-spinner kt-spinner--md kt-spinner--light');
                },
                success: function (data) {
                    $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
                    $('#store_or_update_form').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            var key = key.split('.').join('_');
                            $('#store_or_update_form input#' + key).addClass('is-invalid');
                            $('#store_or_update_form #' + key).parent().append(
                                '<small class="error text-danger">' + value + '</small>');
                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') {
                            window.location.replace('{{ url("hrm-setting") }}');
                        }
                    }
                },
                error: function (xhr, ajaxOption, thrownError) {
                    console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                }
            });
    })

    });

</script>
@endpush
