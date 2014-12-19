var facebook = facebook || {};

/**
 *	페이스북 API초기설정
 *	모든 페이스북 API사용시 미리 실행을 해야함
 *
 *	@param Integer appid 앱의 고유 ID
 *	@param Array option 추가로 설정할 option
 * */
facebook.init = function(appid,option){
	config = {appId:appid,cookie:true};
	config = $.extend(config,option);
	FB.init(config);
}

/**
 *	페이스북 로그인 POPUP을 호출함(로그인 되어 있는 상태라면 바로 팝업이 닫힘)
 *	권한은 크게 get과 put으로 나뉜다
 *	서로 다른 타입을 동시에 요청하는 경우 앱 승인창이 2번 나오게 된다.
 *
 *	@param String scope 앱승인시 사용자로 부터 받을 권한(publish_stream,read_friendlists...)
 *	@param Function callback 로그인 창을 띄운뒤 일어나는 액션을 받아서 실행 시킬 함수 
 * */
facebook.doLogin = function(scope,callback){	
	FB.login(
		function(response) {
			callback(response);
		},{ scope: scope }
	);
}

/**
 *	페이스북 로그인 url을 가지고옴 
 * */
facebook.getLoginUrl = function(appid,scope,redirect) {	
	var oauth_url = 'https://www.facebook.com/dialog/oauth/';
	oauth_url += '?client_id='+appid;
	oauth_url += '&scope='+scope;
	oauth_url += '&redirect_uri='+encodeURIComponent(redirect);
	return oauth_url;
}

/**
 * 앱승인 권한을 받음
 * 	response = {
 * 		perms:
 * 	}
 * 
 * */
facebook.getPermission = function(scope,callback){
	var permission = {
		method:'permissions.request',
		perms:scope,
		display:'popup'
	};
	
	FB.ui(permission,function(response){
		if(typeof callback == 'function'){
    		callback(response);
    	}
	});
}

/**
 *	페이스북 로그인 하기
 *	popup - 팝업창
 *	page - 페이지 이동
 *	팝업의 경우 자동으로 띄우게 되면 팝업차단에서는 나타나지 않음 
 * 	
 * 	@param String btnId 클릭이벤트를 걸어줄 태그의 ID
 *  @param String scope 앱승인시 사용자로 부터 받을 권한(publish_stream,read_friendlists...)
 *  @param Function callback 로그인 창을 띄운뒤 일어나는 액션을 받아서 실행 시킬 함수 
 *  
 *  @param String btnId 클릭이벤트를 걸어줄 태그의 ID
 *  @param Integer appid 앱의 고유 ID
 *  @param String scope 앱승인시 사용자로 부터 받을 권한(publish_stream,read_friendlists...)
 *  @param String redirect 페이스북 로그인 페이지로 이동한뒤 돌아올 url
 *  @param Function callback 로그인 창을 띄운뒤 일어나는 액션을 받아서 실행 시킬 함수
 *  
 * */
facebook.login = {
	popup:function(btnId,scope,callback){
		$('#'+btnId).click(function(){ facebook.doLogin(scope,callback); });		
	}
	,page:function(btnId,appid,scope,redirect,callback){
		$('#'+btnId).click(function(){
			loginUrl = facebook.getLoginUrl(appid,scope,redirect);
			location.href = loginUrl;
		});
		FB.getLoginStatus(function(response) {			
			if(response.status == 'connected'){
	        	if(typeof callback == 'function'){
	        		callback(response);
	        	}
	        }
		});
	}
}

/**
 *  페이스북 oauth가져오기 
 *	response = {
 * 		status:'connected'(connected : 로그인 OK, 앱승인 OK,not_authorized : 로그인 OK, 앱승인 NO, '' : 로그인NO, 앱승인 NO)
 *  	,authResponse:{
 *  		accessToken:'사용자의 accessToken'
 *  		,expiresIn:토큰의 유효기간
 *      	,signedRequest:'암호화된 인증 정보'
 *      	,userID:'페이스북 사용자의 ID'
 *  	}
 * 	}
 * 	
 * 	stauts가 connected가 아니면 authResponse값이 없음
 * 
 * 	@param Function callback 로그인 창을 띄운뒤 일어나는 액션을 받아서 실행 시킬 함수
 * */
facebook.getOauth = function(callback){
	FB.getLoginStatus(function(response){
		callback(response);
	});
}

/**
 * 	페이스북 공유 dialog를 띄움(모바일용)
 * 
 *  @param String url 공유시킬 url
 * 	@param Function callback 로그인 창을 띄운뒤 일어나는 액션을 받아서 실행 시킬 함수
 * */
facebook.openShareDialog = function(url,callback){
	var share = {
		method: 'stream.share',
      	u:encodeURIComponent(url),
	};

	FB.ui(share, function(response){
		if(typeof callback == 'function'){
			callback(response);
		}
	});
}

/**
 * 	페이스북 담벼락에 글쓰기 dialog를 띄움
 * 	option = {to:targetid} 로 하면 targetid로 feed를 남기는 dialog가 뜸
 * 	
 * 	@param String link 담벼락을 클릭하면 이동될 페이지의 url
 * 	@param String name 담벼락 박스에 남게 되는 이름
 *  @param String desc 담벼락 박스에 남게 되는 설명
 *  @param String capt 담벼락 박스에 남게 되는 캡션
 *  @param String pic 담벼락 박스에 남게 되는 이미지의 url
 *  @param Array option 추가로 설정할 데이터
 *  @param Function callback 로그인 창을 띄운뒤 일어나는 액션을 받아서 실행 시킬 함수
 * */
facebook.openFeedDialog = function(link,name,desc,pic,option,callback){
	if(!pic){ pic = 'http://img.tourtips.com/images/fblogos/fb_tourtips_logo_200_200.jpg'; }
	var feed = {
		method: 'feed',
		name: name,
		link: link,
		description: desc,
		picture: pic,
		caption: '친절한 여행가이드, 투어팁스'
	};
	
	if(option){ feed = $.extend(feed,option); }
	
	FB.ui(feed, function(response){
		if(typeof callback == 'function'){
			callback(response);
		}
	});
}

/**
 * 	페이스북에 공유 할 수 있는 팝업창을 띄움
 * 
 * 	@param String link 담벼락을 클릭하면 이동될 페이지의 url
 * 	@param String name 담벼락 박스에 남게 되는 이름
 *  @param String desc 담벼락 박스에 남게 되는 설명
 *  @param String pic 담벼락 박스에 남게 되는 이미지의 url
 * */
facebook.openSharePopUp= function(link,name,desc,pic){
	link = encodeURIComponent(link);
	name = encodeURIComponent(name);
	desc = encodeURIComponent(desc);
	pic  = encodeURIComponent(pic);
	
	var D=626,A=375,C=screen.height,B=screen.width,H=Math.round((B/2)-(D/2)),G=0,F=document,E;
    if(C>A){G=Math.round((C/2)-(A/2))}

	param = 's=100&p[url]='+link+'&p[images][0]='+pic+'&p[title]='+name+'&p[summary]='+desc;

	popUrl = 'https://www.facebook.com/sharer/sharer.php?'+param;
	window.open(popUrl,'sharer','scrollbars=1,toolbar=0,status=0,left='+H+',top='+G+',width=800,height=600');
}

/**
 * 	페이스북 자신의 담벼락에 글쓰기
 * 
 * 	@param String link 담벼락을 클릭하면 이동될 페이지의 url
 * 	@param String name 담벼락 박스에 남게 되는 이름
 * 	@param String message 담벼락에 쓰일 메세지
 * 	@param String desc 담벼락 박스에 남게 되는 설명
 * 	@param String pic 담벼락 박스에 남게 되는 이미지의 url
 * 	@param Function callback 로그인 창을 띄운뒤 일어나는 액션을 받아서 실행 시킬 함수
 * */
facebook.putFeedMe = function(link,name,message,desc,pic,callback){
	FB.getLoginStatus(function(response) {
		if(response.status == 'connected'){			
			if(!pic){ pic = 'http://img.tourtips.com/images/fblogos/fb_tourtips_logo_200_200.jpg'; }
			var feed = {
				method: 'feed',
				name: name,
				link: link,
				description: desc,
				picture: pic,
				message : message				
			};
		
			FB.api('/me/feed', 'post', feed, function(response) {
				if(typeof callback == 'function'){
					callback(response);
				}
			});
		}
	});
}

/**
 * 	친구목록 불러오기
 * 	
 * 	@param Integer uid 페이스북에서 사용되는 사용자의 고유 ID
 * 	@param Fcuntion callback 친구목록을 불러온뒤 실행 할 함수
 * */
facebook.getFriend = function(uid,callback){
	query = 'SELECT uid, name, pic FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1='+uid+')';
	fql = {method:'fql.query',query:query};
	FB.api(fql,function(res){
		callback(res);
	});
}

/**
 * 	투어팁스 사용여부에 따른 친구 목록
 * 	
 * 	@param Integer uid 페이스북에서 사용되는 사용자의 고유 ID
 *  @param Integer type 투어팁스 사용여부 (1:사용함 0:사용안함)
 * 	@param Fcuntion callback 친구목록을 불러온뒤 실행 할 함수
 * */
facebook.getTourtipsFriend = function(uid,type,callback){
	query = 'SELECT uid, name, pic FROM user WHERE is_app_user = '+type+' AND uid IN (SELECT uid2 FROM friend WHERE uid1='+uid+')';
	fql = {method:'fql.query',query:query};
	FB.api(fql,function(res){
		callback(res);
	});
}