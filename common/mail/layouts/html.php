<?php
use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
    <style type="text/css">
		.wrapper {
			width: 80%;
		    margin: 0 auto;
		    padding: 0;
		    border: 1px solid #EEE;
		}

		header {
			width: 100%;
		    height: 60px;
		    background-color: #8BC34A;
		    display: flex;
		    justify-content: space-between;
		    align-items: center;
		}

		header img {
			max-width: 100%;
		    height: 50px;
		    margin: 0 0 0 5px;
		}

		header ul {
			display: flex;
			align-items: center;
		}

		header ul li {
			list-style-type: none;
    		margin: 0 30px 0 0;
		}

		header ul li a {
			font-size: 1.25em;
			font-family: Arial;
		}

		h3, h4 {
			font-family: Arial;
    		margin: 5px 0 0 10px;
		}

		p {
			font-size: .9125em;
			font-family: Arial;
			margin: 5px 0 0 10px;
		}

		a {
    		font-size: 1em;
		}

		table, th, td {
			width: 100%;
		    font-family: Arial;
		    border: 1px solid #eee;
		    border-collapse: collapse;
		    padding: 5px;
		    text-align: left;
		    margin: 10px 0 0 0;
		}

		.info {
			margin: 15px 0 0 0;
			background-color: #fbfbfb;
			padding: 10px;
		}

		.info label {
			font-family: Arial;
			font-size: .875em;
			color: #607d8b;
			width: 100%;
		}

		.info p {
			font-family: Arial;
		    line-height: 18px;
		    margin: 0 10px 10px 10px;
		    padding: 0 0 3px 0;
		    border-bottom: 1px solid #EEE;
		}

	</style>
    <?php $this->head() ?>
</head>
<body>
	<?php $this->beginBody() ?>
    <div class="wrapper">
    	<?= $content ?>
	</div>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
