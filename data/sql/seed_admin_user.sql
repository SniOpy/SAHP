-- Compte administrateur SAHP (à exécuter dans phpMyAdmin sur la BDD OVH)
-- Identifiant de connexion : admin
-- Mot de passe : celui utilisé lors de la génération du hash (ex. 94Pedzou94)
--
-- Si la ligne existe déjà : DELETE FROM admin_users WHERE username = 'admin';

INSERT INTO admin_users (username, password_hash)
VALUES (
    'admin',
    '$2y$10$rFk3djCEPIW2XnBLbLUnUufhCIo2zjLIw9GLdVHNbe3IbTbbo9WAO'
);
