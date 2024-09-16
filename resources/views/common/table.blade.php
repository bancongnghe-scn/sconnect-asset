<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="example2" class="table table-bordered table-hover dataTable dtr-inline"
                                   aria-describedby="example2_info">
                                <thead>
                                <tr>
                                    <th rowspan="1" colspan="1">STT</th>
                                    <template x-for="(columnName, key) in columns">
                                        <th rowspan="1" colspan="1" x-text="columnName"></th>
                                    </template>
                                    <th rowspan="1" colspan="1" class="col-2 text-center">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template x-for="(data,index) in dataTable" x-data="{line: 1}">
                                    <tr>
                                        <td x-text="index + 1"></td>
                                        <template x-for="(columnName, key) in columns">
                                            <td x-text="data[key]"></td>
                                        </template>
                                        <td class="text-center align-middle">
                                            <button class="border-0 bg-body" @click="$dispatch('view', { id: data.id })">
                                                <i class="fa-regular fa-eye" style="color: #63E6BE;"></i>
                                            </button>
                                            <button class="border-0 bg-body" @click="$dispatch('edit', { id: data.id })">
                                                <i class="fa-solid fa-pen-to-square" style="color: #1ec258;"></i>
                                            </button>
                                            <button class="border-0 bg-body" @click="$dispatch('remove', { id: data.id })">
                                                <i class="fa-solid fa-trash" style="color: #cd1326;"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('common.pagination')
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const totalPages = 10;
        let currentPage = 1;

        const pagination = document.getElementById('pagination');

        function renderPagination() {
            pagination.innerHTML = '';

            if (totalPages <= 1) return;

            const range = (currentPage > 3 && currentPage < totalPages - 2)
                ? [1, currentPage - 1, currentPage, currentPage + 1, totalPages]
                : (currentPage <= 3)
                    ? [1, 2, 3, 4, 5, totalPages]
                    : [1, totalPages - 4, totalPages - 3, totalPages - 2, totalPages - 1, totalPages];

            if (range[0] > 1) {
                pagination.appendChild(createPageItem(1));
                if (range[0] > 2) {
                    pagination.appendChild(createEllipsis());
                }
            }

            range.forEach(page => {
                if (page === currentPage) {
                    pagination.appendChild(createActivePageItem(page));
                } else {
                    pagination.appendChild(createPageItem(page));
                }
            });

            if (range[range.length - 1] < totalPages) {
                if (range[range.length - 1] < totalPages - 1) {
                    pagination.appendChild(createEllipsis());
                }
                pagination.appendChild(createPageItem(totalPages));
            }
        }

        function createPageItem(page) {
            const li = document.createElement('li');
            li.className = 'page-item';
            const a = document.createElement('a');
            a.className = 'page-link';
            a.href = '#';
            a.textContent = page;
            a.addEventListener('click', function () {
                currentPage = page;
                renderPagination();
            });
            li.appendChild(a);
            return li;
        }

        function createActivePageItem(page) {
            const li = document.createElement('li');
            li.className = 'page-item active';
            const a = document.createElement('a');
            a.className = 'page-link';
            a.href = '#';
            a.textContent = page;
            li.appendChild(a);
            return li;
        }

        function createEllipsis() {
            const li = document.createElement('li');
            li.className = 'page-item disabled';
            const span = document.createElement('span');
            span.className = 'page-link';
            span.textContent = '...';
            li.appendChild(span);
            return li;
        }

        renderPagination();
    });
</script>
