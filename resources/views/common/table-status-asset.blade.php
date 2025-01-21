<div :class="status ? 'd-flex justify-content-center' : 'col-7'">
    @include('assets.asset.common.commonSvg')
    <span x-text="{{ $status ?? 'data[key]' }}" x-html="arrSvgStatus[{{ $status ?? 'data[key]' }}]"></span>
</div>
