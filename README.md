基于百度webuploader 做的Yii2上传组件

<?php
use fatjiong\webuploader\WebUploader;
?>

<?=WebUploader::widget(['url' => Url::to(['/admini/home/upload']), 'thumb' => ['width' => 1920, 'height' => 451]]);?>