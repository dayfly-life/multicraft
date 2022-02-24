<?php
/**
 *
 *   Copyright © 2010-2021 by xhost.ch GmbH
 *
 *   All rights reserved.
 *
 **/
$this->pageTitle=Yii::app()->name . ' - '.Yii::t('mc', 'Home');
$this->breadcrumbs=array(
    Yii::t('mc', 'Home'),
);

$this->menu=array(
    array(
        'label'=>Yii::t('mc', '환영합니다!'),
        'url'=>array('', 'view'=>'home'),
        'icon'=>'welcome',
    ),
//    array(
//        'label'=>Yii::t('mc', 'Help'),
//        'url'=>'#',
//        'icon'=>'help',
//        'linkOptions'=>array(
//            'submit'=>'http://multicraft.org/site/docs',
//            'confirm'=>Yii::t('mc', "You are leaving your control panel.\n\nYou will be forwarded to the documentation on the official Multicraft website.")),
//    ),
//    array(
//        'label'=>Yii::t('mc', 'About'),
//        'url'=>array('', 'view'=>'about'),
//        'icon'=>'about',
//    ),
);

?>
<br/>

<?php echo Yii::t('mc', '서버24 - 마인크래프트 서버 관리 콘솔에 오신 것을 환영합니다!', array('{Multicraft}'=>CHtml::link('SERVER24.KR', 'http://www.server24.kr'))) ?><br/>

<?php if (Yii::app()->params['demo_mode'] == 'enabled'): ?>
<br/>
<br/>
<div class="infoBox">
<h3>데모 페이지</h3>
<h4><?php echo CHtml::link('로그인이 필요합니다', array('site/login')) ?></h4>
<br/>
The Demo Mode does not cover all of the features Multicraft provides as there is no real server running behind it. Please use the free version of Multicraft for evaluation.<br/>
</div>
<br/>
<br/>
<div class="infoBox">
<h3>Disclaimer</h3>
The content of this Demo Mode installation can be edited by anyone. We are not responsible in any way for any of the content on this installation.<br/>
The content will be reset on a regular basis.<br/>
</div>
<?php endif ?>
