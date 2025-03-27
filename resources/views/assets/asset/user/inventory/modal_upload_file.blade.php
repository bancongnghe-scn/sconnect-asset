<div class="modal fade" id="modalUploadFile" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Kiểm kê</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <div>Bước 1: Tải file dưới đây về máy và chạy file</div>
                    <a href="/files/getSystemtInfo.ps1" download="getSystemtInfo.ps1">Tải file về máy</a>
                </div>
                <div class="mt-3">
                    <div>Bước 2: Upload file đã chạy lên tại đây</div>
                    <div class="mt-3">
                        <input class="form-control d-none" type="file" id="fileInputContract" x-ref="fileInput" @change="handleFile" accept=".txt">
                        <label for="fileInputContract" class="tw-cursor-pointer border border-1 bg-light align-content-center text-center tw-w-40 tw-h-32 text-black-50">
                            + Tải lên
                        </label>
                        <div class="color-sc" x-text="file?.name"></div>
                    </div>
                </div>

                <div class="mt-3">Bước 3: Tải file hoàn thành và gửi đi</div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-success" aria-label="Close" data-bs-dismiss="modal">Hủy</button>
                <button class="btn btn-sc" @click="uploadFileInventory">Gửi</button>
            </div>
        </div>
    </div>
</div>


