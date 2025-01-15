<div>
    <template x-for="(organization_name, key) in {{$organization}}" :key="key">
        <div x-text="organization_name" :class="key === 0 : 'tw-font-bold'"></div>
    </template>
</div>
