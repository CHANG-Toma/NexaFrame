-- Supprimer les tables avec CASCADE
DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS articles CASCADE;
DROP TABLE IF EXISTS categories CASCADE;
DROP TABLE IF EXISTS comments CASCADE;
DROP TABLE IF EXISTS likes_users_articles CASCADE;
DROP TABLE IF EXISTS pages CASCADE;
DROP TABLE IF EXISTS settings CASCADE;

-- Créer la table "categories"
CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    label VARCHAR(50)
);

-- Créer la table "settings"
CREATE TABLE settings (
    key VARCHAR(50) PRIMARY KEY,
    value TEXT NOT NULL
);

-- Créer la table "users"
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    login VARCHAR(50) NOT NULL,
    email VARCHAR(320) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL,
    status SMALLINT DEFAULT 0,
    validate BOOLEAN DEFAULT FALSE,
    validation_token VARCHAR(32)
);

-- Créer la table "articles"
CREATE TABLE articles (
    id SERIAL PRIMARY KEY,
    title VARCHAR(170) NOT NULL,
    content TEXT NOT NULL,
    keywords TEXT NOT NULL,
    picture_url VARCHAR(255) NULL,
    id_category INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_creator INT NOT NULL,
    updated_at TIMESTAMP NULL,
    id_updator INT NULL,
    published_at TIMESTAMP DEFAULT NULL,
    FOREIGN KEY (id_category) REFERENCES categories(id),
    FOREIGN KEY (id_creator) REFERENCES users(id),
    FOREIGN KEY (id_updator) REFERENCES users(id)
);

-- Créer la table "likes_users_articles"
CREATE TABLE likes_users_articles (
   id_article INT NOT NULL,
   id_user INT NOT NULL,
   PRIMARY KEY (id_article, id_user),
   FOREIGN KEY (id_article) REFERENCES articles(id),
   FOREIGN KEY (id_user) REFERENCES users(id)
);

-- Créer la table "comments"
CREATE TABLE comments (
    id SERIAL PRIMARY KEY,
    id_article INT,
    id_comment_response INT DEFAULT NULL,
    id_user INT,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    valid BOOLEAN DEFAULT FALSE,
    validate_at TIMESTAMP NULL,
    id_validator INT NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (id_article) REFERENCES articles(id),
    FOREIGN KEY (id_comment_response) REFERENCES comments(id),
    FOREIGN KEY (id_user) REFERENCES users(id),
    FOREIGN KEY (id_validator) REFERENCES users(id)
);

-- Créer la table "pages"
CREATE TABLE pages (
    id SERIAL PRIMARY KEY,
    url VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    meta_description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_creator INT NOT NULL,
    updated_at TIMESTAMP NULL,
    id_updator INT NULL,
    FOREIGN KEY (id_creator) REFERENCES users(id),
    FOREIGN KEY (id_updator) REFERENCES users(id)
);

-- Insérer des données dans la table "categories"
INSERT INTO categories (label) VALUES
('Musique'),
('Sport'),
('Art');

-- Insérer des données dans la table "users"
-- Note: Assurez-vous de remplacer le mot de passe par un hash sécurisé avant l'insertion
INSERT INTO users (login, email, password) VALUES
('admin', 'admin@nexaframe.com', '$$Azerty123!');

-- Insérer des données dans la table "pages"
-- Note : Cette insertion contient du contenu HTML comme exemple. Assurez-vous que le contenu inséré est sécurisé et nettoyé pour éviter les vulnérabilités XSS.
INSERT INTO pages (url, title, content, meta_description, id_creator) VALUES
('/', 'Accueil', 'Votre contenu HTML ici', 'Meta description de la page d''accueil', 1);
