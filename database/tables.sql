CREATE VIEW view_column_types AS
SELECT
    TABLE_NAME,
    COLUMN_NAME,
    CASE
        WHEN DATA_TYPE = 'bigint' THEN 'BIGINT'
        WHEN DATA_TYPE = 'int' THEN 'INT'
        WHEN DATA_TYPE = 'varchar' THEN 'VARCHAR'
        ELSE DATA_TYPE
    END AS DATA_TYPE
FROM
    INFORMATION_SCHEMA.COLUMNS;

CREATE TABLE national_identifier_type (
    id bigint UNSIGNED NOT NULL AUTO_INCREMENT, PRIMARY KEY (id) USING BTREE,
    name varchar(50) NOT NULL,
    country varchar(50) NOT NULL,
    description TEXT,
    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO national_identifier_type (name, country, description) VALUES
('DNI', 'Argentina', 'Documento Nacional de Identidad'),
('CUIL', 'Argentina', 'Código Único de Identificación Laboral'),
('CUIT', 'Argentina', 'Código Único de Identificación Tributaria para personas jurídicas y trabajadores independientes'),
('RFC', 'México', 'Registro Federal de Contribuyentes'),
('CURP', 'México', 'Clave Única de Registro de Población'),
('INE', 'México', 'Identificación oficial emitida por el Instituto Nacional Electoral'),
('SSN', 'Estados Unidos', 'Social Security Number'),
('NIT', 'Colombia', 'Número de Identificación Tributaria'),
('Cédula de Ciudadanía', 'Colombia', 'Documento de identidad de ciudadanos colombianos'),
('RUT', 'Chile', 'Rol Único Tributario'),
('Cédula de Identidad', 'Chile', 'Documento de identidad chileno'),
('NIE', 'España', 'Número de Identidad de Extranjero para residentes no españoles'),
('NIF', 'España', 'Número de Identificación Fiscal'),
('CPF', 'Brasil', 'Cadastro de Pessoas Físicas'),
('Cédula de Identidad', 'Venezuela', 'Documento de identidad de los ciudadanos venezolanos'),
('CUI', 'Guatemala', 'Código Único de Identificación de los ciudadanos guatemaltecos');

CREATE TABLE role (
    id bigint UNSIGNED NOT NULL AUTO_INCREMENT, PRIMARY KEY (id) USING BTREE,
    active tinyint(1) DEFAULT 1,
    name varchar(50) NOT NULL, -- Nombre del rol (e.g., admin, user, manager)
    description TEXT, -- Descripción opcional para explicar el rol
    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE user (
    id bigint UNSIGNED NOT NULL AUTO_INCREMENT, PRIMARY KEY (id) USING BTREE,
    active tinyint(1) DEFAULT 1,
    role_id bigint UNSIGNED NOT NULL,
    employee_id bigint UNSIGNED,
    password varchar(100) NOT NULL,
    last_login timestamp NULL,
    reset_token varchar(255),
    reset_token_expiry timestamp NULL,
    created_by bigint UNSIGNED,
    updated_by bigint UNSIGNED,
    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE employee (
    id bigint UNSIGNED NOT NULL AUTO_INCREMENT, PRIMARY KEY (id) USING BTREE,
    active tinyint(1) DEFAULT 1,
    picture TEXT,
    name varchar(255) NOT NULL,
    position varchar(100),
    salary decimal(10, 2),
    hire_date date,
    termination_date date,
    start_time time,
    end_time time,
    address varchar(255),
    city varchar(100),
    state varchar(100),
    zip_code varchar(20),
    country varchar(100),
    phone varchar(50),
    email varchar(100),
    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by bigint UNSIGNED,
    updated_by bigint UNSIGNED
);

ALTER TABLE employee
ADD INDEX fk_employee_created_by_user_id (created_by) USING BTREE,
ADD CONSTRAINT fk_employee_created_by_user_id FOREIGN KEY (created_by) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT,
ADD INDEX fk_employee_updated_by_user_id (updated_by) USING BTREE,
ADD CONSTRAINT fk_employee_updated_by_user_id FOREIGN KEY (updated_by) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT;

ALTER TABLE user
ADD INDEX fk_user_role_id (role_id) USING BTREE,
ADD CONSTRAINT fk_user_role_id FOREIGN KEY (role_id) REFERENCES role (id) ON UPDATE RESTRICT ON DELETE RESTRICT,
ADD INDEX fk_user_employee_id (employee_id) USING BTREE,
ADD CONSTRAINT fk_user_employee_id FOREIGN KEY (employee_id) REFERENCES employee (id) ON UPDATE CASCADE ON DELETE CASCADE,
ADD INDEX fk_user_created_by_user_id (created_by) USING BTREE,
ADD CONSTRAINT fk_user_created_by_user_id FOREIGN KEY (created_by) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT,
ADD INDEX fk_user_updated_by_user_id (updated_by) USING BTREE,
ADD CONSTRAINT fk_user_updated_by_user_id FOREIGN KEY (updated_by) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT;

CREATE TABLE product (
    id bigint UNSIGNED NOT NULL AUTO_INCREMENT, PRIMARY KEY (id) USING BTREE,
    active tinyint(1) DEFAULT 1,
    picture TEXT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price decimal(10, 2) DEFAULT 0.00,
    cost decimal(10, 2) DEFAULT 0.00,
    stock INTEGER DEFAULT 0,
    discount decimal(10, 2) DEFAULT 0.00,
    barcode varchar(100),
    category_id bigint UNSIGNED,
    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by bigint UNSIGNED,
    updated_by bigint UNSIGNED
);

ALTER TABLE product
ADD INDEX fk_product_created_by_user_id (created_by) USING BTREE,
ADD CONSTRAINT fk_product_created_by_user_id FOREIGN KEY (created_by) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT,
ADD INDEX fk_product_updated_by_user_id (updated_by) USING BTREE,
ADD CONSTRAINT fk_product_updated_by_user_id FOREIGN KEY (updated_by) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT;

CREATE TABLE branch (
    id bigint UNSIGNED NOT NULL AUTO_INCREMENT, PRIMARY KEY (id) USING BTREE,
    active tinyint(1) DEFAULT 1,
    name varchar(100) NOT NULL,
    address varchar(255),
    city varchar(100),
    state varchar(100),
    zip_code varchar(20),
    country varchar(100),
    phone varchar(50),
    email varchar(100),
    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by bigint UNSIGNED,
    updated_by bigint UNSIGNED
);

ALTER TABLE branch
ADD INDEX fk_branch_created_by_user_id (created_by) USING BTREE,
ADD CONSTRAINT fk_branch_created_by_user_id FOREIGN KEY (created_by) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT,
ADD INDEX fk_branch_updated_by_user_id (updated_by) USING BTREE,
ADD CONSTRAINT fk_branch_updated_by_user_id FOREIGN KEY (updated_by) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT;

CREATE TABLE customer (
    id bigint UNSIGNED NOT NULL AUTO_INCREMENT, PRIMARY KEY (id) USING BTREE,
    active tinyint(1) DEFAULT 1,
    picture TEXT,
    name varchar(255) NOT NULL,
    email varchar(255),
    phone varchar(20),
    address varchar(255),
    city varchar(100),
    state varchar(100),
    zip_code varchar(20),
    country varchar(100),
    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by bigint UNSIGNED,
    updated_by bigint UNSIGNED
);

ALTER TABLE customer
ADD INDEX fk_customer_created_by_user_id (created_by) USING BTREE,
ADD CONSTRAINT fk_customer_created_by_user_id FOREIGN KEY (created_by) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT,
ADD INDEX fk_customer_updated_by_user_id (updated_by) USING BTREE,
ADD CONSTRAINT fk_customer_updated_by_user_id FOREIGN KEY (updated_by) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT;

CREATE TABLE relation_customer_branch (
    id bigint UNSIGNED NOT NULL AUTO_INCREMENT, PRIMARY KEY (id) USING BTREE,
    customer_id bigint UNSIGNED NOT NULL,
    branch_id bigint UNSIGNED NOT NULL,
    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by bigint UNSIGNED,
    updated_by bigint UNSIGNED
);

ALTER TABLE relation_customer_branch
ADD INDEX fk_relation_customer_branch_customer_id (customer_id) USING BTREE,
ADD CONSTRAINT fk_relation_customer_branch_customer_id FOREIGN KEY (customer_id) REFERENCES customer (id) ON UPDATE CASCADE ON DELETE CASCADE,
ADD INDEX fk_relation_customer_branch_branch_id (branch_id) USING BTREE,
ADD CONSTRAINT fk_relation_customer_branch_branch_id FOREIGN KEY (branch_id) REFERENCES branch (id) ON UPDATE CASCADE ON DELETE CASCADE,
ADD INDEX fk_relation_customer_branch_created_by_user_id (created_by) USING BTREE,
ADD CONSTRAINT fk_relation_customer_branch_created_by_user_id FOREIGN KEY (created_by) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT,
ADD INDEX fk_relation_customer_branch_updated_by_user_id (updated_by) USING BTREE,
ADD CONSTRAINT fk_relation_customer_branch_updated_by_user_id FOREIGN KEY (updated_by) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT;

CREATE TABLE category (
    id bigint UNSIGNED NOT NULL AUTO_INCREMENT, PRIMARY KEY (id) USING BTREE,
    active tinyint(1) DEFAULT 1,
    name varchar(255) NOT NULL,
    description TEXT,
    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by bigint UNSIGNED,
    updated_by bigint UNSIGNED
);

ALTER TABLE category
ADD INDEX fk_category_created_by_user_id (created_by) USING BTREE,
ADD CONSTRAINT fk_category_created_by_user_id FOREIGN KEY (created_by) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT,
ADD INDEX fk_category_updated_by_user_id (updated_by) USING BTREE,
ADD CONSTRAINT fk_category_updated_by_user_id FOREIGN KEY (updated_by) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT;

CREATE TABLE relation_product_category (
    id bigint UNSIGNED NOT NULL AUTO_INCREMENT, PRIMARY KEY (id) USING BTREE,
    product_id bigint UNSIGNED NOT NULL,
    category_id bigint UNSIGNED NOT NULL,
    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by bigint UNSIGNED,
    updated_by bigint UNSIGNED
);

ALTER TABLE relation_product_category
ADD INDEX fk_relation_product_category_product_id (product_id) USING BTREE,
ADD CONSTRAINT fk_relation_product_category_product_id FOREIGN KEY (product_id) REFERENCES product (id) ON UPDATE RESTRICT ON DELETE RESTRICT,
ADD INDEX fk_relation_product_category_category_id (category_id) USING BTREE,
ADD CONSTRAINT fk_relation_product_category_category_id FOREIGN KEY (category_id) REFERENCES category (id) ON UPDATE RESTRICT ON DELETE RESTRICT,
ADD INDEX fk_relation_product_category_created_by_user_id (created_by) USING BTREE,
ADD CONSTRAINT fk_relation_product_category_created_by_user_id FOREIGN KEY (created_by) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT,
ADD INDEX fk_relation_product_category_updated_by_user_id (updated_by) USING BTREE,
ADD CONSTRAINT fk_relation_product_category_updated_by_user_id FOREIGN KEY (updated_by) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT;

CREATE TABLE supplier (
    id bigint UNSIGNED NOT NULL AUTO_INCREMENT, PRIMARY KEY (id) USING BTREE,
    active tinyint(1) DEFAULT 1,
    picture TEXT,
    name varchar(255) NOT NULL,
    email varchar(255),
    phone varchar(20),
    address varchar(255),
    city varchar(100),
    state varchar(100),
    zip_code varchar(20),
    country varchar(100),
    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by bigint UNSIGNED,
    updated_by bigint UNSIGNED
);

ALTER TABLE supplier
ADD INDEX fk_supplier_created_by_user_id (created_by) USING BTREE,
ADD CONSTRAINT fk_supplier_created_by_user_id FOREIGN KEY (created_by) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT,
ADD INDEX fk_supplier_updated_by_user_id (updated_by) USING BTREE,
ADD CONSTRAINT fk_supplier_updated_by_user_id FOREIGN KEY (updated_by) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT;

CREATE TABLE relation_product_supplier (
    id bigint UNSIGNED NOT NULL AUTO_INCREMENT, PRIMARY KEY (id) USING BTREE,
    product_id bigint UNSIGNED NOT NULL,
    supplier_id bigint UNSIGNED NOT NULL,
    price decimal(10, 2) DEFAULT 0.00,
    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by bigint UNSIGNED,
    updated_by bigint UNSIGNED
);

ALTER TABLE relation_product_supplier
ADD INDEX fk_relation_product_supplier_product_id (product_id) USING BTREE,
ADD CONSTRAINT fk_relation_product_supplier_product_id FOREIGN KEY (product_id) REFERENCES product (id) ON UPDATE CASCADE ON DELETE CASCADE,
ADD INDEX fk_relation_product_supplier_supplier_id (supplier_id) USING BTREE,
ADD CONSTRAINT fk_relation_product_supplier_supplier_id FOREIGN KEY (supplier_id) REFERENCES supplier (id) ON UPDATE CASCADE ON DELETE CASCADE,
ADD INDEX fk_relation_product_supplier_created_by_user_id (created_by) USING BTREE,
ADD CONSTRAINT fk_relation_product_supplier_created_by_user_id FOREIGN KEY (created_by) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT,
ADD INDEX fk_relation_product_supplier_updated_by_user_id (updated_by) USING BTREE,
ADD CONSTRAINT fk_relation_product_supplier_updated_by_user_id FOREIGN KEY (updated_by) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT;

CREATE TABLE relation_employee_branch (
    id bigint UNSIGNED NOT NULL AUTO_INCREMENT, PRIMARY KEY (id) USING BTREE,
    employee_id bigint UNSIGNED NOT NULL,
    branch_id bigint UNSIGNED NOT NULL,
    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by bigint UNSIGNED,
    updated_by bigint UNSIGNED
);

ALTER TABLE relation_employee_branch
ADD INDEX fk_relation_employee_branch_employee_id (employee_id) USING BTREE,
ADD CONSTRAINT fk_relation_employee_branch_employee_id FOREIGN KEY (employee_id) REFERENCES employee (id) ON UPDATE CASCADE ON DELETE CASCADE,
ADD INDEX fk_relation_employee_branch_branch_id (branch_id) USING BTREE,
ADD CONSTRAINT fk_relation_employee_branch_branch_id FOREIGN KEY (branch_id) REFERENCES branch (id) ON UPDATE CASCADE ON DELETE CASCADE,
ADD INDEX fk_relation_employee_branch_created_by_user_id (created_by) USING BTREE,
ADD CONSTRAINT fk_relation_employee_branch_created_by_user_id FOREIGN KEY (created_by) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT,
ADD INDEX fk_relation_employee_branch_updated_by_user_id (updated_by) USING BTREE,
ADD CONSTRAINT fk_relation_employee_branch_updated_by_user_id FOREIGN KEY (updated_by) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT;

CREATE TABLE payment_method (
    id bigint UNSIGNED NOT NULL AUTO_INCREMENT, PRIMARY KEY (id) USING BTREE,
    name varchar(50) NOT NULL,
    description TEXT,
    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by bigint UNSIGNED,
    updated_by bigint UNSIGNED
);

ALTER TABLE payment_method
ADD INDEX fk_payment_method_created_by_user_id (created_by) USING BTREE,
ADD CONSTRAINT fk_payment_method_created_by_user_id FOREIGN KEY (created_by) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT,
ADD INDEX fk_payment_method_updated_by_user_id (updated_by) USING BTREE,
ADD CONSTRAINT fk_payment_method_updated_by_user_id FOREIGN KEY (updated_by) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT;

INSERT INTO payment_method (name) VALUES
('Efectivo'),
('Tarjeta de Credito'),
('Tarjeta de Debito'),
('Transferencia'),
('Cheque'),
('Mercado Pago');

CREATE TABLE sale (
    id bigint UNSIGNED NOT NULL AUTO_INCREMENT, PRIMARY KEY (id) USING BTREE,
    customer_id bigint UNSIGNED,
    branch_id bigint UNSIGNED,
    payment_method_id bigint UNSIGNED NOT NULL,
    total_amount decimal(10, 2) NOT NULL,
    sale_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_by bigint UNSIGNED,
    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

ALTER TABLE sale
ADD INDEX fk_sale_customer_id (customer_id) USING BTREE,
ADD CONSTRAINT fk_sale_customer_id FOREIGN KEY (customer_id) REFERENCES customer (id) ON UPDATE CASCADE ON DELETE SET NULL,
ADD INDEX fk_sale_branch_id (branch_id) USING BTREE,
ADD CONSTRAINT fk_sale_branch_id FOREIGN KEY (branch_id) REFERENCES branch (id) ON UPDATE CASCADE ON DELETE SET NULL,
ADD INDEX fk_sale_payment_method_id (payment_method_id) USING BTREE,
ADD CONSTRAINT fk_sale_payment_method_id FOREIGN KEY (payment_method_id) REFERENCES payment_method (id) ON UPDATE CASCADE ON DELETE RESTRICT,
ADD INDEX fk_sale_created_by_user_id (created_by) USING BTREE,
ADD CONSTRAINT fk_sale_created_by_user_id FOREIGN KEY (created_by) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT;

CREATE TABLE relation_sale_product (
    sale_id bigint UNSIGNED NOT NULL,
    product_id bigint UNSIGNED NOT NULL,
    quantity int NOT NULL,
    price_at_sale decimal(10, 2) NOT NULL
);

ALTER TABLE relation_sale_product
ADD INDEX fk_relation_sale_product_sale_id (sale_id) USING BTREE,
ADD CONSTRAINT fk_relation_sale_product_sale_id FOREIGN KEY (sale_id) REFERENCES sale (id) ON UPDATE CASCADE ON DELETE CASCADE,
ADD INDEX fk_relation_sale_product_product_id (product_id) USING BTREE,
ADD CONSTRAINT fk_relation_sale_product_product_id FOREIGN KEY (product_id) REFERENCES product (id) ON UPDATE CASCADE ON DELETE CASCADE;

CREATE TABLE price_list (
    id bigint UNSIGNED NOT NULL AUTO_INCREMENT, PRIMARY KEY (id) USING BTREE,
    branch_id bigint UNSIGNED NOT NULL,
    product_id bigint UNSIGNED NOT NULL,
    price decimal(10, 2) NOT NULL,
    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by bigint UNSIGNED,
    updated_by bigint UNSIGNED
);

ALTER TABLE price_list
ADD INDEX fk_price_list_branch_id (branch_id) USING BTREE,
ADD CONSTRAINT fk_price_list_branch_id FOREIGN KEY (branch_id) REFERENCES branch (id) ON UPDATE CASCADE ON DELETE CASCADE,
ADD INDEX fk_price_list_product_id (product_id) USING BTREE,
ADD CONSTRAINT fk_price_list_product_id FOREIGN KEY (product_id) REFERENCES product (id) ON UPDATE CASCADE ON DELETE CASCADE,
ADD INDEX fk_price_list_created_by_user_id (created_by) USING BTREE,
ADD CONSTRAINT fk_price_list_created_by_user_id FOREIGN KEY (created_by) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT,
ADD INDEX fk_price_list_updated_by_user_id (updated_by) USING BTREE,
ADD CONSTRAINT fk_price_list_updated_by_user_id FOREIGN KEY (updated_by) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT;

CREATE TABLE inventory (
    id bigint UNSIGNED NOT NULL AUTO_INCREMENT, PRIMARY KEY (id) USING BTREE,
    branch_id bigint UNSIGNED NOT NULL,
    product_id bigint UNSIGNED NOT NULL,
    stock INTEGER DEFAULT 0,
    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by bigint UNSIGNED,
    updated_by bigint UNSIGNED
);

ALTER TABLE inventory
ADD INDEX fk_inventory_branch_id (branch_id) USING BTREE,
ADD CONSTRAINT fk_inventory_branch_id FOREIGN KEY (branch_id) REFERENCES branch (id) ON UPDATE CASCADE ON DELETE CASCADE,
ADD INDEX fk_inventory_product_id (product_id) USING BTREE,
ADD CONSTRAINT fk_inventory_product_id FOREIGN KEY (product_id) REFERENCES product (id) ON UPDATE CASCADE ON DELETE CASCADE,
ADD INDEX fk_inventory_created_by_user_id (created_by) USING BTREE,
ADD CONSTRAINT fk_inventory_created_by_user_id FOREIGN KEY (created_by) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT,
ADD INDEX fk_inventory_updated_by_user_id (updated_by) USING BTREE,
ADD CONSTRAINT fk_inventory_updated_by_user_id FOREIGN KEY (updated_by) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT;