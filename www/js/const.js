var _facebookApiConfig = {};
_facebookApiConfig.appid = '1573434069558020';
_facebookApiConfig.scope = 'email';

var _googleApiConfig = {};
_googleApiConfig.clientid = '204554475871-9nmdj8389c41363r0gkj3hkm7q0qbgo9.apps.googleusercontent.com';
_googleApiConfig.cookiepolicy = 'single_host_origin';
_googleApiConfig.requestvisibleactions = 'http://schemas.google.com/AddActivity';
_googleApiConfig.scope = 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email';
_googleApiConfig.callback = 'googleOauthCallback';

var _kakaoApiConfig = {};
_kakaoApiConfig.appid = '75a2305678f687eb58368ea538d5e352';

var _ajaxCommonOpt = {};
_ajaxCommonOpt.dataType = 'json';
_ajaxCommonOpt.type     = 'post';
_ajaxCommonOpt.timeout  = 5000;
_ajaxCommonOpt.async    = true;
_ajaxCommonOpt.cache    = true;

var _SNS_TYPE_FACEBOOK = 'FB';
var _SNS_TYPE_GOOGLE   = 'GG';
var _SNS_TYPE_NAVER    = 'NV';