<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="//www.w3.org/1999/xhtml" xmlns:og="//ogp.me/ns#" xml:lang="ko" lang="ko">
    <head>
        <!-- META -->
        <?php
            if(!empty($this->_meta) && is_array($this->_meta)){
                $meta = implode(chr(13).chr(10),$this->_meta);
                echo $meta;
            }
        ?>

        <!-- favicon -->

        <link rel="icon" href="/img/favicon/favicon.ico">        
        <link rel="apple-touch-icon" sizes="60x60" href="/img/favicon/icon_60x60.jpg">
        <link rel="apple-touch-icon" sizes="76x76" href="/img/favicon/icon_76x76.jpg">
        <link rel="apple-touch-icon" sizes="120x120" href="/img/favicon/icon_120x120.jpg">
        <link rel="apple-touch-icon" sizes="152x152" href="/img/favicon/icon_152x152.jpg">

        <!-- TITLE -->
        <title><?php echo !empty($this->_title)?$this->_title:'van site'; ?></title>

        <!-- CSS -->
        <?php
            if(!empty($this->_styleSheet)){
                foreach($this->_styleSheet as $_css){
                    if(
                        strpos($_css,'https://') === 0
                        || strpos($_css,'http://') === 0
                        || strpos($_css,'/') === 0
                        ){
                        echo '<link href="'.$_css.'" rel="stylesheet">';
                    }else{
                        echo '<link href="/css/'.$_css.'" rel="stylesheet">';
                    }
                }
            }
        ?>        

        <!-- Head JS -->
        <?php
            if(!empty($this->_headScript)){
                foreach($this->_headScript as $_js){
                    if(
                        strpos($_js,'https://') === 0
                        || strpos($_js,'http://') === 0
                        || strpos($_js,'/') === 0
                        ){
                        echo '<script src="'.$_js.'"></script>';
                    }else{
                        echo '<script src="/js/'.$_js.'"></script>';
                    }
                }
            }
        ?>

        <!-- SNS JS -->
        <?php if($this->useFacebookAPI){ ?>
        <script>
            (function(d, s, id){
             var js, fjs = d.getElementsByTagName(s)[0];
             if (d.getElementById(id)) {return;}
             js = d.createElement(s); js.id = id;
             js.src = "//connect.facebook.net/ko_KR/sdk.js";
             fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
        <script src="/js/common/fb_function.js"></script>
        <?php } ?>

        <?php if($this->useGoogleAPI){ ?>
        <script>
            (function() {
             var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
             po.src = 'https://apis.google.com/js/client:plusone.js';
             var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
            })();
        </script>
        <script src="/js/common/gg_function.js"></script>
        <?php } ?>

        <?php if($this->useKakaoAPI){ ?>
        <script src="https://developers.kakao.com/sdk/js/kakao.min.js"></script>
        <script src="/js/common/ka_function.js"></script>
        <?php } ?>

        <!-- Head Inline Script -->
        <?php if(!empty($this->_inlineHeadScript)){ echo '<script>'.$this->_inlineHeadScript.'</script>'; } ?>
    </head>
    <body role="document">
    <?php include_once('gnb.php'); ?>
    <div class="container theme-showcase" role="main" id="siteBody">
    <div class="alert alert-success" id="alertSuccessBar" role="alert" style="display:none;"></div>
    <div class="alert alert-info"    id="alertInfoBar"    role="alert" style="display:none;"></div>
    <div class="alert alert-warning" id="alertWarningBar" role="alert" style="display:none;"></div>
    <div class="alert alert-danger"  id="alertDangerBar"  role="alert" style="display:none;"></div>