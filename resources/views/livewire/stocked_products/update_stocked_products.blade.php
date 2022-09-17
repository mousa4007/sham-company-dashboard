<div>
    <div wire:ignore.self class="modal fade" id="updateStockedProductModal" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">تعديل عنصر</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form form-vertical">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group has-icon-left">
                                        <label for="first-name-icon">العنصر</label>
                                        <div class="position-relative">
                                            <input wire:model='product_item' type="text"
                                                class="form-control @error('product_item') is-invalid @enderror"
                                                placeholder="اسم المنتج" id="first-name-icon" required>
                                            <div class="form-control-icon">
                                                <i class="bi bi-basket"></i>
                                            </div>
                                        </div>
                                        @error('product_item')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12 mb-0">
                                    <label for="first-name-icon" class="d-block me-0">القسم</label>
                                    <fieldset class="form-group">
                                        <select wire:model='parentCategoryId' class="form-select" id="basicSelect">
                                            {{-- <option></option> --}}
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ $parentCategoryId === $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </fieldset>
                                    @error('categoryId')
                                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-0">
                                    <label for="product" class="d-block me-0">المنتج</label>
                                    <fieldset class="form-group">
                                        <select wire:model.defer='product_id' class="form-select" id="basicSelect">
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    {{ $product_id == $product->id ? 'selected' : '' }}>
                                                    {{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </fieldset>

                                    @error('product_id')
                                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="button" class="btn btn-success" wire:click.prevent='update'>تعديل</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
