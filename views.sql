CREATE VIEW vw_Doctor_Drug AS
SELECT ps.drug, ps.manufacturer, d.active_ingredient, ps.doctor, ps.patient, ps.datetime
FROM prescribe ps, drug d
WHERE ps.drug = d.commercial_name AND ps.manufacturer = d.manufacturer

CREATE VIEW vw_Doctor_Patient
AS
SELECT p.name, p.gender, v.datetime, v.diagnosis, v.doctor, v.patient
FROM visit v, patient p
WHERE p.id_number = v.patient

CREATE VIEW vw_Patient AS
SELECT v.patient, v.datetime, d.name, v.diagnosis, p.drug, p.manufacturer, p.period, p.frequency, p.dosage, p.usage_method, p.other_instruction
FROM visit v, prescribe p, doctor d
WHERE v.patient=p.patient AND v.doctor=p.doctor AND v.doctor=d.id_number AND v.datetime=p.datetime

CREATE VIEW doctor_basic AS
SELECT name, specialization, department
FROM doctor
