<?php

function widget_lfmplayer_init() {

    if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
        return; 

    function widget_lfmplayer($args) {

        extract($args);

        $options = get_option('widget_lfmplayer');
        $lfmptitle = empty($options['lfmptitle']) ? 'Player' : $options['lfmptitle'];
	$content = empty($options['content']) ? 'Flyleaf' : $options['content'];
	$lfmp_auto = empty($options['lfmp_auto']) ? 0 : $options['lfmp_auto'];

        $autostart = '';
	if($lfmp_auto == '1')
	    $autostart = '&autostart=true';

        echo $before_widget;
        echo $before_title . $lfmptitle . $after_title;
	echo '<object type="application/x-shockwave-flash" data="http://cdn.last.fm/webclient/s12n/s/14/lfmPlayer.swf" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0"
        id="lfmPlayer" name="lfmPlayer" align="middle"
        width="300" height="221">
        <param name="movie" value="http://cdn.last.fm/webclient/s12n/s/14/lfmPlayer.swf" />
        <param name="flashvars" value="lang=de&lfmMode=playlist&FOD=true&restype=artist&artist='.$content.$autostart.'" />
        <param name="allowScriptAccess" value="always" />
        <param name="allowNetworking" value="all" />
        <param name="allowFullScreen" value="true" />
        <param name="quality" value="high" />
        <param name="bgcolor" value="fff" />
        <param name="wmode" value="transparent" />
        <param name="menu" value="false" />
        </object>';
        echo $after_widget;
    }

    function widget_lfmplayer_control() {

        $options = get_option('widget_lfmplayer');

        if ( $_POST['lfmp-submit'] ) {
            $newoptions['lfmptitle'] = strip_tags(stripslashes($_POST['lfmptitle']));
	    $newoptions['content'] = urlencode($_POST['content']);
	    $newoptions['lfmp_auto'] = $_POST['lfmp_auto'];
        

            if ( $options != $newoptions ) {
                $options = $newoptions;
                update_option('widget_lfmplayer', $options);
            }
	}

        $lfmptitle = $options['lfmptitle'];
	$content = urldecode($options['content']);
	$lfmp_auto = $options['lfmp_auto'];
?>
        <div>
        <label for="lfmptitle" style="line-height:35px;display:block;">Title: <input type="text" id="lfmptitle" name="lfmptitle" value="<?php echo $lfmptitle; ?>" /></label>
	<label for="content" style="line-height:35px;display:block;">Artist: <input type="text" id="content" name="content" value="<?php echo $content; ?>" /></label>
	<label for="lfmp_auto" style="line-height:35px;display:block;">Autostart aktivieren: <input type="checkbox" name="lfmp_auto" value="1" id="lfmp_auto" <?php if($lfmp_auto == '1') echo 'checked'?>></label>
	<input type="hidden" name="lfmp-submit" id="lfmp-submit" value="1" />
        </div>
    <?php
    }

    register_sidebar_widget('LastFM Player', 'widget_lfmplayer');

    register_widget_control('LastFM Player', 'widget_lfmplayer_control');
}

add_action('plugins_loaded', 'widget_lfmplayer_init');
?>
