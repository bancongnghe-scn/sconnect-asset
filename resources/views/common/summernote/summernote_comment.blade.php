<style>
    .note-editor {
        padding: 9px;
        border-radius: 29px !important;
    }
</style>
<div x-data="summernote" x-init="initSummer()">
    <textarea id="summernote"></textarea>

    <div>
        <button class="btn btn-sc" @click="sentComment()">Gửi</button>
        <button class="btn btn-light" @click="resetInputSummer()">Hủy bỏ</button>
    </div>
</div>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('summernote', () => ({
            init() {
                this.$watch('showModal', (value) => {
                    this.resetInputSummer()
                })
            },

            initSummer() {
                const summerNote = $('#summernote')
                summerNote.summernote({
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
                    ],
                    callbacks: {
                        onChange: function(contents) {
                            window.dispatchEvent(new CustomEvent('update-message', { detail: contents }));
                        }
                    }
                });

                summerNote.on('summernote.keydown', function(we, e) {
                    if (e.keyCode === 13 && !e.shiftKey) {
                        e.preventDefault();
                        window.dispatchEvent(new CustomEvent('sent-comment'));
                    }
                });
            },

            resetInputSummer() {
                $('#summernote').summernote('code', null)
            },

            sentComment() {
                window.dispatchEvent(new CustomEvent('sent-comment'));
            }

        }))
    })
</script>
