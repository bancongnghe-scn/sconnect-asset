<select
    x-init="$nextTick(() => {
        // Khởi tạo Select2
        $($el).select2({
            language: {
                noResults: function () {
                    return 'Không tìm thấy kết quả';
                }
            }
        });

        // Đặt giá trị ban đầu cho Select2
        $($el).val(model).trigger('change');

        // Lắng nghe sự kiện thay đổi từ Select2
        let isChanging = false; // Đánh dấu trạng thái thay đổi
        $($el).on('change', () => {
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
    class="form-control"
    multiple="multiple"
    data-placeholder="{{$placeholder ?? 'Chọn giá trị'}}"
    x-bind:disabled="disabled"
>
    <template x-for="(value, key) in values">
        <option :value="key" x-text="value"></option>
    </template>
</select>