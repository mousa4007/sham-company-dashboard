<div>
    <div class="card">
        <div class="card-content">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">الرئيسية</a></li>
                    <li class="breadcrumb-item active" aria-current="page">الوكلاء الفرعيين
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
                        data-bs-toggle="modal" data-bs-target="#createAgentModal">إضافة</a>
                    <a href="#" class="btn btn-danger btn-sm m-0 mx-1" data-bs-toggle="modal"
                        data-bs-target="#deleteAgentModal">حذف</a>
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
                        <h3 class="card-title me-5"> الوكلاء الفرعيين</h3>
                        <div class="mb-1">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control" aria-label="Recipient's username"
                                    aria-describedby="button-addon2" wire:model='searchTerm'>
                                <button class="btn btn-outline-secondary" type="button" id="button-addon2">بحث</button>
                            </div>
                        </div>
                    </div>
                    @include('livewire.agents.create_agent')
                </div>
                @include('livewire.agents.delete_agent')
                @include('livewire.agents.update_agent')

                <div class="card-content">
                    <!-- table striped -->
                    <div class="table-responsive p-2">
                        <table class="table table-responsive-sm">
                            <thead>
                                <tr class="table-primary">
                                    <th><input wire:model.lazy='checked' value="" type="checkbox"
                                            class="form-check-input form-check-secondary"></th>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>الايميل</th>
                                    <th>الرصيد</th>
                                    <th>الوكيل</th>
                                    <th>الحالة</th>
                                    <th>عمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($appUsers as $appUser)
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input form-check-secondary"
                                                wire:model.lazy='selectedRow' value="{{ $appUser->id }}"></td>
                                        <td>{{ $appUser->id }}</td>
                                        <td>{{ $appUser->name }}</td>
                                        <td>{{ $appUser->email }} </td>
                                        <td>{{ $appUser->balance }}</td>
                                        <td>{{ $agents->find($appUser->agent_id)->user->name }}</td>
                                        <td>{{ $appUser->status == 'active' ? 'مفعل' : 'معطل' }}</td>
                                        <td>
                                            <button type="button"class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                data-bs-target="#updateAgentModal"
                                                wire:click='edit({{ $appUser->id }})'>
                                                تعديل
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    @include('components.not-found')
                                @endforelse
                            </tbody>
                            {{-- @dump($selectedRow) --}}
                        </table>
                        <div class="my-3">
                            {{ $appUsers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
