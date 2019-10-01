<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\bootstrap4\ActiveForm;
?>

<div class="modal fade editOrder" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header setBackground">
                <?php echo Html::Tag('h5', 'Изменение состояние заказа', ['class' => 'modal-title']); ?>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php 
                    $form = ActiveForm::begin(['options' => ['id' => 'editOrder']]);
                    
                    echo $form->field($editModel, 'orderId')->hiddenInput(['class' => 'orderId'])->label(false);

                    echo $form->field($editModel, 'orderName')->textInput(['class' => 'form-control orderName']);

                    echo $form->field($editModel, 'orderColor')->dropDownList($color, ['class' => 'form-control getBackground orderColor', 'prompt' => 'Выбор цвета...']);

                    echo $form->field($editModel, 'setRatio', ['options' => ['class' => 'custom-switch']])->checkbox(['class' => 'orderRatio custom-control-input']);

                    echo $form->field($editModel, 'orderCancell', ['options' => ['class' => 'custom-switch']])->checkbox(['class' => 'orderCancell custom-control-input']);

                    echo $form->field($editModel, 'orderEarned', ['options' => ['class' => 'custom-switch']])->checkbox(['class' => 'orderEarned custom-control-input']);

                    echo $form->field($editModel, 'orderExpected', ['options' => ['class' => 'custom-switch']])->checkbox(['class' => 'orderExpected custom-control-input']);

                    echo $form->field($editModel, 'orderHoped', ['options' => ['class' => 'custom-switch']])->checkbox(['class' => 'orderHoped custom-control-input']);
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