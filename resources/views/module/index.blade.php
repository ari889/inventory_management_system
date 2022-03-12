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
                <li class="active breadcrumb-item">{{ $sub_title.' ('.$data['menu']->menu_name.')' }}</li>
              </ol>
        </div>

        <!-- Grid Item -->
        <div class="col-xl-12">

            <!-- Entry Header -->
            <div class="dt-entry__header">

                <!-- Entry Heading -->
                <div class="dt-entry__heading">
                    <h2 class="dt-entry__title mb-0 text-primary"><i class="{{ $page_icon }} mr-2"></i>{{ $sub_title.' ('.$data['menu']->menu_name.')' }}</h2>
                </div>
                <!-- /entry heading -->
                <div>
                    <a href="{{ route('menu') }}" style="font-size: 1.4rem" class="btn btn-danger btn-sm"><i class="fas fa-arrow-circle-left mr-2"></i>Back To Menu List</a>
                    @if(permission('menu-module-delete'))
                        <a href="{{ route('menu.module.create', ['menu' => $data['menu']->id]) }}" style="font-size: 1.4rem" class="btn btn-primary btn-sm"><i class="fas fa-plus-square mr-2"></i>Add New</a>
                    @endif
                </div>

            </div>
            <!-- /entry header -->

            <!-- Card -->
            <div class="dt-card">

                <!-- Card Body -->
                <div class="dt-card__body menu-builder">
                    <h5 class="card-item mb-3">Drag and drop the menu item blow to re-arrange them</h5>
                    <div class="dd">
                        <x-menu-builder :menuItems="$data['menu']->menuItems" />
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
@include('menu.modal')
@endsection

@push('scripts')
<script>
$(function(){
    $('.dd').nestable({maxDepth:2}); // initializzze nestable 2
    $('.dd').on('change', function(e){
        $.post('{{ route("menu.order", ["menu" => $data["menu"]->id]) }}', {
            order: JSON.stringify($('.dd').nestable('serialize')),
            _token: _token
        }, function(data){
            notification('success', 'Menu order updated successfully');
        });
    });
});

function deleteItem(id){
    Swal.fire({
        title: 'Are you sure to delete?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            document.getElementById('delete_form_'+id).submit();
        }
    });

}

$(document).ready(function(){
    @if(session('success'))
    notification('success', "{{ session('success') }}")
    @endif

    @if(session('error'))
    notification('error', "{{ session('error') }}")
    @endif
});
</script>
@endpush
