<div>
    <div class="d-flex tw-gap-x-14 mb-3 p-4" x-data="statistic_plan_inventory">
        <div>
            <div class="d-flex gap-2 align-items-center" style="color:red;">
                <div class="tw-w-fit rounded" style="background: #CC8B8B33;padding: 8px 10px 8px 10px;">
                    <i class="bi bi-dash-circle tw-text-4xl rounded"></i>
                </div>
                <span class="text-lg" x-text="total_difference"></span>
            </div>
            <span>Chênh lệch số lượng</span>
        </div>

        <div>
            <div class="d-flex gap-2 align-items-center" style="color:#FAAD14;">
                <div class="tw-w-fit rounded" style="background: #FAAD1433;padding: 8px 10px 8px 10px;">
                    <i class="bi bi-repeat tw-text-4xl rounded"></i>
                </div>
                <span class="text-lg" x-text="user_difference"></span>
            </div>
            <span>Thay đổi đối tượng sử dụng</span>
        </div>

        <div>
            <div class="d-flex gap-2 align-items-center" style="color:#00CEB6;">
                <div class="tw-w-fit rounded" style="background: #00CEB61A;padding: 8px 10px 8px 10px;">
                    <i class="bi bi-geo-alt tw-text-4xl rounded"></i>
                </div>
                <span class="text-lg" x-text="location_difference"></span>
            </div>
            <span>Thay đổi vị trí tài sản</span>
        </div>

        <div>
            <div class="d-flex gap-2 align-items-center" style="color:#6E7276;">
                <div class="tw-w-fit rounded" style="background: #0606060D;padding: 8px 10px 8px 10px;">
                    <i class="bi bi-view-stacked tw-text-4xl rounded"></i>
                </div>
                <span class="text-lg" x-text="status_difference"></span>
            </div>
            <span>Thay đổi tình trạng</span>
        </div>

        <div class="d-flex align-items-center tw-gap-x-2" x-data="{processInventory: 0}" x-effect="processInventory = Math.round((process/count)*100) || 0">
            <svg class="progress-circle tw-w-16 tw-h-16" viewBox="0 0 100 100">

                <circle fill="none" stroke-width="10" stroke="#e0e0e0" class="bg" cx="50" cy="50" r="45"></circle>


                <circle stroke-linecap="round" :stroke-dashoffset="282*(1-(process/count))" stroke-dasharray="282" fill="none" stroke-width="10" stroke="#007bff" class="progress" cx="50" cy="50" r="45"></circle>


                <text id="progress-text" x="50" y="55" text-anchor="middle" font-size="20" fill="#000" x-text="`${processInventory}%`"></text>
            </svg>
            <div class="text-sm">
                <div class="text-primary" x-text="`${process}/${count}`"></div>
                <div>Tổng số lượng</div>
            </div>
        </div>
    </div>

    <script>
        function statistic_plan_inventory() {
            return {
                init() {
                    this.$watch('data.assets', (value) => {
                        if(value) {
                            this.total_difference = 0
                            this.user_difference = 0
                            this.location_difference = 0
                            this.status_difference = 0
                            this.process = 0
                            this.count = 0
                            value.forEach(item => {
                                if (+item.total_present !== 1) {
                                    this.total_difference ++
                                }
                                if (+item.user_id !== +item.user_id_present) {
                                    this.user_difference ++
                                }
                                if (+item.location !== +item.location_present) {
                                    this.location_difference ++
                                }
                                if (+item.status_asset !== +item.status_asset_present) {
                                    this.status_difference ++
                                }
                                if (+item.status === STATUS_ASSET_INVENTORIED) {
                                    this.process ++
                                }
                                this.count ++
                            })
                        }
                    })
                },

                total_difference: 0,
                user_difference: 0,
                location_difference: 0,
                status_difference: 0,
                process: 0,
                count: 0
            }
        }
    </script>
</div>
