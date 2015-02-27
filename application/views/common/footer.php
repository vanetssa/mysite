    </div>

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
    
    <!-- Foot JS -->
    <?php
        if(!empty($this->_footScript)){            
            foreach($this->_footScript as $_js){
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

    <!-- Foot Inline Script -->
    <?php if(!empty($this->_inlineFootScript)){ echo '<script>'.$this->_inlineFootScript.'</script>'; } ?>
  </body>
</html>