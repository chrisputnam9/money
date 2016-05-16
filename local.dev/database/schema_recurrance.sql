DROP TABLE IF EXISTS transaction_recurring;
DROP TABLE IF EXISTS transaction_recurring_transaction;

CREATE TABLE transaction_recurring (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,

    transaction_id int(11) NOT NULL,

    date_end DATETIME NOT NULL,
    recurrance_type varchar(255) NOT NULL,
    recurrance_data TEXT,

    FOREIGN KEY (transaction_id) REFERENCES transaction (id)

);

CREATE TABLE transaction_recurring_transaction (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    transaction_recurring_id int(11) NOT NULL,
    transaction_id int(11) NOT NULL,

    FOREIGN KEY (transaction_recurring_id) REFERENCES transaction_recurring (id),
    FOREIGN KEY (transaction_id) REFERENCES transaction (id)
);
