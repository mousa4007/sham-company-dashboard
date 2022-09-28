<div>
    <div class="card">
        <div class="card-content">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">الرئيسية</a></li>
                    <li class="breadcrumb-item active" aria-current="page">أرصدة الوكلاء الرسميين
                    </li>
                </ol>
            </nav>
        </div>
    </div>


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="buttons m-3">
                    <a href="#" class="btn btn-primary btn-sm m-0 mx-1" wire:click.prevent='resetData'
                        data-bs-toggle="modal" data-bs-target="#createBalanceModal">شحن رصيد</a>
                    <a href="#" class="btn btn-success btn-sm m-0 mx-1" wire:click.prevent='resetData'
                        data-bs-toggle="modal" data-bs-target="#withdrawBalanceModal">سحب رصيد</a>
                    <a href="#" class="btn btn-danger btn-sm m-0 mx-1"
                        wire:click.prevent='cancelCharging'>إلغاء</a>

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
                        <h3 class="card-title me-5"> أرصدة الوكلاء الرسميين</h3>
                        <div class="mb-1">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control" aria-label="Recipient's username"
                                    aria-describedby="button-addon2" wire:model='searchTerm'>
                                <button class="btn btn-outline-secondary" type="button" id="button-addon2">بحث</button>
                            </div>
                        </div>
                    </div>
                    @include('livewire.super_user_balance.charge_balance')
                    @include('livewire.super_user_balance.withdraw_balance')
                </div>

                <div class="card-content">
                    <!-- table striped -->
                    <div class="table-responsive p-2">
                        <table class="table table-responsive-sm">
                            <thead>
                                <tr class="table-primary">
                                    <th><input wire:model='checked' type="checkbox" class="form-check-input"></th>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>الرصيد</th>
                                    <th> الرسالة</th>
                                    <th> التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($superUserChargingBalance as $item)
                                    <tr>
                                        <td><input wire:model='selectedRows' value="{{ $item->id }}" type="checkbox"
                                                class="form-check-input"></td>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->balance }}</td>
                                        <td>{{ $item->message }}</td>
                                        <td>{{ $item->created_at }}</td>
                                    </tr>

                                @empty
                                    @include('components.not-found')
                                @endforelse
                            </tbody>

                        </table>
                        <div class="my-3">
                            {{ $superUserChargingBalance->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
