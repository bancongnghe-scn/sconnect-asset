<div class="d-flex justify-content-center">
    @include('assets.asset.common.commonSvg')
    <span x-text="{{ $status ?? 'data[key]' }}" x-html="arrSvgStatus[{{ $status ?? 'data[key]' }}]"></span>
</div>
