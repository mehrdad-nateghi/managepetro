FROM nginx:stable-alpine

ENV NGINXGROUP=laravel
ENV NGINXUSER=laravel

# This line creates the directory structure for the Nginx web server. The -p flag ensures that all parent directories are also created if they don't exist.
RUN mkdir -p /var/www/html/public

ADD nginx/default.conf /etc/nginx/conf.d/default.conf

# This line modifies the /etc/nginx/nginx.conf file in place using the sed command. It replaces the occurrence of user www-data with user ${NGINXUSER}, effectively changing the user that Nginx runs as to the laravel user defined earlier.
RUN sed -i "s/user www-data/user ${NGINXUSER}/g" /etc/nginx/nginx.conf

# This line creates a new user named laravel with the adduser command. It sets the user's primary group to NGINXGROUP using the -g option and sets the user's shell to /bin/sh using the -s option. The -D option creates the user's home directory.
RUN adduser -g ${NGINXGROUP} -s /bin/sh -D ${NGINXUSER}
