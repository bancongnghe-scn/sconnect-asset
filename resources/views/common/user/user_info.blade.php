<template x-if="{{$user}}">
    <div class="d-flex justify-content-center">
        <img x-bind:src="{{$user}} && {{$user}}.avatar ?
            ({{$user}}.avatar.includes('/uploads/') ? 'https://office.sconnect.com.vn' + {{$user}}.avatar : {{$user}}.avatar)
            : 'https://office.sconnect.com.vn/images/avatar-default.png'"
             style="width: 55px; height: 55px; object-fit: cover; border-radius: 100px;"
        >
        <div class="d-flex flex-column align-items-start justify-content-center text-nowrap" style="margin-left: 10px">
            <span x-text="{{$user}} ? {{$user}}.name : ''" class="font-weight-bold text-sm"></span>
            <span x-text="{{$user}} ? 'Mã nhân sự:' + {{$user}}.code : ''" style="color: #706f6f;"></span>
        </div>
    </div>
</template>
