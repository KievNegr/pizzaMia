<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\bootstrap4\ActiveForm;
?>

<div class="modal fade editPay" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <?php echo Html::Tag('h5', 'Новый вариант оплаты', ['class' => 'modal-title']); ?>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php 
                    $form = ActiveForm::begin(['options' => ['id' => 'editPay']]);

                    echo $form->field($editModel, 'payId')->hiddenInput(['class' => 'editIdPay'])->label(false);
            
                    echo $form->field($editModel, 'payName')->textInput(['class' => 'form-control editNamePay']);

                    echo $form->field($editModel, 'payCard', ['options' => ['class' => 'custom-switch']])->checkbox(['class' => 'useCard custom-control-input']);
                ?>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <?php 
                        echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'addNewPageButton']);
                    
                        ActiveForm::end(); 
                    ?>
                </div>
            </div>    
        </div>
    </div>
</div>