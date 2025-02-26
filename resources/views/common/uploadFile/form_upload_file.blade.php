<div class="file-loading">
    <input x-data="{
             init() {
                 $($el).fileinput({
                     uploadUrl: false,
                     maxFilePreviewSize: 10240,
                     showUpload: false,
                     showCancel: false,
                     previewSettings: {
                         image: { width: '120px', height: '120px' },
                         video: { width: '120px', height: '120px' },
                         pdf: { width: '120px', height: '120px' },
                         text: { width: '120px', height: '120px' },
                         object: { width: '120px', height: '120px' }
                     }
                 });
             },
             files: []
        }" id="input-41" name="input41[]" type="file" multiple>
</div>

<style>
    .kv-file-upload {
        display: none !important;
    }
    .file-upload-indicator {
        display: none !important;
    }
    .kv-file-content {
        display: block;
        width: 100%;
        height: 100%;
    }
    .file-preview-frame {
        width: 9rem;
    }
    .kv-file-content {
        width: 8rem !important;
        height: 8rem !important;
    }
    .file-thumbnail-footer {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .file-footer-caption {
        margin: 0 !important;
        width: 100% !important;
    }
    .file-caption-info {
        width: 100% !important;
    }
    .file-size-info {
        width: 100% !important;
    }
    .kv-preview-data {
        width: 100% !important;
        height: 100% !important;
    }
</style>
