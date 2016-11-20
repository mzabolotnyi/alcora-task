<?php

/* @var $model app\models\Request */

use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="row">
    <div class="col-sm-6 m-t-25">
        <div class="form-group">

            <span class="label-addon fa fa-camera"></span>
            <label class="control-label">Добавить фото (до 5 шт.)</label>
            <span id="loader-upload" class="fa fa-sun-o icon-spin hide"></span>

            <?= Html::fileInput(false, '', [
                'multiple' => true,
                'accept' => 'image/*',
                'id' => 'input-upload',
            ]) ?>

            <?= Html::button("<span class='fa fa-plus'></span>Загрузить", [
                'data-input' => 'input-upload',
                'class' => 'btn btn-sm btn-upload pull-right',
            ])
            ?>

            <p class="help-block help-block-error"></p>

        </div>
    </div>
    <div class="col-sm-6">
        <div id="img-container">
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function () {

        var Photos = {
            count: 0,
            maxCount: 5,
            getLeftCount: function () {
                return this.maxCount - this.count;
            },
            prepareData: function (images) {

                var formData = new FormData();

                $.each(images, function (key, value) {
                    formData.append('UploadForm[imageFiles][]', value);
                });

                return formData;
            },
            upload: function (images) {

                var _this = this;
                var countImages = images.length;
                var countTotal = _this.count + countImages;

                if (countImages == 0) {
                    return;
                }

                if (countTotal > _this.maxCount) {
                    alert("Нельзя загружать более " + _this.maxCount + " фото! Осталось: " + _this.getLeftCount());
                    return;
                }

                _this.count += countImages;
                _this.loader.show();

                $.ajax({
                    url: "<?=Url::toRoute(['upload-files'], true)?>",
                    type: "POST",
                    data: _this.prepareData(images),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        _this.loader.hide();
                        $.each(data, function (key, value) {
                            _this.appendPreview(value);
                        });
                    },
                    error: function (data) {
                        _this.loader.hide();
                        _this.count -= countImages;
                        alert('При загрузке файлов возникла ошибка. Попробуйте еще раз');
                    }
                });
            },
            appendPreview: function (url) {
                $('#img-container').append(this.getPhotoHtmlTemplate(url));
            },
            getPhotoHtmlTemplate: function (url) {
                return '<div class="img-preview"><a href="' + url + '">' +
                    '<img src="' + url + '" alt=""></img></a>' +
                    '<span class="img-preview-delete"></span>' +
                    '<input type="hidden" name="photos[]" value="' + url + '"/></div>';
            },
            loader: {
                show: function () {
                    $('#loader-upload').removeClass('hide');
                },
                hide: function () {
                    $('#loader-upload').addClass('hide');
                }
            }
        };

        $('.btn-upload').click(function () {

            if (Photos.count == Photos.maxCount) {
                alert("Нельзя загружать более " + Photos.maxCount + " фото!");
                return;
            }

            var inputId = $(this).data('input');

            if (inputId) {
                $('#' + inputId).click();
            }
        });

        $('#input-upload').change(function () {
            Photos.upload(this.files);
        });

        $('body').on('click', '.img-preview-delete', function (e) {

            var imgPreview = $(this).parent();
            var url = $(imgPreview).find('a').attr('href');

            if (url) {

                var formData = new FormData();
                formData.append('url', url);

                $.ajax({
                    url: "<?=Url::toRoute(['delete-file'], true)?>",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false
                });
            }

            imgPreview.remove();
            Photos.count--;
        });

        $('#img-container').magnificPopup({
            delegate: 'a',
            type: 'image',
            gallery: {
                enabled: true
            }
        });
    });
</script>