<?php
namespace fatjiong\webuploader;

use yii\web\AssetBundle;

class WebUploaderAsset extends AssetBundle
{
    public $css = [
        'assets/css/webuploader.css',
    ];

    public $js = [
        'assets/js/webuploader.min.js',
    ];
    public $jsOptions = [
        'charset' => 'utf8',
    ];

    public function init()
    {
        //资源所在目录
        $this->sourcePath = dirname(__FILE__) . DIRECTORY_SEPARATOR;
    }
}
