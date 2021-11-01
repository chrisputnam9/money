DROP TABLE IF EXISTS external_item;

CREATE TABLE external_item (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,

	item_id varchar(255) NOT NULL,

    institution_name TEXT NOT NULL,
    institution_id varchar(255) NOT NULL,

	access_token TEXT NOT NULL,
	access_token_request_id TEXT NOT NULL,

	UNIQUE (item_id)
);
