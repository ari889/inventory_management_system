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
                    <div class="card">

                        <!-- Card Header -->
                        <div class="card-header">
                          <!-- Tab Navigation -->
                          <ul class="card-header-pills nav nav-pills" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active show" data-toggle="tab" href="#profile" role="tab"
                                    aria-controls="profile" aria-selected="true">Profile</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#change-password" role="tab"
                                    aria-controls="change-password" aria-selected="false">Change Password</a>
                            </li>
                        </ul>
                          <!-- /tab navigation -->
                        </div>
                        <!-- /card header -->
      
                        <!-- Tab Content -->
                        <div class="tab-content">
      
                          <!-- Tab Pane -->
                          <div id="profile" class="tab-pane active show">
                            <form method="post" id="profile-form" enctype="multipart/form-data">
                                @csrf
                                <div class="row pt-5">
                                    <div class="col-md-9">
                                        <div class="row">
                                            <x-form.textbox labelName="Name" name="name" required="required" col="col-md-12"  placeholder="Enter User Name" value="{{ Auth::user()->name }}" />
                                            <x-form.textbox labelName="Email" name="email" required="required" col="col-md-12"  placeholder="Enter User Email" value="{{ Auth::user()->email }}" />
                                            <x-form.textbox labelName="Mobile Number" name="mobile_no" required="required" col="col-md-12"  placeholder="Enter User Mobile Number" value="{{ Auth::user()->mobile_no }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="avatar">Avatar</label>
                                                <div class="col-md-12 px-0 text-center">
                                                    <div id="avatar">
            
                                                    </div>
                                                </div>
                                                <input type="hidden" name="old_avatar" id="old_avatar" value="{{ Auth::user()->avatar }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <button type="button" class="btn btn-primary btn-sm" id="save-profile" onclick="save_data('profile')"><i class="fas fa-save"></i> Save</button>
                                    </div>
                                </div>
                            </form>
                          </div>
                          <!-- /tab pane-->
      
                          <!-- Tab Pane -->
                          <div id="change-password" class="tab-pane">
                            <form method="post" id="password-form">
                                @csrf
                                <div class="row pt-5">
                                    <x-form.textbox type="password" labelName="Current Password" name="current_password" required="required" col="col-md-12"  placeholder="Enter current password" />
                                    <div class="form-group col-md-12 required">
                                        <label for="password">New Password</label>
                                        <div class="input-group">
                                            <input type="password" name="password" id="password" class="form-control">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-warning" id="generate_password" data-toggle="tooltip" data-placement="top" data-original-title="Generate Password">
                                                    <i class="fas fa-lock text-white" toggle="#password" style="cursor: pointer;"></i>
                                                </span>
                                            </div>
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-primary">
                                                    <i class="fas fa-eye toggle-password text-white" toggle="#password" style="cursor: pointer;"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12 required">
                                        <label for="password_confirmation">Confirm Password</label>
                                        <div class="input-group">
                                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-primary">
                                                    <i class="fas fa-eye toggle-password text-white" toggle="#password_confirmation" style="cursor: pointer;"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <button type="button" class="btn btn-primary btn-sm" id="save-password" onclick="save_data('password')"><i class="fas fa-save"></i> Save</button>
                                    </div>
                                </div>
                            </form>
                          </div>
                          <!-- /tab pane-->
      
                        </div>
                        <!-- /tab content -->
      
                      </div>                    

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
<script src="js/spartan-multi-image-picker-min.js"></script>
<script>
    var table;
    $(document).ready(function () {

        $('.toggle-password').click(function () {
            $(this).toggleClass('fa-eye fa-eye-slash');
            var input = $($(this).attr('toggle'));
            if (input.attr('type') == 'password') {
                input.attr('type', 'text');
            } else {
                input.attr('type', 'password');
            }
        });

        $('#avatar').spartanMultiImagePicker({
            fieldName: 'avatar',
            maxCount: 1,
            rowHeight: '150px',
            groupClassName: 'col-md-12 col-sm-12 col-xs-12',
            maxFileSize: '',
            dropFileLabel: 'Drop Here',
            allowExt: 'png|jpg|jpeg',
            onExtensionErr: function (index, file) {
                Swal.fire({
                    icon: 'error',
                    title: 'Opps...',
                    text: 'Only png, jpg, jpeg file format are allowed!'
                });
            }
        });

        $('input[name="avatar"]').prop('required', true);

        $('.remove-files').on('click', function () {
            $(this).parents('.col-md-12').remove();
        });

        @if(Auth::user()->avatar)
            $('#profile-form #avatar img.spartan_image_placeholder').css('display', 'none');
            $('#profile-form #avatar .spartan_remove_row').css('display', 'none');
            $('#profile-form #avatar .img_').css('display', 'block');
            $('#profile-form #avatar .img_').attr('src', '{{ asset("storage/".USER_AVATAR_PATH."/".Auth::user()->avatar) }}');
        @endif

    });

    function save_data(form_id){
        let form = document.getElementById(form_id+'-form');
        let formData = new FormData(form);
        formData.append('_token', _token);
        let url;
        if (form_id == 'profile') {
            url = '{{ route("update.profile") }}';
        } else if(form_id == 'password') {
            url = '{{ route("update.password") }}';
        }
        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            dataType: "JSON",
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: function () {
                $('#save-'+form_id).addClass('kt-prinner kt-spinner-md kt-spinner--light')
            },
            complete: function () {
                $('#save-'+form_id).removeClass('kt-prinner kt-spinner-md kt-spinner--light')
            },
            success: function (data) {
                $('#'+form_id+'-form').find('.is-invalid').removeClass(
                    'is-invalid');
                $('#'+form_id+'-form').find('.error').remove();
                if (data.status == false) {
                    $.each(data.errors, function (key, value) {
                        $('#'+form_id+ '-form input#' + key).addClass('is-invalid');
                        $('#'+form_id+ '-form textarea#' + key).addClass('is-invalid');
                        $('#'+form_id+ '-form select#' + key).parent().addClass('is-invalid');
                        if (key == 'password' || key =='password_confirmation') {
                            $('#'+form_id+ '-form #' + key).parents('.form-group').append('<small class="error text-danger">' +value + '</small>');
                        } else {
                            $('#'+form_id+ '-form #' + key).parent().append('<small class="error text-danger">' +value + '</small>');
                        }
                    });
                } else {
                    notification(data.status, data.message);
                    if (data.status == 'success') {
                        window.location.reload();
                    }
                }

            },
            error: function (xhr, ajaxOption, thrownError) {
                console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr
                    .responseText);
            }
        });
    }


    /**
     * generate password
     */
    const randomFunc = {
        upper: getRandomUpperCase,
        lower: getRandomLowerCase,
        number: getRandomNumber,
        symbol: getRandomSymbol,
    };

    function getRandomUpperCase() {
        return String.fromCharCode(Math.floor(Math.random() * 26) + 65);
    }

    function getRandomLowerCase() {
        return String.fromCharCode(Math.floor(Math.random() * 26) + 97);
    }

    function getRandomNumber() {
        return String.fromCharCode(Math.floor(Math.random() * 10) + 48);
    }

    function getRandomSymbol() {
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

    function generatePassword(upper, lower, number, symbol, length) {
        let generatedPassword = '';

        const typeCount = upper + lower + number + symbol;
        const typeArr = [{
            upper
        }, {
            lower
        }, {
            number
        }, {
            symbol
        }].filter(item => Object.values(item)[0]);
        if (typeCount === 0) {
            return '';
        }
        for (let i = 0; i <= length; i += typeCount) {
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
