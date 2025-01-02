@extends('layouts.app',[
    'title' => 'Cấp phát - thu hồi'
])

@section('content')
<style> 
    .dropdown-content {
      position: absolute;
      background-color: #f9f9f9;
      min-width: 160px;
      box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
      z-index: 1000;
      right: 40px;
      border-radius: 10px;
    }
    
    .dropdown-content a {
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
    }
    
    .dropdown-content a:hover {background-color: #f1f1f1}
    
    .dropdown:hover .dropdown-content {
      display: block;
    }
    .dropbtn:hover .dropdown-content {
      display: block;
    }

    tr td {
        vertical-align: middle !important;
    }

    .title-menu {
        margin-left: 10px;
        display: flex;
        flex-direction: column;
        flex-wrap: nowrap;
        justify-content: center;
    }

    .item-menu{
        padding: 7px 10px !important;
    }

    ul.sidebar-tab li {
        list-style-type: none;
        padding: 7px 0px 7px 5px;
        font-weight: 600;
        cursor: pointer;
        border-left: 3px solid #fff;
    }

    ul.sidebar-tab li.active-sidebar {
        list-style-type: none;
        border-left: 3px solid #379237;
        padding: 7px 0px 7px 5px;
        background: #E4F0E6;
        color: #379237;
        font-weight: 600;
    }

    .table-repair tr th {
        background-color: #E7E9ED !important;
    }

    #noteAllocation {
        min-height: 50px !important;
    }

    @media (min-width: 1200px) {
        .modal-xl {
            --bs-modal-width: 1250px !important;
        }
    }

    .modal-2{
        --bs-modal-zindex: 1100 !important;
    }
    
    .custom-backdrop {
    z-index: 1060 !important;
    }

    .btn-outline-success{
        --bs-btn-color: #379237;
        --bs-btn-border-color: #379237;
        --bs-btn-hover-color: #fff;
        --bs-btn-hover-bg: #379237;
        --bs-btn-hover-border-color: #379237;
        --bs-btn-focus-shadow-rgb: 25, 135, 84;
        --bs-btn-active-color: #fff;
        --bs-btn-active-bg: #379237;
        --bs-btn-active-border-color: #379237;
        --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
        --bs-btn-disabled-color: #379237;
        --bs-btn-disabled-bg: transparent;
        --bs-btn-disabled-border-color: #379237;
        --bs-gradient: none;
    }

    .btn-success{
        --bs-btn-color: #fff;
        --bs-btn-bg: #379237;
        --bs-btn-border-color: #379237;
        --bs-btn-hover-color: #fff;
        --bs-btn-hover-bg: #379237;
        --bs-btn-hover-border-color: #379237;
        --bs-btn-focus-shadow-rgb: 60, 153, 110;
        --bs-btn-active-color: #fff;
        --bs-btn-active-bg: #379237;
        --bs-btn-active-border-color: #379237;
        --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
        --bs-btn-disabled-color: #fff;
        --bs-btn-disabled-bg: #379237;
        --bs-btn-disabled-border-color: #379237;
    }
    </style>
<div x-data="listAsset">
    <div class="row" >
        <div class="col-xxl-12 col-sm-12">
            <div style="background: #fff; padding: 30px; border-radius: 15px;">
                <div class="d-flex align-items-end mt-3 mb-3">
                    <div class="col-2 d-flex position-relative">
                        <input type="text" class="form-control" id="nameUser" placeholder="Tên/mã nhân viên">
                        <i class="fa-solid fa-magnifying-glass position-absolute mr-3 tw-right-0 tw-w-3" style="height: -webkit-fill-available;"></i>
                    </div>

                    <div class="col-2">
                        <select class="form-control select2" data-placeholder="Đơn vị" id="unitSearch">
                            <option value="0" selected>Chọn đơn vị</option>
                            <template x-for="(unit, key) in listUnit">
                                <option :value="unit.id" x-text="unit.dept_type.cfg_key + ' ' + unit.name"></option>
                            </template>
                        </select>
                    </div>

                    {{-- <div class="col-2">
                        <select class="form-control select2" data-placeholder="Chọn trạng thái" id="statusSearch" x-model="status">
                            <option value="0" selected>Trạng thái</option>
                            <template x-for="(value, key) in listStatus">
                                <option :value="value.num" x-text="value.text"></option>
                            </template>
                        </select>
                    </div> --}}
                
                    <div class="col-2">
                        <span @click="fetchData($('#unitSearch').val(), $('#nameUser').val())" style="cursor: pointer;">
                            <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g filter="url(#filter0_d_13695_32688)">
                                <rect x="2" width="34" height="34" rx="6" fill="#379237"/>
                                <path d="M27 24L23.2223 20.2156L27 24ZM25.3158 15.1579C25.3158 17.0563 24.5617 18.8769 23.2193 20.2193C21.8769 21.5617 20.0563 22.3158 18.1579 22.3158C16.2595 22.3158 14.4389 21.5617 13.0965 20.2193C11.7541 18.8769 11 17.0563 11 15.1579C11 13.2595 11.7541 11.4389 13.0965 10.0965C14.4389 8.75413 16.2595 8 18.1579 8C20.0563 8 21.8769 8.75413 23.2193 10.0965C24.5617 11.4389 25.3158 13.2595 25.3158 15.1579V15.1579Z" stroke="white" stroke-width="2" stroke-linecap="round"/>
                                </g>
                                <defs>
                                <filter id="filter0_d_13695_32688" x="0" y="0" width="38" height="38" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                                <feOffset dy="2"/>
                                <feGaussianBlur stdDeviation="1"/>
                                <feComposite in2="hardAlpha" operator="out"/>
                                <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"/>
                                <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_13695_32688"/>
                                <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_13695_32688" result="shape"/>
                                </filter>
                                </defs>
                            </svg>
                        </span>
                    </div>
                </div>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th class="text-center">STT</th>
                        <th class="text-center">Mã nhân viên</th>
                        <th class="text-left">Tên nhân viên</th>
                        <th class="text-left">Vị trí công việc</th>
                        <th class="text-left">Đơn vị</th>
                        <th class="text-center">TS sử dụng</th>
                        <th class="text-center">TS đại diện</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template x-for="(user, index) in listUserAsset" :key="user.id">
                        <tr>
                            <td class="text-center" x-text="index"></td>
                            <td class="text-center" x-text="user.code"></td>
                            <td class="text-center">
                                <div class="d-flex">
                                    <img x-show="user" x-bind:src="user.avatar 
                                            ? (user.avatar.includes('/uploads/') 
                                                ? 'https://office.sconnect.com.vn' + user.avatar 
                                                : user.avatar) 
                                            : 'https://office.sconnect.com.vn/images/avatar-default.png'" 
                                            alt="" 
                                            style="width: 55px; height: 55px; object-fit: cover; border-radius: 100px;">
                                    <div style="display: flex; flex-direction: column; align-items: flex-start; justify-content: center; margin-left: 10px;">                                   
                                        <span x-text="user.name" style="font-weight: 600; font-size: 16px;"></span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-left" x-text="user.job_position"></td>
                            <td class="text-left" x-text="user.org_last_parent ? user.org_last_parent.org_name : user.organization.dept_type.cfg_key + ' ' + user.organization.name"></td>
                            <td class="text-center" x-text="user.list_asset_use.length">
                                
                            </td>
                            <td class="text-center">0</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center" style="gap: 8px;">
                                    <span style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalDetail" @click="fillData(user); tab = 'allocation-tab';">
                                        <svg width="21" height="23" viewBox="0 0 21 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M14.9562 14.9862C15.6044 14.6379 16.3464 14.4397 17.1366 14.4397H17.1392C17.2196 14.4397 17.2571 14.3433 17.1982 14.2897C16.3764 13.5523 15.4377 12.9566 14.4205 12.5272C14.4098 12.5219 14.3991 12.5192 14.3883 12.5138C16.0517 11.3058 17.1339 9.34241 17.1339 7.12723C17.1339 3.45759 14.166 0.484375 10.5044 0.484375C6.84281 0.484375 3.87763 3.45759 3.87763 7.12723C3.87763 9.34241 4.95978 11.3058 6.62585 12.5138C6.61513 12.5192 6.60442 12.5219 6.59371 12.5272C5.39638 13.0335 4.32228 13.7594 3.39817 14.6862C2.4794 15.6033 1.74794 16.6904 1.2446 17.8871C0.749365 19.0588 0.482093 20.3143 0.457098 21.5862C0.456383 21.6147 0.461398 21.6432 0.471846 21.6698C0.482294 21.6964 0.497965 21.7207 0.517935 21.7412C0.537905 21.7616 0.56177 21.7779 0.588124 21.789C0.614477 21.8001 0.642787 21.8058 0.671384 21.8058H2.27585C2.39103 21.8058 2.48746 21.7121 2.49013 21.5969C2.54371 19.529 3.37138 17.5924 4.83656 16.1246C6.34996 14.6058 8.36424 13.7701 10.5071 13.7701C12.0258 13.7701 13.483 14.1906 14.7392 14.9781C14.7715 14.9984 14.8086 15.0098 14.8466 15.0112C14.8847 15.0126 14.9225 15.004 14.9562 14.9862V14.9862ZM10.5071 11.7344C9.28031 11.7344 8.12585 11.2549 7.25531 10.3844C6.827 9.95717 6.48744 9.44943 6.25621 8.89043C6.02497 8.33142 5.90663 7.73218 5.90799 7.12723C5.90799 5.89777 6.38746 4.74062 7.25531 3.87009C8.12317 2.99955 9.27763 2.52009 10.5071 2.52009C11.7366 2.52009 12.8883 2.99955 13.7589 3.87009C14.1872 4.2973 14.5268 4.80503 14.758 5.36404C14.9892 5.92305 15.1076 6.52229 15.1062 7.12723C15.1062 8.3567 14.6267 9.51384 13.7589 10.3844C12.8883 11.2549 11.7339 11.7344 10.5071 11.7344ZM20.3589 18.1094H18.1089V15.8594C18.1089 15.7415 18.0125 15.6451 17.8946 15.6451H16.3946C16.2767 15.6451 16.1803 15.7415 16.1803 15.8594V18.1094H13.9303C13.8125 18.1094 13.716 18.2058 13.716 18.3237V19.8237C13.716 19.9415 13.8125 20.0379 13.9303 20.0379H16.1803V22.2879C16.1803 22.4058 16.2767 22.5022 16.3946 22.5022H17.8946C18.0125 22.5022 18.1089 22.4058 18.1089 22.2879V20.0379H20.3589C20.4767 20.0379 20.5732 19.9415 20.5732 19.8237V18.3237C20.5732 18.2058 20.4767 18.1094 20.3589 18.1094Z" fill="#344054"/>
                                        </svg>
                                    </span>
                                    <span style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalDetail" @click="fillData(user); tab = 'allocation-tab';">
                                        <svg width="21" height="23" viewBox="0 0 21 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M14.9523 15.3377C15.6005 14.9895 16.3425 14.7913 17.1327 14.7913H17.1353C17.2157 14.7913 17.2532 14.6949 17.1943 14.6413C16.3725 13.9038 15.4338 13.3082 14.4166 12.8788C14.4059 12.8734 14.3952 12.8708 14.3844 12.8654C16.0478 11.6574 17.13 9.69397 17.13 7.4788C17.13 3.80915 14.1621 0.835938 10.5005 0.835938C6.83891 0.835938 3.87373 3.80915 3.87373 7.4788C3.87373 9.69397 4.95587 11.6574 6.62194 12.8654C6.61123 12.8708 6.60051 12.8734 6.5898 12.8788C5.39248 13.385 4.31837 14.1109 3.39426 15.0377C2.47549 15.9548 1.74403 17.042 1.24069 18.2386C0.745459 19.4104 0.478187 20.6658 0.453192 21.9377C0.452477 21.9663 0.457491 21.9948 0.46794 22.0214C0.478388 22.048 0.494059 22.0723 0.514029 22.0927C0.533999 22.1132 0.557864 22.1295 0.584217 22.1406C0.610571 22.1517 0.638881 22.1574 0.667478 22.1574H2.27194C2.38712 22.1574 2.48355 22.0636 2.48623 21.9484C2.5398 19.8806 3.36748 17.944 4.83266 16.4761C6.34605 14.9574 8.36034 14.1217 10.5032 14.1217C12.0219 14.1217 13.4791 14.5422 14.7353 15.3297C14.7676 15.35 14.8047 15.3614 14.8427 15.3628C14.8808 15.3642 14.9186 15.3556 14.9523 15.3377V15.3377ZM10.5032 12.0859C9.27641 12.0859 8.12194 11.6065 7.25141 10.7359C6.82309 10.3087 6.48353 9.801 6.2523 9.24199C6.02106 8.68298 5.90272 8.08374 5.90409 7.4788C5.90409 6.24933 6.38355 5.09219 7.25141 4.22165C8.11926 3.35112 9.27373 2.87165 10.5032 2.87165C11.7327 2.87165 12.8844 3.35112 13.755 4.22165C14.1833 4.64886 14.5229 5.15659 14.7541 5.7156C14.9853 6.27461 15.1037 6.87385 15.1023 7.4788C15.1023 8.70826 14.6228 9.8654 13.755 10.7359C12.8844 11.6065 11.73 12.0859 10.5032 12.0859ZM20.355 18.4609H13.9264C13.8086 18.4609 13.7121 18.5574 13.7121 18.6752V20.1752C13.7121 20.2931 13.8086 20.3895 13.9264 20.3895H20.355C20.4728 20.3895 20.5693 20.2931 20.5693 20.1752V18.6752C20.5693 18.5574 20.4728 18.4609 20.355 18.4609Z" fill="#344054"/>
                                        </svg> 
                                    </span>
                                </div>
                            </td>
                        </tr>
                    </template>
                    </tbody>
              </table>
              @include('common.pagination')
            @include('assets.asset.common.modal-asset')
                    
            </div>
        </div>
    </div>
</div>
<style>
    table tr td{
        background-color: #fff !important;
        font-size: 14px !important;
    }
</style>
@endsection

@include('assets.asset.common.commonSvg')

@section('js')
<script>
    function openModal(modalId) {
        const modal = new bootstrap.Modal(document.querySelector(modalId));
        modal.show();

        const backdrops = document.querySelectorAll('.modal-backdrop');
        if (backdrops.length > 1) {
            backdrops[1].classList.add('custom-backdrop');
        }
    }

    function closeModal(modalId) {
        const modal = new bootstrap.Modal(document.querySelector(modalId));
        modal.hide();
    }

    function formatCurrency(value) {
        return value.toLocaleString('vi-VN') + 'đ';
    }

    // function fetchDataComponent() {
    //     return {
    //         listUserAsset: [],
    //         listUnit: [],
    //         listAssetType: [],
    //         tab: 'general-tab',
    //         userObj: {},
    //         listAsset: [],
    //         listAssetAllocate: [],
    //         listAssetOfUser: [],

    //         async fetchData(unit = '', nameUser = '') {
    //             try {
    //                 let urlSearch = '/api/asset/get-data-list-user-asset?';

    //                 if (unit) {
    //                     urlSearch += 'unit=' + unit + '&';
    //                 }

    //                 if (nameUser) {
    //                     urlSearch += 'nameUser=' + nameUser + '&';
    //                 }

    //                 const response = await axios.get(urlSearch);
    //                 const data = response.data;
    //                 this.listUserAsset = data.data.listUserAsset.data;
    //                 this.listUnit = data.data.listUnit;
    //                 this.listAssetType = data.data.listAssetType;

    //                 console.log(this.listUserAsset);
                                      
    //             } catch (error) {
    //                 console.error('Lỗi khi gọi API:', error);
    //             }
    //         },

    //         async getDataAsset(type = '', nameCodeAsset = '') {
    //             try {
    //                 let urlSearch = '/api/asset/get-data-list-asset?status=2&';

    //                 if (type) {
    //                     urlSearch += 'type=' + type + '&';
    //                 }

    //                 if (nameCodeAsset) {
    //                     urlSearch += 'nameCodeAsset=' + nameCodeAsset + '&';
    //                 }

    //                 urlSearch += 'userId=' + this.userObj.id + '&';

    //                 const response = await axios.get(urlSearch);
    //                 const data = response.data;
    //                 this.listAsset = data.data.listAsset.data;
    //             } catch (error) {
    //                 console.error('Lỗi khi gọi API:', error);
    //             }
    //         },
    //         async getDataAssetOfUser() {
    //             try {
    //                 let urlSearch = '/api/asset/get-list-asset-of-user?userId=' + this.userObj.id;

    //                 const response = await axios.get(urlSearch);
    //                 const data = response.data;
    //                 this.listAssetOfUser = data.data.listAssetOfUser;                   
    //             } catch (error) {
    //                 console.error('Lỗi khi gọi API:', error);
    //             }
    //         },
    //         fillData(user) {
    //             this.userObj = user;
    //             this.getDataAssetOfUser();
    //         },
    //         toggleSelection(asset, isChecked) {
    //             if (isChecked) {
    //                 if (!this.listAssetAllocate.some(selected => selected.id === asset.id)) {
    //                     this.listAssetAllocate.push(asset);
    //                 }
    //             } else {
    //                 this.listAssetAllocate = this.listAssetAllocate.filter(assetAllocate => assetAllocate.id !== asset.id);
    //             }
    //         },
    //         deleteSelection(assetId){
    //             this.listAssetAllocate = this.listAssetAllocate.filter(assetAllocate => assetAllocate.id !== assetId);
    //         },
    //         async allocateAsset(){
    //             try {
    //                 let urlSearch = '/api/asset/allocate-asset';

    //                 const response = await axios.post(urlSearch, {
    //                     listAssetAllocate: this.listAssetAllocate,
    //                     user: this.userObj,
    //                 }, {
    //                     headers: {
    //                         'X-CSRF-TOKEN': '{{ csrf_token() }}'
    //                     }
    //                 });

    //                 const data = response.data;
    //                 this.listAssetOfUser = data.data.listAssetOfUser;
    //                 this.listAssetAllocate = [];

    //                 openModal('#successAllocateModal');
    //             } catch (error) {
    //                 console.error('Lỗi khi gọi API:', error);
    //             }
    //         }
    //     };
    // }

    document.addEventListener('alpine:init', () => {
        Alpine.data('listAsset', () => ({
            init() {
                window.initSelect2Modal('searchAssetModal');

                this.fetchData();

                window.addEventListener('change-page', (event) => {
                    this.pageParam = event.detail.page;
                    this.fetchData();
                });

                window.addEventListener('change-limit', (event) => {
                    this.pageParam = 1;
                    this.limitParam = event.target.value;
                    this.fetchData();
                });
            },
            listUserAsset: [],
            listUnit: [],
            listAssetType: [],
            tab: 'general-tab',
            userObj: {},
            listAsset: [],
            listAssetAllocate: [],
            listAssetOfUser: [],

            totalPages: null,
            currentPage: 1,
            pageParam: 1,
            limitParam: 10,

            async fetchData(unit = '', nameUser = '') {
                try {
                    let urlSearch = '/api/asset/get-data-list-user-asset?';

                    if (this.limitParam) {
                        urlSearch += 'limit=' + this.limitParam + '&';
                    }

                    if (this.pageParam) {
                        urlSearch += 'page=' + this.pageParam + '&';
                    }

                    if (unit) {
                        urlSearch += 'unit=' + unit + '&';
                    }

                    if (nameUser) {
                        urlSearch += 'nameUser=' + nameUser + '&';
                    }

                    const response = await axios.get(urlSearch);
                    const data = response.data;
                    this.listUserAsset = data.data.listUserAsset.data;
                    this.listUnit = data.data.listUnit;
                    this.listAssetType = data.data.listAssetType;

                    this.totalPages = data.data.listUserAsset.last_page;
                    this.currentPage = data.data.listUserAsset.current_page;

                    console.log(this.listUserAsset);
                                      
                } catch (error) {
                    console.error('Lỗi khi gọi API:', error);
                }
            },

            async getDataAsset(type = '', nameCodeAsset = '') {
                try {
                    let urlSearch = '/api/asset/get-data-list-asset?status=2&';

                    if (type) {
                        urlSearch += 'type=' + type + '&';
                    }

                    if (nameCodeAsset) {
                        urlSearch += 'nameCodeAsset=' + nameCodeAsset + '&';
                    }

                    urlSearch += 'userId=' + this.userObj.id + '&';

                    const response = await axios.get(urlSearch);
                    const data = response.data;
                    this.listAsset = data.data.listAsset.data;
                } catch (error) {
                    console.error('Lỗi khi gọi API:', error);
                }
            },
            async getDataAssetOfUser() {
                try {
                    let urlSearch = '/api/asset/get-list-asset-of-user?userId=' + this.userObj.id;

                    const response = await axios.get(urlSearch);
                    const data = response.data;
                    this.listAssetOfUser = data.data.listAssetOfUser;                   
                } catch (error) {
                    console.error('Lỗi khi gọi API:', error);
                }
            },
            fillData(user) {
                this.userObj = user;
                this.getDataAssetOfUser();
            },
            toggleSelection(asset, isChecked) {
                if (isChecked) {
                    if (!this.listAssetAllocate.some(selected => selected.id === asset.id)) {
                        this.listAssetAllocate.push(asset);
                    }
                } else {
                    this.listAssetAllocate = this.listAssetAllocate.filter(assetAllocate => assetAllocate.id !== asset.id);
                }
            },
            deleteSelection(assetId){
                this.listAssetAllocate = this.listAssetAllocate.filter(assetAllocate => assetAllocate.id !== assetId);
            },
            async allocateAsset(){
                try {
                    let urlSearch = '/api/asset/allocate-asset';

                    const response = await axios.post(urlSearch, {
                        listAssetAllocate: this.listAssetAllocate,
                        user: this.userObj,
                    }, {
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    const data = response.data;
                    this.listAssetOfUser = data.data.listAssetOfUser;
                    this.listAssetAllocate = [];

                    openModal('#successAllocateModal');
                } catch (error) {
                    console.error('Lỗi khi gọi API:', error);
                }
            }
        }));
    });
</script>
@endsection