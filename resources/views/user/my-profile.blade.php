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

                @if(permission('user-add'))
                    <button type="button" style="font-size: 1.4rem" class="btn btn-primary btn-sm" onclick="showUserFormModal('Add New User', 'Save')"><i class="fas fa-plus-square mr-2"></i>Add New</button>
                @endif

            </div>
            <!-- /entry header -->

            <!-- Card -->
            <div class="dt-card">

                <!-- Card Body -->
                <div class="dt-card__body">

                    <form id="form-filter">
                        <div class="row">
                            <x-form.textbox labelName="Name" name="name" col="col-md-3"  placeholder="Enter User Name" />
                            <x-form.textbox labelName="Email" name="email" col="col-md-3"  placeholder="Enter User Email" />
                            <x-form.textbox labelName="Mobile Number" name="mobile_no" col="col-md-3"  placeholder="Enter User Mobile Number" />
                            <x-form.selectbox labelName="Role" name="role_id" col="col-md-3" class="selectpicker">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                                @endforeach
                            </x-form.selectbox>
                            <x-form.selectbox labelName="Status" name="status" col="col-md-3" class="selectpicker">
                                <option value="0">Active</option>
                                <option value="1">Inactive</option>
                            </x-form.selectbox>
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
                                    @if(permission('user-bulk-delete'))
                                    <th>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="select_all" onchange="select_all()">
                                            <label class="custom-control-label" for="select_all"></label>
                                        </div>
                                    </th>
                                    @endif
                                    <th>SL</th>
                                    <th>Avatar</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Mobile No</th>
                                    <th>Gender</th>
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
@include('user.view-modal')
@include('user.modal')
@endsection

@push('scripts')
<script>
var table;
$(document).ready(function(){
    

        $(document).on('click', '#save-btn', function () {
            let form = document.getElementById('store_or_update_form');
            let formData = new FormData(form);
            formData.append('_token', _token);
            let url = "{{route('user.store.or.update')}}";
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
                    $('#save-btn').addClass('kt-prinner kt-spinner-md kt-spinner--light')
                },
                complete: function(){
                    $('#save-btn').removeClass('kt-prinner kt-spinner-md kt-spinner--light')
                },
                success: function (data) {
                    $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
                    $('#store_or_update_form').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            $('#store_or_update_form input#' + key).addClass('is-invalid');
                            $('#store_or_update_form textarea#' + key).addClass('is-invalid');
                            $('#store_or_update_form select#' + key).parent().addClass('is-invalid');
                            if(key == 'password' || key == 'password_confirmation'){
                                $('#store_or_update_form #' + key).parents('.form-group').append(
                                '<small class="error text-danger">' + value + '</small>');
                            }else{
                                $('#store_or_update_form #' + key).parent().append(
                                '<small class="error text-danger">' + value + '</small>');
                            }
                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') {
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

        $('.toggle-password').click(function(){
            $(this).toggleClass('fa-eye fa-eye-slash');
            var input = $($(this).attr('toggle'));
            if(input.attr('type') == 'password'){
                input.attr('type', 'text');
            }else{
                input.attr('type', 'password');
            }
        });

});


/**
 * generate password
 */
 const randomFunc = {
    upper : getRandomUpperCase,
    lower : getRandomLowerCase,
    number : getRandomNumber,
    symbol : getRandomSymbol,
};

function getRandomUpperCase(){
    return String.fromCharCode(Math.floor(Math.random() * 26) + 65);
}

function getRandomLowerCase(){
    return String.fromCharCode(Math.floor(Math.random() * 26) + 97);
}

function getRandomNumber(){
    return String.fromCharCode(Math.floor(Math.random() * 10) + 48);
}
function getRandomSymbol(){
    var symbol = "!@#$%^&*=~?";
    return symbol[Math.floor(Math.random() * symbol.length)];
}

/**
 * generate event
 */
document.getElementById("generate_password").addEventListener('click', () => {
    const length = 10; //password length
    const hasUpper = true;
    const hasLower = true;
    const hasSymbol = true;
    const hasNumber = true;

    let password = generatePassword(hasUpper, hasLower, hasNumber, hasSymbol, length);
    document.getElementById('password').value = password;
    document.getElementById('password_confirmation').value = password;
});

function generatePassword(upper, lower, number, symbol, length){
    let generatedPassword = '';

    const typeCount = upper + lower + number + symbol;
    const typeArr = [{upper}, {lower}, {number}, {symbol}].filter(item => Object.values(item)[0]);
    if(typeCount === 0){
        return '';
    }
    for(let i = 0; i <= length; i += typeCount){
        typeArr.forEach(type => {
            const funcName = Object.keys(type)[0];
            generatedPassword += randomFunc[funcName]();
        });
    }
    const finalPassword = generatedPassword.slice(0, length);
    return finalPassword;
}

</script>
@endpush
