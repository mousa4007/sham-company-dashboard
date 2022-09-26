<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateProductDiscount" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">تخصيص الأسعار لكافة المجموعات
                </h5>
                @include('components.spinner')
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row" id="table-striped">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div class="d-flex align-items-baseline">

                                    <h3 class="card-title me-5"> المنتجات
                                    </h3>
                                    <div class="mb-1">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1"><i
                                                    class="bi bi-search"></i></span>
                                            <input type="text" class="form-control" aria-label="Recipient's username"
                                                aria-describedby="button-addon2" wire:model='searchTerm'>
                                            <button class="btn btn-outline-secondary" type="button"
                                                id="button-addon2">بحث</button>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="card-content">
                                <!-- table striped -->
                                <div class="table-responsive p-2">
                                    <table class="table mb-0">
                                        <thead>
                                            <th>#</th>
                                            <th>اسم المنتج</th>
                                            <th>السعر</th>
                                            @foreach ($discounts as $discount)
                                                <th class="fs-6">{{ $discount->name }} ( {{ $discount->percentage }} %)
                                                </th>
                                            @endforeach
                                        </thead>
                                        <tbody>
                                            @foreach ($products as $product)
                                                <tr>
                                                    <td>{{ $product->id }}
                                                    </td>
                                                    <td>{{ $product->name }}
                                                    </td>
                                                    <td class="text-success" style="font-weight: bold">
                                                        {{ $product->sell_price }}
                                                    </td>
                                                    @foreach ($discounts as $discount)
                                                        <td>
                                                            @if ($discount->exceptions->where('product_id', $product->id)->first())
                                                                <div style="display: flex">
                                                                    <input disabled class="form-control d-flex "
                                                                        type="text"
                                                                        value="{{ $discount->exceptions->where('product_id', $product->id)->first()->sell_price }}">
                                                                    <input class="form-control d-flex mx-2"
                                                                        type="text"
                                                                        wire:model.defer='price.{{ $product->id }}.{{ $discount->id }}'>
                                                                    <button class="btn btn-success"
                                                                        wire:click='createDiscountException({{ $product->id }},{{ $discount->id }})'>
                                                                        <span style="color: white">✔</span>
                                                                    </button>
                                                                </div>
                                                            @else
                                                                <div style="display: flex">
                                                                    <input disabled class="form-control d-flex "
                                                                        type="text"
                                                                        value="{{ $product->sell_price + ($product->sell_price * $discount->percentage) / 100 }}">
                                                                    <input class="form-control d-flex mx-2"
                                                                        type="text"
                                                                        wire:model.defer='price.{{ $product->id }}.{{ $discount->id }}'>
                                                                    <button class="btn btn-success"
                                                                        wire:click='createDiscountException({{ $product->id }},{{ $discount->id }})'>
                                                                        <span style="color: white">✔</span>
                                                                    </button>
                                                                </div>
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-warning" wire:click='resetPercentage'>ارجاع النسب الأصلية</button>

            </div>
        </div>
    </div>
</div>
<!-- Modal -->
