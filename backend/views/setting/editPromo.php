<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\bootstrap4\ActiveForm;
?>

<div class="modal fade editPromo" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <?php echo Html::Tag('h5', 'Редактировать купон для <span class="editCustomerName">всех</span>', ['class' => 'modal-title']); ?>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php 
                    $form = ActiveForm::begin(['options' => ['id' => 'editPromo'], 'enableAjaxValidation' => true, 'validationUrl' => Url::to(['setting/promoeditvalidation'])]);

                    echo $form->field($editModel, 'promoId')->hiddenInput(['class' => 'promoId'])->label(false);
            
                    echo $form->field($editModel, 'promoCode')->textInput(['class' => 'form-control promoCode']);

                    echo $form->field($editModel, 'noCheckPromoCode')->hiddenInput(['class' => 'form-control promoCode'])->label(false);

                    echo $form->field($editModel, 'promoExpire')->textInput(['class' => 'form-control promoExpire promo-picker datepicker-here', 'placeholder ' => 'dd.mm.YYYY']);

                    //echo $form->field($editModel, 'promoUser')->textInput(['class' => 'form-control promoSelectUser promoUser']);

                    echo $form->field($editModel, 'promoDiscount')->textInput(['class' => 'form-control promoDiscount']);

                    echo $form->field($editModel, 'promoApplying', ['options' => ['class' => 'custom-switch']])->checkbox(['class' => 'promoApplying custom-control-input']);
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