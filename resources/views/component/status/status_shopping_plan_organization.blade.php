<span
    x-text="STATUS_SHOPPING_PLAN_ORGANIZATION[{{$status}}]"
    x-data="{
        getStyle(status) {
            if (status === STATUS_SHOPPING_PLAN_ORGANIZATION_OPEN_REGISTER) {
                return {
                    color: '#A229CC',
                    backgroundColor: '#FAE5FD',
                    border: '1px solid #A229CC'
                };
            } else if (status === STATUS_SHOPPING_PLAN_ORGANIZATION_REGISTERED
            || status === STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_ACCOUNTANT_APPROVAL)
            {
                return {
                    color: '#52C41A',
                    backgroundColor: '#F6FFED',
                    border: '1px solid #52C41A'
                };
            } else if ([
                STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNTANT_REVIEWED,
                STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_MANAGER_APPROVAL,
                STATUS_SHOPPING_PLAN_ORGANIZATION_APPROVAL].includes(status)
            ) {
                return {
                    color: '#FAAD14',
                    backgroundColor: '#FFFBE6',
                    border: '1px solid #FAAD14'
                };
            } else if ([
                STATUS_SHOPPING_PLAN_ORGANIZATION_CANCEL,
                STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNT_CANCEL
                ].includes(status)) {
                return {
                    color: '#F5222D',
                    backgroundColor: '#FFF1F0',
                    border: '1px solid #F5222D'
                };
            } else if ([
                STATUS_SHOPPING_PLAN_ORGANIZATION_HR_HANDLE,
                STATUS_SHOPPING_PLAN_ORGANIZATION_HR_SYNTHETIC,
                STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_HR_MANAGER
            ].includes(status)) {
                return {
                    color: '#A229CC',
                    backgroundColor: '#FAE5FD',
                    border: '1px solid #A229CC'
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
    class="@if(isset($tooltip)) d-block tw-w-fit text-xs @endif"
    @if(isset($tooltip))
        data-bs-toggle="tooltip" data-bs-placement="bottom" :title="{{$tooltip}}"
    @endif
>
</span>
