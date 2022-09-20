<div>
    <div wire:ignore.self class="modal fade" id="updateProductModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> إضافة منتج تكويد مباشر</h5>
                    @include('components.spinner')
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form form-vertical">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group has-icon-left">
                                        <label for="first-name-icon" class="mb-2">الاسم</label>
                                        <div class="position-relative">
                                            <input wire:model.defer='name' type="text"
                                                class="form-control @error('name') is-invalid @enderror"
                                                placeholder="اسم المنتج" id="first-name-icon" required>
                                            <div class="form-control-icon">
                                                <i class="bi bi-basket"></i>
                                            </div>
                                        </div>
                                        @error('name')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
{{--
                                <div class="col-md-12">
                                    <div class="form-group has-icon-left">
                                        <label for="first-name-icon" class="mb-2">السعر</label>
                                        <div class="position-relative">
                                            <input wire:model.defer='price' type="number"
                                                class="form-control @error('name') is-invalid @enderror"
                                                placeholder="السعر" id="first-name-icon" required>
                                            <div class="form-control-icon">
                                                <i class="bi bi-cash"></i>
                                            </div>
                                        </div>
                                        @error('price')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div> --}}

                                <div class="col-md-12">
                                    <div class="form-group ">
                                        <label for="first-name-icon" class="mb-2">تفاصيل المنتج</label>
                                        <div class="position-relative">
                                            <textarea rows="2" wire:model.defer='description' type="text"
                                                class="form-control @error('description') is-invalid @enderror" placeholder="التفاصيل" id="first-name-icon"
                                                required></textarea>

                                        </div>
                                        @error('description')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-0">
                                    <label for="first-name-icon" class="d-block mb-2">العملة</label>
                                    <fieldset class="form-group">
                                        <select wire:model='currency' class="form-select" id="basicSelect">
                                            <option value="1">دولار</option>
                                            <option value="2">تركي</option>
                                        </select>
                                    </fieldset>
                                    @error('currency')
                                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-0">
                                    <label for="first-name-icon" class="d-block me-0 mb-2">القسم</label>
                                    <fieldset class="form-group">
                                        <select wire:model='category_id' class="form-select" id="basicSelect">
                                            <option></option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </fieldset>
                                    @error('category_id')
                                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                                {{-- direct product --}}
                                <div class="col-md-4 mb-0">
                                    <div class="form-group has-icon-left">
                                        <label for="first-name-icon" class="d-block me-0 mb-2">كود البلد</label>
                                        <div class="position-relative">
                                            <input wire:model.defer='country_number' type="text"
                                                class="form-control @error('country_number') is-invalid @enderror"
                                                placeholder="كود البلد" id="first-name-icon" required>
                                            <div class="form-control-icon">
                                                <i class="bi bi-flag-fill"></i>
                                            </div>
                                        </div>
                                        @error('country_number')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4 mb-0">
                                    <div class="form-group has-icon-left">
                                        <label for="first-name-icon" class="d-block me-0 mb-2">رمز المنتج</label>
                                        <div class="position-relative">
                                            <input wire:model.defer='service_code' type="text"
                                                class="form-control @error('service_code') is-invalid @enderror"
                                                placeholder="رمز المنتج" id="first-name-icon" required>
                                            <div class="form-control-icon">
                                                <i class="bi bi-upc-scan"></i>
                                            </div>
                                        </div>
                                        @error('service_code')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4 mb-0">
                                    <label for="first-name-icon" class="d-block me-0 mb-2">الموقع</label>
                                    <fieldset class="form-group">
                                        <select wire:model='web_api' class="form-select" id="basicSelect">
                                            <option value="sms-activate">sms-activate</option>
                                        </select>
                                    </fieldset>
                                    @error('web_api')
                                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                                {{--  --}}
                                <div class="col-md-6">
                                    <div class="form-group has-icon-left">
                                        <label for="first-name-icon" class="mb-2">الترتيب</label>
                                        <div class="position-relative">
                                            <input wire:model.defer='arrangement' type="number"
                                                class="form-control @error('arrangement') is-invalid @enderror"
                                                placeholder="الترتيب" id="first-name-icon" required>
                                            <div class="form-control-icon">
                                                <i class="bi bi-layer-backward"></i>
                                            </div>
                                        </div>
                                        @error('arrangement')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group has-icon-left col-md-6">
                                    <div class="input-group mb-3">
                                        <label for="first-name-icon" class="d-block mb-2">الصورة</label>
                                        <label for="imageUpload" class="btn btn-primary btn-block btn-outlined">رفع
                                            صورة
                                        </label>
                                        <input wire:model='image_url' type="file" id="imageUpload"
                                            accept="image/*" style="display: none">
                                        @if ($image_url)
                                            <img src="{{ $image_url->temporaryUrl() }}" width="75"
                                                class="py-4">
                                        @else
                                            <img src="{{ $image_url_preview }}" width="75" class="py-4">
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
                            <button type="button" class="btn btn-info" wire:click.prevent='update()'>تعديل</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
