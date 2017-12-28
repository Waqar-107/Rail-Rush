CREATE OR REPLACE TRIGGER ADD_RETURN_TRIP
AFTER INSERT
ON TRIP
DECLARE
	DEPART VARCHAR2(30);
	ARRIVE VARCHAR2(30);
	TID NUMBER;
	TDATE DATE;
	TRAIN_NO NUMBER;
BEGIN
	FOR VAR IN (SELECT * FROM INSERTED);
	LOOP
		TID:=VAR.TRIP_ID;
		TRAIN_NO;=VAR.TRAIN_ID;
		TDATE:=VAR.TRIP_DATE;
		DEPART:=DESTINATION;
		ARRIVE:=STARTING;
	END LOOP;

	INSERT INTO TRIP VALUES(TID+1,TRAIN_NO,TDATE+2,DEPART,ARRIVE);

