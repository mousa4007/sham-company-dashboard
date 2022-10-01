<div>

    <div wire:ignore.self class="modal fade" id="createAdModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> إضافة إعلان</h5>
                    @include('components.spinner')
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form form-vertical">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group has-icon-left">
                                        <label for="first-name-icon" class="mb-2">الوصف</label>
                                        <div class="position-relative">
                                            <input wire:model.defer='description' type="text"
                                                class="form-control @error('description') is-invalid @enderror"
                                                placeholder="وصف الإعلان" id="first-name-icon" required>
                                            <div class="form-control-icon">
                                                <i class="bi bi-basket"></i>
                                            </div>
                                        </div>
                                        @error('description')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group has-icon-left col-md-12">
                                    <div class="input-group mb-3">
                                        <label for="first-name-icon" class="d-block mb-2">الصورة</label>
                                        <label for="imageUpload" class="btn btn-primary btn-block btn-outlined">رفع صورة
                                        </label>
                                        <input wire:model='image_url' type="file" id="imageUpload" accept="image/*"
                                            style="display: none">
                                        @if ($image_url)
                                            <img src="{{ $image_url->temporaryUrl() }}" width="75" class="py-4">
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
