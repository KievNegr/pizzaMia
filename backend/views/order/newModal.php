<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\bootstrap4\ActiveForm;
    use yii\helpers\ArrayHelper;

    $this->registerJsFile('@web/js/neworderlist.js', ['depends' => 'yii\web\JqueryAsset']);
?>

<div class="modal fade newOrder" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <?php echo Html::Tag('h5', 'Новый заказ', ['class' => 'modal-title']); ?>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                    $form = ActiveForm::begin(['options' => ['id' => 'newOrder'], 'enableAjaxValidation' => true, 'validationUrl' => Url::to(['order/validate'])]);
                ?>
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <?php
                                echo $form->field($newModel, 'orderPhone')->textInput(['class' => 'getCustomer phone_mask form-control form-control-sm']);

                                echo $form->field($newModel, 'orderAnotherPhone')->textInput(['class' => 'setCustomerAnotherPhone phone_mask form-control form-control-sm']);
                                
                                echo $form->field($newModel, 'noCheckOrderAnotherPhone')->hiddenInput(['class' => 'noCheckOrderAnotherPhone form-control form-control-sm'])->label(false);

                                echo $form->field($newModel, 'orderCustomer')->textInput(['class' => 'setCustomerName form-control form-control-sm']);

                                echo $form->field($newModel, 'orderCustomerId')->hiddenInput(['class' => 'setCustomerId'])->label(false);

                                echo $form->field($newModel, 'orderEmail')->textInput(['class' => 'setCustomerEmail form-control form-control-sm']);

                                echo $form->field($newModel, 'orderAddress')->textarea(['rows' => 2, 'class' => 'form-control setCustomerAddress form-control-sm']);

                                echo $form->field($newModel, 'orderAdditions')->textarea(['rows' => 2, 'class' => 'form-control setCustomerAdditions form-control-sm']);

                                echo $form->field($newModel, 'orderDelivery')->dropDownList($deliveryList, ['class' => 'getDelivery form-control form-control-sm']);

                                echo $form->field($newModel, 'orderPay')->dropDownList($payList, ['class' => 'form-control form-control-sm']);
                            ?>
                        </div>
                        <div class="col">
                            <div class="getOrderList"></div>
                            <?php
                                echo $form->field($newModel, 'orderList')->hiddenInput(['class' => 'orderList'])->label(false);

                                echo Html::button('Добавить позицию к заказу', ['class' => 'btn btn-success btn-sm', 'data-toggle' => 'modal', 'data-target' => '.newItem', 'style' => 'margin-bottom: 10px;']);
                            ?>
                        </div>
                        <div class="col">
                            <?php
                                echo $form->field($newModel, 'orderDate')->hiddenInput(['placeholder ' => 'Дата доставки', 'class' => 'form-control order-picker datepicker-here form-control-sm', 'data-timepicker' => 'true'])->label(false);

                                echo $form->field($newModel, 'orderDiscount')->textInput(['value' => '0', 'class' => 'orderDiscount form-control form-control-sm']);
                            ?>
                            <div class="orderCoupons"></div>
                            <?php
                                echo $form->field($newModel, 'couponId')->hiddenInput(['value' => 0, 'class' => 'couponId', 'discount="0"'])->label(false);

                                echo $form->field($newModel, 'orderStatus')->radioList($orderStatus, ['class' => 'status-flex'])->label(false);

                            ?>

                            <p class="order-delivery">Доставка: <span class="deliveryPrice">0</span> <?php echo $currency['name']; ?></p>
                            <p class="order-value">Заказ на сумму: <span class="allOrderPrice">0</span> <?php echo $currency['name']; ?></p>
                            <p class="order-pay">К оплате: <span class="forPay">0</span> <?php echo $currency['name']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <?php 
                        echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary saveNewOrder']);
                    ?>
                </div>
                <?php 
                    ActiveForm::end();
                ?>
            </div>
        </div>
    </div>
</div>