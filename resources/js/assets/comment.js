class comment {
    constructor() {
        const self = this;
        window.addEventListener("load", function () {
            self.doLoad();
        });
    }

    /**
     * Do load all action and element.
     */
    doLoad() {
        this.init();
    }

    //==================================================================
    // initial when load
    //==================================================================
    init() {
        this.openCommentInput();
        this.removeFileAdd();
        this.reactionComment();
        this.showBoxReaction();
        this.deleteComment();
        this.repComment();
        this.editComment();
        this.showListFile();
        this.cancelComment();
        this.createFormComment();
        this.loadOldComment();
        this.hideOldComments();
        this.commentBroadcast();
        this.popupInfoUser();
        this.showListUserReacted();
        this.emojiPicker();

    }

    getColComment(targetId, commentType) {
        $.ajax({
            type: "get",
            url: commentListUrl,
            data: { elementId: targetId, commentType: commentType },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $('a[href="#comments"] .totalComment').html(response.totalComments); //tổng số comment

                    $('.list-comments-body').html('');
                    $('.list-comments-body').html(response.comments);
                }
            }
        });
    }

    openCommentInput() {
        const self = this;
        $('#comment-input').focus(function () {
            $('.edit-comment-box').slideUp();
            $('.edit-comment-box').html('');

            self.renderSummernote($(this));
            $('.bottom-form').slideDown();
        });
    };

    //hủy bình luận
    cancelComment() {
        $('#cancel-comment').click(function () {
            $('#comment-input').summernote("code", "");
            $('#comment-input').summernote('destroy');
            $('.list-file').html('');
            $('.box-files').css('display', 'none');
            $('.upload-input').val('');
            $('.upload-files-list').val('');
            $('.bottom-form').slideUp();
            $('.required-error').html('');
        });
    }

    renderSummernote(element) {
        const self = this;
        element.summernote({
            toolbar: [
                ['misc', ['undo', 'redo']],
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'hr']]
            ],
            callbacks: {
                onKeyup: function (e) {
                    //bắt ký tự @
                    if (e.keyCode == 50 && e.shiftKey == true) {
                        element.parent().find('.btn-click-tagUser').trigger('click');
                    }
                },
            }
        });
        element.summernote("code", element.val());
        element.summernote("focus");
        self.listUserTag();
        self.searchUser();
        self.uploadFile();
        self.cancelEditComment();
    };

    //danh sách bình luận cũ
    loadOldComment() {
        const self = this;
        $(document).on('click', '.btn-show-more', function () {
            var element = $(this).parent().next();

            if (element.find('.comment-box-list').length > 0) {
                element.slideDown();
            } else {
                var id = $(this).data('elementid');
                var limit = $(this).data('count');

                $.ajax({
                    type: "get",
                    url: getMoreComments,
                    data: { elementId: id, limit: limit },
                    dataType: "json",
                }).done(function (data) {
                    element.html(data.view);
                    element.slideDown();
                });
            }
            $(this).hide();
            $(this).next().show();

        });
    }
    //ẩn bình luận cũ
    hideOldComments() {
        $(document).on('click', '.btn-hide', function () {
            var element = $(this).parent().next();
            element.slideUp();
            $(this).hide();
            $(this).prev().show();
        })
    }

    //chọn emoji
    emojiPicker() {
        $(document).on('click', '.dropdown-menu', function (e) {
            e.stopPropagation();
        });

        var picker = document.querySelector('emoji-picker');
        picker.addEventListener('emoji-click', event => {
            var textArea = $(event.target).closest('.bottom-form').parent().find('.comment-input');
            var currentText = textArea.summernote("code");
            if (currentText !== "<p><br></p>") {
                textArea.summernote("code", currentText + ' ' + event.detail.unicode);
            } else {
                textArea.summernote("code", event.detail.unicode);
            }
        });
    };

    //chọn user tag
    choseUserTag() {
        const self = this;
        $('.chose-user-tag').click(function () {
            var textArea = $(this).closest('.bottom-form').parent().find('.comment-input');
            var currentText = textArea.summernote("code");
            var userTag = '<u><font color="#085294"><span class="id-user-tag popup-user" userid="' + $(this).data('userid') + '">' + $(this).data('username') + '</span></font></u> <span>&nbsp;</span>';
            var atIndex = currentText.lastIndexOf('@');

            if (currentText !== " ") {
                if (atIndex !== -1) {
                    var newCurrent = currentText.substring(0, atIndex);
                    textArea.summernote("code", newCurrent + userTag);
                } else {
                    textArea.summernote("code", currentText + userTag);
                }
            } else {
                textArea.summernote("code", userTag);
            }

            $('.dropdown-menu').removeClass('show');

            self.moveCursorToLast(textArea);

        });
    };
    //danh sách user tag
    listUserTag() {
        const self = this;
        $('.btn-click-tagUser').click(function () {
            $('.search-user-tag').val('');
            var element = $(this).next('.dropdown-menu').find('.list-user');
            if (sessionStorage.getItem('usertags') == null) {
                var urlUser = getUserTagUrl;
                loadAjaxGet(
                    urlUser,
                    {
                        beforeSend: function () {
                            element.append(
                                '<section id="loading_box"><div id="loading_image"></div></section>'
                            );
                            $("#loading_box")
                                .css({ visibility: "visible", opacity: 0.0, height: "100%", position: "absolute", backgroundColor: "rgb(255 255 255 / 0%)" })
                                .animate({ opacity: 1.0 }, 200);
                        },
                        success: function (result) {
                            $("#loading_box").remove();
                            self.renderUserTag(result.data, element);
                            sessionStorage.setItem('usertags', JSON.stringify(result.data));

                        },
                        error: function (error) { },
                    },
                    "",
                )
            } else {
                self.renderUserTag(JSON.parse(sessionStorage.getItem('usertags')), element);
            }
            self.choseUserTag();
        });
    };
    //tìm kiếm user tag
    searchUser() {
        const self = this;
        $('.search-user-tag').keyup(function (e) {
            var results = [];
            var elementList = $(this).parent().next().next();
            var arrayOfObjects = JSON.parse(sessionStorage.getItem('usertags'));

            for (var i = 0; i < arrayOfObjects.length; i++) {
                var name = arrayOfObjects[i].name.toLowerCase();
                var code = arrayOfObjects[i].code.toLowerCase();
                var input = $(this).val().toLowerCase();

                if (name.includes($.trim(input)) || code.includes($.trim(input))) {
                    results.push(arrayOfObjects[i]);
                }
            }

            self.renderUserTag(results, elementList);
            self.choseUserTag();
        });
    };
    //render user tag
    renderUserTag(users, element) {
        element.html('');
        $.each(users, function (indexInArray, valueOfElement) {
            let avatar = valueOfElement.avatar ? "" : ""
            element.append(
                '<div class="user-tag-box chose-user-tag" data-userid="' + valueOfElement.id + '" data-username="' + valueOfElement.name + '">' +
                '<div class="user-tag-info" style="display: flex; align-items: center;">' +
                '<div class="propose__box-avatar rounded-circle">' +
                '<img onerror="' + errorDefaultAva + '" class="w-100 h-100 rounded-circle object-fit" src="' + avatar + '" alt="">' +
                '</div>' +
                '<div class="user-tag-name"><div>' + valueOfElement.code + ' - ' + valueOfElement.name + '</div>' +
                '</div>' +
                '</div>'
            );
        });
    }

    uploadFile() {
        const self = this;
        $('.uploadFile').off('click').on('click', function (e) {
            $('.box-files').slideToggle();
        });

        $('.drop-click-upload').off('click').on('click', function (e) {
            $(this).parent().find('.upload-input').click();
        });

        $('.drag-white-space').off('drop').on('drop', function (e) {
            e.preventDefault();
            var inputFilesList = $(this).parent().find('.btn-upload').find('.upload-files-list')[0];
            var file = e.originalEvent.dataTransfer.files;
            var elem = $(inputFilesList).closest('.btn-upload').parent().find('.list-file');

            var valiedateFile = self.checkValidateFiles(inputFilesList.files, file);
            if (valiedateFile == false) {
                $('.note-editor.note-frame.panel.panel-default').removeClass('dragover');
                $(this).css('display', 'none');
                $(this).parent().removeClass('drop-zone');

                return false;
            }

            self.updateFileInput(inputFilesList, file);
            self.renderListFiles(elem, inputFilesList.files);

            $(this).css('display', 'none');
            $(this).parent().removeClass('drop-zone');
        });

        $('.upload-input').off('change').on('change', function (e) {
            e.preventDefault();
            var elem = $(this).closest('.btn-upload').parent().find('.list-file');
            var inputFilesList = $(this).next('.upload-files-list')[0];

            var valiedateFile = self.checkValidateFiles(inputFilesList.files, $(this)[0].files);
            if (valiedateFile == false) return false;

            self.updateFileInput(inputFilesList, $(this)[0].files);
            self.renderListFiles(elem, inputFilesList.files);
        });

        $('.box-files').bind('dragover', function (e) {
            e.preventDefault();
            $(this).addClass('drop-zone');
            $(this).find('.drag-white-space').css('display', 'block');
        });

        $('.drag-white-space').bind('dragleave', function (e) {
            e.preventDefault();
            $(this).css('display', 'none');
            $(this).closest('.box-files').removeClass('drop-zone');
        });
    };

    checkValidateFiles(filesExist, files) {
        var totalFiles = filesExist.length + files.length;
        var totalFilesEdit = $('.card-file-info.card-edit-file').length + totalFiles;

        if (totalFiles > 5 || files.length > 5 || totalFilesEdit > 5) {
            toastr.error('Tối đa 5 tập tin');
            return false;
        }

        for (var i = 0; i < files.length; i++) {
            if (files[i].size > fileSizeAllow) {
                toastr.error('Dung lượng tối đa 10MB. Tập tin: ' + files[i].name);
                return false;
            }

            var typeFile = files[i].name.split('.').pop().toLowerCase();
            if (jQuery.inArray(typeFile, fileTypesAllow) === -1) {
                toastr.error('Định dạng tập tin không hợp lệ. Vui lòng nhập các định dạng .jpg, .jpeg, .gif, .png, .svg, .docx, .doc, .xlsx, .pdf, .zip, .exe, .pptx');
                return false;
            }
        }
    };

    updateFileInput(input, file) {
        const existInputFiles = Array.from(input.files);
        if (existInputFiles.length != 0) {
            for (let i = 0; i < Array.from(file).length; i++) {
                existInputFiles.push(Array.from(file)[i]);
            }

            var newFileList = new DataTransfer();
            existInputFiles.forEach(function (fileIn) {
                newFileList.items.add(fileIn);
            });

            input.files = newFileList.files;
        } else {
            input.files = file;
        }
    };

    renderListFiles(element, files) {
        element.find('.card-add-new.card-file-info').remove();
        $.each(Array.from(files), function (indexInArray, valueOfElement) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var imgSrc = '';
                var typeFile = valueOfElement.name.split('.').pop().toLowerCase();
                imgSrc = e.target.result
                if (fileTypePath[typeFile]) imgSrc = baseUrl + fileTypePath[typeFile];

                element.append(
                    // '<div class="col-4">' +
                    '<div class="card-add-new card-file-info">' +
                    '<button type="button" data-index="' + indexInArray + '" class="remove-file-add">X</button>' +
                    '<div class="image-file">' +
                    '<img style="width: 100%" src="' + imgSrc + '" alt="">' +
                    '</div>' +
                    '<div class="name-file">' + valueOfElement.name + '</div>' +
                    '</div>'
                    // '</div>'
                );
            };
            reader.readAsDataURL(valueOfElement);
        });
    };

    //hủy chọn file
    removeFileAdd() {
        const self = this;
        $(document).on('click', '.remove-file-add', function () {
            var inputListFile = $(this).closest('.list-file').next('.btn-upload').find('.upload-files-list')[0];
            var elemBox = $(this).closest('.list-file');
            // var input = $(this).closest('.box-files').find('input[type="file"]')[0];
            var index = $(this).data('index');
            var oldFileList = Array.from(inputListFile.files);

            var newFileList = new DataTransfer();
            oldFileList.forEach(function (file, fileIndex) {
                if (fileIndex != index) {
                    newFileList.items.add(file);
                }
            });
            inputListFile.files = newFileList.files;

            self.renderListFiles(elemBox, inputListFile.files);
        });
    };

    cancelEditComment() {
        $('.btn-cancel-edit').click(function () {
            var elem = $(this).closest('.edit-comment-box');
            elem.slideUp();
            elem.html('');
        });
    };

    //thả reaction bình luận
    reactionComment() {
        $(document).on('click', '.icon-reaction', function () {
            let data = {
                commentId: $(this).data('comment'),
                react: $(this).data('react'),
                reactText: $(this).data('text'),
            };
            loadAjaxPost(
                commentReactUrl,
                data,
                {
                    beforeSend: function () { },
                    success: function (result) {
                        $('.react-emoji-box').hide();
                    },
                    error: function (error) { },
                    async: false,
                    contentType: "application/json",
                    processData: false,
                    json: true,
                },
                "loading",
            );
        });
    }

    popupInfoUser() {
        $(document).on('click', '.popup-user', function (e) {
            e.preventDefault();
            var data = {
                userid: $(this).attr('userid')
            };
            loadAjaxGet(
                getInfoUserUrl,
                {
                    beforeSend: function () { },
                    success: function (result) {
                        var response = result.data;
                        var dateObj = new Date(response.info_personal.date_of_birth);
                        $("#right-modal-info-user").modal("show");
                        $("#right-modal-info-user #info-code").text(response.code);
                        $("#right-modal-info-user #info-full-name").text(response.name);
                        $("#right-modal-info-user #info-email").text(response.email);
                        $("#right-modal-info-user #info-date-of-birth").text(dateObj.getDate() + '/' + (dateObj.getMonth() + 1) + '/' + dateObj.getFullYear());
                        $("#right-modal-info-user #info-department").text(response.job_title ? (response.job_title.organization.dept_type ? response.job_title
                            .organization
                            .dept_type?.cfg_key + ' ' + response.job_title.organization?.name : '') : '');
                        $("#right-modal-info-user #info-position").text(response.job_title ? (response.job_title.position_office.cfg_key + ' ' + response
                            .job_title
                            .job_position.cfg_key) : '');
                        $("#right-modal-info-user #info-avatar-user").attr("src", response.avatar ? response.avatar : defaultAva);
                    },
                    error: function (error) { },
                    async: false,
                    contentType: "application/json",
                    processData: false,
                    contentType: false,
                },
                false,
                data
            );
        });
    }

    //ẩn hiện box reaction
    showBoxReaction() {
        $('.btn-like-action').hover(function () {
            $(this).parent().find('.react-emoji-box').show();

        }, function () {
            $(this).find('.react-emoji-box').hide();
        }
        );
    }

    //xóa comment
    deleteComment() {
        $(document).on('click', '.btn-del-comment', function () {
            var routeDel = $(this).data('route');
            var dialog = SconnectJs.showConfirmDialog(
                `Bạn có chắc chắn muốn xóa &nbsp<span class='text-danger'>bản ghi</span>&nbspnày không???`,
                "XÁC NHẬN XÓA");
            dialog.modal('show');
            dialog.find(".cmd-save").click(function () {
                $(".cmd-save").hide();
                loadAjaxPost(
                    routeDel,
                    {},
                    {
                        beforeSend: function () { },
                        success: function (result) {
                            dialog.modal('hide');
                        },
                        error: function (error) { },
                        processData: false,
                        contentType: false,
                    },
                    "loading"
                );
            })
        });
    }

    //trả lời bình luận
    repComment() {
        const self = this;
        $(document).on('click', '.user-reply', function () {
            $('.edit-comment-box').slideUp();
            $('.edit-comment-box').html('');

            self.renderSummernote($('#comment-input'));
            $('.bottom-form').slideDown();

            var textArea = $('#comment-input');
            var currentText = textArea.summernote("code");
            var userTag = '<u><font color="#085294"><span class="id-user-tag popup-user" userid="' + $(this).data('userid') + '">' + $(this).data('user') + '</span></font></u> <span>&nbsp;</span>';

            if (currentText !== " ") {
                textArea.summernote("code", currentText + userTag);
            } else {
                textArea.summernote("code", userTag);
            }

            self.moveCursorToLast($('#comment-input'));
        });
    }

    //di chuyển trỏ chuột tới ký tự cuối cùng
    moveCursorToLast(element) {
        element.summernote('focus');
        var summernoteElement = element.next('.note-editor').find('.note-editable');

        var range = document.createRange();
        range.selectNodeContents(summernoteElement[0]);
        range.collapse(false);

        var selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);
    }

    //sửa bình luận
    editComment() {
        const self = this;
        $(document).on('click', '.btn-edit-comment', function () {
            $('#cancel-comment').trigger('click');
            $('.edit-comment-box').slideUp();
            $('.edit-comment-box').html('');
            var elem = $(this).parent().parent().next('.edit-comment-box');
            const data = {
                comment_id: $(this).data('comment')
            };
            const url = getCommentEditUrl;
            loadAjaxGet(
                url,
                {
                    beforeSend: function () { },
                    success: function (result) {
                        elem.html('');
                        elem.append(result.view).slideDown();
                        self.renderSummernote(elem.children().find('.comment-input'));
                        self.btnDelFileComment();
                        self.editFormComment();
                        elem.parent().parent().find('.uploadFile').click();
                        self.emojiPicker();
                        self.moveCursorToLast(elem.children().find('.comment-input'));

                    },
                    error: function (error) { },
                },
                "loading",
                data,
                {}
            );
        });
    }

    //thêm bihf luận
    createFormComment() {
        const self = this;
        $('#btn-submit-comment').off('click').on('click', function () {
            var url = $('#form-create-comment').data('url');
            var inputs = $('#form-create-comment').find('.filter-create');
            var inputFiles = $('#form-create-comment').find('.upload-files-list')[0];
            var formData = new FormData();
            if ($('#comment-input').summernote('isEmpty')) {
                $('.required-error').html('Chưa nhập bình luận');
                $('.required-error')[0].scrollIntoView({ block: "center" });
                return;
            } else {
                $('.required-error').html('');
            }
            for (var i = 0; i < inputs.length; i++) {
                formData.append(inputs[i].name, inputs[i].value);
            }

            for (var i = 0; i < inputFiles.files.length; i++) {
                formData.append(inputFiles.name, inputFiles.files[i]);
            }

            var userTag = $('#form-create-comment').find($('.id-user-tag.popup-user'));
            if (userTag.length > 0) {
                for (let index = 0; index < userTag.length; index++) {
                    formData.append('userTags[]', $(userTag[index]).attr('userid'));
                }
            }

            self.formSubmit(url, formData, $('#form-create-comment'));
        })
    }

    //sửa bình luận
    editFormComment() {
        const self = this;
        $('#form-edit-comment').on('submit', function (e) {
            e.preventDefault();
            var input = $(this).find('.comment-input');
            if (input.summernote('isEmpty')) {
                $(this).find('.required-error').html('Chưa nhập bình luận');
                $(this).find('.required-error')[0].scrollIntoView({ block: "center" });
                return;
            } else {
                $(this).find('.required-error').html('');
            }
            var formData = new FormData(this);
            var url = $(this).attr('action');
            var userTag = $('#form-edit-comment').find($('.id-user-tag.popup-user'));
            if (userTag.length > 0) {
                for (let index = 0; index < userTag.length; index++) {
                    formData.append('userTags[]', $(userTag[index]).attr('userid'));
                }
            }
            self.formSubmit(url, formData, $(this));
        });
    }

    formSubmit(url, data, formElement) {
        const self = this;
        $.ajax({
            type: "post",
            url: url,
            data: data,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            // contentType: "multipart/form-data",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            beforeSend: function () {
                formElement.append(
                    '<section id="loading_box"><div id="loading_image"></div></section>'
                );
                $("#loading_box")
                    .css({ visibility: "visible", opacity: 0.0, height: "100%", position: "absolute", backgroundColor: "rgb(255 255 255 / 0%)" })
                    .animate({ opacity: 1.0 }, 200);
            },
        }).done(function (result) {
            console.warn('result', result);

            if (result.success == true) {
                $('.btn-cancel-edit').trigger('click');
                $('#cancel-comment').trigger('click');
            }

            switch (result.data.action_type) {
                case cmTypeCreate:
                    // clssIndexPropose.latestCommentHistory(_.last(result.result.history), result.result.history.length - 1, result.result.propose_type);

                    break;

                default:
                    break;
            }
        }).fail(function () {

        }).always(function () {
            $("#loading_box").remove();
        });
    }

    btnDelFileComment() {
        $('.remove-file-comment').off('click').on('click', function () {
            var inputDelFile = $(this).closest('.list-file').find('.input-del-files');

            var fileArray = JSON.parse(inputDelFile.val() || '[]');

            if (!fileArray || fileArray.length === 0) {
                fileArray = { id: [], path: [] };
            }

            fileArray.id.push($(this).data('fileid'));
            fileArray.path.push($(this).data('filepath'));

            inputDelFile.val(JSON.stringify(fileArray));
            $(this).parent().remove();
        })
    };

    showListFile() {
        $(document).on('click', '.comment-file-content', function () {
            $(this).next('.comment-files-list').slideToggle();
            $(this).find('i').toggleClass('bi-caret-down-fill');
            $(this).find('i').toggleClass('bi-caret-up-fill');

        });
    }

    //hiện bình luận khi click thông báo
    openCommentNoti(commentId) {
        if ($('#comment-' + commentId).length == 0) {
            var btnShow = $('.show-hide-more-comment').find('.btn-show-more');
            var element = btnShow.parent().parent().find('.old-comments-list');

            $.ajax({
                type: "get",
                url: getMoreComments,
                data: { elementId: btnShow.data('elementid'), limit: btnShow.data('count') },
                dataType: "json",
            }).done(function (data) {
                element.html(data.view);
                element.slideDown();

                btnShow.hide();
                btnShow.next().show();

                $('#comment-' + commentId).find('.comment-content').css('backgroundColor', 'rgb(251 239 182 / 62%)');
                setTimeout(function () {
                    $('#comment-' + commentId)[0].scrollIntoView({ block: "center" });
                }, 500)
            });
        } else {
            $('#comment-' + commentId).find('.comment-content').css('backgroundColor', 'rgb(251 239 182 / 62%)');
            $('#comment-' + commentId)[0].scrollIntoView({ block: "center" });
        }

        $('.proposal-comment').click(function (e) {
            $('.comment-content').addClass('focus-remove');
        });
    }

    //list user reacted
    showListUserReacted() {
        $(document).on('click', function (event) {
            if (!$(event.target).closest('.modal-user-react').length) {
                $('.modal-user-react').hide();
                $('.modal-user-react').html('');
            }
        });

        var timer;
        $(document)
            .on("mouseenter", '.comment-reated', function () {
                var element = $(this);
                var modalTabList = $(this).parent().find('.modal-user-react');

                if (modalTabList.css('display') === 'none') {
                    timer = setTimeout(function () {
                        $('.modal-user-react').css('display', 'none');
                        loadAjaxGet(
                            getListReactUserUrl,
                            {
                                beforeSend: function () {
                                    modalTabList.html('');
                                    modalTabList.css({ display: 'block' });

                                    modalTabList.append(
                                        '<section id="loading_box"><div id="loading_image"></div></section>'
                                    );
                                    $("#loading_box")
                                        .css({ visibility: "visible", opacity: 0.0, height: "100%", position: "absolute", backgroundColor: "rgb(255 255 255 / 0%)" })
                                        .animate({ opacity: 1.0 }, 200);
                                },
                                success: function (result) {
                                    $("#loading_box").remove();
                                    modalTabList.html(result.data);
                                },
                                error: function (error) { },
                            },
                            "",
                            { id: element.data('comment') }
                        );
                    }, 1000)
                }

            })
            .on("mouseleave", '.comment-reated', function () {
                clearTimeout(timer);
            });
    }

    //============= comment real time =================
    commentBroadcast() {
        const self = this;
        window.Echo.channel(`comment`).listen('.CommentEvent', function (res) {
            var elemRender = $('.list-comments-body');
            var elementId = $('#target_id').val();

            //lấy data render
            // switch (res.commentType) {
            //     case cmProposalType:
            //         elemRender = $('.list-comments-body');
            //         elementId = $('#propose_view_id').val();
            //         break;
            //     case cmRecruitmentTargetType:
            //         elemRender = $('.list-comments-body');
            //         elementId = $('#target_id').val();
            //         break;
            //     default:
            //         break;
            // }

            switch (res.actionType) {
                case cmTypeCreate: //tạo mới
                    if (elementId == res.commentElemId) {
                        self.renderNewComment(res, elemRender);
                        self.separateAction(res.commentType, cmTypeCreate);
                    }
                    break;
                case cmTypeUpdate: //cập nhật
                    self.renderUpdateComment(res);
                    break;
                case cmTypeDel: //xóa
                    self.renderDeleteComment(res);
                    self.separateAction(res.commentType, cmTypeDel); //trừ tổng bình luận đi
                    break;
                case cmTypeReact:// thả biểu cảm
                    self.rederReactComment(res);
                    break;
                default:
                    break;
            }
        });
    }

    renderNewComment(res, elemRender) {
        var btnReply = '';
        var btnDelEdit = '';
        var commentMedisa = '';
        var listReactIcon = '';

        //render nút trả lời
        if (res.commentUser != userLogin.id) {
            btnReply = '<div>' +
                '<span class="btn-action user-reply" data-userid="' + res.commentUser + '" data-user="' + res.userName + '">Trả lời</span> ' +
                '</div>'
        }
        //render sửa , xóa
        if (res.commentUser == userLogin.id) {
            btnDelEdit = '<div>' +
                '<span data-comment="' + res.commentId + '" class="btn-action btn-edit-comment">Sửa</span>' +
                '</div>' +
                '<div>' +
                '<span data-route="' + commentDeleteUrl.replace(':id', res.commentId) + '" class="btn-action btn-del-comment">Xóa</span>' +
                '</div>'
        }

        //render dánh sách file
        if (res.commentMedias.length > 0) {
            var listMeida = '';
            $.each(res.commentMedias, function (index, value) {
                var type = value.name.split('.').pop().toLowerCase();
                var valuePath = value.path;
                if (fileTypePath[type]) valuePath = baseUrl + fileTypePath[type];

                listMeida += '<div class="card-file-info">' +
                    '<div class="image-file">' +
                    '<a download href="' + value.path + '" target="_blank"><img style="width: 100%" src="' + valuePath + '" alt=""></a>' +
                    '</div>' +
                    '<div class="name-file">' + value.name + '</div>' +
                    '</div>'
            })

            commentMedisa = '<div>' +
                '<span class="comment-file-content">Tài liệu<i class="bi-caret-up-fill"></i></span>' +
                '<div class="comment-files-list" style="display: none">' +
                '<div style="display: flex;flex-wrap: wrap;">' +
                listMeida +

                '</div>' +
                '</div>' +
                '</div>'
        }

        //render react icons
        $.each(iconReactComment, function (key, val) {
            listReactIcon += '<img class="icon-reaction" data-comment="' + res.commentId + '" data-react="' + key + '" src="' + baseUrl + '/' + val.url + '" data-text="' + val.text + '" alt="">';
        })

        //render comment
        elemRender.append(
            '<div class="comment-box-list comment-' + res.commentId + '" id="comment-' + res.commentId + '">' +
            '<div class="user-comment">' +
            '<div class="row">' +
            '<div class="col-1">' +
            '<div class="user-avatar propose__box-avatar rounded-circle">' +
            '<img onerror="this.src=' + errorDefaultAva + '" class="w-100 h-100 rounded-circle object-fit" src="' + res.userAvatar + '" alt="">' +
            '</div>' +
            '</div>' +
            '<div class="col">' +
            '<div class="comment-content">' +
            '<div class="comment-reated" data-comment="' + res.commentId + '"></div>' +
            '<div class="modal-user-react">' +
            '<span class="d-inline-block tab-container-react-list" >' +
            '<ul id="ul-tab" class="nav nav-tabs nav-list-emoji" style="background-color: #ffffff; border: 0; white-space: nowrap; flex-flow: nowrap;border-bottom:1px solid #e9ecef" role="tablist">' +
            '</ul>' +
            '</span>' +
            '<div class="tab-content"  style="padding: 5px;max-height: 190px;overflow: scroll; overflow-x: hidden;">' +
            '</div>' +
            '</div>' +
            '<div class="user-info">' +
            '<span class="user-name popup-user">' + res.userName + '</span> <span class="comment-created">' + res.commentCreated + '</span>' +
            '</div> ' +
            '<div class="descipt">' +
            res.commentContent +
            commentMedisa +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="action">' +
            '<div>' +
            '<span class="btn-action btn-like-action">' +
            '<span class="icon-reaction" data-comment="' + res.commentId + '" data-react="' + cmReactTypeLike + '" data-text="Thích"> Thích </span>' +
            '</span>' +
            '<div class="react-emoji-box">' +
            '<div class="list-react-emoji">' +
            listReactIcon +
            '</div>' +
            '</div>' +
            '</div>' +
            btnReply +
            btnDelEdit +
            '</div>' +

            '<div class="edit-comment-box">' +

            '</div>' +

            '</div>'
        );
    }

    renderUpdateComment(res) {
        var element = $('.comment-box-list.comment-' + res.commentId + '').find('.descipt');
        var commentMedisa = '';
        //render dánh sách file
        if (res.commentMedias.length > 0) {
            var listMeida = '';
            $.each(res.commentMedias, function (index, value) {
                var type = value.name.split('.').pop().toLowerCase();
                var valuePath = value.path;
                if (fileTypePath[type]) valuePath = baseUrl + fileTypePath[type];
                listMeida += '<div class="card-file-info">' +
                    '<div class="image-file">' +
                    '<a download href="' + value.path + '" target="_blank"><img style="width: 100%" src="' + valuePath + '" alt=""></a>' +
                    '</div>' +
                    '<div class="name-file">' + value.name + '</div>' +
                    '</div>'
            })

            commentMedisa = '<div>' +
                '<span class="comment-file-content">Tài liệu<i class="bi-caret-up-fill"></i></span>' +
                '<div class="comment-files-list" style="display: none">' +
                '<div style="display: flex;flex-wrap: wrap;">' +
                listMeida +

                '</div>' +
                '</div>' +
                '</div>'
        }

        element.html(
            res.commentContent +
            commentMedisa
        )
    }

    renderDeleteComment(res) {
        $('.comment-box-list.comment-' + res.commentId + '').remove();
    }

    rederReactComment(res) {
        var element = $('.comment-box-list.comment-' + res.commentId + '').find('.comment-reated');
        var reactIcons = '';

        $.each(res.commentReacts, function (key, value) {
            reactIcons += '<span class="icon-emoji-' + (key + 1) + '"> <img class="" src="' + baseUrl + '/' + iconReactComment[value.react_type].url + '" alt=""></img> </span>';
        })
        if (res.commentReacts.length > 0) {
            reactIcons += '<span class="total-react">' + res.totalCommentReacts + '</span>';
        }
        element.html(reactIcons);

        if (userLogin.id == res.userId) {
            var elementIcon = $('.comment-box-list.comment-' + res.commentId + '').find('.btn-action.btn-like-action');
            if (res.reactionAction == 1) {
                elementIcon.html('<span class="icon-reaction" data-comment="' + res.commentId + '" data-react="' + cmReactTypeLike + '" data-text="Thích"> Thích </span>');
            } else {
                elementIcon.html(
                    '<span class="icon-reaction text-react" data-comment="' + res.commentId + '" data-react="' + res.reactType + '" data-text="' + res.reactText + '">' +
                    res.reactText +
                    '</span>'
                )

            }
        }

    }

    //hành động sau khi xử lý xong
    separateAction(commentType, actionType) {
        // tăng tổng số bình luận sau khi comment
        var comments = $('a[href="#comments"] .totalComment');

        if (actionType == cmTypeCreate) {
            comments.html(parseInt(comments.html()) + 1);
        }
        if (actionType == cmTypeDel) { //trừ tổng bình luận đi
            comments.html(parseInt(comments.html()) - 1);
        }
        switch (commentType) {
            case cmProposalType:
                // var comments = $('a[href="#comments"] .totalComment');
                var histories = $('a[href="#histories"] .totalHistory');
                if (actionType == cmTypeCreate) {
                    // comments.html(parseInt(comments.html()) + 1);
                    histories.html(parseInt(histories.html()) + 1);
                }
                break;
            case cmProjectType:
                var comments = $('a[href="#comments-tab-content"] .totalComments');
                var histories = $('a[href="#histories-tab-content"] .totalHistories');
                if (actionType == cmTypeCreate) {
                    // comments.html(parseInt(comments.html()) + 1);
                    histories.html(parseInt(histories.html()) + 1);
                }
                break;
            case cmRecruitmentTargetType:
                break;
            case cmRecruitmentProcessType:
                break;
            default:
                break;
        }
    }
}

clsComment = new comment();
