CREATE TABLE IF NOT EXISTS  `api_formdesigner_crm` (
  `ID` INT(18) NOT NULL AUTO_INCREMENT,
  `NAME` VARCHAR(255) NULL DEFAULT '',
  `URL` VARCHAR(255) NULL DEFAULT '',
  `HASH` VARCHAR(32) NULL DEFAULT '',
  PRIMARY KEY (`ID`)
);