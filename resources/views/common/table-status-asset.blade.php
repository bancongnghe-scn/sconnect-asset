<div class="d-flex justify-content-center">
    <span x-text="data[key]" class="pl-2 pr-2 border-none rounded"
        :class="{
            'tw-text-slate-400 tw-bg-slate-50':     data[key] === 'Tạm dừng',
            'tw-text-red-600 tw-bg-red-300':        data[key] === 'Đã hủy',
            'tw-text-yellow-500 tw-bg-yellow-100':  data[key] === 'Đề nghị thanh lý',
            'tw-text-yellow-500 tw-bg-yellow-200':  data[key] === 'Đang thanh lý',
            'tw-text-yellow-500 tw-bg-yellow-300':  data[key] === 'Đã thanh lý',
            'tw-text-yellow-500 tw-bg-yellow-50':  data[key] === 'Chờ duyệt',
            'tw-text-green-400 tw-bg-green-200':    data[key] === 'Đã duyệt',
            'tw-text-red-500 tw-bg-red-100':    data[key] === 'Từ chối',
            'tw-text-blue-400 tw-bg-blue-100':    data[key] === 'Mới tạo',
        }"
        :style="
            data[key] === 'Đã mất' ? 'color: #ea5455 !important; background-color: #fce5e6 !important;' : ''
        "
    ></span>
</div>
