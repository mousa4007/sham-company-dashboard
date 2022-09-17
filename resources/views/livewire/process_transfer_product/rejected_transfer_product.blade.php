<div>
    <!-- Button trigger modal -->

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="rejectedTransferProductModal" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">منتجات التحويل اليدوي المرفوضة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    @include('components.datepicker')
                                </div>
                            </div>
                        </div>

                        <div class="row" id="table-striped">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between">
                                        <div class="d-flex align-items-baseline">
                                            <h3 class="card-title me-5">منتجات التحويل اليدوي المرفوضة</h3>
                                            <div class="mb-1">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="basic-addon1"><i
                                                            class="bi bi-search"></i></span>
                                                    <input type="text" class="form-control"
                                                        aria-label="Recipient's username"
                                                        aria-describedby="button-addon2" wire:model='searchTerm'>
                                                    <button class="btn btn-outline-secondary" type="button"
                                                        id="button-addon2">بحث</button>
                                                    @include('components.spinner')
                                                </div>
                                            </div>
                                            {{-- @include('livewire.stocked_products.create_stocked_products') --}}
                                        </div>

                                    </div>
                                    {{-- @include('livewire.stocked_products.delete_stocked_products') --}}
                                    {{-- @include('livewire.stocked_products.update_stocked_products') --}}
                                    {{-- @include('livewire.stocked_products.show_stocked_products') --}}


                                    <div class="card-content">
                                        <!-- table striped -->
                                        <div class="table-responsive p-2">
                                            <table class="table table-bordered ">
                                                <thead>
                                                    <tr class="table-primary">
                                                        <th><input wire:model='checked' type="checkbox"
                                                                class="form-check-input"></th>
                                                        <th>#</th>
                                                        <th>المستخدم</th>
                                                        <th>المنتج</th>
                                                        <th>المنتج المراد تحويله</th>
                                                        <th> المبلغ </th>
                                                        <th>التاريخ</th>
                                                        <th>تعديل</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @forelse ($rejectedTransferProducts as  $item)
                                                        <tr>
                                                            <td><input wire:model='selectedRows'
                                                                    value="{{ $item->id }}" type="checkbox"
                                                                    class="form-check-input">
                                                            </td>
                                                            <td> {{ $item->id }}</td>
                                                            <td>{{ $app_users->find($item->app_user_id)->name }}</td>
                                                            <td>{{ $products->find($item->product_id)->name }}</td>
                                                            <td>{{ $item->address }}</td>
                                                            <td>{{ $item->amount }}</td>
                                                            <td>{{ $item->created_at }}</td>
                                                            <td>
                                                                <button type="button"class="btn btn-sm btn-primary"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#createMessageModal"
                                                                    wire:click='createAcceptMessage({{ $item->id }})'>
                                                                    <i class="bi-check-circle-fill"></i></button>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <!-- Modal -->
                                                    @empty
                                                        @include('components.not-found')
                                                    @endforelse
                                                </tbody>
                                            </table>
                                            <div class="my-3">
                                                {{ $rejectedTransferProducts->links() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
