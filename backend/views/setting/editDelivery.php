<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\bootstrap4\ActiveForm;
?>

<div class="modal fade editDelivery" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <?php echo Html::Tag('h5', 'Редактирование варианта доставки', ['class' => 'modal-title']); ?>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php 
                    $form = ActiveForm::begin(['options' => ['id' => 'editDelivery'], 'enableAjaxValidation' => true, 'validationUrl' => Url::to(['delivery/validateeditdelivery'])]);
            
                    echo $form->field($editModel, 'deliveryId')->hiddenInput(['class' => 'editIdDelivery'])->label(false);

                    echo $form->field($editModel, 'deliveryName')->textInput(['class' => 'form-control editNameDelivery']);

                    echo $form->field($editModel, 'deliveryNameNoCheck')->hiddenInput(['class' => 'form-control editNameDelivery'])->label(false);

                    echo $form->field($editModel, 'deliveryValue')->textInput(['class' => 'form-control editValueDelivery']);
                ?>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <?php 
                        echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'editDeliveryButton']);
                    
                        ActiveForm::end(); 
                    ?>
                </div>
            </div>    
        </div>
    </div>
</div>