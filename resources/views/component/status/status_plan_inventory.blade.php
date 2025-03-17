<span x-text="LIST_STATUS_PLAN_INVENTORY[{{$status}}]"
      x-data="{
        getStyle(status) {
            if (status === STATUS_INVENTORY_NEW) {
                return {
                    color: '#1890FF',
                    backgroundColor: '#E6F7FF',
                    border: '1px solid #1890FF'
                };
            }
            else if (status === STATUS_TAKING_INVENTORY) {
                return {
                    color: '#1890FF',
                    backgroundColor: '#E6F7FF',
                    border: '1px solid #1890FF'
                };
            }
            else if (status === STATUS_INVENTORIED) {
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
      "
      :style="getStyle(+{{$status}})"
>
</span>
