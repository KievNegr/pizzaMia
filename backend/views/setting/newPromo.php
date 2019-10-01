<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\bootstrap4\ActiveForm;
?>

<div class="modal fade newPromo" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <?php echo Html::Tag('h5', 'Новый купон для <span class="setCustomerName">всех</span>', ['class' => 'modal-title']); ?>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php 
                    $form = ActiveForm::begin(['options' => ['id' => 'newPromo'], 'enableAjaxValidation' => true, 'validationUrl' => Url::to(['setting/promonewvalidation'])]);
            
                    echo $form->field($newModel, 'promoCode')->textInput();

                    echo $form->field($newModel, 'promoExpire')->textInput(['placeholder ' => 'mm/dd/YYYY', 'class' => 'form-control promo-picker datepicker-here']);

                    echo '<label for="newpromoform-promoexpire">Для клиента (ввести номер телефона)</label>';
                    echo Html::textInput(null, null, ['class' => 'form-control getCustomer phone_mask']);
                    echo $form->field($newModel, 'promoUser')->hiddenInput(['value' => '0', 'class' => 'form-control setCustomerId'])->label(false);

                    echo $form->field($newModel, 'promoDiscount')->textInput();

                    echo $form->field($newModel, 'applying', ['options' => ['class' => 'custom-switch']])->checkbox();
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