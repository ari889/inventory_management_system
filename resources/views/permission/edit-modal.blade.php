<div class="modal fade show" id="update_modal" tabindex="-1" role="dialog" aria-labelledby="model-1" aria-modal="true">
    <div class="modal-dialog" role="document">

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
                <form id="update_form" method="POST">
                    <div class="row">
                        <input type="hidden" name="update_id" id="update_id">
                        <x-form.textbox labelName="Name" name="name" required="required" col="col-md-12"  placeholder="Enter Name" onkeyup="url_generator(this.value, 'slug')" />
                        <x-form.textbox labelName="Slug" name="slug" required="required" col="col-md-12"  placeholder="Enter Slug" />
                    </div>
                </form>
            </div>
            <!-- /modal body -->

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-sm" id="update-btn"></button>
            </div>
            <!-- /modal footer -->

        </div>
        <!-- /modal content -->

    </div>
</div>
