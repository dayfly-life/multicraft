<?php
/**
 *
 *   Copyright © 2010-2021 by xhost.ch GmbH
 *
 *   All rights reserved.
 *
 **/
$this->pageTitle=Yii::app()->name . ' - '.Yii::t('mc', 'Login');
$this->breadcrumbs=array(
    Yii::t('mc', 'Login'),
);

/**
 * passToken
 *  0 - 로딩중
 *  1 - 통과
 * -1 - 토큰이 안들어왔음
 * -2 - 이상한 토큰
 * -3 - 사용한 토큰
 * -4 - 토큰 만료
 * -5 - 알수없는 오류
 */
$passToken = 0;

$url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$components = parse_url($url);
parse_str($components['query'], $params);

if(isset($params['token'])) {
    $mcAuth = Yii::app()->db->createCommand()
        ->select('gma_mc_no, gma_enabled, gma_create_time')
        ->from('game_mc_auth')
        ->where('gma_token=:token', array(':token'=>$params['token']))
        ->queryRow();

    if(!empty($mcAuth)) {
        if($mcAuth['gma_enabled'] == 1) {

            $now = new DateTime();  // 현재일시
            $before = new DateTime($mcAuth['gma_create_time']);
            $diff = $now->getTimestamp() - $before->getTimestamp();
            if($diff <= 600) {
                //update
                $user = Yii::app()->db->createCommand()
                    ->select('gm_id, gm_password')
                    ->from('game_mc')
                    ->where('gm_no=:gmNo', array(':gmNo'=>$mcAuth['gma_mc_no']))
                    ->queryRow();
                if(!empty($user)) {
                    $passToken = 1;
//                    CHtml::hiddenField('LoginForm[name]', $user['gm_id']);
//                    CHtml::hiddenField('LoginForm[password]', $user['gm_password']);
//                    CHtml::submitButton(Yii::t('mc', 'Login'));

                }else $passToken = -5;
            }else $passToken = -4;
        } else $passToken = -3;
    }else $passToken = -2;
}else $passToken = -1;
//$6$rounds=50000$1DbyvtrcMdHzSY7S$eN4.Xp/wpLiVT.Mq26BznEiP3MdDb9VoTKrzdsBWwl/FmpHOaPdK8RTjxINnx.6xvHFadF.jy5ZTP.8r4/i8N/
$pass = crypt('test', 'sha512_crypt');


var_dump($pass);

?>

<?php if (!Yii::app()->params['register_disabled']): ?>
<p><?php echo CHtml::link(Yii::t('mc', 'Register here'), array('site/register')) ?> <?php echo Yii::t('mc', 'if you don\'t have an account yet.') ?></p>
<?php endif ?>

<?php if (Yii::app()->user->hasFlash('login')): ?>
<div class="flash-success">
    <?php echo Yii::app()->user->getFlash('login'); ?>
</div>
<?php endif ?>

<?php if (Yii::app()->params['demo_mode'] != 'enabled'): ?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'login-form',
    'enableAjaxValidation'=>false,
    'focus'=>array($model, strlen($model->name) ? 'password' : 'name'),
)); ?>

    <div class="form-group">
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->textField($model,'name'); ?>
        <?php echo $form->error($model,'name'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model,'password'); ?>
        <?php echo $form->passwordField($model,'password'); ?>
        <?php echo $form->error($model,'password'); ?>
    </div>

    <?php if ($model->gauthEnabled()): ?>
    <?php
        if (strlen($model->password))
            $form->focus = array($model, 'gauthCode');
    ?>
        <div class="form-group">
            <?php echo $form->labelEx($model,'gauthCode'); ?>
            <?php echo $form->passwordField($model,'gauthCode'); ?>
            <?php echo $form->error($model,'gauthCode'); ?>
        </div>
    <?php endif ?>

    <div class="row rememberMe">
    <div class="col-lg-6 form-group">
        <?php echo $form->checkBox($model,'rememberMe'); ?>
        <?php echo $form->label($model,'rememberMe'); ?>
        <?php echo $form->error($model,'rememberMe'); ?>
        <?php if (Yii::app()->params['reset_token_hours'] > 0): ?>
        <br/>
        <?php echo CHtml::link(Yii::t('mc', 'Forgot password?'), array('site/requestResetPw'), array('style'=>'font-size: 11px')); ?>
        <?php endif ?>
    </div>

    <div class="col-lg-6 form-group">
        <?php echo $form->checkBox($model,'ignoreIp'); ?>
        <?php echo $form->label($model,'ignoreIp'); ?>
        <?php echo $form->error($model,'ignoreIp'); ?>
    </div>
    </div>

    <?php echo $form->error($model,'ignoreIp'); ?>
    <?php
        switch ($passToken) {
            case 0:
                echo '로딩 중';
                break;
            case 1:
                echo '로그인 가능';
                break;
            case -1:
                echo '토큰이 안들어옴';
                break;
            case -2:
                echo '이상한 토큰';
                break;
            case -3:
                echo '사용한 토큰';
                break;
            case -4:
                echo '토큰 만료 - 10분';
                break;
            case -5:
                echo '알수없는 오류 - api error';
                break;
        }
    ?>
    <?php
                        CHtml::hiddenField('LoginForm[name]', 'admin');
                        CHtml::hiddenField('LoginForm[password]', 'admin');
    ?>
    <?php echo CHtml::submitButton(Yii::t('mc', 'Login')); ?>

<!--    --><?php
//    $model['name'] = 'admin'
//    ?>
<!--    --><?php //$model['password'] = 'admin' ?>
<!--    --><?php //echo CHtml::hiddenField('LoginForm[name]', 'test') ?>
<!--    --><?php //echo CHtml::hiddenField('LoginForm[password]', '1234') ?>
<!--    --><?php //echo CHtml::submitButton(Yii::t('mc', 'Login')); ?>

<!--    --><?php //var_dump(parse_str(parse_url($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])['query'], $result)); ?>
<!--    $url = "https://testurl.com/test/1234?email=abc@test.com&name=sarah";-->
<!--    $components = parse_url($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])['query'];-->
<!--    parse_str($components['query'], $results);-->
<!--    print_r($results);-->

    <?php $this->endWidget(); ?>
</div><!-- form -->

<?php else: ?>
<h1>Demo mode</h1>
<table>
<tr>
<td>
<?php echo CHtml::beginForm() ?>
<?php echo CHtml::hiddenField('LoginForm[name]', 'admin') ?>
<?php echo CHtml::hiddenField('LoginForm[password]', 'admin') ?>
<?php echo CHtml::submitButton('Log me in as Administrator', array('style'=>'width: 180px')); ?>
<?php echo CHtml::endForm() ?>
</td>
<td>
Create servers &amp; users
</td>
</tr>
<tr>
<td>
<?php echo CHtml::beginForm() ?>
<?php echo CHtml::hiddenField('LoginForm[name]', 'owner') ?>
<?php echo CHtml::hiddenField('LoginForm[password]', 'owner') ?>
<?php echo CHtml::submitButton('Log me in as Server Owner', array('style'=>'width: 180px')); ?>
<?php echo CHtml::endForm() ?>
</td>
<td>
Edit server settings, assign permissions to users/players, define custom commands
</td>
</tr>
<tr>
<td>
<?php echo CHtml::beginForm() ?>
<?php echo CHtml::hiddenField('LoginForm[name]', 'user') ?>
<?php echo CHtml::hiddenField('LoginForm[password]', 'user') ?>
<?php echo CHtml::submitButton('Log me in as normal User', array('style'=>'width: 180px')); ?>
<?php echo CHtml::endForm() ?>
</td>
<td>
Edit assigned players, use functions for assigned player
</td>
</tr>
</table>
<br/>
<br/>
<div class="infoBox">
<b>Note</b><br/>
Servers are not running and can't be stopped/restarted.<br/>
</div>

<?php endif ?>
