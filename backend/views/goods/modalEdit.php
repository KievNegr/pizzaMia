<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\bootstrap4\ActiveForm;
?>

<div class="modal fade editItem" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <?php echo Html::Tag('h5', 'Редактирование товара', ['class' => 'modal-title', 'id' => 'editPageModalLabel']); ?>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-8">
                            <?php 
                                $form = ActiveForm::begin(['options' => ['id' => 'editItem'], 'enableAjaxValidation' => true, 'validationUrl' => Url::to(['goods/validateedit'])]);
                                
                                echo $form->field($editModel, 'editItemId')->hiddenInput(['id' => 'editItemId'])->label(false);

                                echo $form->field($editModel, 'editItemTitle')->textInput(['class' => 'makeseo form-control', 'id' => 'editItemTitle']);

                                echo $form->field($editModel, 'editItemSef')->textInput(['class' => 'setseo form-control', 'id' => 'editItemSef']);

                                echo $form->field($editModel, 'editNoCheckItemSef')->hiddenInput(['id' => 'editNoCheckItemSef'])->label(false);

                                echo $form->field($editModel, 'editItemDescription')->textInput(['id' => 'editItemDescription']);

                                echo $form->field($editModel, 'editItemText')->textarea(['rows' => 5, 'id' => 'editText']);

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

                                    echo $form->field($editModel, 'editItemCategory')->dropDownList($listCategories, ['prompt' => 'Выбор категории...', 'class' => 'form-control getOptionsAjax', 'id' => 'editCategory']);
                                    echo $form->field($editModel, 'editCategoryNoCheck')->hiddenInput(['id' => 'editCategoryNoCheck'])->label(false);
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

                                    echo $form->field($editModel, 'editItemIngredient')->checkboxList($listIngredients, 
                                        [   
                                            'class' => 'include-ingredient',
                                            'item' => 
                                                function($index, $label, $name, $checked, $value) {
                                                    $return = '<div><input type="checkbox" name="' . $name . '" value="' . $value . '" id="editCheck' . $value . '" class="checkIngredient">';
                                                    $return .= '<label for="editCheck' . $value . '" >';
                                                    $return .= $label;
                                                    $return .= '</label></div>';

                                                    return $return;
                                                }
                                        ]
                                    );
                                }

                                echo $form->field($editModel, 'editItemNew', ['options' => ['class' => 'custom-switch']])->checkbox(['class' => 'checkNew custom-control-input']);

                                echo $form->field($editModel, 'editItemPopular', ['options' => ['class' => 'custom-switch']])->checkbox(['class' => 'checkPop custom-control-input']);

                                echo $form->field($editModel, 'editItemOnline', ['options' => ['class' => 'custom-switch']])->checkbox(['class' => 'checkOnline custom-control-input']);

                                echo $form->field($editModel, 'editItemImage')->hiddenInput(['class' => 'form-control listImage'])->label(false);

                                echo $form->field($editModel, 'editItemImageNoCheck')->hiddenInput(['class' => 'listImageNoCheck'])->label(false);

                                echo $form->field($editModel, 'editItemMainImage')->hiddenInput(['class' => 'form-control editMainImage'])->label(false);         
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
                                $imgForm = ActiveForm::begin(['options' => ['class' => 'sendEditImage']]);

                                   echo $imgForm->field($editImagesModel, 'image', ['options' => ['class' => 'custom-file'], 'template' => '{input}{label}{error}'])->fileInput(['class' => 'custom-file-input addEditImage'])->label('Выбрать изображения', ['class' => 'custom-file-label', 'data-browse' => 'png, jpg']);

                                ActiveForm::end();
                            ?>
                            <div class="gotImages editImages"></div>
                            <div class="spinner-border text-info" role="status"></div>
                        </div>
                    </div>
                </div>
            </div>    
        </div>
    </div>
</div>