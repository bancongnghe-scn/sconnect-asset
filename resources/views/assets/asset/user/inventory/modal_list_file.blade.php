<div class="modal fade" id="modalListFile" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thông tin kiểm kê</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label>File đã tải lên</label>
                <div class="d-flex tw-gap-x-2 align-items-center">
                    <i class="bi bi-file-earmark-fill tw-text-6xl text-primary"></i>
                    <div>
                        <a x-text="file?.file_name" :href="'/uploads/'+file?.file_url" target="_blank"></a>
                        <div class="tw-text-gray-400" x-text="formatDateVN(file?.created_at || null)"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


