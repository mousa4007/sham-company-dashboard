<div>
    <!-- Update Product Discount -->
    <div wire:ignore.self class="modal fade" id="updateProductDiscount" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">إدارة نسب
                        أسعار الأقسام
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" id="table-striped">
                        <div class="col-12">
                            <div class="card">
                                {{-- <div class="card-header d-flex justify-content-between">
                                    <div class="d-flex align-items-baseline">
                                        <h3 class="card-title me-5"> المنتجات
                                        </h3>
                                        <div class="mb-1">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="basic-addon1"><i
                                                        class="bi bi-search"></i></span>
                                                <input type="text" class="form-control"
                                                    aria-label="Recipient's username" aria-describedby="button-addon2"
                                                    wire:model='searchTerm'>
                                                <button class="btn btn-outline-secondary" type="button"
                                                    id="button-addon2">بحث</button>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="card-content">
                                    <!-- table striped -->
                                    <div class="table-responsive p-2">
                                        <table class="table mb-0">
                                            <thead>
                                                <th>#</th>
                                                <th>اسم المنتج</th>
                                                <th>السعر</th>
                                                @foreach ($discounts as $discount)
                                                    <th rowspan="2">
                                                        {{ $discount->name }}
                                                    </th>
                                                @endforeach
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    @foreach ($discounts as $discount)
                                                        <td>
                                                            <input type="text" class="form-control"
                                                                wire:model='newPercentage.{{ $discount->id }}'>
                                                        </td>
                                                    @endforeach
                                                </tr>

                                                @foreach ($products as $product)
                                                    <tr>
                                                        <td>{{ $product->id }}
                                                        </td>
                                                        <td>{{ $product->name }}
                                                        </td>
                                                        <td>{{ $product->sell_price }}
                                                        </td>
                                                        @foreach ($discounts as $discount)
                                                            <td>
                                                                <input readonly="readonly" class="form-control"
                                                                    type="text"
                                                                    value="{{ $product->sell_price + ($product->sell_price * $discount->percentage) / 100 }}">
                                                            </td>
                                                        @endforeach

                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    @foreach ($discounts as $discount)
                                                        <td>
                                                            <button class="btn btn-warning btn-sm"
                                                                wire:click='update({{ $discount->id }})'>حفظ</button>
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <div class="my-3">
                                            {{-- {{ $products->links() }} --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>

                </div>
            </div>
        </div>
    </div>
    <!-- Update Product Discount -->
</div>
