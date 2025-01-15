<span
    x-text="LIST_STATUS_PLAN_MAINTAIN[{{$status}}]"
    x-data="{
        getStyle(status) {
            if (status === STATUS_MAINTAINING) {
                return {
                    color: '#1890FF',
                    backgroundColor: '#E6F7FF',
                    border: '1px solid #E6F7FF'
                };
            }
            if (status === STATUS_COMPLETE_MAINTAIN) {
                return {
                    color: '#FAAD14',
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
