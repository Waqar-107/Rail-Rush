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
/*------------------------------------------------------------------------------------------------------------------*/


