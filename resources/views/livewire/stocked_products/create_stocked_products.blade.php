<div>
    <div wire:ignore.self class="modal fade" id="createStockedProductModal" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">إضافة منتجات مخزونة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form class="form form-vertical">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="first-name-icon">المنتجات</label>
                                        <div class="position-relative">
                                            <textarea wire:model='product_item' type="text" class="form-control @error('product_item') is-invalid @enderror"
                                                placeholder="المنتجات" id="first-name-icon" required></textarea>
                                        </div>
                                        @error('product_item')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="first-name-icon" class="mb-2">عدد الأسطر لكل منتج</label>
                                        <div class="position-relative">
                                            <input wire:model.defer='lineNumber' type="number"
                                                class="form-control @error('lineNumber') is-invalid @enderror"
                                                placeholder="عدد الأسطر" id="first-name-icon" required>
                                        </div>
                                        @error('lineNumber')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12 mb-0">
                                    <label for="first-name-icon" class="d-block me-0">القسم</label>
                                    <fieldset class="form-group">
                                        <select wire:model='categoryId' class="form-select" id="basicSelect">
                                            <option value="{{ null }}">اختيار</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                                        <select wire:model='product_id' class="form-select" id="basicSelect">
                                            <option value="">اختيار</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
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
                            <button type="button" class="btn btn-success" wire:click.prevent='store()'>إضافة</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
