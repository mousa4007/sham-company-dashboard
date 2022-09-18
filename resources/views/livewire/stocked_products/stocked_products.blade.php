<div>
    <div class="card">
        <div class="card-content">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">الرئيسية</a></li>
                    <li class="breadcrumb-item active" aria-current="page">المنتجات المخزونة</li>
                </ol>
            </nav>
        </div>
    </div>

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
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <label for="imageUpload" class="btn btn-primary btn-sm" style="margin: 10px;">
                            <i class="bi-file-earmark-spreadsheet-fill"></i>
                        </label>
                        <input wire:model='excel_file' type="file" id="imageUpload"
                            accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                            style="display: none">

                        @if ($excel_file)
                            <button type="button" class="btn btn-success btn-sm" wire:click='import'
                                style=" margin-top: 10px; margin-right: -10px;">إضافة
                                المنتجات</button>
                        @else
                            <button type="button" class="btn btn-success btn-sm" wire:click='import'
                                style=" margin-top: 10px; margin-right: -10px;" disabled>رفع ملف إكسل</button>
                        @endif
                    </div>
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
                        <h3 class="card-title me-5"> المنتجات المخزونة</h3>
                        <div class="mb-1">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control" aria-label="Recipient's username"
                                    aria-describedby="button-addon2" wire:model='searchTerm'>
                                <button class="btn btn-outline-secondary" type="button" id="button-addon2">بحث</button>
                                @include('components.spinner')
                            </div>
                        </div>
                        @include('livewire.stocked_products.create_stocked_products')
                    </div>

                </div>
                @include('livewire.stocked_products.delete_stocked_products')
                {{-- @include('livewire.stocked_products.update_stocked_products') --}}
                @include('livewire.stocked_products.show_stocked_products')


                <div class="card-content">
                    <!-- table striped -->
                    <div class="table-responsive p-2">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><input wire:model='checked' type="checkbox" class="form-check-input"></th>
                                    <th> الدفعة</th>
                                    <th>العدد</th>
                                    <th>المنتح</th>
                                    <th>تاريخ الإضافة</th>
                                    <th>الحالة</th>

                                    <th>عمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($count = 20)
                                @forelse ($stockedProducts as $key => $item)
                                    <tr>
                                        <td><input wire:model='selectedRows' value="{{ $key }}" type="checkbox"
                                                class="form-check-input">
                                        </td>
                                        <td> دفعة {{ \Carbon\Carbon::parse($key)->format('d-m-Y') }}</td>
                                        <td>{{ count($item) }}</td>
                                        <td>{{ $productList->find($item->first()->product_id)->name }}</td>
                                        <td>{{ $key }}</td>
                                        <td>{{ $item->first()->status == 'active' ? 'مفعل' : 'معطل' }}</td>
                                        <td>
                                            <button type="button"class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                data-bs-target="#showStockedProductModal"
                                                wire:click='setData({{ $item }})'>
                                                تعديل
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
                            {{ $stockedProducts->links() }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
