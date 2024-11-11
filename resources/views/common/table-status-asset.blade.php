<div class="d-flex justify-content-center">
    <span x-text="data[key]" class="pl-2 pr-2 border rounded"
        :class="{
            'tw-text-green-400 tw-bg-green-200':    data[key] === 'Hoạt động',
            'tw-text-slate-400 tw-bg-slate-50':     data[key] === 'Tạm dừng',
            'tw-text-yellow-600 tw-bg-yellow-300':  data[key] === 'Sửa chữa',
            'tw-text-red-600 tw-bg-red-300':        data[key] === 'Đã hủy',
        }"
        :style="
            data[key] === 'Đã mất' ? 'color: #ea5455 !important; background-color: #fce5e6 !important;' : ''
        "
    ></span>
</div>
