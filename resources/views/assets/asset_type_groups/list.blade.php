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

        <div>
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
                },

                //data
                listTypeGroup: [],

                //methods
                getListTypeGroup() {
                    axios.get("{{ route('hrm.asset.industry.list') }}")
                        .then(response => {
                            const data = response.data;
                            if (!data.success) {
                                toastr.error(data.message)
                            }

                            this.listIndustry = data.data
                        })
                        .catch(error => {
                            toastr.error(data.message)
                        });
                }
            }
        }

    </script>
@endsection
