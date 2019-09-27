<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\bootstrap4\ActiveForm;
?>

<div class="modal fade newItem" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <?php echo Html::Tag('h5', 'Новый товар', ['class' => 'modal-title', 'id' => 'newItemModalLabel']); ?>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-8">
                            <?php 
                                $form = ActiveForm::begin(['options' => ['id' => 'newItem'], 'enableAjaxValidation' => true, 'validationUrl' => Url::to(['goods/validatenew'])]);
                        
                                echo $form->field($newModel, 'newItemTitle')->textInput(['class' => 'makeseo form-control']);

                                echo $form->field($newModel, 'newItemSef')->textInput(['class' => 'setseo form-control']);

                                echo $form->field($newModel, 'newItemDescription')->textInput();

                                echo $form->field($newModel, 'newItemText')->textarea(['rows' => 5, 'id' => 'newPageText']);

                                if($categories)
                                {
                                    $listCategories = Array();
                                    foreach($categories as $category)
                                    {
                                        if($category['parent'] == 0)
                                        {
                                            
                                            foreach($categories as $child)
                                            {
                                                if($child['parent'] == $category['id'])
                                                {
                                                    $listCategories[$category['title']][$child['id']] = $child['title'];
                                                }
                                            }
                                        }
                                    }

                                    echo $form->field($newModel, 'newItemCategory')->dropDownList($listCategories, ['prompt' => 'Выбор категории...', 'class' => 'form-control getOptionsAjax']);
                                }   
                                else
                                {
                                    echo 'Где категории?';
                                }

                                echo '<div class="itemPrices"></div>';
                                
                                if($ingredients)
                                {
                                    foreach($ingredients as $ingredient)
                                    {
                                        $listIngredients[$ingredient['id']] = '<img src="' . $pathIngredientImages . $ingredient['image'] . '" alt="' . $ingredient['name'] . '" title="' . $ingredient['name'] . '" /><p>' . $ingredient['name'] . '</p>';
                                    }

                                    echo $form->field($newModel, 'newItemIngredient')->checkboxList($listIngredients, 
                                        [   
                                            'class' => 'include-ingredient',
                                            'item' => 
                                                function($index, $label, $name, $checked, $value) {
                                                    $return = '<div><input type="checkbox" name="' . $name . '" value="' . $value . '" id="check' . $value . '">';
                                                    $return .= '<label for="check' . $value . '" >';
                                                    $return .= $label;
                                                    $return .= '</label></div>';

                                                    return $return;
                                                }
                                        ]
                                    );
                                }

                                echo $form->field($newModel, 'newItemNew', ['options' => ['class' => 'custom-switch']])->checkbox();

                                echo $form->field($newModel, 'newItemPopular', ['options' => ['class' => 'custom-switch']])->checkbox();

                                echo $form->field($newModel, 'newItemOnline', ['options' => ['class' => 'custom-switch']])->checkbox();

                                echo $form->field($newModel, 'newItemImage')->hiddenInput(['class' => 'form-control listImage'])->label(false);

                                echo $form->field($newModel, 'newItemMainImage')->hiddenInput(['class' => 'form-control mainImage'])->label(false);             
                            ?>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <?php 
                                    echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'addNewPageButton']);
                                
                                    ActiveForm::end(); 
                                ?>
                            </div>
                        </div>
                        
                       
                        <div class="col-4">
                            <?php
                                $imgForm = ActiveForm::begin(['options' => ['class' => 'sendNewImage']]);

                                   echo $imgForm->field($newImagesModel, 'image', ['options' => ['class' => 'custom-file'], 'template' => '{input}{label}{error}'])->fileInput(['class' => 'custom-file-input addNewImage'])->label('Выбрать изображения', ['class' => 'custom-file-label', 'data-browse' => 'png, jpg']);

                                ActiveForm::end();
                            ?>
                            <div class="gotImages newImages"></div>
                            <div class="spinner-border text-info" role="status"></div>
                        </div>
                    </div>
                </div>
            </div>    
        </div>
    </div>
</div>