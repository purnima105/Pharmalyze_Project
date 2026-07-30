CREATE TABLE
  roles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(255) DEFAULT NULL,
    status ENUM ('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT UNSIGNED DEFAULT NULL
  );

INSERT INTO
  roles (role_name, description, status, created_by)
VALUES
  (
    'Admin',
    'Has full access to manage the entire system.',
    'active',
    NULL
  ),
  (
    'Pharmacist',
    'Manages pharmacy inventory and medicine sales.',
    'active',
    NULL
  ),
  (
    'Supplier',
    'Supplies medicines and manages orders.',
    'active',
    NULL
  );

CREATE TABLE
  users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(15) UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password CHAR(32) NOT NULL,
    status ENUM ('active', 'inactive') NOT NULL DEFAULT 'active',
    role_id INT UNSIGNED NOT NULL,
    created_by INT UNSIGNED DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_user_role FOREIGN KEY (role_id) REFERENCES roles (id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_created_by FOREIGN KEY (created_by) REFERENCES users (id) ON UPDATE CASCADE ON DELETE SET NULL
  );