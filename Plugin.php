<?php
/**
 * 一款简洁BGM播放器,需要您的主题支持pjax或者instantclick才能保证页面切换依旧播放
 * 
 * @package YoduBGM
 * @author Jrotty
 * @version 1.6.5
 * @link http://blog.zezeshe.com/
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
$d=array('默认配置');
$n=5;
while ($n<=200) {
$d[$n] = $n.'px';$n=$n+5;
}

    $set2 = new Typecho_Widget_Helper_Form_Element_Select('top', $d, '8', _t('距离顶部间距'), _t('播放器按钮显示在网页的右上角，这里的设置就是播放器组件距离顶部的间距，默认为65px'));
    $form->addInput($set2);

$sxj = new Typecho_Widget_Helper_Form_Element_Radio(
        'sxj', array('0'=> '隐藏', '1'=> '不隐藏'), 0, '手机端是/否隐藏',
            '');
        $form->addInput($sxj);
        $musicList = new Typecho_Widget_Helper_Form_Element_Textarea('musicList', NULL, 
'',_t('歌曲列表'), _t('
<div style="background: #3f51b5;
    color: #fff;
    padding: 10px;
    margin-top: -0.5em;
">填写格式<p><b>直链方式：</b><br>填写歌曲链接地址即可，多首歌曲的话请在两首歌曲之间加换行，千万别多加回车换行。</p>
<p><b>调用网易云：</b><br>书写网易云歌曲id即可，多首歌曲的话请在两首歌曲id之间加换行，单首歌曲直接写id就行，千万别多加回车换行</p>
<p><b>注意：</b><br>这两种填写方式不能混合输入，要么只用直链方式，要么只用网易云方式</p>
</div>
'));
        $form->addInput($musicList);
    }
    
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}
    public static function header(){
        $cssUrl = Helper::options()->pluginUrl . '/YoduBGM/css/player.css?2022';
        echo '<link rel="stylesheet" href="' . $cssUrl . '">';
if(Typecho_Widget::widget('Widget_Options')->Plugin('YoduBGM')->sxj=='0'){	
			echo '<style>@media only screen and (max-width:766px){.ymusic{display:none}}</style>'. "\n";
}
if(Helper::options()->Plugin('YoduBGM')->top){
			echo '<style>bgm{top: '.Helper::options()->Plugin('YoduBGM')->top.'px;}</style>'. "\n";  
}
    }

    public static function footer(){
        $options = Typecho_Widget::widget('Widget_Options')->plugin('YoduBGM');  $musicList = $options->musicList;
 if(empty($musicList)){
       $musicList = "761323";
      }
      
      if(strpos($musicList,'//')===false){
        $musicList = str_replace(PHP_EOL, '.mp3"},{mp3:"https://music.163.com/song/media/outer/url?id=', $musicList);  
  $musicList = '{mp3:"https://music.163.com/song/media/outer/url?id='.$musicList.'.mp3"}';
   $musicList = str_replace(array("\r\n", "\r", "\n", " "), "", $musicList);    
         }else{
              $musicList = str_replace(PHP_EOL, '"},{mp3:"', $musicList);  
  $musicList = '{mp3:"'.$musicList.'"}';
   $musicList = str_replace(array("\r\n", "\r", "\n", " "), "", $musicList);   
      
      }
if(strpos($musicList,',')===false){
    
		echo '
<bgm>			
<a class="ymusic" onclick="playbtu();" target="_blank"><i id="ydmc"></i></a>
</bgm>
             ';
}else{
      
		echo '
<bgm>			
<a class="ymusic" onclick="playbtu();" target="_blank"><i id="ydmc"></i></a><a class="ymusic" onclick="next();" id="ydnext" target="_blank"><i class="iconfont icon-you"></i></a>
</bgm>
             ';

}
      



        echo '<script data-no-instant>
var yaudio = new Audio();
yaudio.controls = true;
var musicArr=[
             '.$musicList.'
              ];
 
/*首次随机播放*/
var a=parseInt(Math.random()*musicArr.length);
var sj=musicArr[a];
yaudio.src=sj.mp3;
 ';
if(Typecho_Widget::widget('Widget_Options')->Plugin('YoduBGM')->bof=='1'){	
			echo 'yaudio.play();</script>'. "\n";
		}else{	echo '</script>'. "\n";
}
        echo '<script  src="'.Helper::options()->pluginUrl . '/YoduBGM/js/player.js" data-no-instant></script><script  src="'.Helper::options()->pluginUrl . '/YoduBGM/js/prbug.js"></script>' . "\n";        
    }

}
