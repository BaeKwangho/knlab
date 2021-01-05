use nexteli;

#오류 메세지 무시
SET SQL_SAFE_UPDATES = 0;

#===========================================================
#=================카테고리 작성================================
#===========================================================

#카테고리 리스트 복사본 생성
CREATE TABLE `nt_categorys_copy` (
  `IDX` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `TYPE` tinyint(1) unsigned DEFAULT '1',
  `CT_NM` varchar(100) DEFAULT NULL,
  `CODE` char(6) DEFAULT NULL,
  `CID` varchar(20) DEFAULT NULL,
  `STAT` tinyint(3) unsigned DEFAULT '0',
  `CD` varchar(10) DEFAULT NULL,
  `PR` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`IDX`)
) ENGINE=InnoDB AUTO_INCREMENT=655 DEFAULT CHARSET=utf8;

#기존 카테고리 가져오기
insert into nt_categorys_copy select * from nt_categorys;

#다른 타입들 없애주기 (code 중복 제거)
select * from nt_categorys_copy; #확인 후 R&D 정책 바로 아래부터 삭제
delete from nt_categorys where idx >256; ##!!!!!!!!!확인필수
delete from nt_categorys where type = 2 ;
delete from nt_categorys where type = 3 ;

# 241% 부분 중복제거
SELECT * FROM nexteli.nt_categorys_copy where CODE like "241%";
delete from nt_categorys where CODE like "241%";
insert into nt_categorys select * from nt_categorys_copy where code like "241%";

#FK지정을 위한 unique 지정
alter table nt_categorys modify column code char(6) unique key;

#===========================================================
#=================코드 부분 작성================================
#===========================================================

#코드 리스트 생성
CREATE TABLE `nt_document_code_list` (
  `IDX` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `PID` int(10) unsigned NOT NULL,
  `CODE` char(6) DEFAULT NULL,
  `STAT` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`IDX`),
  KEY `pid_to_dc` (`PID`),
  KEY `code_to_dc` (`CODE`),
  CONSTRAINT `code_to_dc` FOREIGN KEY (`CODE`) REFERENCES `nt_categorys` (`code`) ON DELETE CASCADE,
  CONSTRAINT `pid_to_dc` FOREIGN KEY (`PID`) REFERENCES `nt_document_list` (`IDX`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6798 DEFAULT CHARSET=utf8;

#없는 value로 인한 FK 실패 반환을 방지
select * from nt_document_list where dc_code not in (select code from nt_categorys);
delete from nt_document_list where dc_code not in (select code from nt_categorys);

#document_list의 code리스트를 옮겨주기
insert into nt_document_code_list ( PID, CODE) select IDX, DC_CODE from nt_document_list;
