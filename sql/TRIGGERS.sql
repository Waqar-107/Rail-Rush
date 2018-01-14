/*-----------------------------------------------------------------------------------------------------------------------------*/
/*SEAT _INSERTION: AFTER ADDING TO TRIP AUTOMATICALLY INSERTS SEAT*/
DECLARE
	SEAT_ID VARCHAR2(25);
	TDATE DATE;
	FC INTEGER;
	SC INTEGER;
	TC INTEGER;
	CA INTEGER;
	TID INTEGER;
	CID INTEGER;
	TNID INTEGER;
	FID INTEGER;
BEGIN
	SELECT MAX(CALC_ID) INTO CID
	FROM TRIP;

	SELECT TRIP_ID INTO TID FROM TRIP WHERE CALC_ID=CID;

	SELECT  TRAIN_ID,TRIP_DATE INTO TNID,TDATE
	FROM TRIP
	WHERE TRIP_ID=TID;

	SELECT FIRST_CLASS,SECOND_CLASS,THIRD_CLASS,CARGO INTO  FC,SC,TC,CA
	FROM TRAIN WHERE TRAIN_ID=TNID;

	/*FIRST*/
	IF FC >0 THEN
		SELECT FARE_ID INTO FID FROM FARE WHERE TRAIN_ID=TNID AND STYPE=1;
		FOR I IN 1..FC
		LOOP
			SEAT_ID:=TO_CHAR(TDATE,'DD-MM-YYYY') || ('#') || (TNID) || ('#1#')  ||  I;
			INSERT INTO SEAT VALUES(SEAT_ID,FID,0);
		END LOOP;
	END IF;
	

	/*SECOND*/
	IF SC >0 THEN
		SELECT FARE_ID INTO FID FROM FARE WHERE TRAIN_ID=TNID AND STYPE=2;
		FOR I IN 1..SC
		LOOP
			SEAT_ID:=TO_CHAR(TDATE,'DD-MM-YYYY') || ('#') || (TNID) || ('#2#')  ||  I;
			INSERT INTO SEAT VALUES(SEAT_ID,FID,0);
		END LOOP;
	END IF;

	/*THIRD*/
	IF TC >0 THEN
		SELECT FARE_ID INTO FID FROM FARE WHERE TRAIN_ID=TNID AND STYPE=3;
		FOR I IN 1..TC
		LOOP
			SEAT_ID:=TO_CHAR(TDATE,'DD-MM-YYYY') || ('#') || (TNID) || ('#3#')  ||  I;
			INSERT INTO SEAT VALUES(SEAT_ID,FID,0);
		END LOOP;
	END IF;

	/*CARGO*/
	IF CA>0 THEN
		SELECT FARE_ID INTO FID FROM FARE WHERE TRAIN_ID=TNID AND STYPE=4;
		FOR I IN 1..CA
		LOOP
			SEAT_ID:=TO_CHAR(TDATE,'DD-MM-YYYY') || ('#') || (TNID) ||  ('#4#')  ||  I;
			INSERT INTO SEAT VALUES(SEAT_ID,FID,0);
		END LOOP;
	END IF;
END;
/*-----------------------------------------------------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------------------------------------------------*/
/*SEAT_DELETION: DELETES SEATS AFTER  DELETING TRIP*/
DECLARE
	S_ID VARCHAR2(25);
	TDATE DATE;
	FC INTEGER;
	SC INTEGER;
	TC INTEGER;
	CA INTEGER;
	TID INTEGER;
	TNID INTEGER;
	FID INTEGER;

	X VARCHAR2(50);
	Y VARCHAR2(50);

	ST VARCHAR2(50);
	EN VARCHAR2(50);

	TT DATE;
	TS DATE;
BEGIN
	TID := :OLD.TRIP_ID;
	TNID := :OLD.TRAIN_ID;
	TDATE := :OLD.TRIP_DATE;
	Y:=  :OLD.TRIP_TIME;
	ST:= :OLD.STARTING;
	EN:= :OLD.DESTINATION;

	SELECT FIRST_CLASS,SECOND_CLASS,THIRD_CLASS,CARGO INTO  FC,SC,TC,CA
	FROM TRAIN WHERE TRAIN_ID=TNID;

	/*MOVE TO HISTORY*/
	X:=TO_CHAR(TDATE,'DD-MM-YYYY')||' '||Y;
	TT:= TO_DATE(X,'DD-MM-YYYY HH24:MI');

	SELECT SYSDATE INTO TS FROM DUAL;

	IF TS>=TT THEN
		INSERT INTO TRIP_HISTORY VALUES(TNID,TDATE,Y,ST,EN);
	END IF;

	/*FIRST*/
	IF FC >0 THEN
		FOR I IN 1..FC
		LOOP
			S_ID:=TO_CHAR(TDATE,'DD-MM-YYYY') || ('#') || (TNID) || ('#1#')  ||  I;
			DELETE FROM SEAT WHERE SEAT_ID=S_ID;
		END LOOP;
	END IF;
	

	/*SECOND*/
	IF SC >0 THEN
		FOR I IN 1..SC
		LOOP
			S_ID:=TO_CHAR(TDATE,'DD-MM-YYYY') || ('#') || (TNID) || ('#2#')  ||  I;
			DELETE FROM SEAT WHERE SEAT_ID=S_ID;
		END LOOP;
	END IF;

	/*THIRD*/
	IF TC >0 THEN
		FOR I IN 1..TC
		LOOP
			S_ID:=TO_CHAR(TDATE,'DD-MM-YYYY') || ('#') || (TNID) || ('#3#')  ||  I;
			DELETE FROM SEAT WHERE SEAT_ID=S_ID;
		END LOOP;
	END IF;

	/*CARGO*/
	IF CA>0 THEN
		FOR I IN 1..CA
		LOOP
			S_ID:=TO_CHAR(TDATE,'DD-MM-YYYY') || ('#') || (TNID) || ('#4#')  ||  I;
			DELETE FROM SEAT WHERE SEAT_ID=S_ID;
		END LOOP;
	END IF;
END;

/*-----------------------------------------------------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------------------------------------------------*/
/*UPDATE REVENUE*/
DECLARE
	RDATE VARCHAR2(20);
	PRICE FLOAT;
	F FLOAT;
	TR_ID INTEGER;
	TID INTEGER;
BEGIN
	RDATE:=TO_CHAR(SYSDATE,'YYYY-MM-DD');
	PRICE:= :NEW.PRICE;
	TID := :NEW.TRIP_ID;
	
	SELECT TRAIN_ID INTO TR_ID FROM TRIP WHERE TRIP_ID=TID;
	SELECT EARNING INTO F FROM REVENUE WHERE RDAY=RDATE AND TR_ID=TRAIN_ID;

	/*INSERT*/
	IF F IS NOT NULL THEN
		UPDATE REVENUE SET EARNING=(F+PRICE) WHERE RDAY=RDATE AND TR_ID=TRAIN_ID;
	END IF;
EXCEPTION
	WHEN NO_DATA_FOUND THEN
		INSERT INTO REVENUE VALUES(RDATE,PRICE,TR_ID);
END;
