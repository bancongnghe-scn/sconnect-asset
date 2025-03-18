<span
    x-text="LIST_STATUS_ASEET[{{$status}}]"
    x-data="{
        getStyle(status) {
            if (status === ASSET_STATUS_ACTIVE) {
                return {
                    color: '#52C41A',
                    backgroundColor: '#F6FFED',
                    border: '1px solid #52C41A'
                };
            } else if ([ASSET_STATUS_PENDING, ASSET_STATUS_NEW].includes(status))
            {
                return {
                    color: '#1890FF',
                    backgroundColor: '#E6F7FF',
                    border: '1px solid #1890FF'
                };
            } else if ([ASSET_STATUS_LOST, ASSET_STATUS_CANCEL, ASSET_STATUS_DAMAGED].includes(status))
            {
                return {
                    color: '#F5222D',
                    backgroundColor: '#FFF1F0',
                    border: '1px solid #F5222D'
                };
            } else if ([ASSET_STATUS_PROPOSAL_LIQUIDATION, ASSET_STATUS_IN_LIQUIDATION, ASSET_STATUS_LIQUIDATED].includes(status))
            {
                return {
                    color: '#A229CC',
                    backgroundColor: '#FAE5FD',
                    border: '1px solid #A229CC'
                };
            } else if ([ASSET_STATUS_REPAIR, ASSET_STATUS_MAINTAIN].includes(status))
            {
                return {
                    color: '#FAAD14',
                    backgroundColor: '#FFFBE6',
                    border: '1px solid #FAAD14'
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
