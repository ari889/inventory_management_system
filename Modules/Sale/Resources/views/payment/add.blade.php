<div class="modal fade show" id="payment_modal" tabindex="-1" role="dialog" aria-labelledby="model-1" aria-modal="true">
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
                <form id="payment_form" method="POST">
                    @csrf
                    <div class="row">
                        <input type="hidden" name="payment_id" id="payment_id">
                        <input type="hidden" name="sale_id" id="sale_id">
                        <input type="hidden" name="balance" id="balance">
                        <x-form.textbox labelName="Received Amount" name="paying_amount" required="required" col="col-md-12" />
                        <div class="form-group col-md-12 required">
                            <label for="payable_amount">Paying Amount</label>
                            <input type="text" name="amount" id="amount" class="form-control">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="change_amount">Change Amount</label>
                            <input type="text" name="change_amount" id="change_amount" class="form-control" readonly>
                        </div>
                        <x-form.selectbox labelName="Payment Method" name="payment_method" required="required" col="col-md-12" class="selectpicker">
                            @foreach (PAYMENT_METHOD as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </x-form.selectbox>
                        <x-form.selectbox labelName="Account" name="account_id" required="required" col="col-md-12" class="selectpicker">
                            @if (!$accounts->isEmpty())
                            @foreach ($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name.' - '.$account->account_no }}</option>
                            @endforeach
                            @endif
                        </x-form.selectbox>
                        <div class="form-group col-md-12 payment_no d-none">
                            <label for="payment_no"><span id="method-name"></span> No</label>
                            <input type="text" name="payment_no" id="payment_no" class="form-control">
                        </div>
                        <x-form.textarea labelName="Payment Note" name="payment_note" col="col-md-12" />
                    </div>
                </form>
            </div>
            <!-- /modal body -->

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-sm" id="payment-save-btn">Save</button>
            </div>
            <!-- /modal footer -->

        </div>
        <!-- /modal content -->

    </div>
</div>
