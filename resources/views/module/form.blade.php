@extends('layouts.app')

@section('title')
    {{$page_title}}
@endsection

@section('content')
<div class="dt-content">

    <!-- Grid -->
    <div class="row">
        <div class="col-xl-12 pb-3">
            <ol class="breadcrumb bg-white">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="active breadcrumb-item">
                    @isset($data['module'])
                        {{ $sub_title }}
                    @else
                        {{ $sub_title.' to ('.$data['menu']->menu_name.')' }}
                    @endisset
                </li>
              </ol>
        </div>

        <!-- Grid Item -->
        <div class="col-xl-12">

            <!-- Entry Header -->
            <div class="dt-entry__header">

                <!-- Entry Heading -->
                <div class="dt-entry__heading">
                    <h2 class="dt-entry__title mb-0 text-primary"><i class="{{ $page_icon }} mr-2"></i>
                        @isset($data['module'])
                            {{ $sub_title }}
                        @else
                            {{ $sub_title.' to ('.$data['menu']->menu_name.')' }}
                        @endisset
                    </h2>
                </div>
                <!-- /entry heading -->

                <a href="{{ route('menu.build', ['id' => $data['menu']->id]) }}" style="font-size: 1.4rem" class="btn btn-danger btn-sm"><i class="fas fa-arrow-circle-left mr-2"></i>Back</a>

            </div>
            <!-- /entry header -->

            <!-- Card -->
            <div class="dt-card">

                <!-- Card Body -->
                <div class="dt-card__body">
                    <h5 class="card-title">Manage menu module/item</h5>
                    <form action="{{ route('menu.module.store.or.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="update_id" value="{{ isset($data['module']) ? $data['module']->id : '' }}">
                        <input type="hidden" name="menu_id" value="{{ $data['menu']->id }}">
                        <div class="form-group required">
                            <label for="type">Type</label>
                            <select name="type" id="type" class="form-control selectpicker @error('type') is-invalid @enderror" onchange="setItemType(this.value)">
                                <option value="">Select Please</option>
                                <option value="1" @isset($data['module']) {{ $data['module']->type == 1 ? 'selected' : '' }} @endisset {{ old('type') == 1 ? 'selected' : '' }}>Divider</option>
                                <option value="2" @isset($data['module']) {{ $data['module']->type == 2 ? 'selected' : '' }} @endisset {{ old('type') == 2 ? 'selected' : '' }}>Module/Item</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="divider_fields d-none">
                            <div class="form-group required">
                                <label for="divider_title">Divider Title</label>
                                <input type="text" class="form-control @error('divider_title') is-invalid @enderror" name="divider_title" id="divider_title" placeholder="Enter Divider Title" value="{{ isset($data['module']) ? $data['module']->divider_title : old('divider_title') }}">
                                @error('divider_title')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="item_fields d-none">
                            <div class="form-group required">
                                <label for="module_name">Module Name</label>
                                <input type="text" class="form-control @error('module_name') is-invalid @enderror" name="module_name" id="module_name" placeholder="Enter Divider Title" value="{{ isset($data['module']) ? $data['module']->module_name : old('module_name') }}">
                                @error('module_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="url">URL for the Module</label>
                                <input type="text" class="form-control @error('url') is-invalid @enderror" name="url" id="url" placeholder="Enter module url" value="{{ isset($data['module']) ? $data['module']->url : old('url') }}">
                                @error('url')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="icon_class">Font Icon class for the Module <a href="https://fontawesome.com">(Use a fontawesome font class)</a></label>
                                <input type="text" class="form-control @error('icon_class') is-invalid @enderror" name="icon_class" id="icon_class" placeholder="Enter icon class name" value="{{ isset($data['module']) ? $data['module']->icon_class : old('icon_class') }}">
                                @error('icon_class')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="icon_class">Open In</label>
                                <select name="target" id="target" class="form-control selectpicker @error('target') is-invalid @enderror">
                                    <option value="">Select Please</option>
                                    <option value="_self" @isset($data['module']) {{ $data['module']->target == '_self' ? 'selected' : '' }} @endisset {{ old('_target') == '_self' ? 'selected' : '' }}>Same Tab</option>
                                    <option value="_blank" @isset($data['module']) {{ $data['module']->target == '_blank' ? 'selected' : '' }} @endisset {{ old('_target') == '_blank' ? 'selected' : '' }}>New Tab</option>
                                </select>
                                @error('target')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger btn-sm" type="reset"><i class="fas fa-redo"></i> Reset</button>
                            <button class="btn btn-primary btn-sm" type="submit">
                                @isset($data['module'])
                                    <i class="fas fa-arrow-circle-up"></i> Update
                                @else
                                    <i class="fas fa-plus-square"></i> Create
                                @endisset
                            </button>
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
<script>
    var type = $('#type option:selected').val();
    if(type){
        setItemType(type)
    }
    function setItemType(type){
        if(type == 1){
            $('.divider_fields').removeClass('d-none');
            $('.item_fields').addClass('d-none');
        }else{
            $('.divider_fields').addClass('d-none');
            $('.item_fields').removeClass('d-none');
        }
    }
</script>
@endpush
