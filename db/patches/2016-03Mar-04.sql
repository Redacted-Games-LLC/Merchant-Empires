


INSERT INTO `goods` (`caption`, `level`, `tech`, `type`) VALUES ('Spring Gun', '10', '500000', '1');
INSERT INTO `weapons` (`caption`, `good`, `racks`, `stations`, `accuracy`, `volley`, `ammunition`, `general_damage`, `shield_damage`, `armor_damage`) VALUES ('Spring Gun', (select record_id from goods where caption = 'Spring Gun'), '0', '1', '0.77', '2', (select record_id from goods where caption = 'Ceramics'), '0', '0', '10');


