<span
    x-text="LIST_STATUS_PLAN_INVENTORY_ASSET[{{$status}}]"
    x-data="{
        getStyle(status) {
            if (status === STATUS_ASSET_NOT_INVENTORIED) {
                return {
                    color: '#667085',
                    backgroundColor: '#6670851A',
                    border: '1px solid #667085'
                };
            } else if (status === STATUS_ASSET_INVENTORIED) {
                return {
                    color: '#52C41A',
                    backgroundColor: '#F6FFED',
                    border: '1px solid #52C41A'
                };
            }
            return {};
        }
    }"
    style="
        font-size: 12px;
        padding: 3px 12px 3px 12px;
        border-radius: 8px;
        text-wrap: nowrap;
    "
    :style="getStyle(+{{$status}})"
    class="@if(isset($tooltip)) d-block tw-w-fit text-xs @endif"
    @if(isset($tooltip))
        data-bs-toggle="tooltip" data-bs-placement="bottom" :title="{{$tooltip}}"
    @endif
>
</span>
