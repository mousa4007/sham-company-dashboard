<div>
    <div class="card">
        <div class="card-content">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">الرئيسية</a></li>
                    <li class="breadcrumb-item active" aria-current="page">المنتجات</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="buttons m-3">
                    <a href="#" class="btn btn-primary btn-sm m-0 mx-1" wire:click.prevent='resetData'
                        data-bs-toggle="modal" data-bs-target="#createProductModal">إضافة</a>
                    <a href="#" class="btn btn-danger btn-sm m-0 mx-1" data-bs-toggle="modal"
                        data-bs-target="#deleteProductModal">حذف</a>
                    <a href="#" class="btn btn-success btn-sm m-0 mx-1" wire:click='activate'>تفعيل</a>
                    <a href="#" class="btn btn-secondary btn-sm m-0 mx-1" wire:click='disable'>تعطيل</a>
                    <span class="mx-3">|</span>
                    @include('components.datepicker')
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="table-striped">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="d-flex align-items-baseline">
                        <h3 class="card-title me-5"> المنتجات
                        </h3>
                        <div class="mb-1">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control" aria-label="Recipient's username"
                                    aria-describedby="button-addon2" wire:model='searchTerm'>
                                <button class="btn btn-outline-secondary" type="button" id="button-addon2">بحث</button>
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
                                <th>سعر الشراء</th>
                                <th>سعر البيع</th>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $product->id }}
                                        </td>
                                        <td>{{ $product->name }}
                                        </td>
                                        <td>
                                            <div style="display: flex">
                                                <input disabled class="form-control d-flex " type="text"
                                                    value="{{ $product->buy_price }}">
                                                <input class="form-control d-flex mx-2" type="text"
                                                    wire:model.defer.lazy='buy_price.{{ $product->id }}'>
                                                <button class="btn btn-success"
                                                    wire:click='updateBuyPrice({{ $product->id }})'>
                                                    <span style="color: white">✔</span>
                                                </button>
                                            </div>
                                            {{-- @dump($buy_price) --}}
                                        </td>

                                        <td>
                                            <div style="display: flex">
                                                <input disabled class="form-control d-flex " type="text"
                                                    value="{{ $product->sell_price }}">
                                                <input class="form-control d-flex mx-2" type="text"
                                                    wire:model.defer='sell_price.{{ $product->id }}'>
                                                <button class="btn btn-success"
                                                    wire:click='updateSellPrice({{ $product->id }})'>
                                                    <span style="color: white">✔</span>
                                                </button>
                                            </div>

                                        </td>

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
