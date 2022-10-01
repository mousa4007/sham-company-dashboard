<div>
    <div>
        <div class="card">
            <div class="card-content">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">الرئيسية</a></li>
                        <li class="breadcrumb-item active" aria-current="page">الإعلانات</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="buttons m-3">
                        <a href="#" class="btn btn-primary btn-sm m-0 mx-1" wire:click.prevent='resetData'
                            data-bs-toggle="modal" data-bs-target="#createAdModal">إضافة</a>
                        <a href="#" class="btn btn-danger btn-sm m-0 mx-1" data-bs-toggle="modal"
                            data-bs-target="#deleteAdModal">حذف</a>
                        <a href="#" class="btn btn-success btn-sm m-0 mx-1" wire:click='activate'>تفعيل</a>
                        <a href="#" class="btn btn-secondary btn-sm m-0 mx-1" wire:click='disable'>تعطيل</a>
                        <span class="mx-3">|</span>
                        <div class='d-inline-flex' style="max-height: 38px">


                            @if ($paginateNumber == '')
                                <input wire:model.lazy='paginateNumber' type="number" class="form-control"
                                    placeholder="0">
                            @else
                                <fieldset class="form-group paginate-select" style="width: 60px">
                                    <select wire:model='paginateNumber' class="form-select" id="basicSelect">
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="">مخصصة</option>
                                    </select>
                                </fieldset>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="table-striped">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="d-flex align-items-baseline">
                            <h3 class="card-title me-5"> الإعلانات</h3>

                        </div>
                        @include('livewire.ads.create_ad')
                    </div>
                    @include('livewire.ads.delete_ad')
                    @include('livewire.ads.update_ad')

                    <div class="card-content">
                        <!-- table striped -->
                        <div class="table-responsive p-2">
                            <table class="table mb-0">
                                <thead>
                                    <th><input wire:model='checked' type="checkbox" class="form-check-input"></th>
                                    <th>#</th>
                                    <th>الوصف</th>
                                    <th>الحالة</th>
                                    <th>الصورة</th>
                                    <th>عمليات</th>
                                </thead>
                                <tbody>
                                    @forelse ($ads as $ad)
                                        <tr>
                                            <td><input wire:model='selectedRows' value="{{ $ad->id }}"
                                                    type="checkbox" class="form-check-input"></td>
                                            <td>{{ $ad->id }}</td>
                                            <td>{{ $ad->description }}</td>
                                            <td>{{ $ad->status == 'active' ? 'مفعل' : 'معطل' }}</td>
                                            <td><img src="{{ $ad->image_url }}" width="35" alt=""></td>
                                            <td>
                                                <button type="button"class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                    data-bs-target="#updateAdModal"
                                                    wire:click='edit({{ $ad->id }})'>
                                                    <i class="bi-pencil-square"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        @include('components.not-found')
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="my-3">
                                {{ $ads->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
