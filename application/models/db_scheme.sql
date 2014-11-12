CREATE TABLE `USER`.`UserData` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Email` varchar(120) NOT NULL comment '이메일주소',
  `Name` varchar(90) NOT NULL comment '사용자이름',
  `PassWord` varchar(20) NOT NULL comment '사용자비밀번호',  
  `Type` char(2) not null default 'AA' comment '사용자 타입 AA : 일반, ZZ : ROOT',
  `Status` char(2) not null default 'AA' comment '사용자 상태 AA : 정상, CA : 노출안함 등',
  `CreateDate` datetime NOT NULL comment '최초 생성일',
  `ModifyDate` datetime NOT NULL comment '최종 수성일',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `USER`.`UserSNS` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL comment '사용자ID',
  `SNSID` varchar(50) NOT NULL comment 'SNSID',  
  `Type` char(2) not null default '' comment 'SNS 연결 타입 FB : 페이스북, NV : 네이버, TW : 트위터, GG : 구글',
  `Status` char(2) not null default 'AA' comment '사용자 상태 AA : 정상, CA : 사용안함 등',
  `CreateDate` datetime NOT NULL comment '최초 생성일',
  `ModifyDate` datetime NOT NULL comment '최종 수성일',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `BOARD`.`Board` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Type` char(2) not null comment '게시판 타입', 
  `Name` varchar(120) NOT NULL comment '게시판 이름',
  `Desc` varchar(240) NOT NULL comment '게시판 설명',  
  `UseComment` char(1) not null default 'Y' comment '댓글 사용유무',
  `UseRecommend` char(1) not null default 'Y' comment '추천 사용유무',
  `UseFile` char(1) not null default 'Y' comment '첨부파일 사용 유무',
  `FileSize` int(11) not null default 0 comment '첨부파일 1개당 허용 용량(단위 MB)',
  `FileCount` int(11) not null default 0 comment '첨부파일 최대 첨부 갯수',
  `Status` char(2) not null default 'AA' comment '게시판 상태 AA : 정상, CA : 노출안함 등',
  `CreateDate` datetime NOT NULL comment '최초 작성일',
  `ModifyDate` datetime NOT NULL comment '최종 수성일',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `BOARD`.`Category` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `BoardID` int(11) not null comment '게시판 ID', 
  `Name` varchar(120) NOT NULL comment '게시판 이름',
  `Order` int(11) NOT NULL comment '정렬순서',
  `Status` char(2) not null default 'AA' comment '카테고리 상태 AA : 정상, CA : 노출안함 등',
  `CreateDate` datetime NOT NULL comment '최초 작성일',
  `ModifyDate` datetime NOT NULL comment '최종 수성일',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `BOARD`.`Data` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `BoardID` int(11) not null comment '게시판 ID',
  `CategoryID` int(11) not null comment '카테고리 ID',
  `UserID` int(11) not null comment '사용자 ID',
  `Title` varchar(300) NOT NULL comment '게시글 제목',
  `Content` text not null default '' comment '게시글 내용',
  `ViewCount` int(11) not null default 0 comment '조회수',
  `CommentCount` int(11) not null default 0 comment '댓글 갯수',
  `RecommendCount` int(11) not null default 0 comment '추천 횟수',
  `Status` char(2) not null default 'AA' comment '게시글 상태 AA : 정상, CA : 노출안함 등',
  `CreateDate` datetime NOT NULL comment '최초 작성일',
  `ModifyDate` datetime NOT NULL comment '최종 수성일',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `BOARD`.`Comment` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `DataID` int(11) not null comment '게시글 ID',  
  `UserID` int(11) not null comment '사용자 ID',
  `Comment` varchar(900) NOT NULL comment '댓글 내용',
  `Status` char(2) not null default 'AA' comment '댓글 상태 AA : 정상, CA : 노출안함 등',
  `CreateDate` datetime NOT NULL comment '최초 작성일',
  `ModifyDate` datetime NOT NULL comment '최종 수성일',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `BOARD`.`Recommend` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `DataID` int(11) not null comment '게시글 ID',  
  `UserID` int(11) not null comment '사용자 ID',  
  `Status` char(2) not null default 'AA' comment '추천 상태 AA : 추천, BA : 추천취소 등',
  `CreateDate` datetime NOT NULL comment '최초 작성일',
  `ModifyDate` datetime NOT NULL comment '최종 수성일',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `BOARD`.`File` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `DataID` int(11) not null comment '게시글 ID',
  `Type` char(2) not null comment '첨부파일 종류 AA : 첨부, BA : 내용에 포함된 이미지',
  `Name` varchar(300) NOT NULL comment '원본 파일명',
  `Path` varchar(100) NOT NULL comment '파일위치',
  `Extention` varchar(10) NOT NULL comment '파일 확장자',
  `Size` int(11) not null comment '파일 사이즈',
  `Status` char(2) not null default 'AA' comment '파일 상태 AA : 정상, CA : 삭제함 등',
  `CreateDate` datetime NOT NULL comment '최초 작성일',
  `ModifyDate` datetime NOT NULL comment '최종 수성일',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;