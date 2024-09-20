<select class="form-control select2" multiple="multiple" :data-placeholder="placeholder" :id="id">
    <template x-for="item in data" :key="item.id">
        <option :value="item.id" x-text="item.name"></option>
    </template>
</select>
