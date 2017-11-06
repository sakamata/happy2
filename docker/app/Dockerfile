FROM centos:centos7

RUN yum update -y
RUN yum install -y epel-release
RUN rpm -Uvh http://rpms.famillecollet.com/enterprise/remi-release-7.rpm
RUN yum install -y git httpd httpd-devel httpd-tools mod_ssl wget && \
    yum install -y ImageMagick ImageMagick-devel ImageMagick-perl && \
    yum install -y gcc && \
    yum install -y make

RUN yum install --enablerepo=remi,remi-php56 -y php php-devel php-mbstring php-pdo php-gd php-xml php-mcrypt
RUN yum install --enablerepo=remi,remi-php56 -y phpmyadmin php-pear 

RUN cd /usr/bin && wget http://pear.php.net/go-pear.phar && php go-pear.phar

RUN echo include_path=".:/usr/PEAR" >> /etc/php.ini
RUN sed -i -e 's/-C -n -q/-C -q/' /usr/bin/pecl
RUN pecl install imagick
RUN echo extension=imagick.so >> /etc/php.ini

RUN sed -i -e 's/^;default_charset/default_charset/' /etc/php.ini
RUN sed -i -e 's/upload_max_filesize = 2M/upload_max_filesize = 5M/' /etc/php.ini
RUN echo 'date.timezone = Asia/Tokyo' >> /etc/php.ini && \
    echo 'mbstring.language = Japanese' >> /etc/php.ini && \
    echo 'mbstring.internal_encoding = UTF-8' >> /etc/php.ini && \
    echo 'mbstring.http_input = pass' >> /etc/php.ini && \
    echo 'mbstring.encoding_translation = Off' >> /etc/php.ini && \
    echo 'mbstring.detect_order =  UTF-8,EUC-JP,SJIS,JIS,ASCII' >> /etc/php.ini && \
    echo 'mbstring.substitute_character = none;' >> /etc/php.ini

RUN cd /var/www && git clone --depth 1 https://github.com/sakamata/happy2.git

RUN mkdir -p /var/www/hidden  /var/hidden/ && \
    ln -s /var/www/happy2 /var/www/happy2/


COPY info.php /var/hidden/
COPY virtual.conf /etc/httpd/conf.d/


CMD httpd -DFOREGROUND

