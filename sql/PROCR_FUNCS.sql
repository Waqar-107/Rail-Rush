/*------------------------------------------------------------------------------*/
/*PROCEDURE TO ADD A RETURN TRIP*/
CREATE OR REPLACE 
PROCEDURE "ADD_RETURN_TRIP" (TID  NUMBER)
AS
	TRAIN_NO NUMBER;
	TRP_DATE DATE;
	ARRIVAL VARCHAR2(30);
	DEPARTURE VARCHAR2(30);
BEGIN
	SELECT TRAIN_ID,TRIP_DATE,STARTING,DESTINATION 
	INTO TRAIN_NO,TRP_DATE,ARRIVAL,DEPARTURE
	FROM TRIP 
	WHERE TRIP_ID=TID;

	INSERT INTO TRIP VALUES(TID+1,TRAIN_NO,TRP_DATE+2,DEPARTURE,ARRIVAL);
END;
/*------------------------------------------------------------------------------*/


/*------------------------------------------------------------------------------*/
/*AFTER GIVING A COMPANY TENDER, DELETE THEM FROM OFFER AND STORE IN HISTORY*/
CREATE OR REPLACE 
PROCEDURE "DEAL_WITH_TENDER" (TID  INTEGER, COM  VARCHAR2)
AS
	DES VARCHAR2(250);
	TDATE DATE;
	MAIL VARCHAR2(50);
	MCOSTING INTEGER;
BEGIN
	SELECT T2.EMAIL1,T2.EXP_TIME,T2.COSTING,T1.DESCRIPTION 
	INTO MAIL,TDATE,MCOSTING,DES
	FROM TENDER_DES T1 
	JOIN TENDER_OFFER T2 ON T1.TENDER_ID=T2.TENDER_ID; 
	INSERT INTO TENDER_HISTORY VALUES(TID,COM,MAIL,TDATE,MCOSTING,DES);
	DELETE FROM TENDER_DES WHERE TENDER_ID=TID;
END;
/*------------------------------------------------------------------------------*/


/*------------------------------------------------------------------------------*/
/*------------------------------------------------------------------------------*/
