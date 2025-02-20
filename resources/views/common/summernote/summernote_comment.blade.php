<style>
    .note-editor {
        padding: 9px;
        border-radius: 29px !important;
    }
</style>
<div x-data="summernote">
    <textarea id="summernote" name="content"></textarea>
</div>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('summernote', () => ({
            init() {
                $('#summernote').summernote({
                    height: 70,
                    placeholder: 'Nhập nội dung...',
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['strikethrough', 'superscript', 'subscript']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['height', ['height']],
                        ['insert', ['link']],
                    ]
                });
            }
        }))
    })
</script>
