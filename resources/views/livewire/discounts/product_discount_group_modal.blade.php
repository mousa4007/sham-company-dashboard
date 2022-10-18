<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateProductDiscount" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content modal-dialog-scrollable">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">   تخصيص أسعار المنتجات
                </h5>
                @include('components.spinner')
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row" id="table-striped">
                    <div class="col-12">
                        <div class="card">
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
                                                                        value="{{ $discount->exceptions->where('product_id', $product->id)->first()->price }}">
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
                {{-- <button type="button" class="btn btn-warning" wire:click='resetPercentage'>ارجاع النسب الأصلية</button> --}}

            </div>
        </div>
    </div>
</div>
<!-- Modal -->
