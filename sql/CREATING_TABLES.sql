/*------------------------------------------------------------------------------------------------------------------*/
/*CREATE TABLE FOR ADMINS*/

CREATE TABLE ADMIN
(
	ADMIN_ID INTEGER CONSTRAINT ADMIN_PK PRIMARY KEY,
	EMPLOYEE_ID INTEGER NOT NULL UNIQUE,
	FIRST_NAME VARCHAR2(60) NOT NULL,
	LAST_NAME VARCHAR2(60) NOT NULL,
	EMAIL_ID VARCHAR2(60) NOT NULL ,
	PHONE VARCHAR2(11) NOT NULL,
	A_PASSWORD VARCHAR(50) NOT NULL
);
/*------------------------------------------------------------------------------------------------------------------*/


/*------------------------------------------------------------------------------------------------------------------*/
/*BANK, INSERT USING TRIGGER */
CREATE TABLE BANK
(
	ACCOUNT_NO VARCHAR2(60) CONSTRAINT BANK_PK  PRIMARY KEY,
	B_PASSWORD VARCHAR2(100) NOT NULL,
	TAKA FLOAT NOT NULL 
);

/*------------------------------------------------------------------------------------------------------------------*/
/*BOOKING*/
CREATE TABLE BOOKING
(
	BOOKING_ID INTEGER CONSTRAINT BOOKING_PK PRIMARY KEY, 
	PASSENGER_ID INTEGER NOT NULL,
	BDATE DATE NOT NULL,
	SEAT_NO VARCHAR2(25) NOT NULL,
	PRICE FLOAT NOT NULL,
	TRIP_ID INTEGER NOT NULL,

	FOREIGN KEY(TRIP_ID) REFERENCES TRIP(TRIP_ID) ON DELETE CASCADE
);
/*------------------------------------------------------------------------------------------------------------------*/


/*------------------------------------------------------------------------------------------------------------------*/
/*TABLE TO STORE COMPLAINTS  AND THEIR REPLIES*/
CREATE TABLE COMPLAINT
(
	COMPLAINT_ID INTEGER CONSTRAINT COMPLAINT_PK PRIMARY KEY, 
	COMPLAINANT INTEGER NOT NULL,
	TRAIN_ID INTEGER NOT NULL,
	RESPONDENT INTEGER,
	STATUS INTEGER DEFAULT 0,
	REPLY VARCHAR2(250),
	TRIP_DATE DATE NOT NULL,
	MESSAGE VARCHAR2(250) NOT NULL
);
/*------------------------------------------------------------------------------------------------------------------*/


/*------------------------------------------------------------------------------------------------------------------*/
/*EMPLOYEE DETAILS*/
CREATE TABLE EMPLOYEE
(
	EMPLOYEE_ID INTEGER CONSTRAINT EMPLOYEE_PK PRIMARY KEY,
	FIRST_NAME VARCHAR2(25) NOT NULL,
	LAST_NAME VARCHAR2(25) NOT NULL,
	EMAIL VARCHAR2(60),
	PHONE VARCHAR2(15),
	JOIN_DATE DATE NOT NULL,
	SALARY FLOAT NOT NULL,
	JOB_TYPE VARCHAR2(30) NOT NULL
)
/*------------------------------------------------------------------------------------------------------------------*/


/*------------------------------------------------------------------------------------------------------------------*/
/*FARE LIST*/
CREATE TABLE FARE
(
	FARE_ID INTEGER CONSTRAINT FARE_PK PRIMARY KEY,
	STARTING VARCHAR2(30) NOT NULL,
	FINISHING VARCHAR2(30) NOT NULL,
	TRAIN_ID INTEGER NOT NULL,
	STYPE INTEGER NOT NULL,
	PRICE FLOAT NOT NULL
)
/*------------------------------------------------------------------------------------------------------------------*/


/*------------------------------------------------------------------------------------------------------------------*/
/*FREIGHT HISTORY*/
CREATE TABLE FREIGHT
(
	FREIGHT_ID INTEGER CONSTRAINT FREIGHT_PK PRIMARY KEY,
	TRAIN_ID INTEGER NOT NULL,
	TRAILER_NO INTEGER NOT NULL,
	COMPANY_NAME VARCHAR2(50) NOT NULL,
	WEIGHT FLOAT NOT NULL,
	INSIDE VARCHAR2(25) NOT NULL,
	DELIVERY_STATUS INTEGER DEFAULT 0,
	TRIP_DATE DATE NOT NULL
);
/*------------------------------------------------------------------------------------------------------------------*/


/*------------------------------------------------------------------------------------------------------------------*/
/*TABLE TO STORE REFUELING HISTORY*/
CREATE TABLE FUEL
(
	REFUELING_DATE DATE DEFAULT SYSDATE,
	EMPLOYEE_ID INTEGER NOT NULL,
	TRAIN_ID INTEGER NOT NULL,
	QUANTITY FLOAT NOT NULL,
	FCOST FLOAT NOT NULL
);
/*------------------------------------------------------------------------------------------------------------------*/


/*------------------------------------------------------------------------------------------------------------------*/
/*CREATE TABLE FOR THE USERS/PASSENGERS*/

CREATE TABLE PASSENGER
(

	PASSENGER_ID INTEGER CONSTRAINT PASSENGER_PK PRIMARY KEY, 
	FIRST_NAME VARCHAR2(60) NOT NULL,
	LAST_NAME VARCHAR2(60) NOT NULL,
	EMAIL_ID VARCHAR2(60) NOT NULL ,
	PHONE VARCHAR2(11) NOT NULL,
	P_PASSWORD VARCHAR(25) NOT NULL
);
/*------------------------------------------------------------------------------------------------------------------*/


/*------------------------------------------------------------------------------------------------------------------*/
/*REVENUE*/
CREATE TABLE REVENUE
(
	RDAY VARCHAR2(20) CONSTRAINT REVENUE_PK PRIMARY KEY,
	EARNING FLOAT
);
/*------------------------------------------------------------------------------------------------------------------*/


/*------------------------------------------------------------------------------------------------------------------*/
/*SEATS, WILL BE INSERTED USING TRIGGER AFTER INSERTION IN TRIP*/
CREATE TABLE SEAT
(
	SEAT_ID VARCHAR2(25) CONSTRAINT SEAT_PK PRIMARY KEY,
	FARE_ID INTEGER NOT NULL,
	SOLD INTEGER DEFAULT 0
);
/*------------------------------------------------------------------------------------------------------------------*/


/*------------------------------------------------------------------------------------------------------------------*/
/*TENDER*/
CREATE TABLE TENDER_HISTORY
(
	TENDER_ID INTEGER CONSTRAINT TENDER_PK PRIMARY KEY,
	COMPANY VARCHAR2(60) NOT NULL,
	EMAIL VARCHAR2(50) NOT NULL,
	TDATE DATE NOT NULL,
	COSTING INTEGER NOT NULL,
	WORK_DESCRIPTION VARCHAR2(250) NOT NULL
);

CREATE TABLE  TENDER_DES
(
	TENDER_ID INTEGER CONSTRAINT TENDER_DES_PK PRIMARY KEY,
	DESCRIPTION VARCHAR2(200),
	EXP_TIE DATE NOT NULL,
);

CREATE TABLE TENDER_OFFER
(
	TENDER_ID INTEGER,
	COMPANY VARCHAR2(50) NOT NULL,
	EMAIL1 VARCHAR2(50) UNIQUE NOT NULL,
	EMAIL2 VARCHAR2(50) UNIQUE NOT NULL,
	PHONE1 VARCHAR2(50) UNIQUE NOT NULL,
	PHONE2 VARCHAR2(50) UNIQUE NOT NULL,
	COSTING INTEGER NOT NULL,

	FOREIGN KEY(TENDER_ID) REFERENCES TENDER_DES(TENDER_ID) ON DELETE CASCADE
);
/*------------------------------------------------------------------------------------------------------------------*/


/*------------------------------------------------------------------------------------------------------------------*/
/*PARTIAL TRAIN-DETAIL*/
CREATE TABLE TRAIN
(
	TRAIN_ID INTEGER CONSTRAINT TRAIN_PK PRIMARY KEY,
	TRAIN_NAME VARCHAR2(50) NOT NULL,
	EMPLOYEE_ID INTEGER UNIQUE,
	DEPARTURE VARCHAR2(30) NOT NULL,
	ARRIVAL VARCHAR2(30) NOT NULL,
	COMPARTMENT INTEGER NOT NULL,
	FIRST_CLASS INTEGER,
	SECOND_CLASS INTEGER,
	THIRD_CLASS INTEGER,
	CARGO INTEGER
);
/*------------------------------------------------------------------------------------------------------------------*/


/*------------------------------------------------------------------------------------------------------------------*/
/*TRIP DETAIL*/
CREATE TABLE TRIP
(
	CALC_ID INTEGER NOT NULL,
	TRIP_ID INTEGER CONSTRAINT TRIP_PK PRIMARY KEY,
	TRAIN_ID INTEGER NOT NULL,
	TRIP_DATE DATE NOT NULL,
	TRIP_TIME VARCHAR2(20) NOT NULL,
	STARTING VARCHAR2(60) NOT NULL,
	DESTINATION VARCHAR2(60) NOT NULL
)
/*------------------------------------------------------------------------------------------------------------------*/



/*------------------------------------------------------------------------------------------------------------------*/