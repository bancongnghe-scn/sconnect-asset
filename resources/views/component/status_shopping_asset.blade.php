<span x-text="LIST_STATUS_SHOPPING_ASSET[{{$status}}]" class="p-1 border rounded"
      :class="{
            'tw-text-amber-400 tw-bg-amber-100'  : +{{$status}} === SHOPPING_ASSET_STATUS_PENDING_HR_MANAGER_APPROVAL,
            'tw-text-green-900 tw-bg-green-100'  : +{{$status}} === SHOPPING_ASSET_STATUS_HR_MANAGER_APPROVAL
            || +{{$status}} === SHOPPING_ASSET_STATUS_ACCOUNTANT_APPROVAL,
            'tw-text-red-600 tw-bg-red-100'  : +{{$status}} === SHOPPING_ASSET_STATUS_HR_MANAGER_DISAPPROVAL
            || +{{$status}} === SHOPPING_ASSET_STATUS_ACCOUNTANT_DISAPPROVAL,
      }">
</span>
