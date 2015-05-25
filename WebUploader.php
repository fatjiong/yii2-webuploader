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
	public $options = array();

	public function init()
	{
		parent::init();
		if (empty($this->url)) $this->url = Url::to(['/index/upload']);
		if (empty($this->id)) $this->id = 'filePicker';
       	if (empty($this->template)) $this->template = '<div id="uploader-demo"><!--用来存放item--><div id="fileList" class="uploader-list"></div><div id="'.$this->id.'">选择图片</div></div>';
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
		$script = <<<EOT
				var uploader = WebUploader.create({
					auto: true,
					swf: './asset/js/Uploader.swf',
					server: '$this->url',
					pick: '#$this->id',
					resize: true
					});
EOT;
		$this->view->registerJs($script, View::POS_READY);
	}
}
