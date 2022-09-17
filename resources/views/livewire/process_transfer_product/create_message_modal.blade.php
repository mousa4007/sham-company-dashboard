<div>
    <div wire:ignore.self class="modal fade" id="createMessageModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> تحديد الرسالة</h5>

                    @include('components.spinner')
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1" class="form-label">حدد الرسالة</label>
                            <textarea wire:model="message" class="form-control" id="exampleFormControlTextarea1" rows="5">
                            </textarea>
                        </div>
                    </div>

                    {{-- <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <div class="border d-flex justify-content-between px-5 py-2">
                                    <div class=" fw-bold">المستخدم :</div>
                                    <div class="text-primary fw-bold">
                                        @if ($app_user_id)
                                            {{ $app_users->find($app_user_id)->name }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="border d-flex justify-content-between px-5 py-2">
                                    <div class=" fw-bold">المنتج :</div>
                                    <div class="text-primary fw-bold">
                                        @if ($product_id)
                                            {{ $products->find($product_id)->name }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="border d-flex justify-content-between px-5 py-2">
                                    <div class=" fw-bold">المبلغ :</div>
                                    <div class="text-primary fw-bold">
                                        @if ($amount)
                                            {{ $amount }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="border d-flex justify-content-between px-5 py-2">
                                    <div class=" fw-bold">تاريخ الطلب :</div>
                                    <div class="text-primary fw-bold">
                                        @if ($created_at)
                                            {{ $created_at }}
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="border border-primary d-flex justify-content-between px-5 py-2">
                                    <div class="fw-bold"> رقم الهاتف :</div>

                                    <div class="text-primary fw-bold d-flex">
                                        <input disabled class="form-control d-inline text-primary fw-bold"
                                            type="text" value="{{ $address }}" id="myInput"
                                            style="    max-width: 110px;">
                                        <button id="btnCopy" class="btn btn-primary btn-sm" onclick="copy()"><i
                                                class="bi-clipboard"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <textarea name="" id=""  rows="10"></textarea>
                        </div>
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                            class="bi-x-circle-fill"></i></button>
                    <button type="button" class="btn btn-success"
                        @if ($is_accept) wire:click='acceptTransfer'
                        @else
                        wire:click='rejectTransfer' @endif><i
                            class="bi-check-circle-fill"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>
