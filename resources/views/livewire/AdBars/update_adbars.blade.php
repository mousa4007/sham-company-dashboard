<div>

    <div wire:ignore.self class="modal fade" id="updateAdBarModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> تعديل شريط إعلاني</h5>
                    @include('components.spinner')
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form form-vertical">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="first-name-icon" class="mb-2">الشريط</label>
                                        <div class="position-relative">
                                            <textarea wire:model.defer='adbar' type="text"
                                                class="form-control @error('adbar') is-invalid @enderror"
                                                placeholder="الشريط" id="first-name-icon" required></textarea>

                                        </div>
                                        @error('adbar')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="button" class="btn btn-success" wire:click.prevent='update()'>إضافة</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
