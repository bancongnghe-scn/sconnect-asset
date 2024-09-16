@extends('layouts.app',[
    'title' => 'Type Group'
])

@section('content')
    <div x-data="typeGroup">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Multiple</label>
                            <select class="select2 select2-hidden-accessible" multiple="" data-placeholder="Select a State"
                                    style="width: 100%;" data-select2-id="7" tabindex="-1" aria-hidden="true">
                                <option>Alabama</option>
                                <option>Alaska</option>
                                <option>California</option>
                                <option>Delaware</option>
                                <option>Tennessee</option>
                                <option>Texas</option>
                                <option>Washington</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            @view="viewTypeGroup($event.detail.id)"
            @edit="editTypeGroup($event.detail.id)"
            @remove="removeTypeGroup($event.detail.id)"
            @page-change.window="console.log($event.detail.page)"
        >
            @include('common.table')
        </div>
    </div>
@endsection

@section('js')
    <script>
        function typeGroup() {
            return {
                //created
                init() {
                    $('.select2').select2()
                    this.dataTable = this.getListTypeGroup()
                },

                //data
                dataTable: [],
                columns: {
                    id: 'ID',
                    name: 'Tên loại'
                },
                totalPages: 5,
                currentPage: 1,
                //methods
                getListTypeGroup() {
                    return [
                        {
                            id: 1,
                            name: 'Loai 1',
                        },
                        {
                            id: 2,
                            name: 'Loai 2',
                        },
                    ];
                },

                viewTypeGroup(id) {
                    this.totalPages = 5
                    this.currentPage = Math.min(this.currentPage, this.totalPages); // Ensure currentPage does not exceed totalPages

                },

                editTypeGroup(id) {
                    console.log(id)
                },

                removeTypeGroup(id) {
                    console.log(id)
                }
            }
        }

    </script>
@endsection
