<div class="tab-container">
    <div class="@if (Route::currentRouteName() == 'assets.report.overviewReport') tab-item-active @else tab-item @endif" onclick="location.href='{{ route('assets.report.overviewReport') }}'">
        Tổng quan
    </div>
    <div class="@if (Route::currentRouteName() == 'assets.report.valueReport') tab-item-active @else tab-item @endif" onclick="location.href='{{ route('assets.report.valueReport') }}'">
        Báo cáo giá trị tài sản
    </div>
    <div class="@if (Route::currentRouteName() == 'assets.report.operatingReport') tab-item-active @else tab-item @endif" onclick="location.href='{{ route('assets.report.operatingReport') }}'">
        Báo cáo chi phí vận hành
    </div>
    <div class="@if (Route::currentRouteName() == 'assets.report.useReport') tab-item-active @else tab-item @endif" onclick="location.href='{{ route('assets.report.useReport') }}'">
        Báo cáo sử dụng tài sản
    </div>
    <div class="@if (Route::currentRouteName() == 'assets.report.maintainReport') tab-item-active @else tab-item @endif" onclick="location.href='{{ route('assets.report.maintainReport') }}'">
        Báo cáo bảo dưỡng
    </div>
    <div class="@if (Route::currentRouteName() == 'assets.report.buyReport') tab-item-active @else tab-item @endif" onclick="location.href='{{ route('assets.report.buyReport') }}'">
        Báo cáo mua sắm
    </div>
    <div class="@if (Route::currentRouteName() == 'assets.report.supplierReport') tab-item-active @else tab-item @endif" onclick="location.href='{{ route('assets.report.supplierReport') }}'">
        Báo cáo nhà cung cấp
    </div>
</div>

<style>
.tab-container{
    height: 40px;
    margin-bottom: 10px;
    background: #fff;
    display: flex;
    padding: 0px 10px;
}
.tab-item-active{
    border-bottom: 2px solid #379237;
    padding: 5px 10px;
    color: #379237;
    font-weight: 600;
    cursor: pointer;
}

.tab-item{
    padding: 5px 10px;
    color: #7b7b7b;
    cursor: pointer;
}
</style>