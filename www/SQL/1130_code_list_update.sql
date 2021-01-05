use nexteli;

#오류 메세지 무시
SET SQL_SAFE_UPDATES = 0;

#===========================================================
#=================UID colunm 추가============================
#===========================================================
alter table nt_document_code_list add `UID` int(10) unsigned;
alter table nt_document_code_list add constraint `uid_to_ct` foreign key 
(`UID`)  REFERENCES `nt_user_list` (`IDX`) ON DELETE CASCADE;

#지정 후 defalut로 2번 작업자 등록. 이전 데이터 한해서 ㅇㅇ..
update nt_document_code_list set UID = 2 where idx>0;

#===========================================================
#=================등록 날짜 colunm 추가=========================
#===========================================================

alter table nt_document_code_list add `DT_WRITE` int(10) unsigned DEFAULT '0';