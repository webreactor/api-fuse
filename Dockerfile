FROM webreactor/nginx-php:v0.0.1

RUN echo 'Etc/UTC' | tee /etc/timezone && dpkg-reconfigure --frontend noninteractive tzdata

