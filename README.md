# Manuale di Installazione

Open 2.0
## Scopo
La manualistica contenuta è puramente tecnica e per tanto **deve essere letta ed eseguita da personale esperto** onde evitare danni alla macchina che deve ospitare il software

- Si tenga conto della necessità per alcune operazioni di avere **accesso a internet** con la macchina su cui si procederà all’installazione
- Alcuni comandi richiedono l’accesso di SuperUser, si consiglia caldamente l’uso dell’utente root o di pari privilegi per eseguire questa installazione
- **Sulla base della configurazione** della macchina **alcuni percorsi potrebbero non essere corretti** e per tanto vanno verificati accuratamente prima di eseguirli
- I valori scelti nelle configurazioni di **PHP** sono valori **Raccomandati**, sta alla discrezione del tecnico se sceglierne di differenti

## Prerequisiti
 - Abilitare la connessione a **internet** per il download dei pacchetti necessari
    - export http_proxy=http://reglomb.proxy:8080
    - export https_proxy=http://reglomb.proxy:8080
 - Verificare che la versione di **PHP** Rispetti I requisiti (8.x.x * nei sistemi operativi RedHat potrebbe essere necessario collegare repository non ufficiali. *)
   - php -v
 - Verificare che la versione di **Mysql** Rispetti I requisiti (5.7)
   - php -v
 - Verificare che la versione di **Apache** Rispetti I requisiti (2.4.29 * nei sistemi operativi RedHat potrebbe essere necessario collegare repository non ufficiali. *)
   - apache2 –v

## Installazione Requisiti
 - **PHP 8.2**
  - apcu
  - bcmath
  - curl
  - fpm
  - gd
  - imagick
  - intl
  - ldap
  - mbstring
  - mysql
  - soap
  - xml
  - zip
   
 - **GIT**
   - yum install git.x86_64

 - **Composer**
   - php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
   - php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
   - php composer-setup.php
   - php -r "unlink('composer-setup.php');"
   - chmod +x /usr/bin/composer
   - composer –help
   
 - **Estensioni Apache/Lighttpd**
   - rewrite

## Pre-configurazioni Macchina
Queste configurazioni devono essere eseguite per ovviare ad alcune mancanze sulla macchina fornitaci in modo da risolvere alcuni problemi che impedirebbero alla piattaforma di funzionare
 - Modifica **.htaccess** in **/opt/rh/httpd24/root/var/www/html/basic-template/backend/web**
   - RewriteEngine on
   - RewriteCond %{REQUEST_FILENAME} !-f
   - RewriteCond %{REQUEST_FILENAME} !-d
   - RewriteCond $1 !^(index\.php)
   - RewriteRule (.*) index.php?$1 [PT,L,QSA]
   
 - **Abilitazione Modulo PHP**
   - ln -s /opt/rh/httpd24/root/etc/httpd/modules/librh-php82-php8.so/etc/httpd/modules/
   - vi /etc/httpd/conf.modules.d/00-base.conf
   - vi /etc/httpd/conf/httpd.conf
   - apachectl restart
   
- **Configurazione PHP**
  - Configurazioni in /etc/php/8.2/apache2/php.ini
    - memory_limit = 4G
    - upload_max_filesize = 300M
    - post_max_size = 300M
    
## Installazione Piattaforma
- **Git Pull**
  - Clonare il repository della piattaforma.

- **Importare la struttura base delle banche dati**
  - Utilizzare il file "open2github_struttura.sql" per importazione banche dati.
      
- **Configurazione Piattaforma**
  - vi common/config/main-local.php
       ```
       'components' => [
       'db' => [
       'class' => 'yii\db\Connection',
       'dsn' => 'mysql:host=localhost;dbname=DB_NAME_HERE',
       'username' => 'DB_USERNAME_HERE',
       'password' => 'DB_PASSWORD_HERE',
       'charset' => 'utf8',
       ],
       ```
   - vi common/config/params-local.php si modificano le email di sistema
   
## Aggiornamento Piattaforma
- **git pull**
  - Aggiornamenti disponibili dal repository della piattaforma.
- **cd backend/runtime**
  - ci si sposta nella directory della caches
- **rm -R**
  - si rimuovono tutte le caches