UPDATE `ships` SET `racks` = '2' WHERE (`record_id` = '1');
UPDATE `ships` SET `stations` = '2' WHERE (`record_id` = '2');
UPDATE `ships` SET `racks` = '3', `stations` = '0' WHERE (`record_id` = '3');
UPDATE `ships` SET `racks` = '4', `stations` = '0' WHERE (`record_id` = '18');
UPDATE `ships` SET `racks` = '4', `stations` = '1' WHERE (`record_id` = '21');
UPDATE `ships` SET `racks` = '3', `stations` = '1' WHERE (`record_id` = '19');
UPDATE `ships` SET `racks` = '5', `stations` = '0' WHERE (`record_id` = '20');
UPDATE `ships` SET `racks` = '4', `stations` = '1' WHERE (`record_id` = '22');
UPDATE `ships` SET `racks` = '4', `stations` = '2' WHERE (`record_id` = '23');
UPDATE `ships` SET `racks` = '3', `stations` = '2' WHERE (`record_id` = '24');
UPDATE `ships` SET `racks` = '6', `stations` = '1' WHERE (`record_id` = '25');
UPDATE `ships` SET `racks` = '5', `stations` = '2' WHERE (`record_id` = '26');
UPDATE `ships` SET `racks` = '9', `stations` = '0' WHERE (`record_id` = '27');
UPDATE `ships` SET `racks` = '3', `stations` = '1' WHERE (`record_id` = '10');
UPDATE `ships` SET `racks` = '4', `stations` = '1' WHERE (`record_id` = '12');
UPDATE `ships` SET `racks` = '5', `stations` = '1' WHERE (`record_id` = '14');
UPDATE `ships` SET `racks` = '2', `stations` = '3' WHERE (`record_id` = '13');
UPDATE `ships` SET `racks` = '4', `stations` = '4' WHERE (`record_id` = '15');
UPDATE `ships` SET `racks` = '3', `stations` = '3' WHERE (`record_id` = '16');
UPDATE `ships` SET `racks` = '5', `stations` = '4' WHERE (`record_id` = '17');
UPDATE `ships` SET `racks` = '3', `stations` = '3' WHERE (`record_id` = '5');
UPDATE `ships` SET `racks` = '3', `stations` = '3' WHERE (`record_id` = '6');
UPDATE `ships` SET `racks` = '4', `stations` = '5' WHERE (`record_id` = '7');
UPDATE `ships` SET `racks` = '4', `stations` = '4' WHERE (`record_id` = '8');
UPDATE `ships` SET `racks` = '5', `stations` = '5' WHERE (`record_id` = '9');
UPDATE `ships` SET `racks` = `racks` * 2, `stations` = `stations` * 2, `holds` = `holds` * 2, `armor` = `armor` * 2, `shields` = `shields` * 2 where `record_id` > 0;
