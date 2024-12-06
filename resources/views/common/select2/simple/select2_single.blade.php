<select class="form-control"
    x-init="$nextTick(() => {
        // Khởi tạo Select2
        const selectInstance = $($el).select2({
                language: {
                    noResults: () => 'Không tìm thấy kết quả'
                }
            });

        selectInstance.val([model]).trigger('change');

        // Lắng nghe sự kiện thay đổi từ Select2
        let isChanging = false; // Đánh dấu trạng thái thay đổi
        selectInstance.on('change', () => {
            if (!isChanging) {
                isChanging = true;
                const newValue = $($el).val();
                $dispatch('select-change', newValue); // Cập nhật filters.status
                setTimeout(() => (isChanging = false), 0); // Đặt lại trạng thái
            }
        });

        // Lắng nghe thay đổi từ Alpine (model)
        $watch('model', (newValue) => {
            if (!isChanging) {
                isChanging = true;
                $($el).val(newValue).trigger('change'); // Cập nhật lại giá trị Select2
                setTimeout(() => (isChanging = false), 0); // Đặt lại trạng thái
            }
        });
    })"
    @if(isset($disabled)) :disabled="{{$disabled}}" @endif
    @if(isset($id)) id="{{$id}}" @endif
>
    <option value="">{{$placeholder ?? 'Chọn giá trị'}}</option>
    <template x-for="(value, key) in {{$values ?? 'values'}}" :key="key">
        <option :value="key" x-text="value"></option>
    </template>
</select>
