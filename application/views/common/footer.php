    </div>
    
    <!-- Foot JS -->
    <?php
        if(!empty($this->_footScript)){            
            foreach($this->_footScript as $_js){
                echo '<script src="/js/'.$_js.'"></script>';
            }
        }
    ?>

    <!-- Foot Inline Script -->
    <?php if(!empty($this->_inlineFootScript)){ echo '<script>'.$this->_inlineFootScript.'</script>'; } ?>
  </body>
</html>