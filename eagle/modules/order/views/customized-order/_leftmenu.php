<?php
use yii\helpers\Url;
use eagle\modules\util\helpers\TranslateHelper;
use eagle\modules\order\models\OdOrder;
use eagle\modules\delivery\helpers\DeliveryHelper;
$active = '';
$customConditionArray = [
TranslateHelper::t('所有订单')=>[
	'url'=>Url::to(['/order/customized-order/list'])
],
];
if (!empty($counter['custom_condition'])){
	$sel_custom_condition = array_merge(['加载常用删选'] , array_keys($counter['custom_condition']));
	$tmpCondition = array_flip($sel_custom_condition);
	foreach($counter['custom_condition'] as $custom_condition_name=>$thisCondition):		
		$thisToUrl = Url::to(['/order/customized-order/list']);
		$thisToUrl .='?sel_custom_condition='.$tmpCondition[$custom_condition_name].'&'. http_build_query($thisCondition);
		
		$customConditionArray [$custom_condition_name]  = [ 'url'=>$thisToUrl];
		if (isset($_REQUEST['sel_custom_condition']) ){
			if(@$_REQUEST['sel_custom_condition']==$tmpCondition[$custom_condition_name]){
				$active=$custom_condition_name;
			}
		}else if(@$_REQUEST['custom_condition_name']==$custom_condition_name){
			$active=$custom_condition_name;
		}			
	 endforeach;
}

$deliverMenu = DeliveryHelper::getDeliveryMenuByPlatform('customized');

$menu = [
	TranslateHelper::t('自定义店铺业务待处理')=>[
		'icon'=>'icon-stroe',
		'items'=>[
			TranslateHelper::t('所有订单')=>[
				'url'=>Url::to(['/order/customized-order/list','menu_select'=>'all'])
			],
		]
	],

	TranslateHelper::t('订单业务流程')=>[
		'icon'=>'icon-stroe',
		'items'=>[
			TranslateHelper::t('已付款')=>[
				'url'=>Url::to(['/order/customized-order/list','order_status'=>OdOrder::STATUS_PAY , 'pay_order_type'=>'all']),
				'tabbar'=>empty($counter[OdOrder::STATUS_PAY])?0:$counter[OdOrder::STATUS_PAY]
			],		
			TranslateHelper::t('发货中')=>[
				'qtipkey'=>'@oms_faHuoZhong',
				'url'=>'#',
				'items'=>$deliverMenu,
			],
			TranslateHelper::t('已完成')=>[
				'url'=>Url::to(['/order/customized-order/list','order_status'=>OdOrder::STATUS_SHIPPED]),
				'tabbar'=>empty($counter[OdOrder::STATUS_SHIPPED])?0:$counter[OdOrder::STATUS_SHIPPED]
			],
			TranslateHelper::t('已取消')=>[
				'url'=>Url::to(['/order/customized-order/list','order_status'=>OdOrder::STATUS_CANCEL]),
				'tabbar'=>empty($counter[OdOrder::STATUS_CANCEL])?0:$counter[OdOrder::STATUS_CANCEL]
			],
			TranslateHelper::t('暂停发货')=>[
				'url'=>Url::to(['/order/customized-order/list','order_status'=>OdOrder::STATUS_SUSPEND]),
				'tabbar'=>empty($counter[OdOrder::STATUS_SUSPEND])?0:$counter[OdOrder::STATUS_SUSPEND]
			],
			TranslateHelper::t('缺货')=>[
				'url'=>Url::to(['/order/customized-order/list','order_status'=>OdOrder::STATUS_OUTOFSTOCK]),
				'tabbar'=>empty($counter[OdOrder::STATUS_OUTOFSTOCK])?0:$counter[OdOrder::STATUS_OUTOFSTOCK]
			],
		]
	],
];

if (@$_REQUEST['order_status']==OdOrder::STATUS_NOPAY && empty($_REQUEST['custom_condition_name'])){
	$active = TranslateHelper::t('未付款');
}
if(@$_REQUEST['order_status']==OdOrder::STATUS_PAY && empty($_REQUEST['custom_condition_name'])){
	$active=TranslateHelper::t('已付款');
}
if(@$_REQUEST['order_status']==OdOrder::STATUS_WAITSEND && empty($_REQUEST['custom_condition_name'])){
	$active=TranslateHelper::t('发货中');
}
if(@$_REQUEST['order_status']==OdOrder::STATUS_SHIPPED && empty($_REQUEST['custom_condition_name'])){
	$active=TranslateHelper::t('已完成');
}
if(@$_REQUEST['order_status']==OdOrder::STATUS_CANCEL && empty($_REQUEST['custom_condition_name'])){
	$active=TranslateHelper::t('已取消');
}
if(@$_REQUEST['order_status']==OdOrder::STATUS_SUSPEND&& empty($_REQUEST['custom_condition_name'])){
	$active=TranslateHelper::t('暂停发货');
}
if(@$_REQUEST['order_status']==OdOrder::STATUS_OUTOFSTOCK&& empty($_REQUEST['custom_condition_name'])){
	$active=TranslateHelper::t('缺货');
}
if(@$_REQUEST['menu_select']== 'all'){
	$active= TranslateHelper::t('所有订单');
}
if (!empty($menu_select_list[@$_REQUEST['menu_select']])){
	$active = $menu_select_list[$_REQUEST['menu_select']];
}
echo $this->render('//layouts/new/left_menu_2',[
		'menu'=>$menu,
		'active'=>$active
		]);

?>
