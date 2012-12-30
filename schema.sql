CREATE TABLE doctor (
id_number CHAR(9) PRIMARY KEY,
name VARCHAR(64) NOT NULL,
contact_number CHAR(8) NOT NULL,
specialization VARCHAR(64) NOT NULL,
department VARCHAR(64)
);

CREATE TABLE patient (
id_number CHAR(9) PRIMARY KEY,
name VARCHAR(64) NOT NULL,
gender VARCHAR(6) NOT NULL CHECK(gender = 'male' OR gender='female'),
contact_number CHAR(8) NOT NULL,
address VARCHAR(128) NOT NULL,
date_of_birth DATETIME NOT NULL,
blood_group VARCHAR(2) CHECK(blood_group = 'A' OR blood_group = 'B' OR blood_group = 'O' OR blood_group = 'AB' OR blood_group = NULL)
);

CREATE TABLE visit (
doctor CHAR(9) REFERENCES doctor(id_number) ON UPDATE CASCADE,
patient CHAR(9) REFERENCES patient(id_number) ON UPDATE CASCADE,
datetime DATETIME DEFAULT GetDate(),
diagnosis TEXT,
PRIMARY KEY (doctor, patient, datetime)
)

CREATE TABLE drug (
commercial_name VARCHAR(64) NOT NULL,
manufacturer VARCHAR(64) NOT NULL,
active_ingredient VARCHAR(256),
selling_unit VARCHAR(32) ,
selling_unit_price DECIMAL(18,2) NOT NULL,
usage_unit VARCHAR(32) NOT NULL,
PRIMARY KEY (commercial_name, manufacturer)
)

CREATE TABLE prescribe (
doctor CHAR(9),
patient CHAR(9),
datetime DATETIME,
drug VARCHAR(64),
manufacturer VARCHAR(64),

FOREIGN KEY (doctor, patient, datetime) REFERENCES visit(doctor, patient, datetime) ON UPDATE CASCADE ON DELETE CASCADE,
FOREIGN KEY (drug, manufacturer) REFERENCES drug(commercial_name, manufacturer) ON UPDATE CASCADE ON DELETE CASCADE,

PRIMARY KEY (doctor, patient, datetime, drug, manufacturer),

period INT NOT NULL,
frequency INT NOT NULL,
dosage INT NOT NULL,
usage_method VARCHAR(32) NOT NULL,
other_instruction TEXT
)


