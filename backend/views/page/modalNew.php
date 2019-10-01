<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\bootstrap4\ActiveForm;
?>

<div class="modal fade newPageModalXl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <?php echo Html::Tag('h5', 'Новая страница', ['class' => 'modal-title', 'id' => 'newPageModalLabel']); ?>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-8">
                            <?php 
                                $form = ActiveForm::begin(['options' => ['id' => 'newPage'], 'enableAjaxValidation' => true, 'validationUrl' => Url::to(['page/validatenew'])]);
                        
                                echo $form->field($newPageModel, 'newPageTitle')->textInput(['class' => 'makeseo form-control', 'id' => 'previewTitle']);

                                echo $form->field($newPageModel, 'newPageSef')->textInput(['class' => 'setseo form-control']);

                                echo $form->field($newPageModel, 'newPageDescription')->textInput();

                                echo $form->field($newPageModel, 'newPageText')->textarea(['rows' => 6, 'class' => 'pageText form-control']);

                                echo $form->field($newPageModel, 'newPageShowHeader', ['options' => ['class' => 'custom-switch']])->checkbox();

                                echo $form->field($newPageModel, 'newPageShowFooter', ['options' => ['class' => 'custom-switch']])->checkbox();
                                
                                echo $form->field($newPageModel, 'newPageImage')->hiddenInput(['class' => 'listImage'])->label(false); 
                            ?>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <?php 
                                    echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'addNewPageButton']);
                                
                                    ActiveForm::end(); 
                                ?>
                            </div>
                        </div>
                        
                       
                        <div class="col-4">
                            <?php
                                $imgForm = ActiveForm::begin(['options' => ['class' => 'sendNewImage']]);

                                   echo $imgForm->field($pageImagesModel, 'image[]', ['options' => ['class' => 'custom-file'], 'template' => '{input}{label}{error}'])->fileInput(['class' => 'custom-file-input addNewImage', 'multiple' => true])->label('Выбрать изображение', ['class' => 'custom-file-label', 'data-browse' => 'png, jpg']);

                                ActiveForm::end();
                            ?>
                            <div class="gotImages newImages"></div>
                            <div class="spinner-border text-info" role="status"></div>
                        </div>
                    </div>
                </div>
            </div>    
        </div>
    </div>
</div>