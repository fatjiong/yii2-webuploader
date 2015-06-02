<?php

namespace fatjiong\webuploader;

use Yii;
use yii\web\View;
use yii\helpers\Url;
/**
 * This is just an example.
 */
class WebUploader extends \yii\base\Widget
{	
	public $id = 'filePicker';
	public $url = '';
	public $template =  '';
	public $options = [];
	public $jsOptions = [];
	public $thumb = [];

	public function init()
	{	
		parent::init();
		if (empty($this->url)) $this->url = Url::to(['/index/upload']);
		if (empty($this->id)) $this->id = 'filePicker';
       	if (empty($this->template)) $this->template = '<div id="'.$this->id.'">选择图片</div>';

   		$this->jsOptions['auto'] = 'true';
   		$this->jsOptions['swf'] = "'./asset/js/Uploader.swf'";
   		$this->jsOptions['server'] = "'$this->url'";
   		$this->jsOptions['pick'] = "'#$this->id'";
	}

    public function run()
    {
    	$this->registerClientScript();
    	return $this->template;	
	}

	// 注册组件
	private function registerClientScript()
	{
		WebUploaderAsset::register($this->view);
		$script = "var uploader = WebUploader.create({";
		foreach ($this->jsOptions as $key => $value) {
			$script .= "$key : $value,";
		}
		$script .= "});";
		if ($this->thumb) {
			$script .= "uploader.on( 'fileQueued', function( file ) {
						uploader.makeThumb( file, function( error, ret ) {
								endThumb(ret); // 生成缩略图后执行方法
						},".$this->thumb['width'].",".$this->thumb['height'].");
				 	});";
		}
		$script .= "uploader.on('uploadSuccess', function (file) {
						endUploader(file);   // 上传完成后执行方法
					});";
		$this->view->registerJs($script, View::POS_READY);
	}
}
