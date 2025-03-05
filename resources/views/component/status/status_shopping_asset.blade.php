<span
    x-text="LIST_STATUS_SHOPPING_ASSET[{{$status}}]"
    x-data="{
        getStyle(status) {
            if ([
                SHOPPING_ASSET_STATUS_HR_MANAGER_DISAPPROVAL,
                SHOPPING_ASSET_STATUS_ACCOUNTANT_DISAPPROVAL,
                SHOPPING_ASSET_STATUS_GENERAL_DISAPPROVAL
            ].includes(status)) {
                return {
                    color: '#F5222D',
                    backgroundColor: '#FFF1F0',
                    border: '1px solid #F5222D'
                };
            } else if ([
                SHOPPING_ASSET_STATUS_HR_MANAGER_APPROVAL,
                SHOPPING_ASSET_STATUS_ACCOUNTANT_APPROVAL,
                SHOPPING_ASSET_STATUS_GENERAL_APPROVAL
            ].includes(status)) {
                return {
                    color: '#52C41A',
                    backgroundColor: '#F6FFED',
                    border: '1px solid #52C41A'
                };
            } else if ([SHOPPING_ASSET_STATUS_PENDING_HR_MANAGER_APPROVAL,
                SHOPPING_ASSET_STATUS_PENDING_ACCOUNTANT_APPROVAL,
                SHOPPING_ASSET_STATUS_PENDING_GENERAL_APPROVAL].includes(status)) {
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
    "
    :style="getStyle(+{{$status}})"
    @if(isset($tooltip))
        data-bs-toggle="tooltip" data-bs-placement="bottom" :title="{{$tooltip}}"
    @endif
>
</span>
