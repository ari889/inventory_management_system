@extends('layouts.app')

@section('title')
{{$page_title}}
@endsection

@push('stylesheets')
<link rel="stylesheet" href="css/jquery-ui.min.css">
<style>
    .ui-menu .ui-menu-item {
        padding: 5px 10px !important;
        transition: 0.3s !important;
    }

    .ui-menu .ui-menu-item:hover {
        background: #ddd !important;
    }

</style>
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
                @if (permission('sale-edit'))
                <a href="{{ route('sale') }}" class="btn btn-warning btn-sm"><i
                        class="fas fa-arow-left mr-2"></i>Back</a>
                @endif

            </div>
            <!-- /entry header -->

            <!-- Card -->
            <div class="dt-card">

                <!-- Card Body -->
                <div class="dt-card__body">

                    <form id="sale-form">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="sale_id" value="{{ $sale->id }}">
                            <input type="hidden" name="warehouse_id_hidden" value="{{ $sale->warehouse_id }}">
                            <input type="hidden" name="customer_id_hidden" value="{{ $sale->customer_id }}">
                            <input type="hidden" name="sale_status_hidden" value="{{ $sale->sale_status }}">
                            <input type="hidden" name="order_tax_rate_hidden" value="{{ $sale->order_tax_rate }}">
                            <x-form.selectbox labelName="Warehouse" name="warehouse_id" required="required" col="col-md-6" class="selectpicker">
                                @if (!$warehouses->isEmpty())
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                @endif
                            </x-form.selectbox>
                            <x-form.selectbox labelName="Customer" name="customer_id"  required="required" col="col-md-6" class="selectpicker">
                                @if (!$customers->isEmpty())
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}
                                            {{ $customer->company_name ? ' - '.$customer->company_name : '' }}</option>
                                    @endforeach
                                @endif
                            </x-form.selectbox>
                            <div class="form-group col-md-12">
                                <label for="product_code_name">Search Product</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="product_code_name"
                                        id="product_code_name" class="form-control"
                                        placeholder="Type barcode or name and select product">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <table class="table table-bordered" id="product-list">
                                    <thead class="bg-primary">
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th class="text-center">Unit</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-right">Net Unit Price</th>
                                        <th class="text-right">Discount</th>
                                        <th class="text-right"> Tax</th>
                                        <th class="text-right">Subtotal</th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                        @php
                                            $temp_unit_name            = [];
                                            $temp_unit_operator        = [];
                                            $temp_unit_operation_value = [];
                                        @endphp
                                        @if (!$sale->sale_products->isEmpty())
                                            @foreach ($sale->sale_products as $key => $sale_product)
                                                <tr>
                                                    @php
                                                        $tax = DB::table('taxes')->where('rate', $sale_product->pivot->tax_rate)->first();

                                                        $units = DB::table('units')->where('base_unit', $sale_product->unit_id)
                                                                                    ->orWhere('id', $sale_product->unit_id)
                                                                                    ->get();
                                                        $warehouse_qty = DB::table('warehouse_products')->where(['warehouse_id' => $sale->warehouse_id, 'product_id' => $sale_product->id])->value('qty');
                                                        $stock_qty = ($warehouse_qty ? $warehouse_qty : 0) + $sale_product->pivot->qty;
                                                        $unit_name            = [];
                                                        $unit_operator        = [];
                                                        $unit_operation_value = [];

                                                        if($units){
                                                            foreach ($units as $unit) {
                                                                if($sale_product->pivot->sale_unit_id == $unit->id){
                                                                    array_unshift($unit_name, $unit->unit_name);
                                                                    array_unshift($unit_operator, $unit->operator);
                                                                    array_unshift($unit_operation_value, $unit->operation_value);
                                                                }else{
                                                                    $unit_name           [] = $unit->unit_name;
                                                                    $unit_operator       [] = $unit->operator;
                                                                    $unit_operation_value[] = $unit->operation_value;
                                                                }
                                                            }
                                                            if($sale_product->tax_method == 1){
                                                                $product_price = ($sale_product->pivot->net_unit_price + ($sale_product->pivot->discount / $sale_product->pivot->qty));
                                                            }else{
                                                                $product_price = (($sale_product->pivot->total + $sale_product->pivot->qty) +
                                                                ($sale_product->pivot->discount / $sale_product->pivot->qty));
                                                            }

                                                            if($unit_operator[0] == '*'){
                                                                $product_price = $product_price * $unit_operation_value[0];
                                                            }else if($unit_operator[0] == '/'){
                                                                $product_price = $product_price / $unit_operation_value[0];
                                                            }

                                                            $temp_unit_name            = $unit_name            = implode(",", $unit_name).',';
                                                            $temp_unit_operator        = $unit_operator        = implode(",", $unit_operator).',';
                                                            $temp_unit_operation_value = $unit_operation_value = implode(",", $unit_operation_value).',';
                                                        }
                                                    @endphp
                                                    <td>{{ $sale_product->name }}</td>

                                                    <td>{{ $sale_product->code }}</td>
                                                    <td class="unit-name"></td>
                                                    <td><input type="text" class="form-control qty text-center" name="products[{{ $key+1 }}][qty]" id="products_{{ $key+1 }}_qty" value="{{ $sale_product->pivot->qty }}"></td>
                                                    <td class="net_unit_price text-right">{{ number_format((float)$sale_product->pivot->net_unit_price , 2, '.', ',') }}</td>
                                                    <td class="discount text-right">{{ number_format((float)$sale_product->pivot->discount , 2, '.', ',') }}</td>
                                                    <td class="tax text-right">{{ number_format((float)$sale_product->pivot->tax , 2, '.', ',') }}</td>
                                                    <td class="sub-total text-right">{{ number_format((float)$sale_product->pivot->total , 2, '.', ',') }}</td>
                                                    <td>
                                                        <button type="button" class="edit-product btn btn-sm btn-primary mr-2" data-toggle="modal" data-target="#editModal"><i class="fas fa-edit"></i></button>
                                                        <button type="button" class="btn btn-danger btn-sm remove-product"><i class="fas fa-trash"></i></button>
                                                    </td>
                                                    <input type="hidden" class="product-id" name="products[{{ $key+1 }}][id]"  value="{{ $sale_product->id }}">
                                                    <input type="hidden" class="stock-qty" name="products[{{ $key+1 }}][stock_qty]"  value="{{ $stock_qty }}">
                                                    <input type="hidden" class="product-code" name="products[{{ $key+1 }}][code]" value="{{ $sale_product->code }}">
                                                    <input type="hidden" class="product-price" name="products[{{ $key+1 }}][price]" value="{{ $product_price }}">
                                                    <input type="hidden" class="sale-unit" name="products[{{ $key+1 }}][unit]" value="{{ $unit_name }}">
                                                    <input type="hidden" class="sale-unit-operator" value="{{ $unit_operator }}">
                                                    <input type="hidden" class="sale-unit-operation-value" value="{{ $unit_operation_value }}">
                                                    <input type="hidden" class="net_unit_price" name="products[{{ $key+1 }}][net_unit_price]" value="{{ $sale_product->pivot->net_unit_price }}">
                                                    <input type="hidden" class="discount-value" name="products[{{ $key+1 }}][discount]" value="{{ $sale_product->pivot->discount }}">
                                                    <input type="hidden" class="tax-rate" name="products[{{ $key+1 }}][tax_rate]" value="{{ $sale_product->pivot->tax_rate }}">
                                                    @if ($tax)
                                                    <input type="hidden" class="tax-name" value="{{ $tax->name }}">
                                                    @else
                                                    <input type="hidden" class="tax-name" value="No Tax">
                                                    @endif
                                                    <input type="hidden" class="tax-method" value="{{ $sale_product->tax_method }}">
                                                    <input type="hidden" class="tax-value" name="products[{{ $key+1 }}][tax]" value="{{ $sale_product->pivot->tax }}">
                                                    <input type="hidden" class="subtotal-value" name="products[{{ $key+1 }}][subtotal]" value="{{ $sale_product->pivot->total }}">
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                    <tfoot class="bg-primary">
                                        <th colspan="3">Total</th>
                                        <th id="total-qty" class="text-center">{{ $sale->total_qty }}</th>
                                        <th></th>
                                        <th id="total-discount" class="text-right">{{ number_format($sale->total_discount, 2, '.', ',') }}</th>
                                        <th id="total-tax" class="text-right">{{ number_format($sale->total_tax, 2, '.', ',') }}</th>
                                        <th id="total" class="text-right">{{ number_format($sale->total_price, 2, '.', ',') }}</th>
                                        <th></th>
                                    </tfoot>
                                </table>
                            </div>
                            <x-form.selectbox labelName="Order Tax" name="order_tax_rate" col="col-md-4"
                                class="selectpicker">
                                <option value="0" selected>No Tax</option>
                                @if(!$taxes->isEmpty())
                                    @foreach($taxes as $tax)
                                        <option value="{{ $tax->rate }}">{{ $tax->name }}</option>
                                    @endforeach
                                @endif
                            </x-form.selectbox>

                            <div class="form-group col-md-4">
                                <label for="order_discount">Order Discount</label>
                                <input type="number" class="form-control" name="order_discount" id="order_discount" class="form-control" value="{{ $sale->order_discount }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="shipping_cost">Shipping Cost</label>
                                <input type="number" class="form-control" name="shipping_cost" id="shipping_cost" class="form-control" value="{{ $sale->shipping_cost }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="document">Attach Document</label>
                                <input type="file" class="form-control" name="document" id="document"
                                    class="form-control">
                            </div>
                            <x-form.selectbox labelName="Sale Status" name="sale_status"  required="required" col="col-md-4" class="selectpicker">
                                @foreach (SALE_STATUS as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </x-form.selectbox>
                            <div class="form-group col-md-12">
                                <label for="note">Note</label>
                                <textarea name="note" id="note" cols="30" rows="3" class="form-control">{{ $sale->note }}</textarea>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <thead class="bg-primary">
                                        <th><strong>Items</strong><span class="float-right" id="item">0.00</span></th>
                                        <th><strong>Total</strong><span class="float-right" id="subtotal">0.00</span>
                                        </th>
                                        <th><strong>Order Tax</strong><span class="float-right"
                                                id="order_total_tax">0.00</span></th>
                                        <th><strong>Order Discount</strong><span class="float-right"
                                                id="order_total_discount">0.00</span></th>
                                        <th><strong>Shipping Cost</strong><span class="float-right"
                                                id="shipping_total_cost">0.00</span></th>
                                        <th><strong>Grand Total</strong><span class="float-right"
                                                id="grand_total">0.00</span></th>
                                    </thead>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <input type="hidden" name="total_qty" value="{{ $sale->total_qty }}">
                                <input type="hidden" name="total_discount" value="{{ $sale->total_discount }}">
                                <input type="hidden" name="total_tax" value="{{ $sale->total_tax }}">
                                <input type="hidden" name="total_price" value="{{ $sale->total_price }}">
                                <input type="hidden" name="item" value="{{ $sale->item }}">
                                <input type="hidden" name="order_tax" value="{{ $sale->order_tax }}">
                                <input type="hidden" name="grand_total" value="{{ $sale->grand_total }}">
                                <input type="hidden" name="payment_status" value="{{ $sale->payment_status }}">
                            </div>
                            <div class="form-group col-md-12 text-center">
                                <button class="btn btn-danger btn-sm" id="reset-btn" type="button">Reset</button>
                                <button class="btn btn-primary btn-sm" id="save-btn" type="button">Update</button>
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

    <!-- start edit modal -->
    <div class="modal fade show" id="editModal" tabindex="-1" role="dialog" aria-labelledby="model-1" aria-modal="true">
        <div class="modal-dialog" role="document">

            <!-- Modal Content -->
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header bg-primary">
                    <h3 class="modal-title text-white" id="model-title"></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">??</span>
                    </button>
                </div>
                <!-- /modal header -->

                <!-- Modal Body -->
                <div class="modal-body">
                    <form id="edit_form" method="POST">
                        <div class="row">
                            <x-form.textbox labelName="Quantity" name="edit_qty" required="required" col="col-md-12" />
                            <x-form.textbox labelName="Unit Discount" name="edit_discount" col="col-md-12"/>
                            <x-form.textbox labelName="Unit Cost" name="edit_unit_cost" col="col-md-12"/>
                            @php
                                $tax_name_all[] =  'No Tax';
                                $tax_rate_all[] =  0;
                                foreach ($taxes as $tax) {
                                    $tax_name_all[] =  $tax->name;
                                    $tax_rate_all[] =  $tax->rate;
                                }
                            @endphp
                            <div class="form-group col-md-12">
                                <label for="edit_tax_rate">Tax Rate</label>
                                <select name="edit_tax_rate" class="form-control selectpicker" data-live-search-placeholder="Search" data-live-search="true" id="edit_tax_rate">
                                    @foreach ($tax_name_all as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="edit_unit">Product Unit</label>
                                <select name="edit_unit" class="form-control selectpicker" data-live-search-placeholder="Search" data-live-search="true" id="edit_unit"></select>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /modal body -->

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-sm" id="update-btn">Update</button>
                </div>
                <!-- /modal footer -->

            </div>
            <!-- /modal content -->

        </div>
    </div>

    <!-- end edit modal -->
</div>
@endsection

@push('scripts')
<script src="js/jquery-ui.min.js"></script>
<script>
    $(document).ready(function () {
        $('#product_code_name').on('input', function(){
            var customer_id  = $('#customer_id option:selected').val();
            var warehouse_id  = $('#warehouse_id option:selected').val();
            var temp_data = $('#product_code_name').val();
            if(!warehouse_id){
                $('#product_code_name').val(temp_data.substring(0, temp_data.length - 1));
                notification('error', 'Please select warehouse');
            }else if(!customer_id){
                $('#product_code_name').val(temp_data.substring(0, temp_data.length - 1));
                notification('error', 'Please select customer');
            }
        });
        $('#product_code_name').autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "{{ url('product-autocomplete-search') }}",
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        _token: _token,
                        search: request.term,
                        warehouse_id : $('#warehouse_id option:selected').val()
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            minLength: 1,
            response: function (event, ui) {
                if (ui.content.length == 1) {
                    var data = ui.content[0].value;
                    $(this).autocomplete('close');
                    product_search(data);
                }
            },
            select: function (event, ui) {
                var data = ui.item.value;
                product_search(data);
            }
        }).data('ui-autocomplete')._renderItem = function (ul, item) {
            return $("<li class='ui-autocomplete-row'></li>")
                .data('item.autocomplete', item)
                .append(item.label)
                .appendTo(ul);
        };

        // array data depend on warehouse
        var product_array = [];
        var product_code = [];
        var product_name = [];
        var product_qty = [];

        // array data with selection
        var product_price = [];
        var product_discount = [];
        var tax_rate = [];
        var tax_name = [];
        var tax_method = [];
        var unit_name = [];
        var unit_operator = [];
        var unit_operation = [];
        var unit_operation_value = [];

        // temporary array
        var temp_unit_name = [];
        var temp_unit_operation = [];
        var temp_unit_operation_value = [];

        var rowindex;
        var customer_group_rate;
        var row_product_price;
        var pos;

        var rownumber = $('#product-list tbody tr:last').index();

        for (rowindex = 0; rowindex <= rownumber; rowindex++) {
            product_price.push(parseFloat($('#product-list tbody tr:nth-child('+(rowindex + 1)+')').find('.product-price').val()));
            var total_discount = parseFloat($('#product-list tbody tr:nth-child('+(rowindex + 1)+')').find('td:nth-child(6)').text());
            var quantity = parseFloat($('#product-list tbody tr:nth-child('+(rowindex + 1)+')').find('.qty').val());
            product_discount.push((total_discount / quantity).toFixed(2));
            product_qty.push(parseFloat($('#product-list tbody tr:nth-child('+(rowindex + 1)+')').find('.stock-qty').val()));
            tax_rate.push(parseFloat($('#product-list tbody tr:nth-child('+(rowindex + 1)+')').find('.tax-rate').val()));
            tax_name.push($('#product-list tbody tr:nth-child('+(rowindex + 1)+')').find('.tax-name').val());
            tax_method.push($('#product-list tbody tr:nth-child('+(rowindex + 1)+')').find('.tax-method').val());
            temp_unit_name = $('#product-list tbody tr:nth-child('+(rowindex + 1)+')').find('.sale-unit').val().split(',');
            unit_name.push($('#product-list tbody tr:nth-child('+(rowindex + 1)+')').find('.sale-unit').val());
            unit_operator.push($('#product-list tbody tr:nth-child('+(rowindex + 1)+')').find('.sale-unit-operator').val());
            unit_operation_value.push($('#product-list tbody tr:nth-child('+(rowindex + 1)+')').find('.sale-unit-operation-value').val());
            $('#product-list tbody tr:nth-child('+(rowindex + 1)+')').find('.sale-unit').val(temp_unit_name[0]);
            $('#product-list tbody tr:nth-child('+(rowindex + 1)+')').find('.unit-name').text(temp_unit_name[0]);
        }

        //assign value
        $('select[name="customer_id"]').val($('input[name="customer_id_hidden"]').val());
        $('select[name="warehouse_id"]').val($('input[name="warehouse_id_hidden"]').val());
        $('select[name="sale_status"]').val($('input[name="sale_status_hidden"]').val());
        $('select[name="order_tax_rate"]').val($('input[name="order_tax_rate_hidden"]').val());
        $('.selectpicker').selectpicker('refresh');

        $('#item').text($('input[name="item"]').val() + '('+$('input[name="total_qty"]').val()+')');
        $('#subtotal').text(parseFloat($('input[name="total_price"]').val()).toFixed(2));
        $('#order_total_tax').text(parseFloat($('input[name="order_tax"]').val()).toFixed(2));

        if(!$('input[name="order_discount"]').val()){
            $('input[name="order_discount"]').val('0.00')
        }

        $('#order_discount').text(parseFloat($('input[name="order_discount"]').val()).toFixed(2));

        if(!$('input[name="shipping_cost"]').val()){
            $('input[name="shipping_cost"]').val('0.00')
        }

        $('#shipping_total_cost').text(parseFloat($('input[name="shipping_cost"]').val()).toFixed(2));
        $('#grand_total').text(parseFloat($('input[name="grand_total"]').val()).toFixed(2));

        var id = $('input[name="customer_id_hidden"]').val();
        $.get('{{ url("customer/group-data") }}/'+id, function(data){
            customer_group_rate = (data/100);
        });

        // edit product
        $('#product-list').on('click', '.edit-product', function(){
            rowindex = $(this).closest('tr').index();
            edit();
        });

        // update edit product data
        $('#update-btn').on('click', function(){
            var edit_discount  = $('#edit_discount').val();
            var edit_qty       = $('#edit_qty').val();
            var edit_unit_cost = $('edit_unit_cost').val();

            if(parseFloat(edit_discount) > parseFloat(edit_unit_cost)){
                notification('error', 'Invalid discount input');
                return;
            }

            if(edit_qty < 1){
                $('#edit_qty').val(1);
                edit_qty = 1;
                notification('error', 'Quantity can\'t be less than 1');
            }

            var row_unit_operator        = unit_operator[rowindex].slice(0, unit_operator[rowindex].indexOf(','));
            var row_unit_operation_value = unit_operation_value[rowindex].slice(0, unit_operation_value[rowindex].indexOf(','));
                row_unit_operation_value = parseFloat(row_unit_operation_value);
            var tax_rate_all             = <?php echo json_encode($tax_rate_all) ?>;

            tax_rate[rowindex] = parseFloat(tax_rate_all[$('#edit_tax_rate option:selected').val()]);
            tax_name[rowindex] = $('#edit_tax_rate option:selected').text();

            if(row_unit_operator == '*')
            {
                product_price[rowindex] = $('#edit_unit_cost').val() / row_unit_operation_value;
            }else{
                product_price[rowindex] = $('#edit_unit_cost').val() * row_unit_operation_value;
            }

            product_discount[rowindex] = $('#edit_discount').val();
            var position = $('#edit_unit').val();
            var temp_operator = temp_unit_operator[position];
            var temp_operation_value = temp_unit_operation_value[position];

            $('#product-list tbody tr:nth-child('+(rowindex + 1)+')').find('.sale-unit').val(temp_unit_name[position]);
            temp_unit_name.splice(position,1);
            temp_unit_operator.splice(position,1);
            temp_unit_operation_value.splice(position,1);
            temp_unit_name.unshift($('#edit_unit option:selected').text());
            temp_unit_operator.unshift(temp_operator);
            temp_unit_operation_value.unshift(temp_operation_value);
            unit_name[rowindex] = temp_unit_name.toString() + ',';
            unit_operator[rowindex] = temp_unit_operator.toString() + ',';
            unit_operation_value[rowindex] = temp_unit_operation_value.toString() + ',';
            checkQuantity(edit_qty,false);
        });

        $('#product-list').on('keyup', '.qty', function(){
            rowindex = $(this).closest('tr').index();
            if($(this).val() < 1 && $(this).val() != ''){
                $('#product-list tbody tr:nth-child('+(rowindex + 1)+') .qty').val(1);
                notification('error', 'Quantity can\'t be less than 1');
            }

            checkQuantity($(this).val(), true);
        });

        $('#product-list').on('click', '.remove-product', function(){
            rowindex = $(this).closest('tr').index();
            product_price.splice(rowindex, 1);
            product_qty.splice(rowindex, 1);
            product_discount.splice(rowindex, 1);
            tax_rate.splice(rowindex, 1);
            tax_name.splice(rowindex, 1);
            tax_method.splice(rowindex, 1);
            unit_name.splice(rowindex, 1);
            unit_operator.splice(rowindex, 1);
            unit_operation_value.splice(rowindex, 1);
            $(this).closest('tr').remove();
            calculateTotal();
        });

        @if(!$sale->sale_products->isEmpty())
        var count = "{{ count($sale->sale_products) + 1 }}";
        @else
        var count = 1;
        @endif

        function product_search(data) {
            $.ajax({
                url: "{{ url('product-search') }}",
                type:"POST",
                data:{data:data,_token:_token,warehouse_id : $('#warehouse_id option:selected').val(), type: 'sale'},
                success: function(data)
                {
                    var flag = 1;
                    $('.product-code').each(function(i){
                        if($(this).val() == data.code){
                            rowindex = i;
                            var qty = parseFloat($('#product-list tbody tr:nth-child('+(rowindex + 1)+') .qty').val()) + 1;
                            $('#product-list tbody tr:nth-child('+(rowindex + 1)+') .qty').val(qty);
                            checkQuantity(String(qty), true);
                            flag = 0;
                        }
                    });
                    $('#product_code_name').val('');
                    if(flag)
                    {
                        temp_unit_name = data.unit_name.split(',');
                        var newRow = $('<tr>');
                        var cols = '';
                        cols += `<td>`+data.name+`</td>`;

                        cols += `<td>`+data.code+`</td>`;
                        cols += `<td class="unit-name"></td>`;
                        cols += `<td><input type="text" class="form-control qty text-center" name="products[`+count+`][qty]"
                            id="products_`+count+`_qty" value="1"></td>`;

                        cols += `<td class="net_unit_price text-right"></td>`;
                        cols += `<td class="discount text-right"></td>`;
                        cols += `<td class="tax text-right"></td>`;
                        cols += `<td class="sub-total text-right"></td>`;
                        cols += `<td><button type="button" class="edit-product btn btn-sm btn-primary mr-2" data-toggle="modal"
                            data-target="#editModal"><i class="fas fa-edit"></i></button>
                            <button type="button" class="btn btn-danger btn-sm remove-product"><i class="fas fa-trash"></i></button></td>`;
                        cols += `<input type="hidden" class="product-id" name="products[`+count+`][id]"  value="`+data.id+`">`;
                        cols += `<input type="hidden" class="stock-qty" name="products[`+count+`][stock_qty]"  value="`+data.qty+`">`;
                        cols += `<input type="hidden" class="product-code" name="products[`+count+`][code]" value="`+data.code+`">`;
                        cols += `<input type="hidden" class="product-unit" name="products[`+count+`][unit]" value="`+temp_unit_name[0]+`">`;
                        cols += `<input type="hidden" class="net_unit_price" name="products[`+count+`][net_unit_price]">`;
                        cols += `<input type="hidden" class="discount-value" name="products[`+count+`][discount]">`;
                        cols += `<input type="hidden" class="tax-rate" name="products[`+count+`][tax_rate]" value="`+data.tax_rate+`">`;
                        cols += `<input type="hidden" class="tax-value" name="products[`+count+`][tax]">`;
                        cols += `<input type="hidden" class="subtotal-value" name="products[`+count+`][subtotal]">`;
                        newRow.append(cols);
                        $('#product-list tbody').append(newRow);

                        product_price.push(parseFloat(data.price) + parseFloat(data.price * customer_group_rate));
                        product_qty.push(data.qty);
                        product_discount.push('0.00');
                        tax_rate.push(parseFloat(data.tax_rate));
                        tax_name.push(data.tax_name);
                        tax_method.push(data.tax_method);
                        unit_name.push(data.unit_name);
                        unit_operator.push(data.unit_operator);
                        unit_operation_value.push(data.unit_operation_value);
                        rowindex = newRow.index();
                        checkQuantity(1, true);
                        count++;
                    }
                }
            });
        }

        function checkQuantity(sale_qty, flag){
            var row_product_code = $('#product-list tbody tr:nth-child('+(rowindex + 1)+')').find('td:nth-child(2)').text();
            var operator = unit_operator[rowindex].split(',');
            var operation_value = unit_operation_value[rowindex].split(',');
            if(operator[0] == '*')
            {
                total_qty = sale_qty * operation_value[0];
            }else if(operator[0] == '/'){
                total_qty = sale_qty / operation_value[0];
            }

            if(total_qty > parseFloat(product_qty[rowindex])){
                notification('error', 'Quantity exced stock quantity');
                if(flag){
                    sale_qty = sale_qty.substring(0, sale_qty.length - 1);
                    $('#product-list tbody tr:nth-child('+(rowindex + 1)+')').find('.qty').val(sale_qty);
                }else{
                    edit();
                    return;
                }
            }
            if(!flag){
                $('#editModal').modal('hide');
                $('#product-list tbody tr:nth-child('+(rowindex + 1)+')').find('.qty').val(sale_qty);
            }
            calculateProductData(sale_qty);

        }

        function edit(){
            var row_product_name = $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(1)').text();
            var row_product_code = $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(2)').text();
            $('#model-title').text(row_product_name+'('+row_product_code+')');

            var qty = $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val();
            $('#edit_qty').val(qty);
            $('#edit_discount').val(parseFloat(product_discount[rowindex]).toFixed(2));

            unitConversion();
            $('#edit_unit_cost').val(row_product_price.toFixed(2));

            var tax_name_all = <?php echo json_encode($tax_name_all) ?>;
            var pos = tax_name_all.indexOf(tax_name[rowindex]);
            $('#edit_tax_rate').val(pos);

            temp_unit_name = (unit_name[rowindex]).split(',');
            temp_unit_name.pop();
            temp_unit_operator = (unit_operator[rowindex]).split(',');
            temp_unit_operator.pop();
            temp_unit_operation_value = (unit_operation_value[rowindex]).split(',');
            temp_unit_operation_value.pop();

            $('#edit_unit').empty();

            $.each(temp_unit_name, function(key, value){
                $('#edit_unit').append('<option value="'+key+'">'+value+'</option>')
            });

            $('.selectpicker').selectpicker('refresh');
        }

    function calculateProductData(quantity) {
        unitConversion();
        $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(6)').text((
            product_discount[rowindex] * quantity).toFixed(2));
        $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.discount-value').val((
            product_discount[rowindex] * quantity).toFixed(2));
        $('#product-list tbody tr:nth-child('+(rowindex + 1)+')').find('.tax-rate').val(tax_rate[rowindex].toFixed(2));
        $('#product-list tbody tr:nth-child('+(rowindex + 1)+')').find('.unit-name').text(unit_name[rowindex].slice(0,unit_name[rowindex].indexOf(",")));

        if (tax_method[rowindex] == 1) {
            var net_unit_price = row_product_price - product_discount[rowindex];
            var tax = net_unit_price * quantity * (tax_rate[rowindex] / 100);
            var sub_total = (net_unit_price * quantity) + tax;
        } else {
            var sub_total_unit = row_product_price - product_discount[rowindex];
            var net_unit_price = (100 / (100 + tax_rate[rowindex])) * sub_total_unit;
            var tax = (sub_total_unit - net_unit_price) * quantity;
            var sub_total = sub_total_unit * quantity;
        }

        $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(5)').text(net_unit_price
            .toFixed(2));
        $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.net_unit_price').val(net_unit_price
            .toFixed(2));
        $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(7)').text(tax.toFixed(
            2));
        $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax-value').val(tax.toFixed(2));
        $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(8)').text(sub_total
            .toFixed(2));
        $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.subtotal-value').val(sub_total
            .toFixed(2));

        calculateTotal();
    }

    function unitConversion() {
        var row_unit_operator = unit_operator[rowindex].slice(0, unit_operator[rowindex].indexOf(','));
        var row_unit_operation_value = unit_operation_value[rowindex].slice(0, unit_operation_value[rowindex]
            .indexOf(','));
        row_unit_operation_value = parseFloat(row_unit_operation_value);
        if (row_unit_operator == '*') {
            row_product_price = product_price[rowindex] * row_unit_operation_value;
        } else {
            row_product_price = product_price[rowindex] / row_unit_operation_value;
        }
    }

    function calculateTotal() {
        // sum of qty
        var total_qty = 0;
        $('.qty').each(function () {
            if ($(this).val() == '') {
                total_qty += 0;
            } else {
                total_qty += parseFloat($(this).val());
            }
        });
        $('#total-qty').text(total_qty);
        $('input[name="total_qty"]').val(total_qty);

        // sum of discount
        var total_discount = 0;
        $('.discount').each(function () {
            total_discount += parseFloat($(this).text());
        });

        $('#total-discount').text(total_discount.toFixed(2));
        $('input[name="total_discount"]').val(total_discount);

        //sum of tax
        var total_tax = 0;
        $('.tax').each(function () {
            total_tax += parseFloat($(this).text());
        });

        $('#total-tax').text(total_tax.toFixed(2));
        $('input[name="total_tax"]').val(total_tax);

        //sum of subtotal
        var total = 0;
        $('.sub-total').each(function () {
            total += parseFloat($(this).text());
        });

        $('#total').text(total.toFixed(2));
        $('input[name="total_price"]').val(total);

        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        var item = $('#product-list tbody tr:last').index();
        var total_qty = parseFloat($('#total-qty').text());
        var subtotal = parseFloat($('input[name="total_price"]').val());
        var order_tax = parseFloat($('select[name="order_tax_rate"]').val());
        var order_discount = parseFloat($('#order_discount').val());
        var shipping_cost = parseFloat($('#shipping_cost').val());

        if (!order_discount) {
            order_discount = 0.00;
        }
        if (!shipping_cost) {
            shipping_cost = 0.00;
        }


        item = ++item + '(' + total_qty + ')';
        order_tax = (subtotal - order_discount) * (order_tax / 100);
        var grand_total = (subtotal + order_tax + shipping_cost) - order_discount;

        $('#item').text(item);
        $('input[name="item"]').val($('#product-list tbody tr:last').index() + 1);
        $('#subtotal').text(subtotal.toFixed(2));
        $('#order_total_tax').text(order_tax.toFixed(2));
        $('input[name="order_tax"]').val(order_tax.toFixed(2));
        $('#order_total_discount').text(order_discount.toFixed(2));
        $('#shipping_total_cost').text(shipping_cost.toFixed(2));
        $('#grand_total').text(grand_total.toFixed(2));
        $('input[name="grand_total"]').val(grand_total.toFixed(2));
    }


    $('input[name="order_discount"]').on('input', function () {
        calculateGrandTotal();
    }); $('input[name="shipping_cost"]').on('input', function () {
        calculateGrandTotal();
    }); $('select[name="order_tax_rate"]').on('change', function () {
        calculateGrandTotal();
    });

    $(document).on('click', '#save-btn', function(e){
        e.preventDefault();
        var rownumber = $('#product-list tbody tr:last').index();

        if(rownumber < 0){
            notification('error', 'Please add product to table');
        }else{
            let form = document.getElementById('sale-form');
            let formData = new FormData(form);
            $.ajax({
                url: "{{route('sale.update')}}",
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
                    $('#purchase-form').find('.is-invalid').removeClass('is-invalid');
                    $('#purchase-form').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            var key = key.split('.').join('_');
                            $('#purchase-form input#' + key).addClass('is-invalid');
                            $('#purchase-form textarea#' + key).addClass('is-invalid');
                            $('#purchase-form select#' + key).parent().addClass('is-invalid');
                            $('#purchase-form #' + key).parent().append(
                                '<small class="error text-danger">' + value + '</small>');
                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') {
                            window.location.replace('{{ route("sale") }}');
                        }
                    }
                },
                error: function (xhr, ajaxOption, thrownError) {
                    console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                }
            });
        }
    })

    });

</script>
@endpush
