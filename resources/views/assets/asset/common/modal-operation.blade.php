<!-- Modal -->
<style>
    .item-rotation-modal{
        margin-bottom: 15px;
    }
    .label-field{
        font-size: 14px;
        color: #929292;
    }
    .col-field{
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
</style>
<div>
    <div class="modal fade" id="modalLiquidation" tabindex="-1" aria-labelledby="modalLiquidation" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="text-bold" style="margin: 0;">Đề nghị thanh lý tài sản</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 10px 150px; margin: 15px 0px; background: #F2F4F7;">
                    <div class="row" style="background: #fff; border-radius: 25px; padding: 30px 25px; border: 1px solid #d7d7d7;">
                        <h5 class="text-bold">Thông tin tài sản</h5>
                        <div class="row">
                            <div class="col-6 item-rotation-modal row">
                                <div class="col-6 col-field">
                                    <span class="label-field">Mã tài sản</span>
                                    <span class="label-field">Tên tài sản</span>
                                    <span class="label-field">Loại tài sản</span>
                                    <span class="label-field">Ngày mua</span>
                                    <span class="label-field">Hạn bảo hành</span>
                                </div>
                                <div class="col-6 col-field">
                                    <span x-text="assetSelect.code"></span>
                                    <span x-text="assetSelect.name"></span>
                                    <span x-text="assetSelect.asset_type ? assetSelect.asset_type.name : '--'"></span>
                                    <span x-text="formatDateVN(assetSelect.created_at)"></span>
                                    <span x-text="formatDateVN(assetSelect.created_at)"></span>
                                </div>
                            </div>
                            <div class="col-6 item-rotation-modal row">
                                <div class="col-6 col-field">
                                    <span class="label-field">Giá trị tài sản</span>
                                    <span class="label-field">Giá trị còn lại</span>
                                    <span class="label-field">Vị trí</span>
                                    <span class="label-field">Người sử dụng</span>
                                    <span class="label-field">Trạng thái</span>
                                </div>
                                <div class="col-6 col-field">
                                    <span x-text="assetSelect.price"></span>
                                    <span>--</span>
                                    <span x-text="assetSelect.location_text"></span>
                                    <span x-text="assetSelect.user ? assetSelect.user.name : '--'"></span>
                                    <span x-html="arrSvgStatus[assetSelect.status]"></span>
                                </div>
                            </div>
                        </div>
                        <h5 class="text-bold">Thông tin ghi nhận tài sản thanh lý</h5>
                        <div class="row">
                            <div class="col-6 item-rotation-modal">
                                <div class="col-12 mb-3">
                                    <span>Ngày đánh dấu</span>
                                    <input type="date" class="form-control" placeholder="dd/mm/yyyy" x-model="dateLiquidation">
                                </div>
                            </div>
                            <div class="col-6 item-rotation-modal">
                                <div class="col-12 mb-3">
                                    <span>Giá thanh lý</span>
                                    <input type="number" class="form-control" placeholder="Giá thanh lý" x-model="priceLiquidation">
                                </div>
                            </div>
                            <div class="col-12 item-rotation-modal">
                                <div class="col-12 mb-3">
                                    <span>Lý do đề nghị thanh lý</span>
                                    <textarea id="noteAllocation" name="" class="form-control" x-model="reasonLiquidation" style="width: 100%; min-height: 110px !important;"></textarea>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="d-flex" style="justify-content: flex-end; gap: 5px;">
                        <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal" @click="closeModal('#modalLiquidation')">Hủy</button>
                        <button type="button" class="btn btn-success" @click="openModal('#confirmLiquidationModal')">Xác nhận</button>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-2" id="confirmLiquidationModal" tabindex="-1" aria-labelledby="confirmLiquidationModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle" style="color: #111; font-size: 20px;">Xác nhận thanh lý</h5>
              <button type="button" class="close" data-bs-dismiss="modal" @click="closeModal('#confirmLiquidationModal')">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body d-flex" style="gap: 10px;">
              <span>
                <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="50" height="50" rx="25" fill="#E4F0E6"/>
                    <rect x="8" y="8" width="34" height="34" rx="17" fill="#379237"/>
                    <path d="M25 29.99V30M25 27C25 25 28 25 28 22.9091C28 21.3024 26.6834 20 25 20C23.5797 20 22.3384 20.9271 22 22.1818M34 25C34 29.9706 29.9706 34 25 34C20.0294 34 16 29.9706 16 25C16 20.0294 20.0294 16 25 16C29.9706 16 34 20.0294 34 25Z" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
              </span>
              <span style="display: flex;
              flex-direction: column;
              flex-wrap: nowrap;
              justify-content: center;">
                Xác nhận thanh lý tài sản
              </span>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal" @click="closeModal('#confirmLiquidationModal')">Hủy</button>
              <button type="button" class="btn btn-success" data-bs-dismiss="modal" @click="liquidationAsset(); closeModal('#confirmLiquidationModal');">Xác nhận</button>
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade modal-2" id="successLiquidationModal" tabindex="-1" aria-labelledby="successLiquidationModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-body" style="display: flex; flex-direction: column; gap: 10px; padding: 15px 30px;">
                <div class="d-flex" style="gap: 10px;">
                    <span>
                        <svg width="42" height="42" viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_13830_27524)">
                            <path d="M29.7662 13.5449H27.5678C27.0897 13.5449 26.635 13.7746 26.3537 14.1684L18.985 24.3871L15.6475 19.7559C15.3662 19.3668 14.9162 19.1324 14.4334 19.1324H12.235C11.9303 19.1324 11.7522 19.4793 11.9303 19.7277L17.7709 27.8277C17.9089 28.0203 18.0908 28.1772 18.3015 28.2855C18.5122 28.3937 18.7457 28.4502 18.9826 28.4502C19.2195 28.4502 19.453 28.3937 19.6638 28.2855C19.8745 28.1772 20.0564 28.0203 20.1943 27.8277L30.0662 14.1402C30.249 13.8918 30.0709 13.5449 29.7662 13.5449Z" fill="#52C41A"/>
                            <path d="M21 0C9.40313 0 0 9.40313 0 21C0 32.5969 9.40313 42 21 42C32.5969 42 42 32.5969 42 21C42 9.40313 32.5969 0 21 0ZM21 38.4375C11.3719 38.4375 3.5625 30.6281 3.5625 21C3.5625 11.3719 11.3719 3.5625 21 3.5625C30.6281 3.5625 38.4375 11.3719 38.4375 21C38.4375 30.6281 30.6281 38.4375 21 38.4375Z" fill="#52C41A"/>
                            </g>
                            <defs>
                            <clipPath id="clip0_13830_27524">
                            <rect width="42" height="42" fill="white"/>
                            </clipPath>
                            </defs>
                        </svg>
                      </span>
                      <span class="text-bold" style="display: flex;
                      flex-direction: column;
                      flex-wrap: nowrap;
                      justify-content: center;
                          font-size: 17px;">
                        Đề nghị thanh lý thành công
                      </span>
                </div>
            </div>
            <div class="modal-footer" style="background: #fff; border: none;">
              <button type="button" class="btn btn-success" data-bs-dismiss="modal" @click="closeModal('#successLiquidationModal');">Đóng</button>
            </div>
          </div>
        </div>
    </div>
</div>

<div>
    <div class="modal fade" id="modalCancel" tabindex="-1" aria-labelledby="modalCancel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="text-bold" style="margin: 0;">Hủy tài sản</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 10px 150px; margin: 15px 0px; background: #F2F4F7;">
                    <div class="row" style="background: #fff; border-radius: 25px; padding: 30px 25px; border: 1px solid #d7d7d7;">
                        <h5 class="text-bold">Thông tin tài sản</h5>
                        <div class="row">
                            <div class="col-6 item-rotation-modal row">
                                <div class="col-6 col-field">
                                    <span class="label-field">Mã tài sản</span>
                                    <span class="label-field">Tên tài sản</span>
                                    <span class="label-field">Loại tài sản</span>
                                    <span class="label-field">Ngày mua</span>
                                    <span class="label-field">Hạn bảo hành</span>
                                </div>
                                <div class="col-6 col-field">
                                    <span x-text="assetSelect.code"></span>
                                    <span x-text="assetSelect.name"></span>
                                    <span x-text="assetSelect.asset_type ? assetSelect.asset_type.name : '--'"></span>
                                    <span x-text="formatDateVN(assetSelect.created_at)"></span>
                                    <span x-text="formatDateVN(assetSelect.created_at)"></span>
                                </div>
                            </div>
                            <div class="col-6 item-rotation-modal row">
                                <div class="col-6 col-field">
                                    <span class="label-field">Giá trị tài sản</span>
                                    <span class="label-field">Giá trị còn lại</span>
                                    <span class="label-field">Vị trí</span>
                                    <span class="label-field">Người sử dụng</span>
                                    <span class="label-field">Trạng thái</span>
                                </div>
                                <div class="col-6 col-field">
                                    <span x-text="assetSelect.price"></span>
                                    <span>--</span>
                                    <span x-text="assetSelect.location_text"></span>
                                    <span x-text="assetSelect.user ? assetSelect.user.name : '--'"></span>
                                    <span x-html="arrSvgStatus[assetSelect.status]"></span>
                                </div>
                            </div>
                        </div>
                        <h5 class="text-bold">Thông tin hủy tài sản</h5>
                        <div class="row">
                            <div class="col-6 item-rotation-modal">
                                <div class="col-12">
                                    <span>Ngày hủy</span>
                                    <input type="date" class="form-control" placeholder="dd/mm/yyyy" x-model="dateLiquidation">
                                </div>
                            </div>
                            <div class="col-12 item-rotation-modal">
                                <div class="col-12 mb-3">
                                    <span>Lý do hủy</span>
                                    <textarea id="noteAllocation" name="" class="form-control" x-model="reasonLiquidation" style="width: 100%; min-height: 110px !important;"></textarea>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="d-flex" style="justify-content: flex-end; gap: 5px;">
                        <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal" @click="closeModal('#modalCancel')">Hủy</button>
                        <button type="button" class="btn btn-success" @click="openModal('#confirmCancelModal')">Xác nhận</button>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-2" id="confirmCancelModal" tabindex="-1" aria-labelledby="confirmCancelModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle" style="color: #111; font-size: 20px;">Xác nhận hủy tài sản</h5>
              <button type="button" class="close" data-bs-dismiss="modal" @click="closeModal('#confirmCancelModal')">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body d-flex" style="gap: 10px;">
              <span>
                <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="50" height="50" rx="25" fill="#E4F0E6"/>
                    <rect x="8" y="8" width="34" height="34" rx="17" fill="#379237"/>
                    <path d="M25 29.99V30M25 27C25 25 28 25 28 22.9091C28 21.3024 26.6834 20 25 20C23.5797 20 22.3384 20.9271 22 22.1818M34 25C34 29.9706 29.9706 34 25 34C20.0294 34 16 29.9706 16 25C16 20.0294 20.0294 16 25 16C29.9706 16 34 20.0294 34 25Z" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
              </span>
              <span style="display: flex;
              flex-direction: column;
              flex-wrap: nowrap;
              justify-content: center;">
                Xác nhận hủy tài sản
              </span>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal" @click="closeModal('#confirmCancelModal')">Hủy</button>
              <button type="button" class="btn btn-success" data-bs-dismiss="modal" @click="cancelAsset(); closeModal('#confirmCancelModal');">Xác nhận</button>
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade modal-2" id="successCancelModal" tabindex="-1" aria-labelledby="successCancelModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-body" style="display: flex; flex-direction: column; gap: 10px; padding: 15px 30px;">
                <div class="d-flex" style="gap: 10px;">
                    <span>
                        <svg width="42" height="42" viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_13830_27524)">
                            <path d="M29.7662 13.5449H27.5678C27.0897 13.5449 26.635 13.7746 26.3537 14.1684L18.985 24.3871L15.6475 19.7559C15.3662 19.3668 14.9162 19.1324 14.4334 19.1324H12.235C11.9303 19.1324 11.7522 19.4793 11.9303 19.7277L17.7709 27.8277C17.9089 28.0203 18.0908 28.1772 18.3015 28.2855C18.5122 28.3937 18.7457 28.4502 18.9826 28.4502C19.2195 28.4502 19.453 28.3937 19.6638 28.2855C19.8745 28.1772 20.0564 28.0203 20.1943 27.8277L30.0662 14.1402C30.249 13.8918 30.0709 13.5449 29.7662 13.5449Z" fill="#52C41A"/>
                            <path d="M21 0C9.40313 0 0 9.40313 0 21C0 32.5969 9.40313 42 21 42C32.5969 42 42 32.5969 42 21C42 9.40313 32.5969 0 21 0ZM21 38.4375C11.3719 38.4375 3.5625 30.6281 3.5625 21C3.5625 11.3719 11.3719 3.5625 21 3.5625C30.6281 3.5625 38.4375 11.3719 38.4375 21C38.4375 30.6281 30.6281 38.4375 21 38.4375Z" fill="#52C41A"/>
                            </g>
                            <defs>
                            <clipPath id="clip0_13830_27524">
                            <rect width="42" height="42" fill="white"/>
                            </clipPath>
                            </defs>
                        </svg>
                      </span>
                      <span class="text-bold" style="display: flex;
                      flex-direction: column;
                      flex-wrap: nowrap;
                      justify-content: center;
                          font-size: 17px;">
                        Hủy tài sản thành công
                      </span>
                </div>
            </div>
            <div class="modal-footer" style="background: #fff; border: none;">
              <button type="button" class="btn btn-success" data-bs-dismiss="modal" @click="closeModal('#successLiquidationModal');">Đóng</button>
            </div>
          </div>
        </div>
    </div>
</div>

<div>
    <div class="modal fade" id="modalBroken" tabindex="-1" aria-labelledby="modalBroken" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="text-bold" style="margin: 0;">Đánh dấu hỏng tài sản</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 10px 150px; margin: 15px 0px; background: #F2F4F7;">
                    <div class="row" style="background: #fff; border-radius: 25px; padding: 30px 25px; border: 1px solid #d7d7d7;">
                        <h5 class="text-bold">Thông tin tài sản</h5>
                        <div class="row">
                            <div class="col-6 item-rotation-modal row">
                                <div class="col-6 col-field">
                                    <span class="label-field">Mã tài sản</span>
                                    <span class="label-field">Tên tài sản</span>
                                    <span class="label-field">Loại tài sản</span>
                                    <span class="label-field">Ngày mua</span>
                                    <span class="label-field">Hạn bảo hành</span>
                                </div>
                                <div class="col-6 col-field">
                                    <span x-text="assetSelect.code"></span>
                                    <span x-text="assetSelect.name"></span>
                                    <span x-text="assetSelect.asset_type ? assetSelect.asset_type.name : '--'"></span>
                                    <span x-text="formatDateVN(assetSelect.created_at)"></span>
                                    <span x-text="formatDateVN(assetSelect.created_at)"></span>
                                </div>
                            </div>
                            <div class="col-6 item-rotation-modal row">
                                <div class="col-6 col-field">
                                    <span class="label-field">Giá trị tài sản</span>
                                    <span class="label-field">Giá trị còn lại</span>
                                    <span class="label-field">Vị trí</span>
                                    <span class="label-field">Người sử dụng</span>
                                    <span class="label-field">Trạng thái</span>
                                </div>
                                <div class="col-6 col-field">
                                    <span x-text="assetSelect.price"></span>
                                    <span>--</span>
                                    <span x-text="assetSelect.location_text"></span>
                                    <span x-text="assetSelect.user ? assetSelect.user.name : '--'"></span>
                                    <span x-html="arrSvgStatus[assetSelect.status]"></span>
                                </div>
                            </div>
                        </div>
                        <h5 class="text-bold">Thông tin tài sản</h5>
                        <div class="row">
                            <div class="col-6 item-rotation-modal">
                                <div class="col-12">
                                    <span>Ngày đánh dấu hỏng</span>
                                    <input type="date" class="form-control" placeholder="dd/mm/yyyy" x-model="dateLiquidation">
                                </div>
                            </div>
                            <div class="col-12 item-rotation-modal">
                                <div class="col-12 mb-3">
                                    <span>Lý do hỏng</span>
                                    <textarea id="noteAllocation" name="" class="form-control" x-model="reasonLiquidation" style="width: 100%; min-height: 110px !important;"></textarea>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="d-flex" style="justify-content: flex-end; gap: 5px;">
                        <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal" @click="closeModal('#modalBroken')">Hủy</button>
                        <button type="button" class="btn btn-success" @click="openModal('#confirmBrokenModal')">Xác nhận</button>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-2" id="confirmBrokenModal" tabindex="-1" aria-labelledby="confirmBrokenModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle" style="color: #111; font-size: 20px;">Xác nhận đánh dấu hỏng tài sản</h5>
              <button type="button" class="close" data-bs-dismiss="modal" @click="closeModal('#confirmBrokenModal')">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body d-flex" style="gap: 10px;">
              <span>
                <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="50" height="50" rx="25" fill="#E4F0E6"/>
                    <rect x="8" y="8" width="34" height="34" rx="17" fill="#379237"/>
                    <path d="M25 29.99V30M25 27C25 25 28 25 28 22.9091C28 21.3024 26.6834 20 25 20C23.5797 20 22.3384 20.9271 22 22.1818M34 25C34 29.9706 29.9706 34 25 34C20.0294 34 16 29.9706 16 25C16 20.0294 20.0294 16 25 16C29.9706 16 34 20.0294 34 25Z" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
              </span>
              <span style="display: flex;
              flex-direction: column;
              flex-wrap: nowrap;
              justify-content: center;">
                Xác nhận đánh dấu hỏng tài sản
              </span>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal" @click="closeModal('#confirmBrokenModal')">Hủy</button>
              <button type="button" class="btn btn-success" data-bs-dismiss="modal" @click="brokenAsset(); closeModal('#confirmBrokenModal');">Xác nhận</button>
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade modal-2" id="successBrokenModal" tabindex="-1" aria-labelledby="successBrokenModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-body" style="display: flex; flex-direction: column; gap: 10px; padding: 15px 30px;">
                <div class="d-flex" style="gap: 10px;">
                    <span>
                        <svg width="42" height="42" viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_13830_27524)">
                            <path d="M29.7662 13.5449H27.5678C27.0897 13.5449 26.635 13.7746 26.3537 14.1684L18.985 24.3871L15.6475 19.7559C15.3662 19.3668 14.9162 19.1324 14.4334 19.1324H12.235C11.9303 19.1324 11.7522 19.4793 11.9303 19.7277L17.7709 27.8277C17.9089 28.0203 18.0908 28.1772 18.3015 28.2855C18.5122 28.3937 18.7457 28.4502 18.9826 28.4502C19.2195 28.4502 19.453 28.3937 19.6638 28.2855C19.8745 28.1772 20.0564 28.0203 20.1943 27.8277L30.0662 14.1402C30.249 13.8918 30.0709 13.5449 29.7662 13.5449Z" fill="#52C41A"/>
                            <path d="M21 0C9.40313 0 0 9.40313 0 21C0 32.5969 9.40313 42 21 42C32.5969 42 42 32.5969 42 21C42 9.40313 32.5969 0 21 0ZM21 38.4375C11.3719 38.4375 3.5625 30.6281 3.5625 21C3.5625 11.3719 11.3719 3.5625 21 3.5625C30.6281 3.5625 38.4375 11.3719 38.4375 21C38.4375 30.6281 30.6281 38.4375 21 38.4375Z" fill="#52C41A"/>
                            </g>
                            <defs>
                            <clipPath id="clip0_13830_27524">
                            <rect width="42" height="42" fill="white"/>
                            </clipPath>
                            </defs>
                        </svg>
                      </span>
                      <span class="text-bold" style="display: flex;
                      flex-direction: column;
                      flex-wrap: nowrap;
                      justify-content: center;
                          font-size: 17px;">
                        Đánh dấu hỏng tài sản thành công
                      </span>
                </div>
            </div>
            <div class="modal-footer" style="background: #fff; border: none;">
              <button type="button" class="btn btn-success" data-bs-dismiss="modal" @click="closeModal('#successBrokenModal');">Đóng</button>
            </div>
          </div>
        </div>
    </div>
</div>

<div>
    <div class="modal fade" id="modalLost" tabindex="-1" aria-labelledby="modalLost" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="text-bold" style="margin: 0;">Đánh dấu mất tài sản</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 10px 150px; margin: 15px 0px; background: #F2F4F7;">
                    <div class="row" style="background: #fff; border-radius: 25px; padding: 30px 25px; border: 1px solid #d7d7d7;">
                        <h5 class="text-bold">Thông tin tài sản</h5>
                        <div class="row">
                            <div class="col-6 item-rotation-modal row">
                                <div class="col-6 col-field">
                                    <span class="label-field">Mã tài sản</span>
                                    <span class="label-field">Tên tài sản</span>
                                    <span class="label-field">Loại tài sản</span>
                                    <span class="label-field">Ngày mua</span>
                                    <span class="label-field">Hạn bảo hành</span>
                                </div>
                                <div class="col-6 col-field">
                                    <span x-text="assetSelect.code"></span>
                                    <span x-text="assetSelect.name"></span>
                                    <span x-text="assetSelect.asset_type ? assetSelect.asset_type.name : '--'"></span>
                                    <span x-text="formatDateVN(assetSelect.created_at)"></span>
                                    <span x-text="formatDateVN(assetSelect.created_at)"></span>
                                </div>
                            </div>
                            <div class="col-6 item-rotation-modal row">
                                <div class="col-6 col-field">
                                    <span class="label-field">Giá trị tài sản</span>
                                    <span class="label-field">Giá trị còn lại</span>
                                    <span class="label-field">Vị trí</span>
                                    <span class="label-field">Người sử dụng</span>
                                    <span class="label-field">Trạng thái</span>
                                </div>
                                <div class="col-6 col-field">
                                    <span x-text="assetSelect.price"></span>
                                    <span>--</span>
                                    <span x-text="assetSelect.location_text"></span>
                                    <span x-text="assetSelect.user ? assetSelect.user.name : '--'"></span>
                                    <span x-html="arrSvgStatus[assetSelect.status]"></span>
                                </div>
                            </div>
                        </div>
                        <h5 class="text-bold">Thông tin tài sản</h5>
                        <div class="row">
                            <div class="col-6 item-rotation-modal">
                                <div class="col-12">
                                    <span>Ngày đánh dấu mất</span>
                                    <input type="date" class="form-control" placeholder="dd/mm/yyyy" x-model="dateLiquidation">
                                </div>
                            </div>
                            <div class="col-12 item-rotation-modal">
                                <div class="col-12 mb-3">
                                    <span>Lý do mất</span>
                                    <textarea id="noteAllocation" name="" class="form-control" x-model="reasonLiquidation" style="width: 100%; min-height: 110px !important;"></textarea>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="d-flex" style="justify-content: flex-end; gap: 5px;">
                        <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal" @click="closeModal('#modalLost')">Hủy</button>
                        <button type="button" class="btn btn-success" @click="openModal('#confirmLostModal')">Xác nhận</button>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-2" id="confirmLostModal" tabindex="-1" aria-labelledby="confirmLostModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle" style="color: #111; font-size: 20px;">Xác nhận đánh dấu hỏng tài sản</h5>
              <button type="button" class="close" data-bs-dismiss="modal" @click="closeModal('#confirmLostModal')">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body d-flex" style="gap: 10px;">
              <span>
                <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="50" height="50" rx="25" fill="#E4F0E6"/>
                    <rect x="8" y="8" width="34" height="34" rx="17" fill="#379237"/>
                    <path d="M25 29.99V30M25 27C25 25 28 25 28 22.9091C28 21.3024 26.6834 20 25 20C23.5797 20 22.3384 20.9271 22 22.1818M34 25C34 29.9706 29.9706 34 25 34C20.0294 34 16 29.9706 16 25C16 20.0294 20.0294 16 25 16C29.9706 16 34 20.0294 34 25Z" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
              </span>
              <span style="display: flex;
              flex-direction: column;
              flex-wrap: nowrap;
              justify-content: center;">
                Xác nhận đánh dấu mất tài sản
              </span>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal" @click="closeModal('#confirmLostModal')">Hủy</button>
              <button type="button" class="btn btn-success" data-bs-dismiss="modal" @click="lostAsset(); closeModal('#confirmLostModal');">Xác nhận</button>
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade modal-2" id="successLostModal" tabindex="-1" aria-labelledby="successLostModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-body" style="display: flex; flex-direction: column; gap: 10px; padding: 15px 30px;">
                <div class="d-flex" style="gap: 10px;">
                    <span>
                        <svg width="42" height="42" viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_13830_27524)">
                            <path d="M29.7662 13.5449H27.5678C27.0897 13.5449 26.635 13.7746 26.3537 14.1684L18.985 24.3871L15.6475 19.7559C15.3662 19.3668 14.9162 19.1324 14.4334 19.1324H12.235C11.9303 19.1324 11.7522 19.4793 11.9303 19.7277L17.7709 27.8277C17.9089 28.0203 18.0908 28.1772 18.3015 28.2855C18.5122 28.3937 18.7457 28.4502 18.9826 28.4502C19.2195 28.4502 19.453 28.3937 19.6638 28.2855C19.8745 28.1772 20.0564 28.0203 20.1943 27.8277L30.0662 14.1402C30.249 13.8918 30.0709 13.5449 29.7662 13.5449Z" fill="#52C41A"/>
                            <path d="M21 0C9.40313 0 0 9.40313 0 21C0 32.5969 9.40313 42 21 42C32.5969 42 42 32.5969 42 21C42 9.40313 32.5969 0 21 0ZM21 38.4375C11.3719 38.4375 3.5625 30.6281 3.5625 21C3.5625 11.3719 11.3719 3.5625 21 3.5625C30.6281 3.5625 38.4375 11.3719 38.4375 21C38.4375 30.6281 30.6281 38.4375 21 38.4375Z" fill="#52C41A"/>
                            </g>
                            <defs>
                            <clipPath id="clip0_13830_27524">
                            <rect width="42" height="42" fill="white"/>
                            </clipPath>
                            </defs>
                        </svg>
                      </span>
                      <span class="text-bold" style="display: flex;
                      flex-direction: column;
                      flex-wrap: nowrap;
                      justify-content: center;
                          font-size: 17px;">
                        Đánh dấu mất tài sản thành công
                      </span>
                </div>
            </div>
            <div class="modal-footer" style="background: #fff; border: none;">
              <button type="button" class="btn btn-success" data-bs-dismiss="modal" @click="closeModal('#successLostModal');">Đóng</button>
            </div>
          </div>
        </div>
    </div>
</div>