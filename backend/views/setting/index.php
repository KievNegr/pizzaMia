<?php
    use yii\helpers\Url;
    use yii\helpers\Html;
?>
<?php echo Html::tag('h2', 'Настройки сайта'); ?>
<div class="container" style="margin-top: 20px;">
  <div class="row">
    <div class="col-sm">
        <h6>Настройки заказов</h6>
        <div style="height: 20px;"></div>
        <div class="list-group list-group-flush">
            <a href="<?php echo Url::to(['promo']);?>" class="list-group-item list-group-item-action">Промокоды <span class="badge badge-info"><?php echo $promoListCount; ?></span></a>
            <a href="<?php echo Url::to(['delivery']);?>" class="list-group-item list-group-item-action">Настройки доставки <span class="badge badge-info"><?php echo $deliveryListCount; ?></span></a>
            <a href="<?php echo Url::to(['pay']);?>" class="list-group-item list-group-item-action">Настройки оплаты <span class="badge badge-info"><?php echo $payListCount; ?></span></a>
            <a href="<?php echo Url::to(['order']);?>" class="list-group-item list-group-item-action">Состояние заказов <span class="badge badge-info"><?php echo $orderListCount; ?></span></a>
        </div>
    </div>
    <div class="col-sm">
        <h6>Настройки сайта</h6>
        <div style="height: 20px;"></div>
        <div class="list-group list-group-flush">
            <a href="<?php echo Url::to(['sitemap']);?>" class="list-group-item list-group-item-action">Карта сайта (Sitemap)</a>
            <a href="<?php echo Url::to(['clearing']);?>" class="list-group-item list-group-item-action">Оптимизация и очистка сайта</a>
            <a href="<?php echo Url::to(['currency']);?>" class="list-group-item list-group-item-action">Валюта отображения <span class="badge badge-info"><?php echo $currency['name'];?></span></a>
        </div>
    </div>
  </div>
</div>