<div>
    <div wire:ignore.self class="modal fade" id="addBuyPriceModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> تحديد سعر الشراء</h5>

                    @include('components.spinner')
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card">
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1" class="form-label">سعر الشراء</label>
                                <input wire:model="buy_price" class="form-control" id="exampleFormControlTextarea1" rows="5">     
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1" class="form-label">الرسالة </label>
                            <textarea wire:model="message" class="form-control" id="exampleFormControlTextarea1" rows="5">  
                            </textarea>   
                        </div>
                    </div>
                    
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
