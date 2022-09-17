<div>
    <!-- Button trigger modal -->

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="deleteAgentModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> تأكيد حذف العميل</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <h4> هل أنت متأكد من حذف العميل</h4>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="button" class="btn btn-danger" wire:click.prevent='destroy'>حذف</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
