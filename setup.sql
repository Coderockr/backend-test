DROP DATABASE IF EXISTS investments;
CREATE DATABASE investments;
USE investments;

CREATE TABLE investors (
    cpf VARCHAR(11) NOT NULL,
    name TEXT NOT NULL,

    PRIMARY KEY (cpf)
);

CREATE TABLE investments (
    id INT AUTO_INCREMENT,
    initial_amount INT NOT NULL,
    balance INT NOT NULL,
    creation_date DATE NOT NULL,
    investor_cpf VARCHAR(11),
    
    PRIMARY KEY (id),
    FOREIGN KEY (investor_cpf) REFERENCES investors(cpf) 
);

CREATE EVENT apply_interest
  ON SCHEDULE
    EVERY 1 DAY
    STARTS (TIMESTAMP(CURRENT_DATE) + INTERVAL 1 DAY)
  DO
    UPDATE investments
    SET balance = balance * 1.0052
    WHERE IF(DAY(creation_date) > LAST_DAY(CURRENT_DATE), LAST_DAY(CURRENT_DATE), DAY(creation_date)) = DAY(CURRENT_DATE);
