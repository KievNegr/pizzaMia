<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\bootstrap4\ActiveForm;
?>

<div class="modal fade editPageModalXl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <?php echo Html::Tag('h5', 'Новая страница', ['class' => 'modal-title', 'id' => 'editPageModalLabel']); ?>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-8">
                            <?php 
                                $form = ActiveForm::begin(['options' => ['id' => 'editPage'], 'enableAjaxValidation' => true, 'validationUrl' => Url::to(['page/validateedit'])]);
                            
                                echo $form->field($editPageModel, 'editPageId')->hiddenInput(['id' => 'editPageId'])->label(false);

                                echo $form->field($editPageModel, 'editPageTitle')->textInput(['class' => 'makeseo form-control', 'id' => 'editPageTitle']);

                                echo $form->field($editPageModel, 'editPageSef')->textInput(['class' => 'setseo form-control', 'id' => 'editPageSef']);

                                echo $form->field($editPageModel, 'editPageNoCheckSef')->hiddenInput(['id' => 'editPageNoCheckSef'])->label(false);

                                echo $form->field($editPageModel, 'editPageDescription')->textInput(['id' => 'editPageDescription']);

                                echo $form->field($editPageModel, 'editPageText')->textarea(['rows' => 6, 'class' => 'pageText form-control']);

                                echo $form->field($editPageModel, 'editPageShowHeader', ['options' => ['class' => 'custom-switch']])->checkbox(['class' => 'checkShowheader custom-control-input']);

                                echo $form->field($editPageModel, 'editPageShowFooter', ['options' => ['class' => 'custom-switch']])->checkbox(['class' => 'checkShowfooter custom-control-input']);

                                echo $form->field($editPageModel, 'editPageImage')->hiddenInput(['class' => 'listImage'])->label(false);

                                echo $form->field($editPageModel, 'editInsertedImages')->hiddenInput(['class' => 'listImageNoCheck'])->label(false); 
                            ?>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <?php 
                                    echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'editPageButton']);
                                    
                                    ActiveForm::end();
                                ?>
                            </div>
                        </div>
                        
                       
                        <div class="col-4">
                            <?php
                                $imgEditForm = ActiveForm::begin(['options' => ['class' => 'sendEditImage']]);

                                   echo $imgEditForm->field($editPageImagesModel, 'image[]', ['options' => ['class' => 'custom-file'], 'template' => '{input}{label}{error}'])->fileInput(['class' => 'custom-file-input addEditImage', 'multiple' => true])->label('Выбрать изображение', ['class' => 'custom-file-label', 'data-browse' => 'png, jpg']);

                                ActiveForm::end();
                            ?>
                            <div class="gotImages editImages"></div>
                            <div class="spinner-border text-info" role="status"></div>
                        </div>
                    </div>
                </div>
            </div>    
        </div>
    </div>
</div>