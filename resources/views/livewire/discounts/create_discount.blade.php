<div>
    <button wire:click.prevent='' type="button"class="btn btn-sm btn-primary" data-bs-toggle="modal"
        data-bs-target="#createPricesModal">
        إضافة مجموعة أسعار
    </button>

    <div wire:ignore.self class="modal fade" id="createPricesModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createPricesModal">إضافة مجموعة أسعار</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form form-vertical">
                        <div class="form-body">
                            <div class="row">
                                {{-- <div class="col-md-12 mb-0">
                                    <label for="first-name-icon" class="d-block me-0">المستهدف</label>
                                    <fieldset class="form-group">
                                        <select wire:model='target' class="form-select" id="basicSelect">
                                            <option>اختيار</option>
                                            <option value="1">الوكلاء الرسميين</option>
                                            <option value="2">الوكلاء الفرعيين</option>
                                        </select>
                                    </fieldset>
                                    @error('target')
                                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                                    @enderror
                                </div> --}}
                                <div class="col-12">
                                    <div class="form-group has-icon-left">
                                        <label for="first-name-icon">الاسم</label>
                                        <div class="position-relative">
                                            <input wire:model='name' type="text"
                                                class="form-control @error('name') is-invalid @enderror"
                                                placeholder="اسم المجموعة" id="first-name-icon" required>
                                            <div class="form-control-icon">
                                                <i class="bi bi-chat-square-quote"></i>
                                            </div>
                                        </div>
                                        @error('name')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group has-icon-left">
                                        <label for="first-name-icon">الوصف</label>
                                        <div class="position-relative">
                                            <input wire:model='description' type="text"
                                                class="form-control @error('description') is-invalid @enderror"
                                                placeholder="الوصف" required>
                                            <div class="form-control-icon">
                                                <i class="bi bi-hash"></i>
                                            </div>
                                        </div>
                                        @error('description')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group has-icon-left">
                                        <label for="first-name-icon">النسبة</label>
                                        <div class="position-relative">
                                            <input wire:model='percentage' type="text"
                                                class="form-control @error('percentage') is-invalid @enderror"
                                                placeholder="النسبة" required>
                                            <div class="form-control-icon">
                                                <i class="bi bi-percent"></i>
                                            </div>
                                        </div>
                                        @error('percentage')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
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
