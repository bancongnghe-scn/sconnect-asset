<select class="form-select"
        x-init="$nextTick(() => {
               $($el).select2({
                   language: {
                       noResults: function() {
                             return 'Không tìm thấy kết quả';
                       }
                   }
               });
               $($el).val([model]).change()
               $($el).on('change', (e) => {
                   $dispatch('select-change', $($el).val());
               });
        })"
>
    <option value="" x-text="text"></option>
    <template x-for="value in values" :key="value.id">
        <option :value="value.id" x-text="value.name"></option>
    </template>
</select>
