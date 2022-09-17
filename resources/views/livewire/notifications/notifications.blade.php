<div>

    <div class="card">
        <div class="card-content">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">الرئيسية</a></li>
                    <li class="breadcrumb-item active" aria-current="page">الإشعارات</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="buttons m-3">
                    <a href="#" class="btn btn-primary btn-sm m-0 mx-1" wire:click.prevent='resetData'
                        data-bs-toggle="modal" data-bs-target="#createNotificationModal">إضافة</a>
                    <a href="#" class="btn btn-danger btn-sm m-0 mx-1" data-bs-toggle="modal"
                        data-bs-target="#deleteNotificationModal">حذف</a>
                    {{-- <a href="#" class="btn btn-success btn-sm m-0 mx-1" wire:click='activate'>تفعيل</a>
                    <a href="#" class="btn btn-secondary btn-sm m-0 mx-1" wire:click='disable'>تعطيل</a> --}}
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
                        <h3 class="card-title me-5"> الاشعارات</h3>
                        <div class="mb-1">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control" aria-label="Recipient's username"
                                    aria-describedby="button-addon2" wire:model='searchTerm'>
                                <button class="btn btn-outline-secondary" type="button" id="button-addon2">بحث</button>
                            </div>
                        </div>
                    </div>
                    @include('livewire.notifications.create_notification')
                </div>
                @include('livewire.notifications.delete_notification')
                @include('livewire.notifications.notification_info')
                {{-- @include('livewire.categories.update_category') --}}

                <div class="card-content">
                    <!-- table striped -->
                    <div class="table-responsive p-2">
                        <table class="table table-responsive-sm mb-0">
                            <thead>
                                <tr class="table-primary">
                                    <th><input wire:model='checked' value="" type="checkbox"
                                            class="form-check-input form-check-secondary"></th>
                                    <th>#</th>
                                    <th>العنوان</th>
                                    <th>الرسالة</th>
                                    <th>تاريخ النشر</th>
                                    <th> النوع</th>
                                    <th> معلومات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($notifications as $notification)
                                <tr>
                                    <td><input wire:model='selectedRows' value="{{ $notification->id }}" type="checkbox"
                                            class="form-check-input form-check-secondary"></td>
                                    <td>{{ $notification->id }}</td>
                                    <td>{{ $notification->title }}</td>
                                    <td>{{ $notification->body }}</td>
                                    <td>{{ $notification->created_at }}</td>
                                    <td>{{ $notification->type == 'general' ? 'عامة' : 'خاصة' }}</td>


                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                            data-bs-target="#notificationInfo"
                                            wire:click='info({{ $notification->id }})'>
                                            <i class="bi-info-circle-fill"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                @include('components.not-found')
                                @endforelse
                            </tbody>
                        </table>
                        <div class="my-3">
                            {{ $notifications->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
