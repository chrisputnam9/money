DROP TABLE IF EXISTS transaction_recurring_transaction;
DROP TABLE IF EXISTS transaction_recurring;

CREATE TABLE transaction_recurring (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,

    main_transaction_id int(11) NOT NULL,

    date_start DATETIME NULL,
    date_end DATETIME NULL,

    recurrance_type varchar(255) NOT NULL,
    recurrance_data TEXT,

    UNIQUE unique_per_transaction (main_transaction_id),
    FOREIGN KEY (main_transaction_id) REFERENCES transaction (id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

CREATE TABLE transaction_recurring_transaction (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    transaction_recurring_id int(11) NOT NULL,
    transaction_id int(11) NOT NULL,

    FOREIGN KEY (transaction_recurring_id) REFERENCES transaction_recurring (id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY (transaction_id) REFERENCES transaction (id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);
