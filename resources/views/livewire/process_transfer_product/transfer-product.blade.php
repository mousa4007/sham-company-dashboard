<div>
    <div class="card">
        <div class="card-content">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">الرئيسية</a></li>
                    <li class="breadcrumb-item active" aria-current="page">معالجة منتجات االتحويل اليدوي</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="buttons m-3">
                    <a href="#" class="btn btn-primary btn-sm m-0 mx-1" wire:click.prevent='resetData'
                        data-bs-toggle="modal" data-bs-target="#createTransferProudctModal">موافقة</a>
                    <a href="#" class="btn btn-danger btn-sm m-0 mx-1" data-bs-toggle="modal"
                        data-bs-target="#deleteTransferProudctModal">رفض</a>

                    <a href="#" class="btn btn-success btn-sm m-0 mx-1" data-bs-toggle="modal"
                        data-bs-target="#acceptedTransferProductModal">الطلبات المقبولة</a>
                    <a href="#" class="btn btn-secondary btn-sm m-0 mx-1" data-bs-toggle="modal"
                        data-bs-target="#rejectedTransferProductModal">الطلبات
                        المرفوضة</a>

                    <span class="mx-3">|</span>
                    @include('components.datepicker')
                </div>
            </div>
        </div>
    </div>

    @include('livewire.process_transfer_product.accepted_transfer_product')
    @include('livewire.process_transfer_product.rejected_transfer_product')
    @include('livewire.process_transfer_product.create_message_modal')

    <div class="row" id="table-striped">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="d-flex align-items-baseline">
                        <h3 class="card-title me-5">منتجات التحويل اليدوي</h3>
                        <div class="mb-1">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control" aria-label="Recipient's username"
                                    aria-describedby="button-addon2" wire:model='searchTerm'>
                                <button class="btn btn-outline-secondary" type="button" id="button-addon2">بحث</button>
                                @include('components.spinner')
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-content">
                    <!-- table striped -->
                    <div class="table-responsive p-2">
                        <table class="table table-bordered ">
                            <thead>
                                <tr class="table-primary">
                                    <th><input wire:model='checked' type="checkbox" class="form-check-input"></th>
                                    <th>#</th>
                                    <th>المستخدم</th>
                                    <th>المنتج</th>
                                    <th>المنتج المراد تحويله</th>
                                    <th> المبلغ </th>
                                    <th>التاريخ</th>
                                    <th>معالجة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transferProducts as  $item)
                                    <tr>
                                        <td><input wire:model='selectedRows' value="{{ $item->id }}" type="checkbox"
                                                class="form-check-input">
                                        </td>
                                        <td> {{ $item->id }}</td>
                                        <td>{{ $app_users->find($item->app_user_id)->name }}</td>
                                        <td>{{ $products->find($item->product_id)->name }}</td>
                                        <td>
                                            <div class="text-primary fw-bold d-flex">
                                                <input disabled class="form-control d-inline text-primary fw-bold"
                                                    type="text" value="{{ $item->address }}"
                                                    id="myInput.{{ $item->address }}" style="    max-width: 110px;">
                                                <button id="btnCopy" class="btn btn-primary btn-sm"
                                                    onclick="copy('myInput.{{ $item->address }}')"><i
                                                        class="bi-clipboard"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>{{ $item->amount }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>
                                            <button type="button"class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#createMessageModal"
                                                wire:click='createAcceptMessage({{ $item->id }})'>
                                                <i class="bi-check-lg"></i>
                                                {{-- <i class="bi-pencil-square"></i> --}}
                                            </button>
                                            <button type="button"class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#createMessageModal"
                                                wire:click='createRejectMessage({{ $item->id }})'>
                                                <i class="bi-x-circle-fill"></i></button></i></button>
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
                            {{ $transferProducts->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
