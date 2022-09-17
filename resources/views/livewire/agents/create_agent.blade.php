<div>
    <!-- Button trigger modal -->
    {{-- <button wire:click.prevent='resetData' type="button"class="btn btn-sm btn-primary" data-bs-toggle="modal"
        data-bs-target="#createUserModal">
        إضافة عميل
    </button>
    <button wire:click.prevent='destroy' type="button"class="btn btn-sm btn-danger" data-bs-toggle="modal">
        تعطيل
    </button> --}}

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="createAgentModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> إضافة وكيل فرعي</h5>
                    <div wire:loading wire:target='store'>
                        <img src="{{ asset('assets/vendors/svg-loaders/oval.svg') }}" class="mx-4" style="width: 22px"
                            alt="audio">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form form-vertical">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group has-icon-left">
                                        <label for="first-name-icon">الاسم</label>
                                        <div class="position-relative">
                                            <input wire:model.lazy='name' type="text"
                                                class="form-control @error('name') is-invalid @enderror"
                                                placeholder="اسم المستخدم" id="first-name-icon" required>
                                            <div class="form-control-icon">
                                                <i class="bi bi-person"></i>
                                            </div>
                                        </div>
                                        @error('name')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group has-icon-left">
                                        <label for="email-id-icon">الإيميل</label>
                                        <div class="position-relative">
                                            <input wire:model.lazy='email' type="text"
                                                class="form-control @error('email') is-invalid @enderror"
                                                placeholder="الإيميل" id="email-id-icon">
                                            <div class="form-control-icon">
                                                <i class="bi bi-envelope"></i>
                                            </div>
                                        </div>
                                        @error('email')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group has-icon-left">
                                        <label for="mobile-id-icon">كلمة السر</label>
                                        <div class="position-relative">
                                            <input wire:model.lazy='password' type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                placeholder="كلمة السر" id="mobile-id-icon">
                                            <div class="form-control-icon">
                                                <i class="bi bi-lock"></i>
                                            </div>
                                        </div>
                                        @error('password')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group has-icon-left">
                                        <label for="phone">الهاتف</label>
                                        <div class="position-relative">
                                            <input wire:model.lazy='phone' type="number"
                                                class="form-control @error('phone') is-invalid @enderror"
                                                placeholder="الهاتف">
                                            <div class="form-control-icon">
                                                <i class="bi-phone"></i>
                                            </div>
                                        </div>
                                        @error('phone')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group has-icon-left">
                                        <label for="address">العنوان</label>
                                        <div class="position-relative">
                                            <input wire:model.lazy='address' type="text"
                                                class="form-control @error('address') is-invalid @enderror"
                                                placeholder="العنوان">
                                            <div class="form-control-icon">
                                                <i class="bi-house"></i>
                                            </div>
                                        </div>
                                        @error('address')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h6>الوكيل</h6>
                                <fieldset class="form-group">
                                    <select wire:model='app_user_id' class="form-select" id="basicSelect">
                                        <option>- إختيار - </option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </fieldset>
                                @error('app_user_id')
                                    <span class="text-danger d-block mt-2">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="button" class="btn btn-success" wire:click.prevent='store()'>إضافة</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
