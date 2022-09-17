<div wire:ignore.self class="modal fade" id="withdrawBalanceModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> سحب رصيد</h5>
                @include('components.spinner')
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="form form-vertical">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12 mb-0">
                                <label for="المستخدم" class="mb-2">المستخدم</label>
                                <fieldset class="form-group">
                                    <select wire:model='app_user_id' class="form-select" id="basicSelect">
                                        <option value="">اختيار</option>
                                        @foreach ($appUsers as $appUser)
                                            <option value="{{ $appUser->id }}">{{ $appUser->name }}</option>
                                        @endforeach
                                    </select>
                                </fieldset>
                                @error('app_user_id')
                                    <span class="text-danger d-block mt-2">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <div class="form-group has-icon-left">
                                    <label for="الرصيد الحالي" class="mb-2">الرصيد</label>
                                    <div class="position-relative">
                                        <input wire:model.debounce.300ms='incomingBalance' type="number"
                                            class="form-control @error('balance') is-invalid @enderror"
                                            placeholder="الرصيد الحالي {{ $current_balance }}" required>
                                        <div class="form-control-icon">
                                            <i class="bi bi-cash"></i>
                                        </div>
                                    </div>
                                    @error('incomingBalance')
                                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group ">
                                    <label for="first-name-icon" class="mb-2">الرسالة</label>
                                    <div class="position-relative">
                                        <textarea rows="2" wire:model.debounce.1000ms='witdhraw_message' type="text"
                                            class="form-control @error('witdhraw_message') is-invalid @enderror" placeholder="الرسالة" id="first-name-icon"
                                            required></textarea>
                                    </div>
                                    @error('witdhraw_message')
                                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="button" class="btn btn-success"
                            wire:click.prevent='withdrawBalance()'>إضافة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
