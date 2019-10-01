<?php
	use yii\helpers\Html;
?>

<div class="modal fade newItem" id="newItem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<?php echo Html::Tag('h5', 'Новая позиция', ['class' => 'modal-title', 'id' => 'exampleModalLabel']); ?>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">			
				<?php
					echo Html::dropDownList('goods', null, $listCategories, ['prompt' => 'Выбор позиции...', 'class' => 'form-control getItem']);
                ?>
                <?php foreach($goodsList as $item): ?>
					<div class="card item_<?php echo $item['id']; ?>">
						<img src="<?php echo $pathToItemImage . $item['images'][0]['name']; ?>" class="card-img-top img<?php echo $item['id']; ?>">
						<div class="card-body">
							<h5 class="card-title title<?php echo $item['id']; ?>"><?php echo $item['title']; ?></h5>
						</div>
						<?php
							$options = Array();
							foreach($item['options'] as $option)
							{
	                			$options[$option['id']] = $option['optionName']['title'] . ' <strong>' . $option['price'] . '</strong>';

	                			echo Html::hiddenInput('optionName' . $option['id'], $value = $option['price'], ['optionName' => $option['optionName']['title'], 'class' => 'optionData' . $option['id']]);
	                		}

							echo Html::radioList('options_' . $item['id'] , null, $options, [
                                'item' => function($index, $label, $name, $checked, $value) {

                                    $return = '<label class="btn btn-info form-check-label selectItem" itemId="' . $name . '" optionId="' . $value . '" data-dismiss="modal" aria-label="Close">';
                                    $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" class="form-check-label">';
                                    $return .= ucwords($label);
                                    $return .= '</label>';

                                    return $return;
                                },
                                'class' => 'btn-group btn-group-toggle', 
                                'data-toggle' => 'buttons',
                                'style' => 'width: 100%',
                            ]);
                        ?>
					</div>
                <?php endforeach; ?>
			</div>
		</div>
	</div>
</div>