var __google = __google || {};

var googleOauthOpt = _googleApiConfig;
googleOauthOpt.callback = '__google.oauthCallback';

__google.oauthCallback = function(authResult){
	if(authResult['access_token']){
		__google.getUserInfo(authResult);
	}else if(authResult['error'] == "immediate_failed"){
        gapi.auth.authorize({
            client_id: _googleApiConfig.clientid,
            scope: _googleApiConfig.scope,
            immediate: true
        }, function (authRes) {
            if (authRes['status']['signed_in']) {
                __google.getUserInfo(authRes);
            }
        });
    }
}

__google.getUserInfo = function(authResult){
	gapi.auth.setToken(authResult);
	gapi.client.load('oauth2', 'v2', function(){
    	var request = gapi.client.oauth2.userinfo.get();
    	request.execute(doGoogleProcess);
  	});
}

__google.getOauth = function(){
    gapi.signin.render('googleOauthBtn',googleOauthOpt);
}