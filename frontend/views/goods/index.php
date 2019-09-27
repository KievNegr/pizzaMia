<?php
	use yii\helpers\Url;
?>
<main>
	<div class="product">
		<?php 
			$checked = 'checked';
			$count = 1;
			foreach($product['images'] as $gallery): 
		?>
			<input type="radio" id="img<?php echo $count; ?>" name="img" <?php echo $checked; ?> />
			<label for="img<?php echo $count; ?>" class="img-switcher" style="background-image: url('<?php echo Url::to('@web/images/goods/thumbs/' . $gallery['name']); ?>');"></label>
			<div id="img-<?php echo $count; ?>" class="product-img" style="background-image: url('<?php echo Url::to('@web/images/goods/' . $gallery['name']); ?>');"></div>
		<?php 
				$count++;
				$checked = '';
			endforeach;
		?>
		<div class="product-description">
			<h1><?php echo $product['title']; ?></h1>
			<?php
				foreach($product['options'] as $prices)
				{
					if($prices['price'] != 0)
						$value[] = $prices['price'];
				}
			?>
			<span class="cost"><?php echo intval(min($value)) . ' ' . $currency['name']; ?></span>
			<div class="ratio" title="Рейтин устанавливается клиентами после заказа в личном кабинете">
				<?php
					for($i = 0; $i < $ratio; $i++)
					{
						echo '★';
					}

					for($e = 0; $e < 5 - $ratio; $e++)
					{
						echo '☆';
					}
				?>
			</div>
			<?php if($product['deleted'] != 1): ?>
				<?php if($product['online_order'] != 0): ?>
			<div class="order-features">
				<div class="select-product-size">
					<span>Кликните на нужный размер</span>
					<?php 
						$count = 1;
						foreach($product['options'] as $option): 
							if(intval($option['price']) != 0):
					?>
					<input 
						id="tab<?php echo $count; ?>" 
						type="radio" 
						name="tabs" 
						value="<?php echo $option['id']; ?>" 
						class="checked_size" 
						title="<?php echo $option['optionname']['title']; ?>" 
						price="<?php echo intval($option['price']) . ' ' . $currency['name']; ?>"
					/>
					<label for="tab<?php echo $count; ?>" class="size">
						<img src="<?php echo Url::to('@web/images/option/' . $option['optionname']['image']); ?>" alt="<?php echo $option['optionname']['title']; ?>" title="<?php echo $option['optionname']['title']; ?>"/>
						<div class="options">
							<p><?php echo $option['optionname']['title']; ?></p>
							<p><?php echo $option['optionname']['description']; ?></p>
						</div>
					</label>
					<?php 
						$count++;
						endif;
						endforeach; 
					?>
				</div>
				<button class="btn btn-success btn-lg add-item-to-cart" itemid="<?php echo $product['id']; ?>">В корзину</button>
			</div>
			<div class="alert alert-warning alert-dismissible fade show noempty" role="alert">
				Выберите нужный товар для добавления в корзину
			</div>
				<?php else: ?>
					<div class="alert alert-secondary" role="alert">
						Данный товар недоступен для заказа через интернет.
					</div>
				<?php endif;?>
			<?php else: ?>
				<div class="alert alert-secondary" role="alert">
					В настоящий момент товар недоступен к заказу.
				</div>
			<?php endif; ?>
			<p><?php echo $product['text']; ?></p>
			
			<div style="clear: both; height: 20px;"></div>

			<?php if(!empty($ingredients)):?>
			<h5>Ингредиенты</h5>
			<div class="included">
				<?php foreach($ingredients as $ingredient):?>
				<div class="included-item">
					<img src="<?php echo Url::to('@web/images/ingredient/' . $ingredient['image']); ?>" alt="<?php echo $ingredient['name']; ?>" title="<?php echo $ingredient['name']; ?>" />
					<p><?php echo $ingredient['name']; ?></p>
				</div>
				<?php endforeach; ?>
			</div>
			<?php endif;?>
		</div>
	</div>
</main>