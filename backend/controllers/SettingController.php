<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\setting\OrderStatus;
use backend\models\setting\Promo;
use backend\models\setting\NewOrderStatus;
use backend\models\setting\EditOrderStatus;
use backend\models\setting\RunOrderStatus;
use backend\models\setting\NewPromoForm;
use backend\models\setting\EditPromoForm;
use backend\models\setting\RunPromoForm;
use backend\models\setting\AddPayForm;
use backend\models\setting\EditPayForm;
use backend\models\setting\RunPayForm;
use backend\models\setting\Pay;
use backend\models\setting\Delivery;
use backend\models\setting\AddDeliveryForm;
use backend\models\setting\EditDeliveryForm;
use backend\models\setting\RunDeliveryForm;
use backend\models\setting\Currency;
use backend\models\setting\AddCurrencyForm;
use backend\models\setting\RunCurrencyForm;
use backend\models\setting\EditCurrencyForm;
/*---- Clearing ---*/
use backend\models\category\Category;
use backend\models\page\Image;
use backend\models\option\Option;
use backend\models\ingredient\Ingredient;
use backend\models\user\User;

use backend\models\goods\Goods;
use backend\models\page\Page;

use yii\web\Response;
use yii\bootstrap4\ActiveForm;

class SettingController extends Controller 
{
	public function actionIndex()
	{
		if(Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }
        elseif(Yii::$app->user->identity->level == 3)
        {
            Yii::$app->user->logout();
            return $this->goHome();
        }
        elseif(Yii::$app->user->identity->level == 2)
        {
        	return $this->goHome();
        }

		$orderListCount = count(OrderStatus::find()->asArray()->all());
		$promoListCount = count(Promo::find()->asArray()->all());
		$payListCount = count(Pay::find()->asArray()->all());
		$deliveryListCount = count(Delivery::find()->asArray()->all());
		$currency = Currency::find()->where(['default_view' => 1])->asArray()->one();

		return $this->render('index', compact('orderListCount', 'promoListCount', 'payListCount', 'deliveryListCount', 'currency'));
	}

	public function actionOrder()
	{
		if(Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }
        elseif(Yii::$app->user->identity->level == 3)
        {
            Yii::$app->user->logout();
            return $this->goHome();
        }
        elseif(Yii::$app->user->identity->level == 2)
        {
        	return $this->goHome();
        }

		$newModel = new NewOrderStatus;
		$editModel = new EditOrderStatus;
		$deleteModel = new RunOrderStatus;
		$restoreModel = new RunOrderStatus;

		if($newModel->load(Yii::$app->request->post()) && $newModel->validate())
		{
			if($newModel->save())
			{
				Yii::$app->session->setFlash('success', 'Новое состояние заказа создано');
				return $this->refresh();
			}
		}

		if($editModel->load(Yii::$app->request->post()) && $editModel->validate())
		{
			if($editModel->update())
			{
				Yii::$app->session->setFlash('success', 'Состояние заказа изменено');
				return $this->refresh();
			}
		}

		if($deleteModel->load(Yii::$app->request->post()) && $deleteModel->validate())
		{
			if($deleteModel->delete())
			{
				Yii::$app->session->setFlash('success', 'Состояние заказа удалено');
				return $this->refresh();
			}
		}

		if($restoreModel->load(Yii::$app->request->post()) && $restoreModel->validate())
		{
			if($restoreModel->restore())
			{
				Yii::$app->session->setFlash('success', 'Состояние заказа восстановлено');
				return $this->refresh();
			}
		}

		$color = [
			'btn-primary' => 'Primary',
			'btn-secondary' => 'secondary',
			'btn-success' => 'success',
			'btn-danger' => 'danger',
			'btn-warning' => 'warning',
			'btn-info' => 'info',
			'btn-light' => 'light'
		];

		$orderList = OrderStatus::find()->asArray()->all();

		return $this->render('order', compact('newModel', 'editModel', 'deleteModel', 'restoreModel', 'orderList', 'color'));
	}

	public function actionPromo()
	{
		if(Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }
        elseif(Yii::$app->user->identity->level == 3)
        {
            Yii::$app->user->logout();
            return $this->goHome();
        }
        elseif(Yii::$app->user->identity->level == 2)
        {
        	return $this->goHome();
        }

		$newModel = new NewPromoForm;
		$editModel = new EditPromoForm;
		$deleteModel = new RunPromoForm;
		$restoreModel = new RunPromoForm;

		if($newModel->load(Yii::$app->request->post()) && $newModel->validate())
		{
			if($newModel->save())
			{
				Yii::$app->session->setFlash('success', 'Новый промокод создан');
				return $this->refresh();
			}
		}

		if($editModel->load(Yii::$app->request->post()) && $editModel->validate())
		{
			if($editModel->update())
			{
				Yii::$app->session->setFlash('success', 'Промокод изменен');
				return $this->refresh();
			}
		}

		if($deleteModel->load(Yii::$app->request->post()) && $deleteModel->validate())
		{
			if($deleteModel->delete())
			{
				Yii::$app->session->setFlash('success', 'Промокод удален');
				return $this->refresh();
			}
		}

		if($restoreModel->load(Yii::$app->request->post()) && $restoreModel->validate())
		{
			if($restoreModel->restore())
			{
				Yii::$app->session->setFlash('success', 'Промокод восстановлен');
				return $this->refresh();
			}
		}

		$promoList = Promo::find()
							->joinWith('foruser', true, 'LEFT JOIN')
							->asArray()
							->all();

		return $this->render('promo', compact('promoList', 'newModel', 'editModel', 'deleteModel', 'restoreModel'));
	}

	public function actionPay()
	{
		if(Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }
        elseif(Yii::$app->user->identity->level == 3)
        {
            Yii::$app->user->logout();
            return $this->goHome();
        }
        elseif(Yii::$app->user->identity->level == 2)
        {
        	return $this->goHome();
        }

		$newModel = new AddPayForm;
		$editModel = new EditPayForm;
		$deleteModel = new RunPayForm;
		$restoreModel = new RunPayForm;

		if($newModel->load(Yii::$app->request->post()) && $newModel->validate())
		{
			if($newModel->save())
			{
				Yii::$app->session->setFlash('success', 'Вариант оплаты добавлен');
				return $this->refresh();
			}
		}

		if($editModel->load(Yii::$app->request->post()) && $editModel->validate())
		{
			if($editModel->update())
			{
				Yii::$app->session->setFlash('success', 'Вариант оплаты изменен');
				return $this->refresh();
			}
		}

		if($deleteModel->load(Yii::$app->request->post()) && $deleteModel->validate())
		{
			if($deleteModel->delete())
			{
				Yii::$app->session->setFlash('success', 'Вариант оплаты удален');
				return $this->refresh();
			}
		}

		if($restoreModel->load(Yii::$app->request->post()) && $restoreModel->validate())
		{
			if($restoreModel->restore())
			{
				Yii::$app->session->setFlash('success', 'Вариант оплаты восстановлен');
				return $this->refresh();
			}
		}

		$payList = Pay::find()->asArray()->all();

		return $this->render('pay', compact('payList', 'newModel', 'deleteModel', 'restoreModel', 'editModel'));
	}

	public function actionDelivery()
	{
		if(Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }
        elseif(Yii::$app->user->identity->level == 3)
        {
            Yii::$app->user->logout();
            return $this->goHome();
        }
        elseif(Yii::$app->user->identity->level == 2)
        {
        	return $this->goHome();
        }

		$newModel = new AddDeliveryForm;
		$editModel = new EditDeliveryForm;
		$deleteModel = new RunDeliveryForm;
		$restoreModel = new RunDeliveryForm;

		if($newModel->load(Yii::$app->request->post()) && $newModel->validate())
		{
			if($newModel->save())
			{
				Yii::$app->session->setFlash('success', 'Новый вариант доставки создан');
				return $this->refresh();
			}
		}

		if($editModel->load(Yii::$app->request->post()) && $editModel->validate())
		{
			if($editModel->update())
			{
				Yii::$app->session->setFlash('success', 'Вариант доставки обновлен');
				return $this->refresh();
			}
		}

		if($deleteModel->load(Yii::$app->request->post()) && $deleteModel->validate())
		{
			if($deleteModel->delete())
			{
				Yii::$app->session->setFlash('success', 'Вариант доставки удален');
				return $this->refresh();
			}
		}

		if($restoreModel->load(Yii::$app->request->post()) && $restoreModel->validate())
		{
			if($restoreModel->restore())
			{
				Yii::$app->session->setFlash('success', 'Новый вариант доставки восстановлен');
				return $this->refresh();
			}
		}

		$deliveryList = Delivery::find()->asArray()->all();
		
		return $this->render('delivery', compact('deliveryList', 'newModel', 'editModel', 'deleteModel', 'restoreModel'));
	}

	public function actionSorttime()
	{
		if(Yii::$app->request->isAjax)
		{
			$id = Yii::$app->request->post('id');
			$sort = Yii::$app->request->post('sort');

			SortTime::sort($id, $sort);
		}
	}

	public function actionCurrency()
	{
		if(Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }
        elseif(Yii::$app->user->identity->level == 3)
        {
            Yii::$app->user->logout();
            return $this->goHome();
        }
        elseif(Yii::$app->user->identity->level == 2)
        {
        	return $this->goHome();
        }

		$newModel = new AddCurrencyForm;
		$deleteModel = new RunCurrencyForm;
		$restoreModel = new RunCurrencyForm;
		$editModel = new EditCurrencyForm;

		if($newModel->load(Yii::$app->request->post()) && $newModel->validate())
		{
			if($newModel->save())
			{
				Yii::$app->session->setFlash('success', 'Новая валюта создана');
				return $this->refresh();
			}
		}

		if($deleteModel->load(Yii::$app->request->post()) && $deleteModel->validate())
		{
			if($deleteModel->delete())
			{
				Yii::$app->session->setFlash('success', 'Валюта удалена');
				return $this->refresh();
			}
		}

		if($restoreModel->load(Yii::$app->request->post()) && $restoreModel->validate())
		{
			if($restoreModel->restore())
			{
				Yii::$app->session->setFlash('success', 'Валюта восстановлена');
				return $this->refresh();
			}
		}

		if($editModel->load(Yii::$app->request->post()) && $editModel->validate())
		{
			if($editModel->update())
			{
				Yii::$app->session->setFlash('success', 'Валюта изменена');
				return $this->refresh();
			}
		}

		$currencyList = Currency::find()->asArray()->all();

		return $this->render('currency', compact('currencyList', 'newModel', 'deleteModel', 'restoreModel', 'editModel'));
	}

	public function actionClearing()
	{
		if(Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }
        elseif(Yii::$app->user->identity->level == 3)
        {
            Yii::$app->user->logout();
            return $this->goHome();
        }
        elseif(Yii::$app->user->identity->level == 2)
        {
        	return $this->goHome();
        }
        
		$categoryDB = Category::find()->asArray()->all();
		$pageDB = Image::find()->asArray()->all();
		$optionDB = Option::find()->asArray()->all();
		$ingredientDB = Ingredient::find()->asArray()->all();
		$avatarDB = User::find()->where(['!=', 'image', 'NULL'])->asArray()->all();
		$goodsDB = \backend\models\goods\Image::find()->asArray()->all();
		
		return $this->render('clearing', compact('categoryDB', 'pageDB', 'optionDB', 'ingredientDB', 'avatarDB', 'goodsDB'));
	}

	public function actionClear()
	{
		if(Yii::$app->request->isAjax)
		{
			switch (Yii::$app->request->get('object')) {
				case 'category':
					$categoryDB = Category::find()->asArray()->all();
					$categoryFL = \yii\helpers\BaseFileHelper::findFiles(\yii\helpers\Url::to('@frontend/web/images/category'));

					foreach($categoryDB as $fileDB)
					{
						$baseFile[] = $fileDB['image'];
					}

					$count = 0;
					foreach($categoryFL as $file)
					{
						if(!in_array(basename($file), $baseFile))
						{
							\yii\helpers\BaseFileHelper::unlink($file);
						}
						else
						{
							$count++;
						}
					}
					break;

				case 'page':
					$pageDB = Image::find()->asArray()->all();
					$pageFL = \yii\helpers\BaseFileHelper::findFiles(\yii\helpers\Url::to('@frontend/web/images/page'));

					foreach($pageDB as $fileDB)
					{
						$baseFile[] = $fileDB['name'];
					}

					$count = 0;
					foreach($pageFL as $file)
					{
						if(!in_array(basename($file), $baseFile))
						{
							\yii\helpers\BaseFileHelper::unlink($file);
						}
						else
						{
							$count++;
						}
					}
					break;

				case 'option':
					$optionDB = Option::find()->asArray()->all();
					$optionFL = \yii\helpers\BaseFileHelper::findFiles(\yii\helpers\Url::to('@frontend/web/images/option'));

					foreach($optionDB as $fileDB)
					{
						$baseFile[] = $fileDB['image'];
					}

					$count = 0;
					foreach($optionFL as $file)
					{
						if(!in_array(basename($file), $baseFile))
						{
							\yii\helpers\BaseFileHelper::unlink($file);
						}
						else
						{
							$count++;
						}
					}
					break;

				case 'ingredient':
					$ingredientDB = Ingredient::find()->asArray()->all();
					$ingredientFL = \yii\helpers\BaseFileHelper::findFiles(\yii\helpers\Url::to('@frontend/web/images/ingredient'));

					foreach($ingredientDB as $fileDB)
					{
						$baseFile[] = $fileDB['image'];
					}

					$count = 0;
					foreach($ingredientFL as $file)
					{
						if(!in_array(basename($file), $baseFile))
						{
							\yii\helpers\BaseFileHelper::unlink($file);
						}
						else
						{
							$count++;
						}
					}
					break;

				case 'avatar':
					$userDB = User::find()->asArray()->all();
					$userFL = \yii\helpers\BaseFileHelper::findFiles(\yii\helpers\Url::to('@frontend/web/images/avatar'));

					foreach($userDB as $fileDB)
					{
						$baseFile[] = $fileDB['image'];
					}

					$count = 0;
					foreach($userFL as $file)
					{
						if(!in_array(basename($file), $baseFile))
						{
							if(basename($file) != 'default_avatar.png')
								\yii\helpers\BaseFileHelper::unlink($file);
						}
						else
						{
							$count++;
						}
					}
					break;

				case 'goods':
					$goodsDB = \backend\models\goods\Image::find()->asArray()->all();
					$goodsFL = \yii\helpers\BaseFileHelper::findFiles(\yii\helpers\Url::to('@frontend/web/images/goods/thumbs'));

					foreach($goodsDB as $fileDB)
					{
						$baseFile[] = $fileDB['name'];
					}

					$count = 0;
					foreach($goodsFL as $file)
					{
						if(!in_array(basename($file), $baseFile))
						{
							\yii\helpers\BaseFileHelper::unlink($file);
							\yii\helpers\BaseFileHelper::unlink(\yii\helpers\Url::to('@frontend/web/images/goods/' . basename($file)));
						}
						else
						{
							$count++;
						}
					}
					break;
			}

			return $count;
		}
	}

	public function actionValidatecurrency()
	{
		$model = new AddCurrencyForm;
		return $this->validation($model);
	}

	public function actionValidateeditcurrency()
	{
		$model = new EditCurrencyForm;
		return $this->validation($model);
	}

	public function actionValidatenewdelivery()
	{
		$model = new AddDeliveryForm;
		return $this->validation($model);
	}

	public function actionValidateeditdelivery()
	{
		$model = new EditDeliveryForm;
		return $this->validation($model);
	}

	public function actionPromonewvalidation()
	{
		$model = new NewPromoForm;
		return $this->validation($model);
	}

	public function actionPromoeditvalidation()
	{
		$model = new EditPromoForm;
		return $this->validation($model);
	}

	private function validation($model)
	{
		if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
		{
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
	}

	public function actionGetcustomer()
	{
		if(Yii::$app->request->isAjax)
		{
			$phone = Yii::$app->request->post('phone');

			if($user = User::find()
						->where(['phone' => $phone])
						->orWhere(['another_phone' => $phone])
						->asArray()
						->one())
			{
				return json_encode(['username' => $user['username'], 'user_id' => $user['id']]);
			}
			else
			{
				return json_encode(['username' => 'всех', 'user_id' => 0]);
			}
		}
	}

	public function actionSitemap()
	{
		

		$categories = Category::find()->where(['parent' => 0])->asArray()->all();
		$goods = Goods::find()->asArray()->all();
		$pages = Page::find()->asArray()->all();

		if(Yii::$app->request->post())
		{
			$categorySiteMap[] = '<?xml version="1.0" encoding="UTF-8"?>';
	        $categorySiteMap[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

	        $categorySiteMap[] = '<url>';
	        $categorySiteMap[] = '<loc>' . Yii::$app->request->hostInfo . '/</loc>';
	        $categorySiteMap[] = '<lastmod>' . date("Y-m-d") . '</lastmod>';
	        $categorySiteMap[] = '</url>';


	        foreach($categories as $category)
	        {
	            $categorySiteMap[] = '<url>';
	            $categorySiteMap[] = '<loc>' . Yii::$app->request->hostInfo . '/category/' . $category['sef'] . '.html</loc>';
	            $categorySiteMap[] = '<lastmod>' . date("Y-m-d") . '</lastmod>';
	            $categorySiteMap[] = '</url>';
	        }

	        $categorySiteMap[] .= '</urlset>';

	        $file = fopen('../../frontend/web/sitemap-categories.xml', 'w+t') or die('error');
	        foreach($categorySiteMap as $newLine){
	            fwrite($file, $newLine . "\r\n");
	        }
	        fclose($file);

	        
			$goodsSiteMap[] = '<?xml version="1.0" encoding="UTF-8"?>';
	        $goodsSiteMap[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

	        $goodsSiteMap[] = '<url>';
	        $goodsSiteMap[] = '<loc>' . Yii::$app->request->hostInfo . '/</loc>';
	        $goodsSiteMap[] = '<lastmod>' . date("Y-m-d") . '</lastmod>';
	        $goodsSiteMap[] = '</url>';


	        foreach($goods as $item)
	        {
	            $goodsSiteMap[] = '<url>';
	            $goodsSiteMap[] = '<loc>' . Yii::$app->request->hostInfo . '/goods/' . $item['sef'] . '.html</loc>';
	            $goodsSiteMap[] = '<lastmod>' . date("Y-m-d") . '</lastmod>';
	            $goodsSiteMap[] = '</url>';
	        }

	        $goodsSiteMap[] .= '</urlset>';

	        $file = fopen('../../frontend/web/sitemap-goods.xml', 'w+t') or die('error');
	        foreach($goodsSiteMap as $newLine){
	            fwrite($file, $newLine . "\r\n");
	        }
	        fclose($file);

	  
			$pageSiteMap[] = '<?xml version="1.0" encoding="UTF-8"?>';
	        $pageSiteMap[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

	        $pageSiteMap[] = '<url>';
	        $pageSiteMap[] = '<loc>' . Yii::$app->request->hostInfo . '/</loc>';
	        $pageSiteMap[] = '<lastmod>' . date("Y-m-d") . '</lastmod>';
	        $pageSiteMap[] = '</url>';


	        foreach($goods as $item)
	        {
	            $pageSiteMap[] = '<url>';
	            $pageSiteMap[] = '<loc>' . Yii::$app->request->hostInfo . '/page/' . $item['sef'] . '.html</loc>';
	            $pageSiteMap[] = '<lastmod>' . date("Y-m-d") . '</lastmod>';
	            $pageSiteMap[] = '</url>';
	        }

	        $pageSiteMap[] .= '</urlset>';

	        $file = fopen('../../frontend/web/sitemap-pages.xml', 'w+t') or die('error');
	        foreach($pageSiteMap as $newLine){
	            fwrite($file, $newLine . "\r\n");
	        }
	        fclose($file);

	        $siteMap[] = '<?xml version="1.0" encoding="UTF-8"?>';
	        $siteMap[] = '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

	        $siteMap[] = '<sitemap>';
	        $siteMap[] = '<loc>' . Yii::$app->request->hostInfo . '/sitemap-categories.xml</loc>';
	        $siteMap[] = '</sitemap>';
	        $siteMap[] = '<sitemap>';
	        $siteMap[] = '<loc>' . Yii::$app->request->hostInfo . '/sitemap-goods.xml</loc>';
	        $siteMap[] = '</sitemap>';
	        $siteMap[] = '<sitemap>';
	        $siteMap[] = '<loc>' . Yii::$app->request->hostInfo . '/sitemap-pages.xml</loc>';
	        $siteMap[] = '</sitemap>';
	        $siteMap[] = '</sitemapindex>';

	        $file = fopen('../../frontend/web/sitemap.xml', 'w+t') or die('error');
	        foreach($siteMap as $newLine){
	            fwrite($file, $newLine . "\r\n");
	        }
	        fclose($file);

	        yii::$app->session->setFlash('success', 'Файлы SiteMap созданы.');
	    }

	    $mainExist = 0;
	    $categoryExist = 0;
	    $goodsExist = 0;
	    $pageExist = 0;
	    
	    if(file_exists('../../frontend/web/sitemap.xml'))
	    	$mainExist = 1;

	    if(file_exists('../../frontend/web/sitemap-categories.xml'))
	    	$categoryExist = 1;

	    if(file_exists('../../frontend/web/sitemap-goods.xml'))
	    	$goodsExist = 1;

	    if(file_exists('../../frontend/web/sitemap-pages.xml'))
	    	$pageExist = 1;

		return $this->render('sitemap', compact('categories', 'goods', 'pages', 'mainExist', 'categoryExist', 'goodsExist', 'pageExist'));
	}
}