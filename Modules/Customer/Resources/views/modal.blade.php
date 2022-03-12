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
                        <input type="hidden" name="update_id" id="update_id">
                        <x-form.selectbox labelName="Customer group" name="customer_group_id" required="required" col="col-md-6"  class="selectpicker">
                            @if(!$customer_groups->isEmpty())
                                @foreach($customer_groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->group_name }}</option>
                                @endforeach
                            @endif
                        </x-form.selectbox>
                        <x-form.textbox labelName="Customer Name" name="name" required="required" col="col-md-6"  placeholder="Enter customer name" />
                        <x-form.textbox labelName="Company Name" name="company_name" required="required" col="col-md-6"  placeholder="Enter company name" />
                        <x-form.textbox labelName="Tax Number" name="tax_number" required="required" col="col-md-6"  placeholder="Enter tax number" />
                        <x-form.textbox labelName="Phone number" name="phone" required="required" col="col-md-6"  placeholder="Enter phone number" />
                        <x-form.textbox labelName="Email" name="email" required="required" col="col-md-6"  placeholder="Enter valid email address" />
                        <x-form.textarea labelName="Address" name="address" required="required" col="col-md-6"  placeholder="Enter address" />
                        <x-form.textbox labelName="City" name="city" required="required" col="col-md-6"  placeholder="Enter city" />
                        <x-form.textbox labelName="State" name="state" required="required" col="col-md-6"  placeholder="Enter state" />
                        <x-form.textbox labelName="Postal Code" name="postal_code" required="required" col="col-md-6"  placeholder="Enter postal code" />
                        <x-form.textbox labelName="Country" name="country" required="required" col="col-md-6"  placeholder="Enter country" />
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
