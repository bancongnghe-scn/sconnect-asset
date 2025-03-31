<span
    x-text="STATUS_SHOPPING_ARISE[{{$status}}]"
    x-data="{
        getStyle(status) {
            if (status === STATUS_SHOPPING_ARISE_NEW) {
                return {
                    color: '#1890FF',
                    backgroundColor: '#E6F7FF',
                    border: '1px solid #1890FF'
                };
            } else if (status === STATUS_SHOPPING_ARISE_PENDING_PROCESSING) {
                return {
                    color: '#A229CC',
                    backgroundColor: '#FAE5FD',
                    border: '1px solid #A229CC'
                };
            } else if ([
                STATUS_SHOPPING_ARISE_PENDING_MANAGER_HR, STATUS_SHOPPING_ARISE_PENDING_ACCOUNTANT,
                STATUS_SHOPPING_ARISE_PENDING_MANAGER
            ].includes(status)) {
                return {
                    color: '#FAAD14',
                    backgroundColor: '#FFFBE6',
                    border: '1px solid #FAAD14'
                };
            } else if ([STATUS_SHOPPING_ARISE_COMPLETE, STATUS_SHOPPING_ARISE_MANAGER_APPROVAL].includes(status)) {
                return {
                    color: '#52C41A',
                    backgroundColor: '#F6FFED',
                    border: '1px solid #52C41A'
                };
            }  else if ([
                 STATUS_SHOPPING_ARISE_MANAGER_HR_DISAPPROVAL,STATUS_SHOPPING_ARISE_ACCOUNTANT_DISAPPROVAL,
                 STATUS_SHOPPING_ARISE_MANAGER_DISAPPROVAL
            ].includes(status)) {
                return {
                    color: '#F5222D',
                    backgroundColor: '#FFF1F0',
                    border: '1px solid #F5222D'
                };
            } else if ([STATUS_SHOPPING_ARISE_HR_PROCESSING, STATUS_SHOPPING_ARISE_HR_SYNTHETIC].includes(status)) {
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
        width: min-content;
    "
    :style="getStyle(+{{$status}})"
>
</span>
