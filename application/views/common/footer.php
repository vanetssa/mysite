                </div>
            <?php include_once('rnb.php'); ?>
        </div><!-- /.row -->

    </div><!-- /.container -->

    <div class="blog-footer">
      <p>Blog template built for <a href="http://getbootstrap.com">Bootstrap</a> by <a href="https://twitter.com/mdo">@mdo</a>.</p>
      <p>
        <a href="#">Back to top</a>
      </p>
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