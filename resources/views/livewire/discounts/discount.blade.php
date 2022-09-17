<div>


    <div class="card">
        <div class="card-content">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">الرئيسية</a></li>
                    <li class="breadcrumb-item active" aria-current="page">مجموعات الأسعار</li>
                </ol>
            </nav>
        </div>
    </div>


    <div class="row" id="table-striped">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="d-flex align-items-baseline">
                        <h3 class="card-title me-5"> مجموعات الأسعار</h3>
                        <div class="mb-1">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control" aria-label="Recipient's username"
                                    aria-describedby="button-addon2" wire:model='searchTerm'>
                                <button class="btn btn-outline-secondary" type="button" id="button-addon2">بحث</button>
                            </div>
                        </div>
                    </div>
                    @include('livewire.discounts.create_discount')
                    @include('livewire.discounts.update_discount')
                </div>
                {{-- @include('livewire.product_item.delete_product_item')
                @include('livewire.product_item.update_product_item') --}}

                <div class="card-content">
                    <!-- table striped -->
                    <div class="table-responsive p-2">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>الوصف</th>
                                    <th>النسبة %</th>
                                    <th style="width: 200px">عمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($prices as $price)
                                    <tr>
                                        <td>{{ $price->id }}</td>
                                        <td>{{ $price->name }}</td>
                                        <td>{{ $price->description }}</td>
                                        <td>{{ $price->percentage }}</td>
                                        <td>
                                            <button type="button"class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                data-bs-target="#updatePricesModal"
                                                wire:click='edit({{ $price->id }})'>
                                                تعديل
                                            </button>
                                            <button type="button"class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteStockedProductModal"
                                                wire:click='delete({{ $price->id }})'>
                                                حذف
                                            </button>
                                        </td>

                                    </tr>
                                @empty
                                    @include('components.not-found')
                                @endforelse
                            </tbody>
                        </table>
                        <div class="my-3">
                            {{ $prices->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
