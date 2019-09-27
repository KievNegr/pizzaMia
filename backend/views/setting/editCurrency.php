<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\bootstrap4\ActiveForm;
?>

<div class="modal fade editCurrency" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <?php echo Html::Tag('h5', 'Редактирование валюты', ['class' => 'modal-title']); ?>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php 
                    $form = ActiveForm::begin(['options' => ['id' => 'editCurrency'], 'enableAjaxValidation' => true, 'validationUrl' => Url::to(['setting/validateeditcurrency'])]);
            
                    echo $form->field($editModel, 'currencyId')->hiddenInput(['class' => 'editCurrencyId'])->label(false);

                    echo $form->field($editModel, 'currencyName')->textInput(['class' => 'editCurrencyName form-control']);

                    echo $form->field($editModel, 'currencyNoCheckName')->hiddenInput(['class' => 'nameNoCheckEditCurrency'])->label(false);

                    echo $form->field($editModel, 'currencyDefaultView', ['options' => ['class' => 'custom-switch']])->checkbox(['class' => 'editCurrencyDefault custom-control-input']);
                ?>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <?php 
                        echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary']);
                    
                        ActiveForm::end(); 
                    ?>
                </div>
            </div>    
        </div>
    </div>
</div>