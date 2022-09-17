<div>
    <div wire:ignore.self class="modal fade" id="editReturnModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> تفاصيل المرتجع</h5>
                    @include('components.spinner')
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form form-vertical">
                        <div class="form-body">
                            <div class="row">

                                <div class="col-md-12">
                                    <h6 style="min-width: 150px; display: inline-block;"> المرتجع :</h6>
                                    <input type="text" value="{{ $return }}" class="form-control w-50 d-inline"
                                        disabled>
                                    <hr>
                                    <h6 style="min-width: 150px; display: inline-block;">المنتج
                                        :</h6>
                                    <input type="text"
                                        value="@if ($product_id) {{ $products->find($product_id)->name }} @endif"
                                        class="form-control w-50 d-inline" disabled>
                                    <hr>
                                    <h6 style="min-width: 150px; display: inline-block;">سبب المراجعة :</h6>
                                    <input type="text" value="{{ $reason }}" class="form-control w-50 d-inline"
                                        disabled>
                                    <hr>
                                    <h6 style="min-width: 150px; display: inline-block;">المراجع :</h6>
                                    <input type="text"
                                        value="@if ($returner) {{ $users->find($returner)->name }} @endif"
                                        class="form-control w-50 d-inline" disabled>
                                    <hr>
                                    <h6 style="min-width: 150px; display: inline-block;">الحالة :</h6>
                                    <input type="text" value="{{ $status }}" class="form-control w-50 d-inline"
                                        disabled>
                                    <hr>
                                    <h6 style="min-width: 150px; display: inline-block;">المشتريات :</h6>

                                    <input type="text"
                                        value="@if ($returner) {{ count($users->find($returner)->orders) }} @endif"
                                        class="form-control w-50 d-inline" disabled>
                                    <hr>
                                    <h6 style="min-width: 150px; display: inline-block;"> المرتجعات :</h6>

                                    <input type="text"
                                        value="@if ($returner) {{ count($users->find($returner)->returns) }} @endif"
                                        class="form-control w-50 d-inline" disabled>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm"
                                    data-bs-dismiss="modal">إلغاء</button>
                                <button type="button" class="btn btn-success btn-sm"
                                    wire:click.prevent='accept()'>قبول</button>
                                <button type="button" class="btn btn-danger btn-sm"
                                    wire:click.prevent='reject()'>رفض</button>
                                <button type="button" class="btn btn-warning btn-sm"
                                    wire:click.prevent='ignore()'>تعليق</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
