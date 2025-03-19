<span x-text="LIST_STATUS_ORDER[{{$status}}]"
      x-data="{
        getStyle(status) {
            if (status === ORDER_STATUS_NEW) {
                return {
                    color: '#1890FF',
                    backgroundColor: '#E6F7FF',
                    border: '1px solid #1890FF'
                };
            }
            else if ([ORDER_STATUS_TRANSIT, ORDER_STATUS_DELIVERED].includes(status)) {
                return {
                    color: '#FAAD14',
                    backgroundColor: '#FFFBE6',
                    border: '1px solid #FAAD14'
                };
            }
            else if (status === ORDER_STATUS_CANCEL) {
                return {
                    color: '#F5222D',
                    backgroundColor: '#FFF1F0',
                    border: '1px solid #F5222D'
                };
            }
            else if (status === ORDER_STATUS_WAREHOUSED) {
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
>
</span>
