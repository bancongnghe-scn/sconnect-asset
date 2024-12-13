<span x-text="LIST_STATUS_SHOPPING_ASSET[{{$status}}]" class="p-1 border rounded"
      :class="{
            'tw-text-amber-400 tw-bg-amber-100'  : [
                SHOPPING_ASSET_STATUS_PENDING_HR_MANAGER_APPROVAL,
                SHOPPING_ASSET_STATUS_PENDING_ACCOUNTANT_APPROVAL,
                SHOPPING_ASSET_STATUS_PENDING_GENERAL_APPROVAL
            ].includes(+{{$status}}),
            'tw-text-green-900 tw-bg-green-100'  : [
                SHOPPING_ASSET_STATUS_HR_MANAGER_APPROVAL,
                SHOPPING_ASSET_STATUS_ACCOUNTANT_APPROVAL,
                SHOPPING_ASSET_STATUS_GENERAL_APPROVAL
            ].includes(+{{$status}}),
            'tw-text-red-600 tw-bg-red-100'  : [
                SHOPPING_ASSET_STATUS_HR_MANAGER_DISAPPROVAL,
                SHOPPING_ASSET_STATUS_ACCOUNTANT_DISAPPROVAL,
                SHOPPING_ASSET_STATUS_GENERAL_DISAPPROVAL
            ].includes(+{{$status}})
      }">
</span>
