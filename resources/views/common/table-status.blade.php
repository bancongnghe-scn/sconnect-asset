<div class="d-flex justify-content-center">
    <span x-text="data[key]" class="p-1 border rounded"
        :class="{
             'tw-text-slate-400 tw-bg-slate-50': data[key] === 'Chờ duyệt',
             'tw-text-green-400 tw-bg-green-200': data[key] === 'Đã duyệt',
             'tw-text-red-600 tw-bg-red-300'  : data[key] === 'Hủy'}"
    ></span>
</div>
