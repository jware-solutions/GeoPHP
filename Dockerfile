FROM php:7.3-stretch

# Installs dependences
RUN apt update \
    && apt install -y git

# Create non root user
RUN useradd -ms /bin/bash devuser

USER devuser
WORKDIR /home/devuser

# Installs Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');"

# Allows to run 'source' command
SHELL ["/bin/bash", "-c"]

# Adds composer to .bashrc
RUN echo 'export PATH=$PATH:/home/devuser/' >> ~/.bashrc \
    && echo 'alias composer="composer.phar" ' >> ~/.bashrc \
    && source ~/.bashrc \
    && chown -R devuser ~/.composer/

# Is positioned in the main folder
WORKDIR /home/devuser/geophp

CMD [ "bash" ]