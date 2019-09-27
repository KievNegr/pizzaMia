<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\bootstrap4\ActiveForm;

    $this->registerJsFile('@web/js/editorderlist.js', ['depends' => 'yii\web\JqueryAsset']);
?>

<div class="modal fade editOrder" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable"> <!--  -->
        <div class="modal-content">
            <div class="modal-header">
                <?php echo Html::Tag('h5', 'Редактировать заказ', ['class' => 'modal-title']); ?>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php 
                    $form = ActiveForm::begin(['options' => ['id' => 'editOrder'], 'enableAjaxValidation' => true, 'validationUrl' => Url::to(['order/validate'])]);
                ?>
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <?php 
                                echo $form->field($editModel, 'orderId')->hiddenInput(['class' => 'orderId'])->label(false);
                                echo $form->field($editModel, 'orderCustomerId')->hiddenInput(['class' => 'editOrderCustomerId setCustomerId'])->label(false);

                                echo $form->field($editModel, 'orderPhone')->textInput(['class' => 'editOrderPhone phone_mask form-control form-control-sm']);

                                echo $form->field($editModel, 'orderAnotherPhone')->textInput(['class' => 'editOrderAnotherPhone phone_mask form-control form-control-sm']);

                                echo $form->field($editModel, 'noCheckOrderAnotherPhone')->hiddenInput(['class' => 'noCheckEditOrderAnotherPhone form-control'])->label(false);

                                echo $form->field($editModel, 'orderCustomer')->textInput(['class' => 'editOrderCustomer setCustomerName form-control form-control-sm']);

                                echo $form->field($editModel, 'orderAddress')->textarea(['rows' => 2, 'class' => 'editOrderAddress form-control setCustomerAddress']);

                                echo $form->field($editModel, 'orderAdditions')->textarea(['rows' => 2, 'class' => 'editOrderAdditions form-control setCustomerAdditions']);

                                echo $form->field($editModel, 'orderDelivery')->dropDownList($deliveryList, ['class' => 'editOrderDelivery form-control form-control-sm']);

                                echo $form->field($editModel, 'orderPay')->dropDownList($payList, ['class' => 'editOrderPay form-control form-control-sm']);
                            ?>
                        </div>
                        <div class="col">
                            <div class="editGetOrderList"></div>
                            <?php
                                echo $form->field($editModel, 'orderList')->hiddenInput(['class' => 'editOrderList'])->label(false);

                                echo Html::button('Добавить позицию к заказу', ['class' => 'btn btn-success btn-sm', 'data-toggle' => 'modal', 'data-target' => '.editNewItem', 'style' => 'margin-bottom: 10px;']);
                            ?>
                        </div>
                        <div class="col">
                            <?php
                                echo $form->field($editModel, 'orderDate')->textInput(['placeholder ' => 'Дата доставки', 'class' => 'form-control order-picker datepicker-here form-control-sm editOrderDate', 'data-timepicker' => 'true'])->label(false);

                                echo $form->field($editModel, 'orderDiscount')->textInput(['class' => 'editOrderDiscount form-control form-control-sm'])->label('Предоставить скидку, % <span class="orderDiscountName"></span>');

                                echo $form->field($editModel, 'orderDiscountId')->hiddenInput(['class' => 'orderDiscountId'])->label(false);
                            ?>
                            <div class="editOrderCoupons"></div>
                            <?php
                                echo $form->field($editModel, 'orderStatus')->radioList($orderStatus, ['class' => 'edit-status status-flex'])->label(false);

                                echo $form->field($editModel, 'couponId')->hiddenInput(['class' => 'editCouponId'])->label(false);
                            ?>
                            <p class="order-delivery">Доставка: <span class="editDeliveryPrice"></span> <?php echo $currency['name']; ?></p>
                            <p class="order-value">Заказ на сумму: <span class="editAllOrderPrice"></span> <?php echo $currency['name']; ?></p>
                            <p class="order-pay">К оплате: <span class="editForPay"></span> <?php echo $currency['name']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <?php 
                        echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary saveEditOrder']);
                    ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>    
        </div>
    </div>
</div>