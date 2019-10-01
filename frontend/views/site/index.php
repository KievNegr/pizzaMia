<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
?>
<main>
    <div class="padding">
        <!-- <ul class="filter">
            <?php
                $active = ['class' => 'active'];
                foreach($listGoods as $subCategory)
                {
                    if($subCategory['parent'] != 0 && !empty($subCategory['goods']))
                    {
                        echo Html::tag('li', Html::a($subCategory['title'], '#' . $subCategory['sef'], $active));
                        $active = null;
                    }
                }
            ?>
        </ul> -->
        <div style="clear: both; height: 30px;"></div>
        <?php
            foreach($listGoods as $category):
                if($category['parent'] != 0 && !empty($category['goods'])):
        ?>
        <h2 id="<?php echo $category['sef']; ?>"><?php echo $category['title']; ?></h2>
        <section>
        <?php 
                    foreach($category['goods'] as $item):
        ?>
                        <div class="item">
                            <?php
                                $style = ['class' => 'hot badge badge-danger'];
                                if($item['popular'] == 1 && $item['new'] == 1)
                                    $style = ['style' => 'margin: 17% 0 0 -45%;', 'class' => 'hot badge badge-danger'];

                                if($item['popular'] == 1)
                                    echo Html::tag('div', 'Топ продаж', $style);

                                if($item['new'] == 1)
                                    echo Html::tag('div', 'Новинка', ['class' => 'new badge badge-warning']);
                            ?>
                            <a href="<?php echo Url::to(['goods/index', 'sef' => $item['sef']]); ?>" class="link-img" style="background-image: url(<?php echo Url::to('@web/images/goods/thumbs/' . $item['image']['name']); ?>)"></a>

                            <div class="select-size">
                                <?php 
                                    $value = Array();
                                    foreach($item['options'] as $option): 
                                        if(substr($option['price'], 0, -3) != 0):
                                ?>
                                    <div class="size">
                                        <img src="<?php echo Url::to('@web/images/option/' . $option['optionname']['image']); ?>" alt="<?php echo $item['title']; ?>" title="<?php echo $item['title']; ?>" />
                                        <p class="item-size">
                                            <?php 
                                                echo $option['optionname']['title'];
                                                echo '<br />';
                                                echo substr($option['price'], 0, -3) . ' ' . $currency['name'];
                                            ?>
                                            </p>
                                        <button class="btn btn-dark fast-value" itemid="<?php echo $item['id']; ?>" optionid="<?php echo $option['id']; ?>"  title="Добавить в корзину <?php echo $item['title']; ?>"><i class="fas fa-cart-arrow-down"></i></button>
                                    </div>
                                <?php
                                        $value[] = substr($option['price'], 0, -3);
                                        endif;
                                    endforeach; ?>
                            </div>

                            <a href="<?php echo Url::to(['goods/index', 'sef' => $item['sef']]); ?>" class="name"><?php echo $item['title']; ?></a>
                            <span><?php echo min($value) . ' ' . $currency['name']; ?></span>
                            <hr />
                        </div>
        <?php
                    endforeach;
        ?>
        </section>
        <?php
                endif;
            endforeach;
        ?>
        
    </div>
</main>