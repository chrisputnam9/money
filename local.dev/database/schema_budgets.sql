DROP TABLE IF EXISTS budget;

CREATE TABLE budget (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,

    category int(11) NOT NULL,

    timespan varchar(255) NOT NULL,
    amount decimal(10,4) NOT NULL,

    FOREIGN KEY (category) REFERENCES transaction_category (id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);
