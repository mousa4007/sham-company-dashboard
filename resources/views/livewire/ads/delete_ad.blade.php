<div>
    <!-- Button trigger modal -->

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="deleteAdModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> تأكيد حذف الإعلان</h5>
                    <div wire:loading wire:target='delete'>
                        <img src="assets/vendors/svg-loaders/grid.svg" class="mx-4" style="width: 1.6rem"
                            alt="grid">
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <h4> هل أنت متأكد من حذف المنتج</h4>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="button" class="btn btn-danger" wire:click.prevent='destroy'>حذف</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
