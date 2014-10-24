    <div class="container marketing">
      <footer>
        <p class="pull-right"><a href="#">Back to top</a></p>
        <p>&copy; 2014 Company, Inc. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
      </footer>

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
    <?php if(!empty($this->_inlineFootScript)){ echo $this->_inlineFootScript; } ?>
  </body>
</html>