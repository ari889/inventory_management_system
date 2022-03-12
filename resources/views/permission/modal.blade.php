<div class="modal fade show" id="store_or_update_modal" tabindex="-1" role="dialog" aria-labelledby="model-1" aria-modal="true">
    <div class="modal-dialog modal-lg" role="document">

        <!-- Modal Content -->
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header bg-primary">
                <h3 class="modal-title text-white" id="model-1"></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <!-- /modal header -->

            <!-- Modal Body -->
            <div class="modal-body">
                <form id="store_or_update_form" method="POST">
                    <div class="row">
                        <x-form.selectbox labelName="Module" name="module_id" col="col-md-12" class="selectpicker">
                        @if (!empty($data['modules']))
                            @foreach ($data['modules'] as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        @endif
                        </x-form.selectbox>
                        <div class="col-md-12">
                            <table class="table table-borderless" id="permission-table">
                                <thead class="bg-primary">
                                    <tr>
                                        <th width="45%">Permission Name</th>
                                        <th width="45%">Permission Slug</th>
                                        <th width="10%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input type="text" name="permission[1][name]" onkeyup="url_generator(this.value, 'permission_1_slug')" id="permission_1_name" class="form-control">
                                        </td>
                                        <td>
                                            <input type="text" name="permission[1][slug]" id="permission_1_slug" class="form-control">
                                        </td>
                                        <td>
                                           <button type="button" id="add_permission" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Add More">
                                               <i class="fas fa-plus-square"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /modal body -->

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-sm" id="save-btn"></button>
            </div>
            <!-- /modal footer -->

        </div>
        <!-- /modal content -->

    </div>
</div>
