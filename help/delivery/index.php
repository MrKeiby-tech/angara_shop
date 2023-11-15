<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Условия доставки");
?><h2>Доставка по Москве и Московской области</h2>
<div class="custom-tabs">
	<div class="col-6 tab-header">
		<h3>Самовывоз</h3>
	</div>
	<div class="col-6 tab-header">
		<a href="#conditions">Условия доставки</a>
	</div>
</div>
<p>
	 Мы находимся в 1 км. от МКАД по адресу ул. Адмирала Корнилова 18 строение 1. К Вашим услугам частная охраняемая территория с собственной парковкой, крытые ангары с продукцией, и уютный офис продаж, где Вы сможете получить необходимую консультацию и оформить покупку.
</p>
 <img src="/upload/company/self-pickup.jpg"> <br>
 <br>
<p>
	 АДРЕС<br>
	 108820 г. Москва, п. Мосрентген, Коммунальная зона, владение 25
</p>
<p>
	 РЕЖИМ РАБОТЫ<br>
	 пн-пт: 09.00-18.00; сб: 09.00-13.00; вс: выходной
</p>
<p>
	 ТЕЛЕФОН<br>
 <a href="tel:88002003105">8 (800) 200-31-05</a><br>
 <a href="tel:88002003105">8 (800) 200-31-05</a>
</p>
<p>
	 E-MAIL<br>
 <a href="mailto:info@angara-forest.ru">info@angara-forest.ru</a>
</p>
 <?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?> <?$APPLICATION->IncludeComponent(
	"bitrix:map.yandex.view",
	"map",
	Array(
		"API_KEY" => "",
		"COMPONENT_TEMPLATE" => "map",
		"CONTROLS" => array(0=>"ZOOM",1=>"TYPECONTROL",2=>"SCALELINE",),
		"INIT_MAP_TYPE" => "MAP",
		"MAP_DATA" => "a:4:{s:10:\"yandex_lat\";d:55.60462314790189;s:10:\"yandex_lon\";d:37.464550765133;s:12:\"yandex_scale\";i:18;s:10:\"PLACEMARKS\";a:1:{i:0;a:3:{s:3:\"LON\";d:37.464550765133;s:3:\"LAT\";d:55.604594297556;s:4:\"TEXT\";s:148:\"Компания \"Форест\"<br>###RN###108820 г. Москва, п. Мосрентген, Коммунальная зона, владение 25\";}}}",
		"MAP_HEIGHT" => "",
		"MAP_ID" => "",
		"MAP_WIDTH" => "100%",
		"OPTIONS" => array(0=>"ENABLE_DBLCLICK_ZOOM",1=>"ENABLE_DRAGGING",),
		"USE_REGION_DATA" => "Y"
	)
);?> <br>

<h3 class="anchor"  id="conditions">Условия доставки</h3>
<p>
	 Доставка товара рассчитывается индивидуально и зависит от:
</p>
<ul>
	<li>удаленность точки выгрузки от нашего склада</li>
	<li>длина и вес доставляемого пиломатериала</li>
	<li>тип транспортного средства</li>
	<li>и других данных.</li>
</ul>
<p>
	 Пожалуйста свяжитесь с нашими менеджерами и они подберут для Вас оптимальный вариант. Если Вы сделали заказ через интернет-магазин, то дождитесь звонка от наших менеджеров, они рассчитают Вам стоимость доставки, и согласуют время доставки. Если Вас все устроит, то стоимость доставки может быть включена в Ваш заказ и Вы сможете его оплатить онлайн.
</p><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>