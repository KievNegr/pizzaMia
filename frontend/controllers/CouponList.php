<?php
namespace frontend\controllers;

use Yii;
use frontend\models\order\Coupons;
use frontend\models\order\Order;

class CouponList
{
	public static function getCoupons()
	{
		//Обьявляем массив доступных купонов
        $availableCoupons = Array();

        //Обьявляем массив со списком купонов
        $couponList = Array();
        
        //Если пользователь залогинен то
        if(!Yii::$app->user->isGuest)
        {
            //В БД в таблице купонов ищем купоны которые закреплены за пользователем или же купоны, 
            //которые предназначены для всех клиентов
            $coupons = Coupons::find()
                                ->where(['user' => Yii::$app->user->identity->id]) //Пользовательские купоны
                                ->orWhere(['user' => 0]) //Купоны для всех
                                ->andWhere(['deleted' => 0])
                                ->asArray()
                                ->all();

            //Пробегаемся по всем выбраным купонам если они кнешна ж есть
            foreach ($coupons as $coupon) 
            {
                //Если купон не просрочен по датам то
                if($coupon['expiration'] > date('Y-m-d'))
                {
                    //В таблице заказов проверяем использовался ли уже этот купон пользователем в предыдущих заказах
                    $check = Order::find()->where(['coupon' => $coupon['id'], 'user_id' => Yii::$app->user->identity->id])->exists();

                    //Если купон не использовался или купон является многоразовым то
                    if(!$check || $coupon['applying'] == 1)
                    {
                        //Добавляем этот купон в список доступных при оформлении нового заказа
                        $couponList[] = $coupon;
                    }
                }
            }

            //Полученый список купонов прогоняем через foreach и приводим его к нормальному отображению для сайта
            foreach ($couponList as $coupon) 
            {
                //Приводим дату к нормальному отображению
                $expiration = substr($coupon['expiration'], 8, 2) . '.' . substr($coupon['expiration'], 5, 2) . '.' . substr($coupon['expiration'], 0, 4);

                //Добавлем в массив новый купон который доступен для юзания в заказах
                $availableCoupons[$coupon['id']] = $coupon['code'] . ' (Скидка: ' . $coupon['discount'] . ' % до ' . $expiration . ')';
            }

            //Если список купонов существует то логично добавить в список вариант пункт для отказа от использования купона
            if(!empty($availableCoupons))
                $availableCoupons[0] = 'Не использовать'; //Собсно добавляем и радуемся

            return ['available' => $availableCoupons, 'all' => $coupons];
        }

        return ['available' => $availableCoupons];
	}
}