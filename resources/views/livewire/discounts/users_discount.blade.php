<div>


    <div class="card">
        <div class="card-content">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">الرئيسية</a></li>
                    <li class="breadcrumb-item active" aria-current="page">تخصيص الأسعار للمستخدمين</li>
                </ol>
            </nav>
        </div>
    </div>


    <div class="row" id="table-striped">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="d-flex align-items-baseline">
                        <h3 class="card-title me-5"> تخصيص الإسعار للمستخدمين</h3>
                    </div>
                </div>

                <div class="card-content">
                    <form class="form form-vertical">
                        <div class="form-body m-3">
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <label for="first-name-icon" class="d-block mb-3 fw-bold">المستهدف</label>
                                    <fieldset class="form-group">
                                        <select wire:model='user_id' class="form-select" id="basicSelect">
                                            <option>اختيار</option>
                                            <option value="all">الكل</option>
                                            @foreach ($app_users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </fieldset>
                                    @error('user_id')
                                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-0">
                                    <label for="first-name-icon" class="d-block mb-2 fw-bold">التخصيص</label>
                                    <fieldset class="form-group">
                                        <select wire:model='discount' class="form-select" id="basicSelect">
                                            <option>اختيار</option>
                                            @foreach ($discounts as $discount)
                                                <option value="{{ $discount->id }}">{{ $discount->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </fieldset>
                                    @error('discount')
                                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="button" class="btn btn-success"
                                wire:click.prevent='addUserDiscount'>إضافة</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
