<span
    x-text="STATUS_SHOPPING_PLAN_COMPANY[{{$status}}]"
    x-data="{
        getStyle(status) {
            if (status === STATUS_SHOPPING_PLAN_COMPANY_NEW) {
                return {
                    color: '#1890FF',
                    backgroundColor: '#E6F7FF',
                    border: '1px solid #1890FF'
                };
            } else if (status === STATUS_SHOPPING_PLAN_COMPANY_REGISTER) {
                return {
                    color: '#A229CC',
                    backgroundColor: '#FAE5FD',
                    border: '1px solid #A229CC'
                };
            } else if (status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL || status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_APPROVAL) {
                return {
                    color: '#FAAD14',
                    backgroundColor: '#FFFBE6',
                    border: '1px solid #FAAD14'
                };
            } else if (status === STATUS_SHOPPING_PLAN_COMPANY_APPROVAL || status === STATUS_SHOPPING_PLAN_COMPANY_COMPLETE) {
                return {
                    color: '#B7EB8F',
                    backgroundColor: '#F6FFED',
                    border: '1px solid #B7EB8F'
                };
            } else if (status === STATUS_SHOPPING_PLAN_COMPANY_CANCEL) {
                return {
                    color: '#F5222D',
                    backgroundColor: '#FFF1F0',
                    border: '1px solid #F5222D'
                };
            } else if (
                status === STATUS_SHOPPING_PLAN_COMPANY_HR_HANDLE
                || status === STATUS_SHOPPING_PLAN_COMPANY_HR_SYNTHETIC
                || status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_HR
            ) {
                return {
                    color: '#FFE58F',
                    backgroundColor: '#FFFBE6',
                    border: '1px solid #FFE58F'
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
>
</span>
