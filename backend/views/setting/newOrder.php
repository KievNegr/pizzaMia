<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\bootstrap4\ActiveForm;
?>

<div class="modal fade newOrder" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header setBackground">
                <?php echo Html::Tag('h5', 'Новое состояние заказа', ['class' => 'modal-title']); ?>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php 
                    $form = ActiveForm::begin(['options' => ['id' => 'newOrder']]);
            
                    echo $form->field($newModel, 'orderName')->textInput();

                    echo $form->field($newModel, 'orderColor')->dropDownList($color, ['class' => 'form-control getBackground', 'prompt' => 'Выбор цвета...']);

                    echo $form->field($newModel, 'setRatio', ['options' => ['class' => 'custom-switch']])->checkbox();

                    echo $form->field($newModel, 'orderCancell', ['options' => ['class' => 'custom-switch']])->checkbox();

                    echo $form->field($newModel, 'orderEarned', ['options' => ['class' => 'custom-switch']])->checkbox();

                    echo $form->field($newModel, 'orderExpected', ['options' => ['class' => 'custom-switch']])->checkbox();

                    echo $form->field($newModel, 'orderHoped', ['options' => ['class' => 'custom-switch']])->checkbox();
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