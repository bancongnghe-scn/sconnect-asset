<div class="col-7">
    @include('assets.asset.common.commonSvg')
    <span x-text="{{ $status}}" x-html="arrSvgStatus[{{ $status}}]"></span>
</div>
