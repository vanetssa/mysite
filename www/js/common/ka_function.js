var __kakao = __kakao || {};

__kakao.init = function(appid){
	Kakao.init(appid);
}

__kakao.getOauth = function(){
	Kakao.Auth.login({
		success: function(authObj) {
			console.log(authObj);
			Kakao.API.request({
        		url: '/v1/user/me',
        		data:{propertyKeys:['nickname']},
        		success: function(res) {
          			console.log(res);
        		},
        		fail: function(error) {
          			console.log(error);
        		}
      		});
		},
		fail: function(err) {
			console.log(err);
		}
	});
}