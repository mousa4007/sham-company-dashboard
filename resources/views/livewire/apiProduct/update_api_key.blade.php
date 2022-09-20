<div>
    <button wire:click.prevent='editApiKey()' type="button"class="btn btn-sm btn-info mx-2" data-bs-toggle="modal"
        data-bs-target="#updateApiKey">
        تعديل مفاتيح المواقع
    </button>

    <div wire:ignore.self class="modal fade" id="updateApiKey" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> تعديل مفاتيح المواقع</h5>
                    @include('components.spinner')
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form form-vertical">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group has-icon-left">
                                        <label for="first-name-icon" class="mb-2">أكتيفيت sms-activate</label>
                                        <div class="position-relative">
                                            <input wire:model='smsActivateApi' type="text"
                                                class="form-control @error('name') is-invalid @enderror"
                                                placeholder="المفتاح" id="first-name-icon" required>
                                            <div class="form-control-icon">
                                                <i class="bi-gear-wide-connected"></i>
                                            </div>
                                        </div>
                                        @error('name')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group has-icon-left">
                                        <label for="first-name-icon" class="mb-2">ڤاك
                                            vak-sms </label>
                                        <div class="position-relative">
                                            <input wire:model='vakSmsApi' type="text"
                                                class="form-control @error('name') is-invalid @enderror"
                                                placeholder="المفتاح" id="first-name-icon" required>
                                            <div class="form-control-icon">
                                                <i class="bi-gear-wide-connected"></i>
                                            </div>
                                        </div>
                                        @error('name')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group has-icon-left">
                                        <label for="first-name-icon" class="mb-2"> سكند لاين 2ndLine</label>
                                        <div class="position-relative">
                                            <input wire:model='secondLineApi' type="text"
                                                class="form-control @error('name') is-invalid @enderror"
                                                placeholder="المفتاح" id="first-name-icon" required>
                                            <div class="form-control-icon">
                                                <i class="bi-gear-wide-connected"></i>
                                            </div>
                                        </div>
                                        @error('name')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="button" class="btn btn-info"
                                wire:click.prevent='updateApiKey()'>تعديل</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
