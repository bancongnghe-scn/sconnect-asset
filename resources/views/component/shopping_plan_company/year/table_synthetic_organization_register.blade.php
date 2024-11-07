<div class="tw-max-h-dvh overflow-y-scroll">
    <table id="example2" class="table table-bordered table-hover dataTable dtr-inline"
           aria-describedby="example2_info">
        <thead class="position-sticky z-1" style="top: -1px">
        <tr>
            <th rowspan="1" colspan="1" class="tw-w-fit">STT</th>
            <th rowspan="1" colspan="1" class="text-center">Đơn vị</th>
        </tr>
        </thead>
        <tbody>
        <template x-for="(organization,index) in organizationsRegister">
            <tr>
                <td x-text="index+1"></td>
                <td x-text="organization" class="tw-font-bold"></td>
            </tr>
        </template>
        </tbody>
    </table>
</div>
