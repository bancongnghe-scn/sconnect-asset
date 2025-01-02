<!-- Modal -->
<div>
    <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetail" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-3" style="padding-right: 60px;">
                            <ul class="sidebar-tab" style="padding: 0;">
                                <li @click="tab='general-tab'" :class="tab == 'general-tab' ? 'active-sidebar' : ''">Thông tin chung</li>
                                <li @click="tab='allocation-tab'" :class="tab == 'allocation-tab' ? 'active-sidebar' : ''">Cấp phát/Thu hồi</li>
                                <li @click="tab='asset-tab'" :class="tab == 'asset-tab' ? 'active-sidebar' : ''">Tài sản đang đại diện</li>
                                <li @click="tab='history-tab'" :class="tab == 'history-tab' ? 'active-sidebar' : ''">Lịch sử</li>
                            </ul>
                            <span>Mã QR</span>
                            <img src="https://media.istockphoto.com/id/1095468748/vi/vec-to/m%C3%A3-qr-m%E1%BA%ABu-m%C3%A3-v%E1%BA%A1ch-hi%E1%BB%87n-%C4%91%E1%BA%A1i-vector-tr%E1%BB%ABu-t%C6%B0%E1%BB%A3ng-%C4%91%E1%BB%83-qu%C3%A9t-%C4%91i%E1%BB%87n-tho%E1%BA%A1i-th%C3%B4ng-minh-b%E1%BB%8B-c%C3%B4-l%E1%BA%ADp-tr%C3%AAn.jpg?s=612x612&w=0&k=20&c=nCjpoa8qW4lREJGqVCQZsWcrKGOcKKuy5RSsSVzqlL8=" alt="" style="width: 100%;">
                        </div>
                        <div class="col-9">
                            <div class="name-asset d-flex" style="gap: 10px;">
                                <h5 class="text-bold"></h5>
                            </div>
                            <div class="general-tab" x-show="tab == 'general-tab'">
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
                                            <input type="text" class="form-control" value="0" disabled>

                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <span>Số điện thoại</span>
                                            <input type="text" class="form-control" x-model="userObj.email" disabled>

                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <span>Tài sản đại diện</span>
                                            <input type="number" class="form-control" value="0" disabled>

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
                                                    <input class="form-check-input" checked type="radio" value="" name="changeTab" id="defaultCheck1">
                                                    <label class="form-check-label" for="defaultCheck1">
                                                        Cấp phát
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" value="" name="changeTab" id="defaultCheck2">
                                                    <label class="form-check-label" for="defaultCheck2">
                                                        Cấp phát
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <span>Ngày cấp phát</span>
                                            <input type="date" class="form-control" x-model="new Date().toISOString().slice(0, 10)" disabled>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <span>Nội dung cấp phát</span>
                                            <textarea id="noteAllocation" name="" class="form-control" style="width: 100%; height: 60px !important;"></textarea>

                                        </div>
                                    </div>
                                    <span class="text-bold">Tài sản được cấp phát</span>
                                    <div class="col-12" style="overflow-x: auto;width: 100%;">
                                        <table class="table table-bordered table-repair">
                                            <thead>
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
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
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
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
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

                                    <span style="margin-top: 30px;">
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
                            </div>
                            <div class="history-tab" x-show="tab == 'history-tab'">
                                <h6 class="text-bold">
                                    Lịch sử cấp phát/Thu hồi/Luân chuyển
                                </h6>
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">First</th>
                                                <th scope="col">Last</th>
                                                <th scope="col">Handle</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <th scope="row">1</th>
                                                <td>Mark</td>
                                                <td>Otto</td>
                                                <td>@mdo</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">2</th>
                                                <td>Jacob</td>
                                                <td>Thornton</td>
                                                <td>@fat</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">3</th>
                                                <td colspan="2">Larry the Bird</td>
                                                <td>@twitter</td>
                                            </tr>
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
                                    <div class="col-12" style="overflow-x: auto;width: 100%;">
                                        <table class="table table-bordered table-repair" style="width: 1000px;">
                                            <thead>
                                            <tr style="font-size: 14px;">
                                                <th>Ngày báo hỏng</th>
                                                <th>Tình trạng hỏng</th>
                                                <th>Ngày sửa chữa</th>
                                                <th>Chi phí sửa</th>
                                                <th>Tình trạng sửa chữa</th>
                                                <th>Ngày hoàn thành</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Mark</td>
                                                <td>Otto</td>
                                                <td>@mdo</td>
                                                <td>Otto</td>
                                                <td>@mdo</td>
                                            </tr>
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
                <span class="text-primary">
                    Bienbancapphat
                </span>
            </div>
            <div class="modal-footer" style="background: #fff; border: none;">
              <button type="button" class="btn btn-success" data-bs-dismiss="modal" @click="closeModal('#successAllocateModal')">Đóng</button>
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
                        <input type="text" class="form-control" id="nameCodeAsset" placeholder="Tên/mã tài sản" @change="getDataAsset($('#unitSearch').val(), $('#nameCodeAsset').val())">
                        <i class="fa-solid fa-magnifying-glass position-absolute mr-3 tw-right-0 tw-w-3" style="height: -webkit-fill-available;"></i>
                    </div>
                    <div class="col-4">
                        <select class="form-control select2" data-placeholder="Đơn vị" id="unitSearch" @change="getDataAsset($('#unitSearch').val(), $('#nameCodeAsset').val())">
                            <option value="0" selected>Chọn loại tài sản</option>
                            <template x-for="(assetType, key) in listAssetType">
                                <option :value="assetType.id" x-text="assetType.name"></option>
                            </template>
                        </select>
                    </div>
                </div>
                <div class="col-12" style="overflow-x: auto;width: 100%;">
                    <table class="table table-bordered table-repair">
                        <thead>
                        <tr style="font-size: 14px;">
                            <th></th>
                            <th>Mã tài sản</th>
                            <th>Tên tài sản</th>
                            <th>Loại tài sản</th>
                            <th>Số Seri</th>
                            <th>Đơn vị tính</th>
                            <th>Giá</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-for="(asset, index) in listAsset" :key="asset.id">
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" @change="toggleSelection(asset, $event.target.checked)">
                                    </div>
                                </td>
                                <td x-text="asset.code"></td>
                                <td x-text="asset.name"></td>
                                <td x-text="asset.asset_type ? asset.asset_type.name : ''"></td>
                                <td></td>
                                <td></td>
                                <td></td>                                        
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