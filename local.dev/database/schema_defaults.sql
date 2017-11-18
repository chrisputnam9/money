ALTER TABLE transaction
    CHANGE COLUMN image image TEXT NULL,
    CHANGE COLUMN notes notes TEXT NULL;

ALTER TABLE account
    CHANGE COLUMN account_number account_number varchar(255) NULL;
