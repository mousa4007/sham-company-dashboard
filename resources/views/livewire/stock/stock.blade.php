<div>
    <div class="card">
        <div class="card-content">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">الرئيسية</a></li>
                    <li class="breadcrumb-item active" aria-current="page">المخزن</li>
                </ol>
            </nav>
        </div>
    </div>
{{--
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="buttons m-3">
                    <a href="#" class="btn btn-primary btn-sm m-0 mx-1" wire:click.prevent='resetData'
                        data-bs-toggle="modal" data-bs-target="#createStockedProductModal">إضافة</a>
                    <a href="#" class="btn btn-danger btn-sm m-0 mx-1" data-bs-toggle="modal"
                        data-bs-target="#deleteStockedProductModal">حذف</a>
                    <a href="#" class="btn btn-success btn-sm m-0 mx-1" wire:click='activate'>تفعيل</a>
                    <a href="#" class="btn btn-secondary btn-sm m-0 mx-1" wire:click='disable'>تعطيل</a>

                    <span class="mx-3">|</span>
                    @include('components.datepicker')
                </div>
            </div>
        </div>
    </div> --}}

    <div class="row" id="table-striped">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="d-flex align-items-baseline">
                        <h3 class="card-title me-5"> المنتجات</h3>
                        <div class="mb-1">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control" aria-label="Recipient's username"
                                    aria-describedby="button-addon2" wire:model='searchTerm'>
                                <button class="btn btn-outline-secondary" type="button" id="button-addon2">بحث</button>
                            </div>
                        </div>
                    </div>
                    {{-- @include('livewire.products.create_product') --}}
                </div>
                {{-- @include('livewire.products.delete_product')
                @include('livewire.products.update_product') --}}

                <div class="card-content">
                    <!-- table striped -->
                    <div class="table-responsive p-2">
                        <table class="table mb-0">
                            <thead>
                                <th><input wire:model='checked' type="checkbox" class="form-check-input"></th>
                                <th>#</th>
                                <th>اسم المنتج</th>
                                <th>السعر</th>
                                <th>الكمية</th>
                                <th>مباع</th>
                                <th>غير مباع</th>
                                {{-- <th>الصورة</th> --}}
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                    <tr>
                                        <td><input wire:model='selectedRows' value="{{ $product->id }}" type="checkbox"
                                                class="form-check-input"></td>
                                        <td>{{ $product->id }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->sell_price }}</td>
                                        <td>{{ count($product->stockedProduct)}}</td>
                                        <td>{{ count($product->stockedProduct->where('selled',true)) }}</td>
                                        <td>{{ count($product->stockedProduct->where('selled',false))  }}</td>
                                        {{-- <td><img src="{{ $product->image_url }}" width="35" alt=""></td> --}}
                                    </tr>
                                @empty
                                    @include('components.not-found')
                                @endforelse
                            </tbody>
                        </table>
                        <div class="my-3">
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
