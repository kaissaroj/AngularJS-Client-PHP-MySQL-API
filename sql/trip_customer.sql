CREATE TABLE IF NOT EXISTS tbl_product( id int(11) NOT NULL AUTO_INCREMENT, name varchar(50) NOT NULL, commission_pct int(3) NOT NULL, PRIMARY KEY(id));
CREATE TABLE IF NOT EXISTS tbl_product_price( id int(11) NOT NULL AUTO_INCREMENT, low_num_pers int(1) NOT NULL, high_num_pers int(5) NOT NULL, retail_rate_per_pers int(2) NOT NULL, product_id int(11), PRIMARY KEY(id));
