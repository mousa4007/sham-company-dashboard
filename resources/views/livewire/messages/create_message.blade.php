<div>
    {{-- <button wire:click.prevent='resetData()' type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
        data-bs-target="#createCategoryModal">
        إضافة قسم جديد
    </button> --}}

    <div wire:ignore.self class="modal fade" id="createMessageModal" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> إضافة رسالة</h5>
                    @include('components.spinner')
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form form-vertical">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12 mb-0">
                                    <label for="first-name-icon" class="d-block mb-2">المستخدم</label>
                                    <fieldset class="form-group">
                                        <select wire:model='targetUser' class="form-select" id="basicSelect">
                                            <option value="all">الكل</option>
                                            @foreach ($app_users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </fieldset>
                                    @error('currency')
                                    <span class="text-danger d-block mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <div class="form-group has-icon-left ">
                                        <label for="first-name-icon" class="mb-2">العنوان</label>
                                        <div class="position-relative">
                                            <input wire:model.defer='title' type="text"
                                                class="form-control @error('title') is-invalid @enderror"
                                                placeholder="عنوان الإشعار" required>
                                            <div class="form-control-icon">
                                                <i class="bi bi-diagram-2"></i>
                                            </div>
                                        </div>
                                        @error('title')
                                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="first-name-icon" class="mb-2">نص الرسالة</label>
                                        <div class="position-relative">
                                            <textarea wire:model='body' type="text"
                                                class="form-control @error('description') is-invalid @enderror"
                                                placeholder="نص الرسالة" id="first-name-icon" required></textarea>


                                        </div>

                                        @error('body')
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
