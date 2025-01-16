<div class="d-flex">
    <img x-bind:src="data[key] && data[key].avatar ?
            (data[key].avatar.includes('/uploads/') ? 'https://office.sconnect.com.vn' + data[key].avatar : data[key].avatar)
            : 'https://office.sconnect.com.vn/images/avatar-default.png'"
         style="width: 55px; height: 55px; object-fit: cover; border-radius: 100px;"
     >
    <div class="d-flex flex-column align-items-start justify-content-center" style="margin-left: 10px">
        <span x-text="data[key] ? data[key].name : ''" class="font-weight-bold text-sm"></span>
        <span x-text="data[key] ? 'Mã nhân sự:' + data[key].code : ''" style="color: #706f6f;"></span>
    </div>
</div>
