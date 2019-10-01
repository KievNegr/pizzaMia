<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\bootstrap4\ActiveForm;
?>
<main>
    <div class="header">
        <h4>Привет, <?php echo Yii::$app->user->identity->username; ?></h4>
        <div class="profile-links">
            <a href="<?php echo Url::to(['customer/index']); ?>" class="active">Мои настройки</a>
            <a href="<?php echo Url::to(['myorder/index']); ?>">Мои заказы</a>
        </div>
    </div>
    
    <div class="profile">
        <div class="hello">
            <?php
                $avatar = 'default_avatar.png';
                if(!empty(Yii::$app->user->identity->image))
                    $avatar = Yii::$app->user->identity->image;
            ?>                        
            <div class="avatar-area">
                <?php
                    $size = getimagesize('images/avatar/' . $avatar);
                    $class = substr($avatar, 0, -4);

                    echo '<div class="image ' . $class . '" style="background-image: url(images/avatar/' . $avatar . '?' . rand() . ');">';
                    if(Yii::$app->user->identity->image)
                    {
                        echo '<div class="imageRun" nameImage="' . $avatar . '">
                            <i class="fas fa-crop-alt imageCrop" particularclass="' . $class . '" address="images/avatar/' . $avatar . '" imageWidth="' . $size[0] . '" imageHeight="' . $size[1] . '"  title="Кадрировать изображение"></i>
                        </div>';
                    }
                    echo '</div>';
                ?>
            </div>
            <!-- <img src="<?php echo Url::to('images/avatar/' . $avatar); ?>" class="setAvatar" /> -->
            <div>
                <p><?php echo Yii::$app->user->identity->username; ?> / <span>Настройка профиля</span></p>
                <small>Актуализируйте данные для более быстрого оформления заказа</small>
            </div>
        </div>
        <div class="spinner-border text-dark" role="status"></div>
        <?php
                $imgForm = ActiveForm::begin(['options' => ['class' => 'sendImage']]);
                echo $imgForm->field($image, 'image', ['options' => ['class' => 'custom-file'], 'template' => '{input}{label}{error}'])->fileInput(['class' => 'custom-file-input addImage'])->label('Выбрать изображение', ['class' => 'custom-file-label', 'data-browse' => 'png, jpg']);
                ActiveForm::end();
            ?>

        <?php 
            if(yii::$app->session->getFlash('success'))
            {
                yii\bootstrap4\Alert::begin(['options' => ['class' => 'alert alert-success']]);
                    echo yii::$app->session->getFlash('success');
                yii\bootstrap4\Alert::end();
            }

            $form = ActiveForm::begin(['options' => ['id' => 'edit-customer'], 'enableAjaxValidation' => true, 'validationUrl' => Url::to(['customer/validate'])]); 
            echo $form->field($editModel, 'email')->textInput(['value' => Yii::$app->user->identity->order_email]);
            echo $form->field($editModel, 'phone')->textInput(['value' => Yii::$app->user->identity->phone, 'class' => 'mask-phone form-control']);
            echo $form->field($editModel, 'anotherPhone')->textInput(['value' => Yii::$app->user->identity->another_phone, 'class' => 'mask-phone form-control']);
            echo $form->field($editModel, 'address')->textarea(['value' => Yii::$app->user->identity->address]);
            echo $form->field($editModel, 'additional')->textarea(['value' => Yii::$app->user->identity->additional]);
            
            echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary']);
            ActiveForm::end(); 
        ?>
    </div>
    <div class="aaa"></div>
</main>
<?php echo $this->render('makeAvatar'); ?>