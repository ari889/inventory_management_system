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

                <a href="{{ route('role') }}" class="btn btn-danger btn-sm"><i class="fas fa-arrow-circle-left mr-2"></i>Back</a>

            </div>
            <!-- /entry header -->

            <!-- Card -->
            <div class="dt-card">

                <!-- Card Body -->
                <div class="dt-card__body">
                    <form id="saveDataForm" method="POST">
                        @csrf
                        <div class="row">
                            <x-form.textbox labelName="Role Name" name="role_name" required="required" col="col-md-12"  placeholder="Enter role name" />
                            <div class="col-md-12">
                                <ul id="permission" class="text-left">
                                    @if (!$data->isEmpty())
                                        @foreach ($data as $menu)
                                            @if ($menu->submenu->isEmpty())
                                                <li>
                                                    <input type="checkbox" name="module[]" class="module" value="{{ $menu->id }}">
                                                    {!! $menu->type == 1 ? $menu->divider_title.' <small>(Divider)</small>' : $menu->module_name !!}
                                                    @if (!$menu->permission->isEmpty())
                                                        <ul>
                                                            @foreach ($menu->permission as $permission)
                                                                <li><input type="checkbox" name="permission[]" value="{{ $permission->id }}" />{{ $permission->name }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @else
                                                <li>
                                                    <input type="checkbox" name="module[]" class="module" value="{{ $menu->id }}">
                                                    {!! $menu->type == 1 ? $menu->divider_title.' <small>(Divider)</small>' : $menu->module_name !!}
                                                    <ul>
                                                        @foreach ($menu->submenu as $submenu)
                                                            <li>
                                                                <input type="checkbox" name="module[]" class="module" value="{{ $submenu->id }}"> {{ $submenu->module_name }}
                                                                @if (!$submenu->permission->isEmpty())
                                                                    <u>
                                                                        @foreach ($submenu->permission  as $permission)
                                                                        <li><input type="checkbox" name="permission[]" value="{{ $permission->id }}" />{{ $permission->name }}</li>
                                                                        @endforeach
                                                                    </u>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            @endif
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                            <div class="col-md-12">
                                <button type="reset" class="btn btn-danger btn-sm">Reset</button>
                                <button type="button" class="btn btn-primary btn-sm" id="save-btn">Save</button>
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
@include('menu.modal')
@endsection

@push('scripts')
<script src="js/tree.js"></script>
<script>
    $(document).ready(function(){
        $('input[type="checkbox"]').click(function(){
            $(this).next().find('input[type="checkbox"]').prop('checked', this.checked);
            $(this).parents('ul').prev('input[type="checkbox"]').prop('checked', function(){
                return $(this).next().find(':checked').length;
            });
        });
        $('#permission').treed(); // searialize tree js

        $(document).on('click', '#save-btn', function () {
            let form = document.getElementById('saveDataForm');
            let formData = new FormData(form);
            if($('.module:checked').length >= 1){
                $.ajax({
                    url: "{{route('role.store.or.update')}}",
                    type: "POST",
                    data: formData,
                    dataType: "JSON",
                    contentType: false,
                    processData: false,
                    cache: false,
                    beforeSend: function(){
                        $('#save-btn').addClass('kt-prinner kt-spinner-md kt-spinner--light')
                    },
                    complete: function(){
                        $('#save-btn').removeClass('kt-prinner kt-spinner-md kt-spinner--light')
                    },
                    success: function (data) {
                        $('#saveDataForm').find('.is-invalid').removeClass('is-invalid');
                        $('#saveDataForm').find('.error').remove();
                        if (data.status == false) {
                            $.each(data.errors, function (key, value) {
                                $('#saveDataForm input#' + key).addClass('is-invalid');
                                $('#saveDataForm #' + key).parent().append(
                                    '<small class="error text-danger">' + value + '</small>');
                            });
                        } else {
                            notification(data.status, data.message);
                            if (data.status == 'success') {
                                window.location.replace("{{ route('role') }}");
                            }
                        }
                    },
                    error: function (xhr, ajaxOption, thrownError) {
                        console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                    }
                });
            }else{
                notification('error', 'Please checked at least one menu!');
            }
        });
    });
</script>
@endpush
