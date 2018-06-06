<?php

namespace fatjiong\webuploader;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\InputWidget;

/**
 * This is just an example.
 */
class WebUploader extends InputWidget
{
    public $id          = 'filePicker';
    public $url         = '';
    public $template    = '';
    public $options     = [];
    public $jsOptions   = [];
    public $thumb       = []; // 缩略图
    public $accept      = []; // 允许的文件
    public $status      = []; // 允许状态回调
    public $endCallback = 'endUploader';

    public function init()
    {
        if (empty($this->name)) {
            $this->name = $this->hasModel() ? Html::getInputName($this->model, $this->attribute) : $this->id;
        }
        if (empty($this->url)) {
            $this->url = Url::to(['index/upload']);
        }

        if (empty($this->id)) {
            $this->id = 'filePicker';
        }

        if (empty($this->template)) {
            $this->template = '<div id="' . $this->id . '">选择图片</div>';
        }

        $this->jsOptions['auto']   = isset($this->jsOptions['auto']) ? $this->jsOptions['auto'] : 'true';
        $this->jsOptions['swf']    = "'./asset/js/Uploader.swf'";
        $this->jsOptions['server'] = "'$this->url'";
        $this->jsOptions['pick']   = "{id:'#$this->id',multiple:false}";
        // $this->jsOptions['accept'] = "{
        //                             title: 'Images',
        //                             extensions: 'png,jpg,gif,doc,docx,xls,xlsx,pdf,txt',
        //                             mimeTypes: 'application/zip,application/vnd.ms-excel,application/vnd.ms-powerpoint,image/*' +
        //                                 ',application/vnd.openxmlformats-officedocument.presentationml.presentation' +
        //                                 ',application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword' +
        //                                 ',application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain,application/x-rar-compressed'}";
        parent::init();
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
        if ($this->accept) {
            if ($this->accept['office']) {
                $script .= "accept : {" . $this->accept['office'] . "},";
            }
        }
        $script .= "});";
        if ($this->thumb) {
            // 可以单独执行缩略图生成base64的缓存图片
            // $script .= "uploader.on( 'fileQueued', function( file ) {
            //             uploader.makeThumb( file, function( error, ret ) {
            //                     endThumb(ret); // 生成缩略图后执行方法
            //             },".$this->thumb['width'].",".$this->thumb['height'].");
            //          });";
            $script .= "// 修改后图片上传前，尝试将图片压缩
			uploader.option('compress', {
			    width: " . $this->thumb['width'] . ",
			    height: " . $this->thumb['height'] . "
			});";
        }

        in_array('beforeFileQueued', $this->status) && $script .= "uploader.on('beforeFileQueued', function (file) {
						beforeFileQueued(file);   // 加入队列前执行方法
					});";
        $script .= "uploader.on('uploadSuccess', function (file,response) {
						" . $this->endCallback . "(file,response);   // 上传完成后执行方法
					});";
        $this->view->registerJs($script, View::POS_READY);
    }
}
