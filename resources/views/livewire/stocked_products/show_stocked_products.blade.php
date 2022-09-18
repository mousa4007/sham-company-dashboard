<div>
    <!-- Button trigger modal -->

    <!-- Modal -->
    <div wire:ignore.self class="modal fade {{ $class }}" id="showStockedProductModal" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true" role="dialog" style="{{ $style }}">
        <div class="modal-dialog modal-full modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="showStockedProductModal"> المنتجات</h5>
                    <div class="col-12">
                        <div class="buttons m-3">

                            <a href="#" class="btn btn-danger btn-sm m-0 mx-1" wire:click='destroyShow'>حذف</a>
                            <a href="#" class="btn btn-success btn-sm m-0 mx-1"
                                wire:click='activateShow'>تفعيل</a>
                            <a href="#" class="btn btn-secondary btn-sm m-0 mx-1"
                                wire:click='disableShow'>تعطيل</a>

                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <div class="table-responsive p-2">
                            <table class="table mb-0">

                                <thead class="table-primary">
                                    <th>
                                        <input wire:model='checkedShow' type="checkbox" class="form-check-input">
                                    </th>
                                    <th>#</th>
                                    <th> المنتج المخزون</th>
                                    <th> المنتج</th>
                                    <th>الحالة</th>
                                    <th>مباع</th>
                                </thead>


                                <tbody>
                                    @forelse ($data as $product)
                                        <tr>
                                            <td><input wire:model='selectedRowsShow' value="{{ $product['id'] }}"
                                                    value="2" type="checkbox"
                                                    class="form-check-input form-check-secondary"></td>

                                            <td>{{ $product['id'] }}</td>
                                            <td>{{ $product['product_item'] }}</td>
                                            <td>{{ $productList->find($product['product_id'])->name }}</td>
                                            <td>{{ $product['status'] == 'active' ? 'مفعل' : 'معطل' }}</td>
                                            <td>{{ $product['selled'] == true ? 'مباع' : 'غير مباع' }}</td>
                                        </tr>
                                    @empty
                                        @include('components.not-found')
                                    @endforelse
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
