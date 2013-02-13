CREATE PROCEDURE CheckPatientId
@doctor_id CHAR (9), @patient_id CHAR (9), @forDiagnosis BIT=1
AS
/*This procedure check if the patient has a visit/had a visit with the doctor. If yes, then it return the details depneding on the forDiagnosis parameter*/
DECLARE @name int;
SET @name = (SELECT COUNT(*)
FROM vw_Doctor_Patient
WHERE patient=@patient_id AND doctor=@doctor_id);
If(@name >0) /*A visit of this patient is registered with this doctor. Hence return the details.*/
BEGIN
If (@forDiagnosis = 1) /*If doctor needs to attend to the visit*/
BEGIN
SELECT p.name, p.date_of_birth, p.blood_group, v.datetime, p.id_number
FROM patient p, visit v
WHERE p.id_number=@patient_id AND v.patient = p.id_number AND v.doctor=@doctor_id AND v.diagnosis iS NULL AND v.datetime >=CONVERT (date, GETDATE())
END
Else /*Doctor needs to access his prescriptions so return the registered prescreption which are in the vw_Doctor_Drug View */
BEGIN
SELECT dp.name, dp.diagnosis, dd.drug, dd.manufacturer, dd.datetime
FROM vw_Doctor_Drug dd, vw_Doctor_Patient dp
WHERE dd.patient=@patient_id AND dd.doctor=@doctor_id AND dp.patient=@patient_id AND dp.doctor=@doctor_id AND dd.datetime = dp.datetime
END
END


CREATE PROCEDURE Patients_Seen
@doctor_id CHAR (9), @StartDate DATETIME=NULL, @EndDate DATETIME=NULL OUTPUT
AS
if(@StartDate IS NULL)
BEGIN
SET @StartDate = GETDATE();
END
SET @EndDate = DATEADD(day,1,@startDate);
BEGIN
SELECT *
FROM vw_Doctor_Patient
WHERE doctor = @doctor_id AND datetime BETWEEN CONVERT (date, @StartDate AND CONVERT (date, @EndDate)
END