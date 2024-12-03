<select class="form-select"
        x-init="$nextTick(() => {
               $($el).select2({
                   language: {
                       noResults: function() {
                             return 'Không tìm thấy kết quả';
                       }
                   }
               });

               $($el).val(model).change()

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
        multiple="multiple"
        data-placeholder="{{$placeholder ?? 'Chọn ...'}}"
        @if(isset($disabled)) :disabled="{{$disabled}}" @endif
>
    <template x-for="value in {{$values ?? 'values'}}" :key="value.id">
        <option :value="value.id" x-text="value.name"></option>
    </template>
</select>
