<div>
    {{-- <button wire:click.prevent='resetData()' type="button"class="btn btn-sm btn-primary" data-bs-toggle="modal"
        data-bs-target="#createCategoryModal">
        إضافة قسم جديد
    </button> --}}

    <div wire:ignore.self class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> إضافة قسم</h5>
                    @include('components.spinner')
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form form-vertical">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group has-icon-left ">
                                        <label for="first-name-icon" class="mb-2">الاسم</label>
                                        <div class="position-relative">
                                            <input wire:model.defer='name' type="text"
                                                class="form-control @error('name') is-invalid @enderror"
                                                placeholder="اسم القسم" id="first-name-icon" required>
                                            <div class="form-control-icon">
                                                <i class="bi bi-diagram-2"></i>
                                            </div>
                                        </div>
                                        @error('name')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group has-icon-left">
                                        <label for="first-name-icon" class="mb-2">الوصف</label>
                                        <div class="position-relative">
                                            <input wire:model='description' type="text"
                                                class="form-control @error('description') is-invalid @enderror"
                                                placeholder="الوصف" id="first-name-icon" required>

                                            <div class="form-control-icon">
                                                <i class="bi bi-pen"></i>
                                            </div>


                                        </div>
                                        @if (strlen($description) > 0)
                                            <div
                                                class="mt-2 {{ mb_strlen($description) < 30 ? 'text-danger' : 'text-success' }}">
                                                عدد الأحرف {{ mb_strlen($description) }}
                                            </div>
                                        @endif
                                        @error('description')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group has-icon-left">
                                        <label for="first-name-icon" class="mb-2">ترتيب الظهور</label>
                                        <div class="position-relative">
                                            <input wire:model.defer='arrangement' type="number"
                                                class="form-control @error('arrangement') is-invalid @enderror"
                                                placeholder="يوجد {{ $category_count }} قسم" id="first-name-icon"
                                                required>
                                            <div class="form-control-icon">
                                                <i class="bi-layer-backward"></i>
                                            </div>
                                        </div>
                                        @error('arrangement')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group has-icon-left">
                                    <div class="input-group mb-3">
                                        <label for="first-name-icon" class="d-block mb-2">الصورة</label>
                                        <label for="imageUpload" class="btn btn-primary btn-block btn-outlined">رفع صورة
                                        </label>
                                        <input wire:model='image_url' type="file" id="imageUpload" accept="image/*"
                                            style="display: none">
                                        @if ($image_url)
                                            <img src="{{ $image_url->temporaryUrl() }}" width="150" class="py-4">
                                        @endif
                                    </div>
                                    @error('image_url')
                                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                                    @enderror
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
