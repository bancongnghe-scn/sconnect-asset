<div class="modal fade" id="modalCalendar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex justify-content-between">
                    <div class="col-2">
                        @include('common.datepicker.datepicker_month', [
                            'model' => 'timeCalendar'
                        ])
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="col-12 mt-3">
                    <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="example2" class="table table-bordered dataTable dtr-inline" aria-describedby="example2_info">
                                    <thead>
                                        <tr>
                                            <th rowspan="1" colspan="1" class="text-center w-1-7">T2</th>
                                            <th rowspan="1" colspan="1" class="text-center w-1-7">T3</th>
                                            <th rowspan="1" colspan="1" class="text-center w-1-7">T4</th>
                                            <th rowspan="1" colspan="1" class="text-center w-1-7">T5</th>
                                            <th rowspan="1" colspan="1" class="text-center w-1-7">T6</th>
                                            <th rowspan="1" colspan="1" class="text-center w-1-7">T7</th>
                                            <th rowspan="1" colspan="1" class="text-center w-1-7">CN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="dataWeek in dataCalendar">
                                            <tr>
                                                <template x-for="(dataDay, day) in dataWeek">
                                                    <td class="p-3">
                                                        <div x-text="day"></div>
                                                        <div class="mt-3"
                                                             :class="dataDay > 0 ? 'text-red' : ''"
                                                             x-text="dataDay > 0 ? `Có ${dataDay} tài sản đến hạn bảo dưỡng`: ''"
                                                        ></div>
                                                    </td>
                                                </template>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
