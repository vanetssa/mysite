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
                    if(substr($_css,0,1) == '/'){
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
                    if(substr($_js,0,1) == '/'){
                        echo '<script src="'.$_js.'"></script>';
                    }else{
                        echo '<script src="/js/'.$_js.'"></script>';
                    }
                }
            }
        ?>

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