DROP TABLE IF EXISTS attribute;
DROP TABLE IF EXISTS transaction;
DROP TABLE IF EXISTS transaction_status;
DROP TABLE IF EXISTS transaction_category;
DROP TABLE IF EXISTS transaction_classification;

DROP TABLE IF EXISTS account;
DROP TABLE IF EXISTS account_classification;

CREATE TABLE account_classification (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,

    title varchar(1023) NOT NULL
);

CREATE TABLE account (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,

    title varchar(1023) NOT NULL,
    account_number varchar(255) NOT NULL,

    classification int(11) NOT NULL,

    FOREIGN KEY (classification) REFERENCES account_classification (id)
);


CREATE TABLE transaction_category (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,

    title varchar(1023) NOT NULL
);

CREATE TABLE transaction_status (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,

    title varchar(1023) NOT NULL
);

CREATE TABLE transaction_classification (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,

    title varchar(1023) NOT NULL
);


CREATE TABLE transaction (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,

    amount DECIMAL(10,4) NOT NULL,
    date_occurred DATETIME NOT NULL,
    image TEXT NOT NULL,

    status int(11) NOT NULL,
    classification int(11) NOT NULL,
    category int(11) NOT NULL,
    account_from int(11) NOT NULL,
    account_to int(11) NOT NULL,

    FOREIGN KEY (classification) REFERENCES transaction_classification (id),
    FOREIGN KEY (category) REFERENCES transaction_category (id),
    FOREIGN KEY (account_from) REFERENCES account (id),
    FOREIGN KEY (account_to) REFERENCES account (id)
);


CREATE TABLE attribute (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    date_created DATETIME NOT NULL,
    date_updated DATETIME NOT NULL,

    title varchar(1023) NOT NULL,
    value TEXT NOT NULL,

    transaction int(11) NOT NULL,

    FOREIGN KEY (transaction) REFERENCES transaction (id)
);
