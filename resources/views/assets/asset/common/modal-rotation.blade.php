<!-- Modal -->
<style>
    .item-rotation-modal{
        margin-bottom: 15px;
    }
</style>
<div>
    <div class="modal fade" id="modalRotation" tabindex="-1" aria-labelledby="modalRotation" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="text-bold" style="    margin: 0;">Luân chuyển</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 10px 70px; margin: 15px 0px;">
                    <div class="row">
                        <h5 class="text-bold">Máy tính</h5>
                        <div class="row">
                            <div class="col-6 item-rotation-modal">
                                <div class="col-12 mb-3">
                                    <span>Số biên bản</span>
                                    <input type="text" class="form-control" value="BB01" disabled>
                                </div>
                                <div class="col-12 mb-3">
                                    <span>Ngày luân chuyển</span>
                                    <input type="date" class="form-control" x-model="new Date().toISOString().slice(0, 10)" disabled>
                                </div>
                            </div>
                            <div class="col-6 item-rotation-modal">
                                <div class="mb-3">
                                    <span>Lý do luân chuyển</span>
                                    <textarea id="noteAllocation" name="" class="form-control" x-model="descriptionRotation" style="width: 100%; min-height: 110px !important;"></textarea>

                                </div>
                            </div>
                            <div class="col-6 item-rotation-modal">
                                <h5 class="text-bold">Luân chuyển từ</h5>
                                <div class="mb-3">
                                    <span>Đối tượng luân chuyển</span>
                                    <div class="d-flex" style="gap: 30px;">
                                        <div class="form-check">
                                            <input class="form-check-input" :checked="assetSelect.user"  type="radio" value="" name="changeTabFrom" id="defaultCheckFrom1">
                                            <label class="form-check-label" for="defaultCheckFrom1">
                                                Nhân viên
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" :checked="assetSelect.organization"  type="radio" value="" name="changeTabFrom" id="defaultCheckFrom2">
                                            <label class="form-check-label" for="defaultCheckFrom2">
                                                Đơn vị
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-3" x-show="assetSelect.organization">
                                        <span>Đơn vị</span>
                                        <input type="text" class="form-control" x-model="assetSelect.organization.dept_type.cfg_key + ' ' + assetSelect.organization.name" disabled>
                                    </div>
                                    <div class="mb-3" x-show="assetSelect.user">
                                        <span>Nhân viên</span>
                                        <input type="text" class="form-control" x-model="assetSelect.user.code + ' - ' + assetSelect.user.name" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 item-rotation-modal">
                                <h5 class="text-bold">Luân chuyển đến</h5>
                                <span>Đối tượng luân chuyển</span>
                                <div class="d-flex" style="gap: 30px;">
                                    <div class="form-check">
                                        <input class="form-check-input" :checked="defaultCheckRotation == 'employee'"  type="radio" value="" name="changeTabTo" id="defaultCheckTo1" @click="defaultCheckRotation='employee'; rotationToType='employee';">
                                        <label class="form-check-label" for="defaultCheckTo1">
                                            Nhân viên
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" :checked="defaultCheckRotation == 'unit'"  type="radio" value="" name="changeTabTo" id="defaultCheckTo2" @click="defaultCheckRotation='unit'; rotationToType='unit';">
                                        <label class="form-check-label" for="defaultCheckTo2">
                                            Đơn vị
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-3" x-show="defaultCheckRotation == 'unit'">
                                    <span>Đơn vị</span>
                                    <select class="form-control select2" data-placeholder="Đơn vị" id="unitToSelect">
                                        <option value="" selected>Đơn vị</option>
                                        <template x-for="(org, key) in listOrg">
                                            <option :value="org.id" x-text="org.dept_type.cfg_key + ' ' + org.name"></option>
                                        </template>
                                    </select>
                                </div>
                                <div class="mb-3" x-show="defaultCheckRotation == 'employee'">
                                    <span>Nhân viên</span>
                                    <select class="form-control select2" data-placeholder="Người dùng" id="userToSelect">
                                        <option value="" selected>Người dùng</option>
                                        <template x-for="(user, key) in listUser">
                                            <option :value="user.id" x-text="user.code + ' - ' + user.name"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 item-rotation-modal">
                                <h5 class="text-bold">Tài sản được luân chuyển</h5>
                                <div class="col-12 custom-scroll" style="overflow-x: auto;width: 100%; max-height: 250px;">
                                    <table class="table table-bordered table-repair">
                                        <thead class="sticky-top">
                                        <tr style="font-size: 14px;">
                                            <th>Mã tài sản</th>
                                            <th>Tên tài sản</th>
                                            <th>Loại tài sản</th>
                                            <th>Số Seri</th>
                                            <th>Đơn vị tính</th>
                                            <th>Giá</th>
                                            <th>Vị trí</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td x-text="assetSelect.code"></td>
                                                <td x-text="assetSelect.name"></td>
                                                <td x-text="assetSelect.asset_type ? assetSelect.asset_type.name : ''"></td>
                                                <td x-text="assetSelect.seri_number"></td>
                                                <td x-text="LIST_MEASURE[assetSelect.asset_type.measure]"></td>
                                                <td x-text="assetSelect.price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')"></td> 
                                                <td x-text="assetSelect.location_text"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="d-flex" style="justify-content: flex-end; gap: 5px;">
                        <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal" @click="closeModal('#modalRotation')">Hủy</button>
                        <button type="button" class="btn btn-success" @click="openModal('#confirmRotationModal')">Luân chuyển</button>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-2" id="confirmRotationModal" tabindex="-1" aria-labelledby="confirmRotationModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle" style="color: #111; font-size: 20px;">Xác nhận luân chuyển</h5>
              <button type="button" class="close" data-bs-dismiss="modal" @click="closeModal('#confirmRotationModal')">
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
                Xác nhận luân chuyển tài sản
              </span>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal" @click="closeModal('#confirmRotationModal')">Hủy</button>
              <button type="button" class="btn btn-success" data-bs-dismiss="modal" @click="rotationAsset(); closeModal('#confirmRotationModal');">Xác nhận</button>
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade modal-2" id="successRotationModal" tabindex="-1" aria-labelledby="successRotationModal" aria-hidden="true">
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
                        Cấp phát thành công
                      </span>
                </div>
                <span>
                    Biên bản luân chuyển
                </span>
                <span class="text-primary">
                    Bienbanluanchuyen
                </span>
            </div>
            <div class="modal-footer" style="background: #fff; border: none;">
              <button type="button" class="btn btn-success" data-bs-dismiss="modal" @click="closeModal('#successRotationModal');">Đóng</button>
            </div>
          </div>
        </div>
    </div>
</div>