    </div>
    
    <!-- Foot JS -->
    <?php
        if(!empty($this->_footScript)){            
            foreach($this->_footScript as $_js){
                if(substr($_js,0,1) == '/'){
                    echo '<script src="'.$_js.'"></script>';
                }else{
                    echo '<script src="/js/'.$_js.'"></script>';
                }
            }
        }
    ?>

    <!-- Foot Inline Script -->
    <?php if(!empty($this->_inlineFootScript)){ echo '<script>'.$this->_inlineFootScript.'</script>'; } ?>
  </body>
</html>