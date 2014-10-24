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

        <!-- TITLE -->
        <title><?php echo !empty($this->_title)?$this->_title:'van site'; ?></title>

        <!-- CSS -->
        <?php
            if(!empty($this->_styleSheet)){            
                foreach($this->_styleSheet as $_css){
                    echo '<link href="/css/'.$_css.'" rel="stylesheet">';
                }
            }
        ?>        

        <!-- Head JS -->
        <?php
            if(!empty($this->_headScript)){            
                foreach($this->_headScript as $_js){
                    echo '<script src="/js/'.$_js.'"></script>';
                }
            }
        ?>

        <!-- Head Inline Script -->
        <?php if(!empty($this->_inlineHeadScript)){ echo $this->_inlineHeadScript; } ?>
    </head>
    <body>