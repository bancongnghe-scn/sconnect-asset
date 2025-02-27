<!-- Modal -->
<div>
    <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetail" aria-hidden="true">
        <div class="modal-dialog modal-xl" style="--bs-modal-width: 1400px !important">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="mb-0" x-text="Object.keys(userObj).length != 0 ? userObj.code + ' - ' + userObj.name + ' - ' + userObj.job_position : orgObj.dept_type.cfg_key + ' ' + orgObj.name"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-3" style="padding-right: 60px;">
                            <ul class="sidebar-tab" style="padding: 0;">
                                <li @click="tab='general-tab'" :class="tab == 'general-tab' ? 'active-sidebar' : ''">Thông tin chung</li>
                                <li @click="tab='allocation-tab'" :class="tab == 'allocation-tab' ? 'active-sidebar' : ''">Cấp phát/Thu hồi</li>
                                <li @click="tab='asset-tab'" :class="tab == 'asset-tab' ? 'active-sidebar' : ''" x-show="Object.keys(orgObj).length == 0">Tài sản đang đại diện</li>
                                <li @click="tab='history-tab'" :class="tab == 'history-tab' ? 'active-sidebar' : ''">Lịch sử</li>
                            </ul>
                        </div>
                        <div class="col-9" style="min-height: 500px;">
                            <div class="name-asset d-flex" style="gap: 10px;">
                                <h5 class="text-bold"></h5>
                            </div>
                            <div class="general-tab" x-show="tab == 'general-tab' && Object.keys(orgObj).length == 0">
                                <h6 class="text-bold">Thông tin chung</h6>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <span>Mã nhân viên</span>
                                            <input type="text" class="form-control" x-model="userObj.code" disabled>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <span>Vị trí công việc</span>
                                            <input type="text" class="form-control" x-model="userObj.job_position" disabled>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <span>Tên nhân viên</span>
                                            <input type="text" class="form-control" x-model="userObj.name" disabled>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <span>Đơn vị</span>
                                            <input type="text" class="form-control" x-model="userObj.org_last_parent ? userObj.org_last_parent.org_name : userObj.organization.dept_type.cfg_key + ' ' + userObj.organization.name" disabled>

                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <span>Giới tính</span>
                                            <input type="text" class="form-control" disabled>

                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <span>Tài sản đang sử dụng</span>
                                            <input type="text" class="form-control" x-model="userObj.list_asset_use.length" disabled>

                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <span>Số điện thoại</span>
                                            <input type="text" class="form-control" x-model="userObj.phone" disabled>

                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <span>Tài sản đại diện</span>
                                            <input type="number" class="form-control" x-model="listAssetRepresent.length" disabled>

                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <span>Email</span>
                                            <input type="text" class="form-control" x-model="userObj.email" disabled>

                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <span>Trạng thái làm việc</span>
                                            <input type="text" class="form-control" x-model="userObj.status == 1 ? 'Đang làm việc' : '' " disabled>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="general-tab" x-show="tab == 'general-tab' && Object.keys(orgObj).length != 0">
                                <h6 class="text-bold">Thông tin chung</h6>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <span>Tên</span>
                                            <input type="text" class="form-control" x-model="orgObj.name" disabled>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <span>Người quản lý</span>
                                            <input type="text" class="form-control" x-model="orgObj.manager.name" disabled>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <span>Tài sản đang sử dụng</span>
                                            <input type="number" class="form-control" value="0" disabled>

                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="mb-3">
                                            <span>Tài sản sử dụng chung</span>
                                            <input type="number" class="form-control" value="0" disabled>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="allocation-tab" x-show="tab == 'allocation-tab'">
                                <h6 class="text-bold">
                                    Cấp phát/Thu hồi
                                </h6>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <span>Thao tác</span>
                                            <div class="d-flex" style="gap: 30px;">
                                                <div class="form-check">
                                                    <input class="form-check-input" :checked="tabAllocation == 'allocation-tab'"  type="radio" value="" name="changeTab" id="defaultCheck11" @click="tabAllocation='allocation-tab'">
                                                    <label class="form-check-label" for="defaultCheck11">
                                                        Cấp phát
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" :checked="tabAllocation == 'recovery-tab'"  type="radio" value="" name="changeTab" id="defaultCheck22" @click="tabAllocation='recovery-tab'">
                                                    <label class="form-check-label" for="defaultCheck22">
                                                        Thu hồi
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="allocation-tab" x-show="tabAllocation == 'allocation-tab'">
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <span>Ngày cấp phát</span>
                                                <input type="date" class="form-control" x-model="new Date().toISOString().slice(0, 10)">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <span>Nội dung cấp phát</span>
                                                <textarea id="noteAllocation" name="" class="form-control" x-model="description" style="width: 100%; height: 60px !important;"></textarea>
    
                                            </div>
                                        </div>
                                        <span class="text-bold">Tài sản được cấp phát</span>
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
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <template x-for="(asset, index) in listAssetOfUser" :key="asset.id">
                                                    <tr>
                                                        <td x-text="asset.code"></td>
                                                        <td x-text="asset.name"></td>
                                                        <td x-text="asset.asset_type ? asset.asset_type.name : ''"></td>
                                                        <td x-text="asset.seri_number"></td>
                                                        <td x-text="LIST_MEASURE[asset.asset_type.measure]"></td>
                                                        <td x-text="asset.price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')"></td> 
                                                        <td x-text="asset.location_text"></td>
                                                        <td class="text-center" style="cursor: pointer;">
                                                            
                                                        </td>
                                                    </tr>
                                                </template>
                                                <template x-for="(asset, index) in listAssetAllocate" :key="asset.id">
                                                    <tr>
                                                        <td x-text="asset.code"></td>
                                                        <td x-text="asset.name"></td>
                                                        <td x-text="asset.asset_type ? asset.asset_type.name : ''"></td>
                                                        <td x-text="asset.seri_number"></td>
                                                        <td x-text="LIST_MEASURE[asset.asset_type.measure]"></td>
                                                        <td x-text="asset.price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')"></td> 
                                                        <td x-text="asset.location_text"></td>
                                                        <td class="text-center" style="cursor: pointer;" @click="deleteSelection(asset.id)">
                                                            <svg width="21" height="23" viewBox="0 0 21 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M1.5 5.5H19.5" stroke="#F31111" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                                <path d="M3.5 5.5V19.5C3.5 20.6046 4.39543 21.5 5.5 21.5H15.5C16.6046 21.5 17.5 20.6046 17.5 19.5V5.5" stroke="#F31111" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                                <path d="M6.5 5.5V3.5C6.5 2.39543 7.39543 1.5 8.5 1.5H12.5C13.6046 1.5 14.5 2.39543 14.5 3.5V5.5" stroke="#F31111" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                                <path d="M8.5 11.5L8.5 15.5" stroke="#F31111" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                                <path d="M12.5 11.5L12.5 15.5" stroke="#F31111" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </td>
                                                    </tr>
                                                </template>
                                                </tbody>
                                            </table>
                                        </div>
                                        <span style="cursor: pointer;" @click="openModal('#searchAssetModal'); getDataAsset();">
                                            <svg width="111" height="16" viewBox="0 0 111 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M8 1V15M1 8L15 8" stroke="#379237" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M24.4972 5.09304V3.31818H32.8594V5.09304H29.7422V13.5H27.6143V5.09304H24.4972ZM36.3146 9.08523V13.5H34.1967V3.31818H36.255V7.21094H36.3445C36.5168 6.76018 36.7952 6.4072 37.1797 6.15199C37.5642 5.89347 38.0464 5.7642 38.6264 5.7642C39.1567 5.7642 39.6191 5.88021 40.0135 6.11222C40.4112 6.34091 40.7195 6.67069 40.9382 7.10156C41.1603 7.52912 41.2697 8.04119 41.2663 8.63778V13.5H39.1484V9.01562C39.1518 8.54498 39.0324 8.17874 38.7905 7.9169C38.5518 7.65507 38.2171 7.52415 37.7862 7.52415C37.4979 7.52415 37.2427 7.58546 37.0206 7.7081C36.8018 7.83073 36.6295 8.00971 36.5036 8.24503C36.3809 8.47704 36.3179 8.7571 36.3146 9.08523ZM46.4132 13.6491C45.6277 13.6491 44.9515 13.4901 44.3848 13.1719C43.8213 12.8504 43.3871 12.3963 43.0822 11.8097C42.7773 11.2197 42.6248 10.522 42.6248 9.71662C42.6248 8.93111 42.7773 8.24171 43.0822 7.64844C43.3871 7.05516 43.8163 6.5928 44.3699 6.26136C44.9267 5.92992 45.5796 5.7642 46.3287 5.7642C46.8324 5.7642 47.3014 5.84541 47.7356 6.00781C48.1731 6.1669 48.5543 6.4072 48.8791 6.72869C49.2072 7.05019 49.4624 7.45455 49.6447 7.94176C49.827 8.42566 49.9181 8.99242 49.9181 9.64205V10.2237H43.47V8.91122H47.9245C47.9245 8.6063 47.8583 8.33617 47.7257 8.10085C47.5931 7.86553 47.4092 7.68158 47.1738 7.54901C46.9418 7.41312 46.6717 7.34517 46.3635 7.34517C46.042 7.34517 45.7569 7.41974 45.5083 7.56889C45.2631 7.71473 45.0708 7.91193 44.9316 8.16051C44.7924 8.40578 44.7212 8.67921 44.7179 8.98082V10.2287C44.7179 10.6065 44.7875 10.933 44.9267 11.2081C45.0692 11.4832 45.2697 11.6953 45.5282 11.8445C45.7868 11.9936 46.0933 12.0682 46.448 12.0682C46.6833 12.0682 46.8987 12.035 47.0943 11.9688C47.2898 11.9025 47.4572 11.803 47.5964 11.6705C47.7356 11.5379 47.8417 11.3755 47.9146 11.1832L49.8734 11.3125C49.774 11.7831 49.5701 12.1941 49.2619 12.5455C48.957 12.8935 48.5626 13.1652 48.0787 13.3608C47.5981 13.553 47.0429 13.6491 46.4132 13.6491ZM47.2782 4.94886L46.2789 3.7358L45.2797 4.94886H43.475V4.87926L45.4885 2.69176H47.0645L49.0829 4.87926V4.94886H47.2782ZM51.3002 13.5V5.86364H53.3187V7.21094H53.4082C53.5673 6.76349 53.8324 6.41051 54.2037 6.15199C54.5749 5.89347 55.019 5.7642 55.536 5.7642C56.0597 5.7642 56.5055 5.89512 56.8734 6.15696C57.2413 6.41548 57.4866 6.76681 57.6092 7.21094H57.6887C57.8445 6.77344 58.1262 6.42377 58.5339 6.16193C58.9449 5.89678 59.4305 5.7642 59.9906 5.7642C60.7032 5.7642 61.2815 5.99124 61.7257 6.44531C62.1731 6.89607 62.3968 7.53575 62.3968 8.36435V13.5H60.2839V8.78196C60.2839 8.35772 60.1712 8.03954 59.9458 7.82741C59.7205 7.61529 59.4387 7.50923 59.1007 7.50923C58.7162 7.50923 58.4163 7.63187 58.2008 7.87713C57.9854 8.11908 57.8777 8.43892 57.8777 8.83665V13.5H55.8244V8.73722C55.8244 8.36269 55.7167 8.06439 55.5012 7.84233C55.2891 7.62026 55.0091 7.50923 54.661 7.50923C54.4257 7.50923 54.2136 7.56889 54.0247 7.68821C53.8391 7.80421 53.6916 7.96828 53.5822 8.1804C53.4728 8.3892 53.4181 8.63447 53.4181 8.91619V13.5H51.3002ZM71.3718 5.86364V7.45455H66.7731V5.86364H71.3718ZM67.8171 4.03409H69.935V11.1534C69.935 11.349 69.9648 11.5014 70.0245 11.6108C70.0842 11.7169 70.167 11.7914 70.2731 11.8345C70.3825 11.8776 70.5084 11.8991 70.6509 11.8991C70.7504 11.8991 70.8498 11.8909 70.9492 11.8743C71.0487 11.8544 71.1249 11.8395 71.1779 11.8295L71.511 13.4055C71.4049 13.4387 71.2558 13.4768 71.0636 13.5199C70.8713 13.5663 70.6377 13.5945 70.3626 13.6044C69.8522 13.6243 69.4047 13.5563 69.0202 13.4006C68.6391 13.2448 68.3424 13.0028 68.1303 12.6747C67.9182 12.3466 67.8138 11.9323 67.8171 11.4318V4.03409ZM74.929 13.6442C74.4418 13.6442 74.0076 13.5597 73.6264 13.3906C73.2453 13.2183 72.9437 12.9647 72.7216 12.63C72.5028 12.2919 72.3935 11.871 72.3935 11.3672C72.3935 10.9429 72.4714 10.5866 72.6271 10.2983C72.7829 10.0099 72.995 9.77794 73.2635 9.60227C73.532 9.42661 73.8369 9.29403 74.1783 9.20455C74.523 9.11506 74.8842 9.05208 75.2621 9.01562C75.7062 8.96922 76.0642 8.92614 76.3359 8.88636C76.6077 8.84328 76.8049 8.7803 76.9276 8.69744C77.0502 8.61458 77.1115 8.49195 77.1115 8.32955V8.29972C77.1115 7.98485 77.0121 7.74124 76.8132 7.56889C76.6177 7.39654 76.3393 7.31037 75.978 7.31037C75.5968 7.31037 75.2936 7.39489 75.0682 7.56392C74.8428 7.72964 74.6937 7.93845 74.6207 8.19034L72.6619 8.03125C72.7614 7.56723 72.9569 7.16619 73.2486 6.82812C73.5402 6.48674 73.9164 6.2249 74.3771 6.04261C74.8411 5.85701 75.3781 5.7642 75.9879 5.7642C76.4122 5.7642 76.8182 5.81392 77.206 5.91335C77.5971 6.01278 77.9434 6.1669 78.245 6.37571C78.55 6.58452 78.7902 6.85298 78.9659 7.18111C79.1416 7.50592 79.2294 7.89536 79.2294 8.34943V13.5H77.2209V12.4411H77.1612C77.0386 12.6797 76.8745 12.8902 76.669 13.0724C76.4635 13.2514 76.2166 13.3923 75.9283 13.495C75.6399 13.5945 75.3068 13.6442 74.929 13.6442ZM75.5355 12.1825C75.8471 12.1825 76.1222 12.1212 76.3608 11.9986C76.5994 11.8726 76.7867 11.7036 76.9226 11.4915C77.0585 11.2794 77.1264 11.0391 77.1264 10.7706V9.96023C77.0601 10.0033 76.969 10.0431 76.853 10.0795C76.7403 10.1127 76.6127 10.1442 76.4702 10.174C76.3277 10.2005 76.1851 10.2254 76.0426 10.2486C75.9001 10.2685 75.7708 10.2867 75.6548 10.3033C75.4063 10.3397 75.1892 10.3977 75.0036 10.4773C74.8179 10.5568 74.6738 10.6645 74.571 10.8004C74.4683 10.933 74.4169 11.0987 74.4169 11.2976C74.4169 11.5859 74.5213 11.8063 74.7301 11.9588C74.9422 12.108 75.2107 12.1825 75.5355 12.1825ZM75.3317 4.87926L73.8153 2.61719H75.7692L76.8281 4.87926H75.3317ZM80.8725 13.5V5.86364H82.9904V13.5H80.8725ZM81.9364 4.87926C81.6216 4.87926 81.3514 4.77486 81.1261 4.56605C80.904 4.35393 80.793 4.10038 80.793 3.8054C80.793 3.51373 80.904 3.26349 81.1261 3.05469C81.3514 2.84257 81.6216 2.73651 81.9364 2.73651C82.2513 2.73651 82.5198 2.84257 82.7418 3.05469C82.9672 3.26349 83.0799 3.51373 83.0799 3.8054C83.0799 4.10038 82.9672 4.35393 82.7418 4.56605C82.5198 4.77486 82.2513 4.87926 81.9364 4.87926ZM94.2809 8.04119L92.342 8.16051C92.3088 7.99479 92.2376 7.84564 92.1282 7.71307C92.0188 7.57718 91.8746 7.46946 91.6957 7.38991C91.52 7.30705 91.3095 7.26562 91.0643 7.26562C90.7362 7.26562 90.4594 7.33523 90.234 7.47443C90.0086 7.61032 89.896 7.79261 89.896 8.02131C89.896 8.2036 89.9689 8.35772 90.1147 8.48366C90.2605 8.60961 90.5108 8.7107 90.8654 8.78693L92.2475 9.06534C92.9899 9.2178 93.5434 9.46307 93.908 9.80114C94.2726 10.1392 94.4549 10.5833 94.4549 11.1335C94.4549 11.634 94.3074 12.0732 94.0124 12.451C93.7208 12.8288 93.3197 13.1238 92.8093 13.3359C92.3022 13.5447 91.7172 13.6491 91.0543 13.6491C90.0434 13.6491 89.238 13.4387 88.6381 13.0178C88.0415 12.5935 87.6919 12.0168 87.5891 11.2876L89.6722 11.1783C89.7352 11.4865 89.8877 11.7218 90.1296 11.8842C90.3716 12.0433 90.6815 12.1229 91.0593 12.1229C91.4305 12.1229 91.7288 12.0516 91.9542 11.9091C92.1829 11.7633 92.2989 11.576 92.3022 11.3473C92.2989 11.1551 92.2177 10.9976 92.0586 10.875C91.8995 10.7491 91.6542 10.6529 91.3228 10.5866L90.0004 10.3232C89.2546 10.174 88.6995 9.91548 88.3349 9.54759C87.9736 9.17969 87.793 8.7107 87.793 8.14062C87.793 7.65009 87.9255 7.22751 88.1907 6.87287C88.4592 6.51823 88.8353 6.24479 89.3192 6.05256C89.8065 5.86032 90.3765 5.7642 91.0295 5.7642C91.994 5.7642 92.753 5.96804 93.3065 6.37571C93.8633 6.78338 94.1881 7.33854 94.2809 8.04119ZM97.9661 13.6442C97.4789 13.6442 97.0447 13.5597 96.6635 13.3906C96.2824 13.2183 95.9808 12.9647 95.7587 12.63C95.54 12.2919 95.4306 11.871 95.4306 11.3672C95.4306 10.9429 95.5085 10.5866 95.6642 10.2983C95.82 10.0099 96.0321 9.77794 96.3006 9.60227C96.5691 9.42661 96.874 9.29403 97.2154 9.20455C97.5601 9.11506 97.9213 9.05208 98.2992 9.01562C98.7433 8.96922 99.1013 8.92614 99.373 8.88636C99.6448 8.84328 99.842 8.7803 99.9647 8.69744C100.087 8.61458 100.149 8.49195 100.149 8.32955V8.29972C100.149 7.98485 100.049 7.74124 99.8503 7.56889C99.6548 7.39654 99.3764 7.31037 99.0151 7.31037C98.6339 7.31037 98.3307 7.39489 98.1053 7.56392C97.8799 7.72964 97.7308 7.93845 97.6578 8.19034L95.699 8.03125C95.7985 7.56723 95.994 7.16619 96.2857 6.82812C96.5774 6.48674 96.9535 6.2249 97.4142 6.04261C97.8783 5.85701 98.4152 5.7642 99.025 5.7642C99.4493 5.7642 99.8553 5.81392 100.243 5.91335C100.634 6.01278 100.981 6.1669 101.282 6.37571C101.587 6.58452 101.827 6.85298 102.003 7.18111C102.179 7.50592 102.267 7.89536 102.267 8.34943V13.5H100.258V12.4411H100.198C100.076 12.6797 99.9116 12.8902 99.7061 13.0724C99.5007 13.2514 99.2537 13.3923 98.9654 13.495C98.677 13.5945 98.3439 13.6442 97.9661 13.6442ZM98.5726 12.1825C98.8842 12.1825 99.1593 12.1212 99.3979 11.9986C99.6365 11.8726 99.8238 11.7036 99.9597 11.4915C100.096 11.2794 100.164 11.0391 100.164 10.7706V9.96023C100.097 10.0033 100.006 10.0431 99.8901 10.0795C99.7774 10.1127 99.6498 10.1442 99.5073 10.174C99.3648 10.2005 99.2222 10.2254 99.0797 10.2486C98.9372 10.2685 98.8079 10.2867 98.6919 10.3033C98.4434 10.3397 98.2263 10.3977 98.0407 10.4773C97.8551 10.5568 97.7109 10.6645 97.6081 10.8004C97.5054 10.933 97.454 11.0987 97.454 11.2976C97.454 11.5859 97.5584 11.8063 97.7672 11.9588C97.9793 12.108 98.2478 12.1825 98.5726 12.1825ZM99.8851 4.76989H98.3141L98.2047 3.875C98.556 3.85843 98.8063 3.81203 98.9554 3.7358C99.1046 3.65625 99.1775 3.55185 99.1742 3.42258C99.1775 3.24361 99.083 3.12263 98.8908 3.05966C98.7019 2.99669 98.4649 2.9652 98.1799 2.9652L98.2346 2.00568C99.1195 2.00568 99.7923 2.125 100.253 2.36364C100.717 2.60227 100.947 2.93371 100.944 3.35795C100.947 3.65956 100.838 3.88826 100.616 4.04403C100.397 4.1965 100.154 4.29261 99.8851 4.33239V4.76989ZM106.028 9.08523V13.5H103.91V5.86364H105.928V7.21094H106.018C106.187 6.76681 106.47 6.41548 106.868 6.15696C107.265 5.89512 107.748 5.7642 108.314 5.7642C108.845 5.7642 109.307 5.88021 109.702 6.11222C110.096 6.34422 110.403 6.67566 110.621 7.10653C110.84 7.53409 110.949 8.04451 110.949 8.63778V13.5H108.831V9.01562C108.835 8.5483 108.715 8.18371 108.474 7.92188C108.232 7.65672 107.898 7.52415 107.474 7.52415C107.189 7.52415 106.937 7.58546 106.719 7.7081C106.503 7.83073 106.334 8.00971 106.211 8.24503C106.092 8.47704 106.031 8.7571 106.028 9.08523Z" fill="#379237"/>
                                            </svg>  
                                        </span>
    
                                        <span style="margin-top: 30px;" x-show="userObj">
                                            <span>
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M10 15L10 8M10 5.00996V4.99997M19 10C19 14.9706 14.9706 19 10 19C5.02944 19 1 14.9706 1 10C1 5.02944 5.02944 1 10 1C14.9706 1 19 5.02944 19 10Z" stroke="#FAAD14" stroke-width="1.5" stroke-linecap="round"/>
                                                </svg>
                                            </span>
                                            Đã cấp phát được 4/5 tài sản theo định mức (<span class="text-primary" style="cursor: pointer" @click="openModal('#normModal')">Xem định mức</span>)
                                        </span>
    
                                        <span class="mt-4 d-flex" style="justify-content: flex-end; gap: 5px;">
                                            <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal" @click="closeModal('#searchAssetModal')">Hủy</button>
                                            <button type="button" class="btn btn-success" @click="openModal('#confirmAllocateModal')">Cấp phát</button>
                                        </span>
                                    </div>
                                    <div id="recovery-tab" class="row" x-show="tabAllocation == 'recovery-tab'">
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <span>Ngày thu hồi</span>
                                                <input type="date" class="form-control" x-model="new Date().toISOString().slice(0, 10)" disabled>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <span>Nội dung thu hồi</span>
                                                <textarea id="noteAllocation" name="" class="form-control" x-model="description" style="width: 100%; height: 60px !important; min-height: 30px !important;"></textarea>
    
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" :checked="recoveryCompany" type="checkbox" value="" id="defaultCheck3" @click="recoveryCompany = true;">
                                                <label class="form-check-label" for="defaultCheck3">
                                                    Thu hồi về công ty
                                                </label>
                                            </div>
                                        </div>
                                        <span class="text-bold">Tài sản thu hồi</span>
                                        <div class="col-12 custom-scroll" style="overflow-x: auto;width: 100%; max-height: 250px;">
                                            <table class="table table-bordered table-repair">
                                                <thead class="sticky-top">
                                                <tr style="font-size: 14px;">
                                                    <th class="text-center">
                                                        <div class="form-check">
                                                            {{-- <input class="form-check-input" type="checkbox"> --}}
                                                        </div>
                                                    </th>
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
                                                <template x-for="(asset, index) in listAssetOfUser" :key="asset.id">
                                                    <tr>
                                                        <td class="text-center">
                                                            <div class="form-check">
                                                                <input :checked="listAssetRecovery.some(selected => selected.id === asset.id)" class="form-check-input" type="checkbox" @click="toggleSelectionRecovery(asset, $event.target.checked)">
                                                            </div>
                                                        </td>
                                                        <td x-text="asset.code"></td>
                                                        <td x-text="asset.name"></td>
                                                        <td x-text="asset.asset_type ? asset.asset_type.name : ''"></td>
                                                        <td x-text="asset.seri_number"></td>
                                                        <td x-text="LIST_MEASURE[asset.asset_type.measure]"></td>
                                                        <td x-text="asset.price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')"></td>
                                                        <td x-text="asset.location_text"></td>
                                                    </tr>
                                                </template>
                                                </tbody>
                                            </table>
                                        </div>
    
                                        <span class="mt-4 d-flex" style="justify-content: flex-end; gap: 5px;">
                                            <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal" @click="closeModal('#searchAssetModal')">Hủy</button>
                                            <button type="button" class="btn btn-success" @click="openModal('#confirmRecoveryModal')">Thu hồi</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="history-tab" x-show="tab == 'history-tab'">
                                <h6 class="text-bold">
                                    Lịch sử cấp phát/Thu hồi/Luân chuyển
                                </h6>
                                <div class="row">
                                    <div class="col-12 custom-scroll" style="overflow-x: auto; width: 100%;  padding: 0;">
                                        <table class="table table-bordered table-repair" style="width: 1000px;">
                                            <thead>
                                            <tr style="font-size: 14px;">
                                                <th scope="col" style="position: sticky; left: 0; z-index: 1;">Ngày</th>
                                                <th scope="col" style="position: sticky; left: 95px; z-index: 1;">Hành động</th>
                                                <th scope="col" style="position: sticky; left: 186px; z-index: 1;">Biên bản</th>
                                                <th scope="col">Người thực hiện</th>
                                                <th scope="col">Bàn giao cho</th>
                                                <th scope="col">Cá nhân/Đại diện</th>
                                                <th scope="col">Đơn vị</th>
                                                <th scope="col">Nội dung</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <template x-for="history in listHistory">
                                                <tr>
                                                    <td style="position: sticky; left: 0; z-index: 1;" x-text="formatDateVN(history.created_at)"></td>
                                                    <td style="position: sticky; left: 95px; z-index: 1;" x-text="history.type == 1 ? 'Cấp phát' : ( history.type == 2 ? 'Thu hồi' : 'Luân chuyển') "></td>
                                                    <td style="position: sticky; left: 186px; z-index: 1;">
                                                        <span class="text-primary" x-text="'BB0' + history.id" @click="window.open('/' + history.link_report, '_blank')" style="cursor: pointer;"></span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <img x-show="history.create_by" x-bind:src="history.create_by && history.create_by.avatar 
                                                                    ? (history.create_by.avatar.includes('/uploads/') 
                                                                        ? 'https://office.sconnect.com.vn' + history.create_by.avatar 
                                                                        : history.create_by.avatar) 
                                                                    : 'https://office.sconnect.com.vn/images/avatar-default.png'" 
                                                                    alt="" 
                                                                    style="width: 55px; height: 55px; object-fit: cover; border-radius: 100px;">
                                                            <div style="display: flex; flex-direction: column; align-items: flex-start; justify-content: center; margin-left: 10px;">                                   
                                                                <span x-text="history.create_by ? history.create_by.name : ''" style="font-weight: 600; font-size: 16px;"></span>
                                                                <span x-text="history.create_by ? 'Mã nhân sự:' + history.create_by.code : ''" style="color: #706f6f;"></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <template x-if="history.type !== 3">
                                                            <div>
                                                                <svg x-show="history.user_id" width="76" height="30" viewBox="0 0 76 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <rect x="1" y="0.5" width="74" height="29" rx="5.5" stroke="#379237"/>
                                                                    <path d="M19.9261 13H18.6932C18.6203 12.6454 18.4927 12.3338 18.3104 12.0653C18.1314 11.7969 17.9126 11.5715 17.6541 11.3892C17.3989 11.2036 17.1155 11.0644 16.804 10.9716C16.4924 10.8788 16.1676 10.8324 15.8295 10.8324C15.2131 10.8324 14.6546 10.9882 14.1541 11.2997C13.657 11.6113 13.2609 12.0703 12.9659 12.6768C12.6742 13.2834 12.5284 14.0275 12.5284 14.9091C12.5284 15.7907 12.6742 16.5348 12.9659 17.1413C13.2609 17.7479 13.657 18.2069 14.1541 18.5185C14.6546 18.83 15.2131 18.9858 15.8295 18.9858C16.1676 18.9858 16.4924 18.9394 16.804 18.8466C17.1155 18.7538 17.3989 18.6162 17.6541 18.4339C17.9126 18.2483 18.1314 18.0213 18.3104 17.7528C18.4927 17.4811 18.6203 17.1695 18.6932 16.8182H19.9261C19.8333 17.3385 19.6643 17.8042 19.419 18.2152C19.1738 18.6262 18.8688 18.9759 18.5043 19.2642C18.1397 19.5492 17.7304 19.7663 17.2763 19.9155C16.8255 20.0646 16.3433 20.1392 15.8295 20.1392C14.9612 20.1392 14.1889 19.9271 13.5128 19.5028C12.8366 19.0786 12.3047 18.4754 11.9169 17.6932C11.5291 16.911 11.3352 15.983 11.3352 14.9091C11.3352 13.8352 11.5291 12.9072 11.9169 12.125C12.3047 11.3428 12.8366 10.7396 13.5128 10.3153C14.1889 9.8911 14.9612 9.67898 15.8295 9.67898C16.3433 9.67898 16.8255 9.75355 17.2763 9.9027C17.7304 10.0518 18.1397 10.2706 18.5043 10.5589C18.8688 10.844 19.1738 11.192 19.419 11.603C19.6643 12.0107 19.8333 12.4763 19.9261 13ZM24.0066 20.179C23.5227 20.179 23.0835 20.0878 22.6891 19.9055C22.2947 19.7199 21.9815 19.4531 21.7495 19.1051C21.5175 18.7538 21.4015 18.3295 21.4015 17.8324C21.4015 17.3949 21.4876 17.0402 21.66 16.7685C21.8323 16.4934 22.0627 16.2779 22.351 16.1222C22.6394 15.9664 22.9576 15.8504 23.3056 15.7741C23.6569 15.6946 24.0099 15.6316 24.3645 15.5852C24.8285 15.5256 25.2047 15.4808 25.4931 15.451C25.7847 15.4179 25.9969 15.3632 26.1294 15.2869C26.2653 15.2107 26.3333 15.0781 26.3333 14.8892V14.8494C26.3333 14.3589 26.199 13.9777 25.9306 13.706C25.6654 13.4342 25.2627 13.2983 24.7225 13.2983C24.1623 13.2983 23.7232 13.4209 23.405 13.6662C23.0868 13.9115 22.8631 14.1733 22.7338 14.4517L21.6202 14.054C21.8191 13.59 22.0842 13.2287 22.4157 12.9702C22.7504 12.7083 23.115 12.526 23.5094 12.4233C23.9071 12.3172 24.2982 12.2642 24.6827 12.2642C24.928 12.2642 25.2097 12.294 25.5279 12.3537C25.8494 12.41 26.1593 12.5277 26.4576 12.7067C26.7592 12.8857 27.0094 13.1558 27.2083 13.517C27.4071 13.8783 27.5066 14.3622 27.5066 14.9688V20H26.3333V18.9659H26.2736C26.1941 19.1316 26.0615 19.3089 25.8759 19.4979C25.6903 19.6868 25.4434 19.8475 25.1351 19.9801C24.8269 20.1127 24.4507 20.179 24.0066 20.179ZM24.1855 19.125C24.6496 19.125 25.0407 19.0339 25.3588 18.8516C25.6803 18.6693 25.9223 18.4339 26.0847 18.1456C26.2504 17.8572 26.3333 17.554 26.3333 17.2358V16.1619C26.2836 16.2216 26.1742 16.2763 26.0051 16.326C25.8394 16.3724 25.6472 16.4138 25.4284 16.4503C25.213 16.4834 25.0025 16.5133 24.7971 16.5398C24.5949 16.563 24.4308 16.5829 24.3049 16.5994C23.9999 16.6392 23.7149 16.7038 23.4498 16.7933C23.1879 16.8795 22.9758 17.0104 22.8134 17.1861C22.6543 17.3584 22.5748 17.5937 22.5748 17.892C22.5748 18.2997 22.7256 18.608 23.0272 18.8168C23.3321 19.0223 23.7182 19.125 24.1855 19.125ZM24.2253 11.3295L25.4384 9.02273H26.8105L25.2594 11.3295H24.2253ZM34.7589 15.4062V20H33.5856V12.3636H34.7191V13.5568H34.8185C34.9975 13.169 35.2693 12.8575 35.6339 12.6222C35.9985 12.3835 36.4691 12.2642 37.0458 12.2642C37.5629 12.2642 38.0153 12.3703 38.4031 12.5824C38.7908 12.7912 39.0924 13.1094 39.3079 13.5369C39.5233 13.9612 39.631 14.4981 39.631 15.1477V20H38.4577V15.2273C38.4577 14.6274 38.302 14.16 37.9904 13.8253C37.6789 13.4872 37.2513 13.3182 36.7077 13.3182C36.3332 13.3182 35.9985 13.3994 35.7035 13.5618C35.4118 13.7242 35.1815 13.9612 35.0124 14.2727C34.8434 14.5843 34.7589 14.9621 34.7589 15.4062ZM42.9483 15.4062V20H41.775V9.81818H42.9483V13.5568H43.0478C43.2267 13.1624 43.4952 12.8492 43.8532 12.6172C44.2144 12.3819 44.695 12.2642 45.2949 12.2642C45.8153 12.2642 46.271 12.3686 46.6621 12.5774C47.0532 12.7829 47.3565 13.0994 47.5719 13.527C47.7907 13.9512 47.9 14.4915 47.9 15.1477V20H46.7267V15.2273C46.7267 14.6207 46.5693 14.1518 46.2544 13.8203C45.9429 13.4856 45.5104 13.3182 44.9569 13.3182C44.5724 13.3182 44.2277 13.3994 43.9228 13.5618C43.6212 13.7242 43.3825 13.9612 43.2069 14.2727C43.0345 14.5843 42.9483 14.9621 42.9483 15.4062ZM52.2937 20.179C51.8098 20.179 51.3706 20.0878 50.9762 19.9055C50.5818 19.7199 50.2686 19.4531 50.0366 19.1051C49.8046 18.7538 49.6886 18.3295 49.6886 17.8324C49.6886 17.3949 49.7747 17.0402 49.9471 16.7685C50.1194 16.4934 50.3498 16.2779 50.6381 16.1222C50.9265 15.9664 51.2447 15.8504 51.5927 15.7741C51.944 15.6946 52.297 15.6316 52.6516 15.5852C53.1156 15.5256 53.4918 15.4808 53.7802 15.451C54.0719 15.4179 54.284 15.3632 54.4165 15.2869C54.5524 15.2107 54.6204 15.0781 54.6204 14.8892V14.8494C54.6204 14.3589 54.4862 13.9777 54.2177 13.706C53.9525 13.4342 53.5498 13.2983 53.0096 13.2983C52.4495 13.2983 52.0103 13.4209 51.6921 13.6662C51.3739 13.9115 51.1502 14.1733 51.021 14.4517L49.9073 14.054C50.1062 13.59 50.3713 13.2287 50.7028 12.9702C51.0375 12.7083 51.4021 12.526 51.7965 12.4233C52.1942 12.3172 52.5853 12.2642 52.9698 12.2642C53.2151 12.2642 53.4968 12.294 53.815 12.3537C54.1365 12.41 54.4464 12.5277 54.7447 12.7067C55.0463 12.8857 55.2965 13.1558 55.4954 13.517C55.6942 13.8783 55.7937 14.3622 55.7937 14.9688V20H54.6204V18.9659H54.5607C54.4812 19.1316 54.3486 19.3089 54.163 19.4979C53.9774 19.6868 53.7305 19.8475 53.4222 19.9801C53.114 20.1127 52.7378 20.179 52.2937 20.179ZM52.4727 19.125C52.9367 19.125 53.3278 19.0339 53.646 18.8516C53.9674 18.6693 54.2094 18.4339 54.3718 18.1456C54.5375 17.8572 54.6204 17.554 54.6204 17.2358V16.1619C54.5707 16.2216 54.4613 16.2763 54.2923 16.326C54.1265 16.3724 53.9343 16.4138 53.7156 16.4503C53.5001 16.4834 53.2897 16.5133 53.0842 16.5398C52.882 16.563 52.7179 16.5829 52.592 16.5994C52.2871 16.6392 52.002 16.7038 51.7369 16.7933C51.475 16.8795 51.2629 17.0104 51.1005 17.1861C50.9414 17.3584 50.8619 17.5937 50.8619 17.892C50.8619 18.2997 51.0127 18.608 51.3143 18.8168C51.6192 19.0223 52.0053 19.125 52.4727 19.125ZM54.0835 11.4489L53.0494 10.0568L52.0153 11.4489H50.9016V11.3693L52.5124 9.30114H53.5863L55.1971 11.3693V11.4489H54.0835ZM59.1085 15.4062V20H57.9352V12.3636H59.0687V13.5568H59.1681C59.3471 13.169 59.6189 12.8575 59.9835 12.6222C60.3481 12.3835 60.8187 12.2642 61.3954 12.2642C61.9125 12.2642 62.3649 12.3703 62.7527 12.5824C63.1404 12.7912 63.4421 13.1094 63.6575 13.5369C63.8729 13.9612 63.9806 14.4981 63.9806 15.1477V20H62.8074V15.2273C62.8074 14.6274 62.6516 14.16 62.34 13.8253C62.0285 13.4872 61.6009 13.3182 61.0574 13.3182C60.6828 13.3182 60.3481 13.3994 60.0531 13.5618C59.7614 13.7242 59.5311 13.9612 59.362 14.2727C59.193 14.5843 59.1085 14.9621 59.1085 15.4062Z" fill="#379237"/>
                                                                </svg> 
                                                                <svg x-show="!history.user_id" width="62" height="30" viewBox="0 0 62 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <rect x="0.5" y="0.5" width="61" height="29" rx="5.5" stroke="#379237"/>
                                                                    <path d="M14.375 20H11.929V18.9062H14.2955C15.1373 18.9062 15.835 18.7438 16.3885 18.419C16.942 18.0942 17.3546 17.6319 17.6264 17.032C17.8982 16.4321 18.0341 15.7178 18.0341 14.8892C18.0341 14.0672 17.8999 13.3596 17.6314 12.7663C17.3629 12.1697 16.9619 11.7124 16.4283 11.3942C15.8946 11.0727 15.2301 10.9119 14.4347 10.9119H11.8693V9.81818H14.5142C15.5019 9.81818 16.3471 10.022 17.0497 10.4297C17.7524 10.834 18.291 11.4157 18.6655 12.1747C19.04 12.9304 19.2273 13.8352 19.2273 14.8892C19.2273 15.9498 19.0384 16.8629 18.6605 17.6286C18.2827 18.3909 17.7325 18.9775 17.0099 19.3885C16.2874 19.7962 15.4091 20 14.375 20ZM12.4659 9.81818V20H11.233V9.81818H12.4659ZM9.66193 15.2074V14.2926H14.0369V15.2074H9.66193ZM27.3409 11.7869H28.4347C28.4347 12.5426 28.2689 13.1425 27.9375 13.5866C27.6094 14.0308 27.0327 14.2528 26.2074 14.2528V13.4176C26.5388 13.4176 26.7841 13.3497 26.9432 13.2138C27.1056 13.0779 27.2116 12.8873 27.2614 12.642C27.3144 12.3968 27.3409 12.1117 27.3409 11.7869ZM24.2386 20.1591C23.5492 20.1591 22.9444 19.995 22.424 19.6669C21.907 19.3388 21.5026 18.8797 21.2109 18.2898C20.9226 17.6998 20.7784 17.0104 20.7784 16.2216C20.7784 15.4261 20.9226 14.7318 21.2109 14.1385C21.5026 13.5452 21.907 13.0845 22.424 12.7564C22.9444 12.4283 23.5492 12.2642 24.2386 12.2642C24.928 12.2642 25.5313 12.4283 26.0483 12.7564C26.5687 13.0845 26.973 13.5452 27.2614 14.1385C27.553 14.7318 27.6989 15.4261 27.6989 16.2216C27.6989 17.0104 27.553 17.6998 27.2614 18.2898C26.973 18.8797 26.5687 19.3388 26.0483 19.6669C25.5313 19.995 24.928 20.1591 24.2386 20.1591ZM24.2386 19.1051C24.7623 19.1051 25.1932 18.9709 25.5312 18.7024C25.8693 18.4339 26.1196 18.081 26.282 17.6435C26.4444 17.206 26.5256 16.732 26.5256 16.2216C26.5256 15.7112 26.4444 15.2356 26.282 14.7947C26.1196 14.3539 25.8693 13.9976 25.5312 13.7259C25.1932 13.4541 24.7623 13.3182 24.2386 13.3182C23.715 13.3182 23.2841 13.4541 22.946 13.7259C22.608 13.9976 22.3577 14.3539 22.1953 14.7947C22.0329 15.2356 21.9517 15.7112 21.9517 16.2216C21.9517 16.732 22.0329 17.206 22.1953 17.6435C22.3577 18.081 22.608 18.4339 22.946 18.7024C23.2841 18.9709 23.715 19.1051 24.2386 19.1051ZM30.6768 15.4062V20H29.5036V12.3636H30.6371V13.5568H30.7365C30.9155 13.169 31.1873 12.8575 31.5518 12.6222C31.9164 12.3835 32.3871 12.2642 32.9638 12.2642C33.4808 12.2642 33.9332 12.3703 34.321 12.5824C34.7088 12.7912 35.0104 13.1094 35.2259 13.5369C35.4413 13.9612 35.549 14.4981 35.549 15.1477V20H34.3757V15.2273C34.3757 14.6274 34.2199 14.16 33.9084 13.8253C33.5968 13.4872 33.1693 13.3182 32.6257 13.3182C32.2512 13.3182 31.9164 13.3994 31.6214 13.5618C31.3298 13.7242 31.0994 13.9612 30.9304 14.2727C30.7614 14.5843 30.6768 14.9621 30.6768 15.4062ZM47.8748 12.3636L45.051 20H43.8578L41.0339 12.3636H42.3066L44.4146 18.4489H44.4941L46.6021 12.3636H47.8748ZM49.4235 20V12.3636H50.5968V20H49.4235ZM50.0201 11.0909C49.7914 11.0909 49.5942 11.013 49.4284 10.8572C49.266 10.7015 49.1848 10.5142 49.1848 10.2955C49.1848 10.0767 49.266 9.88944 49.4284 9.73366C49.5942 9.57789 49.7914 9.5 50.0201 9.5C50.2488 9.5 50.4443 9.57789 50.6067 9.73366C50.7724 9.88944 50.8553 10.0767 50.8553 10.2955C50.8553 10.5142 50.7724 10.7015 50.6067 10.8572C50.4443 11.013 50.2488 11.0909 50.0201 11.0909ZM50.0201 22.5256C49.7914 22.5256 49.5942 22.4477 49.4284 22.2919C49.266 22.1361 49.1848 21.9489 49.1848 21.7301C49.1848 21.5114 49.266 21.3241 49.4284 21.1683C49.5942 21.0125 49.7914 20.9347 50.0201 20.9347C50.2488 20.9347 50.4443 21.0125 50.6067 21.1683C50.7724 21.3241 50.8553 21.5114 50.8553 21.7301C50.8553 21.9489 50.7724 22.1361 50.6067 22.2919C50.4443 22.4477 50.2488 22.5256 50.0201 22.5256Z" fill="#379237"/>
                                                                </svg>  
                                                            </div>
                                                    
                                                        </template>
                                                        <template x-if="history.type == 3">
                                                            <div>
                                                                <svg x-show="history.to_user_id" width="76" height="30" viewBox="0 0 76 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <rect x="1" y="0.5" width="74" height="29" rx="5.5" stroke="#379237"/>
                                                                    <path d="M19.9261 13H18.6932C18.6203 12.6454 18.4927 12.3338 18.3104 12.0653C18.1314 11.7969 17.9126 11.5715 17.6541 11.3892C17.3989 11.2036 17.1155 11.0644 16.804 10.9716C16.4924 10.8788 16.1676 10.8324 15.8295 10.8324C15.2131 10.8324 14.6546 10.9882 14.1541 11.2997C13.657 11.6113 13.2609 12.0703 12.9659 12.6768C12.6742 13.2834 12.5284 14.0275 12.5284 14.9091C12.5284 15.7907 12.6742 16.5348 12.9659 17.1413C13.2609 17.7479 13.657 18.2069 14.1541 18.5185C14.6546 18.83 15.2131 18.9858 15.8295 18.9858C16.1676 18.9858 16.4924 18.9394 16.804 18.8466C17.1155 18.7538 17.3989 18.6162 17.6541 18.4339C17.9126 18.2483 18.1314 18.0213 18.3104 17.7528C18.4927 17.4811 18.6203 17.1695 18.6932 16.8182H19.9261C19.8333 17.3385 19.6643 17.8042 19.419 18.2152C19.1738 18.6262 18.8688 18.9759 18.5043 19.2642C18.1397 19.5492 17.7304 19.7663 17.2763 19.9155C16.8255 20.0646 16.3433 20.1392 15.8295 20.1392C14.9612 20.1392 14.1889 19.9271 13.5128 19.5028C12.8366 19.0786 12.3047 18.4754 11.9169 17.6932C11.5291 16.911 11.3352 15.983 11.3352 14.9091C11.3352 13.8352 11.5291 12.9072 11.9169 12.125C12.3047 11.3428 12.8366 10.7396 13.5128 10.3153C14.1889 9.8911 14.9612 9.67898 15.8295 9.67898C16.3433 9.67898 16.8255 9.75355 17.2763 9.9027C17.7304 10.0518 18.1397 10.2706 18.5043 10.5589C18.8688 10.844 19.1738 11.192 19.419 11.603C19.6643 12.0107 19.8333 12.4763 19.9261 13ZM24.0066 20.179C23.5227 20.179 23.0835 20.0878 22.6891 19.9055C22.2947 19.7199 21.9815 19.4531 21.7495 19.1051C21.5175 18.7538 21.4015 18.3295 21.4015 17.8324C21.4015 17.3949 21.4876 17.0402 21.66 16.7685C21.8323 16.4934 22.0627 16.2779 22.351 16.1222C22.6394 15.9664 22.9576 15.8504 23.3056 15.7741C23.6569 15.6946 24.0099 15.6316 24.3645 15.5852C24.8285 15.5256 25.2047 15.4808 25.4931 15.451C25.7847 15.4179 25.9969 15.3632 26.1294 15.2869C26.2653 15.2107 26.3333 15.0781 26.3333 14.8892V14.8494C26.3333 14.3589 26.199 13.9777 25.9306 13.706C25.6654 13.4342 25.2627 13.2983 24.7225 13.2983C24.1623 13.2983 23.7232 13.4209 23.405 13.6662C23.0868 13.9115 22.8631 14.1733 22.7338 14.4517L21.6202 14.054C21.8191 13.59 22.0842 13.2287 22.4157 12.9702C22.7504 12.7083 23.115 12.526 23.5094 12.4233C23.9071 12.3172 24.2982 12.2642 24.6827 12.2642C24.928 12.2642 25.2097 12.294 25.5279 12.3537C25.8494 12.41 26.1593 12.5277 26.4576 12.7067C26.7592 12.8857 27.0094 13.1558 27.2083 13.517C27.4071 13.8783 27.5066 14.3622 27.5066 14.9688V20H26.3333V18.9659H26.2736C26.1941 19.1316 26.0615 19.3089 25.8759 19.4979C25.6903 19.6868 25.4434 19.8475 25.1351 19.9801C24.8269 20.1127 24.4507 20.179 24.0066 20.179ZM24.1855 19.125C24.6496 19.125 25.0407 19.0339 25.3588 18.8516C25.6803 18.6693 25.9223 18.4339 26.0847 18.1456C26.2504 17.8572 26.3333 17.554 26.3333 17.2358V16.1619C26.2836 16.2216 26.1742 16.2763 26.0051 16.326C25.8394 16.3724 25.6472 16.4138 25.4284 16.4503C25.213 16.4834 25.0025 16.5133 24.7971 16.5398C24.5949 16.563 24.4308 16.5829 24.3049 16.5994C23.9999 16.6392 23.7149 16.7038 23.4498 16.7933C23.1879 16.8795 22.9758 17.0104 22.8134 17.1861C22.6543 17.3584 22.5748 17.5937 22.5748 17.892C22.5748 18.2997 22.7256 18.608 23.0272 18.8168C23.3321 19.0223 23.7182 19.125 24.1855 19.125ZM24.2253 11.3295L25.4384 9.02273H26.8105L25.2594 11.3295H24.2253ZM34.7589 15.4062V20H33.5856V12.3636H34.7191V13.5568H34.8185C34.9975 13.169 35.2693 12.8575 35.6339 12.6222C35.9985 12.3835 36.4691 12.2642 37.0458 12.2642C37.5629 12.2642 38.0153 12.3703 38.4031 12.5824C38.7908 12.7912 39.0924 13.1094 39.3079 13.5369C39.5233 13.9612 39.631 14.4981 39.631 15.1477V20H38.4577V15.2273C38.4577 14.6274 38.302 14.16 37.9904 13.8253C37.6789 13.4872 37.2513 13.3182 36.7077 13.3182C36.3332 13.3182 35.9985 13.3994 35.7035 13.5618C35.4118 13.7242 35.1815 13.9612 35.0124 14.2727C34.8434 14.5843 34.7589 14.9621 34.7589 15.4062ZM42.9483 15.4062V20H41.775V9.81818H42.9483V13.5568H43.0478C43.2267 13.1624 43.4952 12.8492 43.8532 12.6172C44.2144 12.3819 44.695 12.2642 45.2949 12.2642C45.8153 12.2642 46.271 12.3686 46.6621 12.5774C47.0532 12.7829 47.3565 13.0994 47.5719 13.527C47.7907 13.9512 47.9 14.4915 47.9 15.1477V20H46.7267V15.2273C46.7267 14.6207 46.5693 14.1518 46.2544 13.8203C45.9429 13.4856 45.5104 13.3182 44.9569 13.3182C44.5724 13.3182 44.2277 13.3994 43.9228 13.5618C43.6212 13.7242 43.3825 13.9612 43.2069 14.2727C43.0345 14.5843 42.9483 14.9621 42.9483 15.4062ZM52.2937 20.179C51.8098 20.179 51.3706 20.0878 50.9762 19.9055C50.5818 19.7199 50.2686 19.4531 50.0366 19.1051C49.8046 18.7538 49.6886 18.3295 49.6886 17.8324C49.6886 17.3949 49.7747 17.0402 49.9471 16.7685C50.1194 16.4934 50.3498 16.2779 50.6381 16.1222C50.9265 15.9664 51.2447 15.8504 51.5927 15.7741C51.944 15.6946 52.297 15.6316 52.6516 15.5852C53.1156 15.5256 53.4918 15.4808 53.7802 15.451C54.0719 15.4179 54.284 15.3632 54.4165 15.2869C54.5524 15.2107 54.6204 15.0781 54.6204 14.8892V14.8494C54.6204 14.3589 54.4862 13.9777 54.2177 13.706C53.9525 13.4342 53.5498 13.2983 53.0096 13.2983C52.4495 13.2983 52.0103 13.4209 51.6921 13.6662C51.3739 13.9115 51.1502 14.1733 51.021 14.4517L49.9073 14.054C50.1062 13.59 50.3713 13.2287 50.7028 12.9702C51.0375 12.7083 51.4021 12.526 51.7965 12.4233C52.1942 12.3172 52.5853 12.2642 52.9698 12.2642C53.2151 12.2642 53.4968 12.294 53.815 12.3537C54.1365 12.41 54.4464 12.5277 54.7447 12.7067C55.0463 12.8857 55.2965 13.1558 55.4954 13.517C55.6942 13.8783 55.7937 14.3622 55.7937 14.9688V20H54.6204V18.9659H54.5607C54.4812 19.1316 54.3486 19.3089 54.163 19.4979C53.9774 19.6868 53.7305 19.8475 53.4222 19.9801C53.114 20.1127 52.7378 20.179 52.2937 20.179ZM52.4727 19.125C52.9367 19.125 53.3278 19.0339 53.646 18.8516C53.9674 18.6693 54.2094 18.4339 54.3718 18.1456C54.5375 17.8572 54.6204 17.554 54.6204 17.2358V16.1619C54.5707 16.2216 54.4613 16.2763 54.2923 16.326C54.1265 16.3724 53.9343 16.4138 53.7156 16.4503C53.5001 16.4834 53.2897 16.5133 53.0842 16.5398C52.882 16.563 52.7179 16.5829 52.592 16.5994C52.2871 16.6392 52.002 16.7038 51.7369 16.7933C51.475 16.8795 51.2629 17.0104 51.1005 17.1861C50.9414 17.3584 50.8619 17.5937 50.8619 17.892C50.8619 18.2997 51.0127 18.608 51.3143 18.8168C51.6192 19.0223 52.0053 19.125 52.4727 19.125ZM54.0835 11.4489L53.0494 10.0568L52.0153 11.4489H50.9016V11.3693L52.5124 9.30114H53.5863L55.1971 11.3693V11.4489H54.0835ZM59.1085 15.4062V20H57.9352V12.3636H59.0687V13.5568H59.1681C59.3471 13.169 59.6189 12.8575 59.9835 12.6222C60.3481 12.3835 60.8187 12.2642 61.3954 12.2642C61.9125 12.2642 62.3649 12.3703 62.7527 12.5824C63.1404 12.7912 63.4421 13.1094 63.6575 13.5369C63.8729 13.9612 63.9806 14.4981 63.9806 15.1477V20H62.8074V15.2273C62.8074 14.6274 62.6516 14.16 62.34 13.8253C62.0285 13.4872 61.6009 13.3182 61.0574 13.3182C60.6828 13.3182 60.3481 13.3994 60.0531 13.5618C59.7614 13.7242 59.5311 13.9612 59.362 14.2727C59.193 14.5843 59.1085 14.9621 59.1085 15.4062Z" fill="#379237"/>
                                                                </svg> 
                                                                <svg x-show="!history.to_user_id" width="62" height="30" viewBox="0 0 62 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <rect x="0.5" y="0.5" width="61" height="29" rx="5.5" stroke="#379237"/>
                                                                    <path d="M14.375 20H11.929V18.9062H14.2955C15.1373 18.9062 15.835 18.7438 16.3885 18.419C16.942 18.0942 17.3546 17.6319 17.6264 17.032C17.8982 16.4321 18.0341 15.7178 18.0341 14.8892C18.0341 14.0672 17.8999 13.3596 17.6314 12.7663C17.3629 12.1697 16.9619 11.7124 16.4283 11.3942C15.8946 11.0727 15.2301 10.9119 14.4347 10.9119H11.8693V9.81818H14.5142C15.5019 9.81818 16.3471 10.022 17.0497 10.4297C17.7524 10.834 18.291 11.4157 18.6655 12.1747C19.04 12.9304 19.2273 13.8352 19.2273 14.8892C19.2273 15.9498 19.0384 16.8629 18.6605 17.6286C18.2827 18.3909 17.7325 18.9775 17.0099 19.3885C16.2874 19.7962 15.4091 20 14.375 20ZM12.4659 9.81818V20H11.233V9.81818H12.4659ZM9.66193 15.2074V14.2926H14.0369V15.2074H9.66193ZM27.3409 11.7869H28.4347C28.4347 12.5426 28.2689 13.1425 27.9375 13.5866C27.6094 14.0308 27.0327 14.2528 26.2074 14.2528V13.4176C26.5388 13.4176 26.7841 13.3497 26.9432 13.2138C27.1056 13.0779 27.2116 12.8873 27.2614 12.642C27.3144 12.3968 27.3409 12.1117 27.3409 11.7869ZM24.2386 20.1591C23.5492 20.1591 22.9444 19.995 22.424 19.6669C21.907 19.3388 21.5026 18.8797 21.2109 18.2898C20.9226 17.6998 20.7784 17.0104 20.7784 16.2216C20.7784 15.4261 20.9226 14.7318 21.2109 14.1385C21.5026 13.5452 21.907 13.0845 22.424 12.7564C22.9444 12.4283 23.5492 12.2642 24.2386 12.2642C24.928 12.2642 25.5313 12.4283 26.0483 12.7564C26.5687 13.0845 26.973 13.5452 27.2614 14.1385C27.553 14.7318 27.6989 15.4261 27.6989 16.2216C27.6989 17.0104 27.553 17.6998 27.2614 18.2898C26.973 18.8797 26.5687 19.3388 26.0483 19.6669C25.5313 19.995 24.928 20.1591 24.2386 20.1591ZM24.2386 19.1051C24.7623 19.1051 25.1932 18.9709 25.5312 18.7024C25.8693 18.4339 26.1196 18.081 26.282 17.6435C26.4444 17.206 26.5256 16.732 26.5256 16.2216C26.5256 15.7112 26.4444 15.2356 26.282 14.7947C26.1196 14.3539 25.8693 13.9976 25.5312 13.7259C25.1932 13.4541 24.7623 13.3182 24.2386 13.3182C23.715 13.3182 23.2841 13.4541 22.946 13.7259C22.608 13.9976 22.3577 14.3539 22.1953 14.7947C22.0329 15.2356 21.9517 15.7112 21.9517 16.2216C21.9517 16.732 22.0329 17.206 22.1953 17.6435C22.3577 18.081 22.608 18.4339 22.946 18.7024C23.2841 18.9709 23.715 19.1051 24.2386 19.1051ZM30.6768 15.4062V20H29.5036V12.3636H30.6371V13.5568H30.7365C30.9155 13.169 31.1873 12.8575 31.5518 12.6222C31.9164 12.3835 32.3871 12.2642 32.9638 12.2642C33.4808 12.2642 33.9332 12.3703 34.321 12.5824C34.7088 12.7912 35.0104 13.1094 35.2259 13.5369C35.4413 13.9612 35.549 14.4981 35.549 15.1477V20H34.3757V15.2273C34.3757 14.6274 34.2199 14.16 33.9084 13.8253C33.5968 13.4872 33.1693 13.3182 32.6257 13.3182C32.2512 13.3182 31.9164 13.3994 31.6214 13.5618C31.3298 13.7242 31.0994 13.9612 30.9304 14.2727C30.7614 14.5843 30.6768 14.9621 30.6768 15.4062ZM47.8748 12.3636L45.051 20H43.8578L41.0339 12.3636H42.3066L44.4146 18.4489H44.4941L46.6021 12.3636H47.8748ZM49.4235 20V12.3636H50.5968V20H49.4235ZM50.0201 11.0909C49.7914 11.0909 49.5942 11.013 49.4284 10.8572C49.266 10.7015 49.1848 10.5142 49.1848 10.2955C49.1848 10.0767 49.266 9.88944 49.4284 9.73366C49.5942 9.57789 49.7914 9.5 50.0201 9.5C50.2488 9.5 50.4443 9.57789 50.6067 9.73366C50.7724 9.88944 50.8553 10.0767 50.8553 10.2955C50.8553 10.5142 50.7724 10.7015 50.6067 10.8572C50.4443 11.013 50.2488 11.0909 50.0201 11.0909ZM50.0201 22.5256C49.7914 22.5256 49.5942 22.4477 49.4284 22.2919C49.266 22.1361 49.1848 21.9489 49.1848 21.7301C49.1848 21.5114 49.266 21.3241 49.4284 21.1683C49.5942 21.0125 49.7914 20.9347 50.0201 20.9347C50.2488 20.9347 50.4443 21.0125 50.6067 21.1683C50.7724 21.3241 50.8553 21.5114 50.8553 21.7301C50.8553 21.9489 50.7724 22.1361 50.6067 22.2919C50.4443 22.4477 50.2488 22.5256 50.0201 22.5256Z" fill="#379237"/>
                                                                </svg>  
                                                            </div>
                                                        </template>
                                                    </td>
                                                    <td>
                                                        <template x-if="history.type != 3">
                                                            <div class="d-flex" x-show="history.type != 3">
                                                                <img x-show="history.user_id" x-bind:src="history.user && history.user.avatar 
                                                                        ? (history.user.avatar.includes('/uploads/') 
                                                                            ? 'https://office.sconnect.com.vn' + history.user.avatar 
                                                                            : history.user.avatar) 
                                                                        : 'https://office.sconnect.com.vn/images/avatar-default.png'" 
                                                                        alt="" 
                                                                        style="width: 55px; height: 55px; object-fit: cover; border-radius: 100px;">
                                                                <div style="display: flex; flex-direction: column; align-items: flex-start; justify-content: center; margin-left: 10px;">                                   
                                                                    <span x-text="history.user ? history.user.name : ''" style="font-weight: 600; font-size: 16px;"></span>
                                                                    <span x-text="history.user ? 'Mã nhân sự:' + history.user.code : ''" style="color: #706f6f;"></span>
                                                                </div>
                                                            </div>
                                                        </template>

                                                        <template x-if="history.type == 3">
                                                            <div class="d-flex">
                                                                <img x-show="history.to_user_id" x-bind:src="history.user_to && history.user_to.avatar 
                                                                        ? (history.user_to.avatar.includes('/uploads/') 
                                                                            ? 'https://office.sconnect.com.vn' + history.user_to.avatar 
                                                                            : history.user_to.avatar) 
                                                                        : 'https://office.sconnect.com.vn/images/avatar-default.png'" 
                                                                        alt="" 
                                                                        style="width: 55px; height: 55px; object-fit: cover; border-radius: 100px;">
                                                                <div style="display: flex; flex-direction: column; align-items: flex-start; justify-content: center; margin-left: 10px;">                                   
                                                                    <span x-text="history.user_to ? history.user_to.name : ''" style="font-weight: 600; font-size: 16px;"></span>
                                                                    <span x-text="history.user_to ? 'Mã nhân sự:' + history.user_to.code : ''" style="color: #706f6f;"></span>
                                                                </div>
                                                            </div>
                                                        </template>
                                                        
                                                    </td>
                                                    <template x-if="history.type != 3">
                                                        <td x-text="history.organization.dept_type.cfg_key + ' ' + history.organization.name"></td>
                                                    </template>
                                                    <template x-if="history.type == 3">
                                                        <td x-text="history.organization_to.dept_type.cfg_key + ' ' + history.organization_to.name"></td>
                                                    </template>
                                                    <td x-text="history.description"></td>
                                                </tr>
                                            </template>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="asset-tab" x-show="tab == 'asset-tab'">
                                <h6 class="text-bold">
                                    Tài sản đang đại diện
                                </h6>
                                <div class="row">
                                    <div class="col-12 custom-scroll" style="overflow-x: auto;width: 100%;">
                                        <table class="table table-bordered table-repair" style="width: 1000px;">
                                            <thead>
                                            <tr style="font-size: 14px;">
                                                <th>Mã tài sản</th>
                                                <th>Tên tài sản</th>
                                                <th>Loại tài sản</th>
                                                <th>Số seri</th>
                                                <th>Đơn vị tính</th>
                                                <th>Trạng thái</th>
                                                <th>Vị trí tài sản</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <template x-for="asset in listAssetRepresent">
                                                    <tr>
                                                        <td x-text="asset.code"></td>
                                                        <td x-text="asset.name"></td>
                                                        <td x-text="asset.asset_type ? asset.asset_type.name : ''"></td>
                                                        <td x-text="asset.seri_number"></td>
                                                        <td x-text="LIST_MEASURE[asset.asset_type.measure]"></td>
                                                        <td x-html="arrSvgStatus[asset.status]"></td>
                                                        <td x-text="asset.location ? LIST_LOCATION_ASSET[asset.location] : '-'"></td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-2" id="normModal" tabindex="-1" aria-labelledby="normModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Định mức cấp phát</h5>
              <button type="button" class="close" data-bs-dismiss="modal" @click="closeModal('#normModal')">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal" @click="closeModal('#normModal')">Đóng</button>
              {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade modal-2" id="confirmAllocateModal" tabindex="-1" aria-labelledby="confirmAllocateModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle" style="color: #111; font-size: 20px;">Xác nhận cấp phát</h5>
              <button type="button" class="close" data-bs-dismiss="modal" @click="closeModal('#confirmAllocateModal')">
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
                Xác nhận cấp phát tài sản
              </span>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal" @click="closeModal('#confirmAllocateModal')">Hủy</button>
              <button type="button" class="btn btn-success" data-bs-dismiss="modal" @click="allocateAsset(); closeModal('#confirmAllocateModal');">Xác nhận</button>
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade modal-2" id="successAllocateModal" tabindex="-1" aria-labelledby="successAllocateModal" aria-hidden="true">
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
                    Biên bản cấp phát
                </span>
                <span class="text-primary" x-text="'BB0' + linkReport.id" @click="window.open('/' + linkReport.link_report, '_blank')" style="cursor: pointer;">
                    
                </span>
            </div>
            <div class="modal-footer" style="background: #fff; border: none;">
              <button type="button" class="btn btn-success" data-bs-dismiss="modal" @click="closeModal('#successAllocateModal'); closeModal('#modalDetail');">Đóng</button>
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade modal-2" id="confirmRecoveryModal" tabindex="-1" aria-labelledby="confirmRecoveryModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle" style="color: #111; font-size: 20px;">Xác nhận thu hồi</h5>
              <button type="button" class="close" data-bs-dismiss="modal" @click="closeModal('#confirmRecoveryModal')">
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
                Xác nhận thu hồi tài sản
              </span>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal" @click="closeModal('#confirmRecoveryModal')">Hủy</button>
              <button type="button" class="btn btn-success" data-bs-dismiss="modal" @click="recoveryAsset(); closeModal('#confirmRecoveryModal');">Xác nhận</button>
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade modal-2" id="successRecoveryModal" tabindex="-1" aria-labelledby="successRecoveryModal" aria-hidden="true">
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
                        Thu hồi thành công
                      </span>
                </div>
                <span>
                    Biên bản thu hồi
                </span>
                <span class="text-primary" x-text="'BB0' + linkReport.id" @click="window.open('/' + linkReport.link_report, '_blank')" style="cursor: pointer;">
                    
                </span>
            </div>
            <div class="modal-footer" style="background: #fff; border: none;">
              <button type="button" class="btn btn-success" data-bs-dismiss="modal" @click="closeModal('#successRecoveryModal'); closeModal('#modalDetail');">Đóng</button>
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade modal-2" id="searchAssetModal" tabindex="-1" aria-labelledby="searchAssetModal" aria-hidden="true" x-init="getDataAsset()">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h6 class="modal-title" id="exampleModalLongTitle" style="color: #111; font-size: 19px;">Tìm kiếm tài sản</h6>
              <button type="button" class="close" data-bs-dismiss="modal" @click="closeModal('#searchAssetModal')">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="col-12 mb-3 row">
                    <div class="col-4 d-flex position-relative">
                        <input type="text" class="form-control" id="nameCodeAssetPopup" placeholder="Tên/mã tài sản" @change="getDataAsset($('#unitSearchPopup').val(), $('#nameCodeAssetPopup').val())">
                        <i class="fa-solid fa-magnifying-glass position-absolute mr-3 tw-right-0 tw-w-3" style="height: -webkit-fill-available;"></i>
                    </div>
                    <div class="col-4">
                        <select class="form-control select2" data-placeholder="Đơn vị" id="unitSearchPopup" @change="getDataAsset($('#unitSearchPopup').val(), $('#nameCodeAssetPopup').val())">
                            <option value="0" selected>Chọn loại tài sản</option>
                            <template x-for="(assetType, key) in assetType">
                                <option :value="assetType.id" x-text="assetType.name"></option>
                            </template>
                        </select>
                    </div>
                </div>
                <div class="col-12 custom-scroll" style="overflow-x: auto; width: 100%; max-height: 500px;">
                    <table class="table table-bordered table-repair">
                        <thead>
                        <tr class="sticky-top" style="font-size: 14px;">
                            <th>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" @change="toggleAllSelection($event.target.checked)">
                            </div>
                            </th>
                            <th>Mã tài sản</th>
                            <th>Tên tài sản</th>
                            <th>Loại tài sản</th>
                            <th>Số Seri</th>
                            <th>Đơn vị tính</th>
                            <th>Giá</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-for="(asset, index) in listAssetSelect" :key="asset.id">
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" :checked="listAssetAllocate.some(selected => selected.id === asset.id)" value="" @change="toggleSelection(asset, $event.target.checked)">
                                    </div>
                                </td>
                                <td x-text="asset.code"></td>
                                <td x-text="asset.name"></td>
                                <td x-text="asset.asset_type ? asset.asset_type.name : ''"></td>
                                <td x-text="asset.seri_number"></td>
                                <td x-text="LIST_MEASURE[asset.asset_type.measure]"></td>
                                <td x-text="asset.price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')"></td>                                       
                            </tr>
                        </template>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal" @click="closeModal('#searchAssetModal')">Hủy</button>
              <button type="button" class="btn btn-success" data-bs-dismiss="modal" @click="closeModal('#searchAssetModal')">Chọn</button>
            </div>
          </div>
        </div>
    </div>
</div>