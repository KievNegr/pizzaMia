<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\bootstrap4\ActiveForm;
?>

<div class="modal fade newLoyalty" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <?php echo Html::Tag('h5', 'Новая скидка', ['class' => 'modal-title']); ?>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php 
                    $form = ActiveForm::begin(['options' => ['id' => 'newLoyalty']/*, 'enableAjaxValidation' => true, 'validationUrl' => Url::to(['delivery/validatenew'])*/]);
            
                    echo $form->field($newModel, 'fromSum')->textInput();

                    echo $form->field($newModel, 'toSum')->textInput();

                    echo $form->field($newModel, 'discount')->textInput();
                ?>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <?php 
                        echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'addNewLoyaltyButton']);
                    
                        ActiveForm::end(); 
                    ?>
                </div>
            </div>    
        </div>
    </div>
</div>