# Php 8.3 avec Apache
FROM php:8.3-apache

RUN a2enmod rewrite

# On met à jour les paquets et on installe les dépendances
RUN apt-get update && \
    apt-get install -y libzip-dev git wget libicu-dev --no-install-recommends && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# On install les extensions PHP
RUN docker-php-ext-install pdo mysqli pdo_mysql zip intl

# Installation de composer pour les bundles symfony
RUN wget https://getcomposer.org/download/2.6.6/composer.phar && \
    mv composer.phar /usr/bin/composer && \
    chmod +x /usr/bin/composer

# On copie les fichiers de configuration apache
COPY docker/apache.conf /etc/apache2/sites-enabled/000-default.conf

RUN cat /etc/apache2/sites-enabled/000-default.conf

# On copie le script d'entrée
COPY docker/entrypoint.sh /entrypoint.sh


# On copie les fichiers du projet
COPY . /var/www

# On se place dans le dossier du projet symfony
WORKDIR /var/www

# On donne les droits d'éxécution au script d'entrée
RUN chmod +x /entrypoint.sh

# On lance le serveur apache
CMD ["apache2-foreground"]

# On lance le script d'entrée
ENTRYPOINT ["/entrypoint.sh"]