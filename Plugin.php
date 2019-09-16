<?php
/**
 * 一款简洁BGM播放器,需要您的主题支持pjax或者instantclick才能保证页面切换依旧播放
 * 
 * @package YoduBGM
 * @author Jrotty
 * @version 1.6.1
 * @link http://qqdie.com/archives/typecho-yodubgm.html
 */
class YoduBGM_Plugin implements Typecho_Plugin_Interface
{ 
 public static function activate()
	{
        Typecho_Plugin::factory('Widget_Archive')->header = array('YoduBGM_Plugin', 'header');
        Typecho_Plugin::factory('Widget_Archive')->footer = array('YoduBGM_Plugin', 'footer');
    }
	/* 禁用插件方法 */
	public static function deactivate(){}
    public static function config(Typecho_Widget_Helper_Form $form){
       $bof = new Typecho_Widget_Helper_Form_Element_Radio(
        'bof', array('0'=> '不自动播放', '1'=> '自动播放'), 0, '播放设置',
            '自动播放顾名思义，就是页面打开后音乐就会自动播放');
        $form->addInput($bof);

$sxj = new Typecho_Widget_Helper_Form_Element_Radio(
        'sxj', array('0'=> '隐藏', '1'=> '不隐藏'), 0, '手机端是/否隐藏',
            '');
        $form->addInput($sxj);
        $musicList = new Typecho_Widget_Helper_Form_Element_Textarea('musicList', NULL, 
'',_t('歌曲列表'), _t('
<div style="
    background: #fff;
    padding: 10px;
    margin-top: -0.5em;
"><p><b>直链方式：</b><br>填写歌曲链接地址，必须以 http / https 开头。</p>
<p><b>网易云音乐：</b><br>填写网易云音乐 ID 即可。</p>
<p><b>注意：</b><br>多首歌曲以 回车 分割，一行一首，不要出现空行，不知道什么是歌曲 ID 请自行搜索<br>不支持解析付费歌曲</p>
<p><b>感谢：</b>https://api.imjad.cn/cloudmusic.md</p>
</div>
'));
        $form->addInput($musicList);
    }
    
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}
    public static function header(){
        $cssUrl = Helper::options()->pluginUrl . '/YoduBGM/css/player.css';
        echo '<link rel="stylesheet" href="' . $cssUrl . '">';
if(Typecho_Widget::widget('Widget_Options')->Plugin('YoduBGM')->sxj=='0'){	
			echo '<style>@media only screen and (max-width:766px){.ymusic{display:none}}</style>'. "\n";
}
    }

    public static function footer(){
        $options = Typecho_Widget::widget('Widget_Options')->plugin('YoduBGM');  $musicList = $options->musicList;
 if(empty($musicList)){
       $musicList = "761323";
      }
      
        $array = explode(PHP_EOL,$musicList);
        foreach ($array as $value) {
            $value = trim($value);
            if (substr($value,0,4) === 'http') {
                $playList .= '{mp3:"'.$value.'"},';
            }
            if (is_numeric($value)) {
                $playList .= '{mp3:"https://api.imjad.cn/cloudmusic/?type=song&id='.$value.'&br=128000&raw=ture"},';
            }
            //if (preg_match('/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9a-zA-Z]+$/',$value)) {
            //    $playList .= '{mp3:"https://www.xxxxxx.com/tool/music/qq.php?id='.$value.'"},';
            //}
        }
        
        if (count($array) === 1) {
            echo '<bgm><a class="ymusic" onclick="playbtu();" target="_blank"><i id="ydmc"></i></a></bgm>';
        } else {
            echo '<bgm><a class="ymusic" onclick="playbtu();" target="_blank"><i id="ydmc"></i></a><a class="ymusic" onclick="next();" id="ydnext" target="_blank"><i class="iconfont icon-you"></i></a></bgm>';
        }

        echo '<script data-no-instant>
        var yaudio = new Audio();yaudio.controls = true;var musicArr=['.$playList.'];
        /*首次随机播放*/
        var a=parseInt(Math.random()*musicArr.length);var sj=musicArr[a];yaudio.src=sj.mp3;';
        if (Typecho_Widget::widget('Widget_Options')->Plugin('YoduBGM')->bof == '1') {
            echo 'yaudio.play();</script>'. "\n";
        } else {
            echo '</script>'. "\n";
        }
        echo '<script  src="'.Helper::options()->pluginUrl . '/YoduBGM/js/player.js" data-no-instant></script><script  src="'.Helper::options()->pluginUrl . '/YoduBGM/js/prbug.js"></script>' . "\n"; 
    }
}
