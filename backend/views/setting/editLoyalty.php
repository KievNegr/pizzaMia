<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\bootstrap4\ActiveForm;
?>

<div class="modal fade editLoyalty" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <?php echo Html::Tag('h5', 'Редактирование скидки', ['class' => 'modal-title']); ?>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php 
                    $form = ActiveForm::begin(['options' => ['id' => 'editLoyalty']/*, 'enableAjaxValidation' => true, 'validationUrl' => Url::to(['delivery/validateedit'])*/]);
            
                    echo $form->field($editModel, 'loyaltyId')->hiddenInput(['class' => 'editIdLoyalty'])->label(false);

                    echo $form->field($editModel, 'fromSum')->textInput(['class' => 'form-control editFromSumLoyalty']);

                    echo $form->field($editModel, 'toSum')->textInput(['class' => 'form-control editToSumLoyalty']);

                    echo $form->field($editModel, 'discount')->textInput(['class' => 'form-control editDiscountLoyalty']);
                ?>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <?php 
                        echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'editLoyaltyButton']);
                    
                        ActiveForm::end(); 
                    ?>
                </div>
            </div>    
        </div>
    </div>
</div>