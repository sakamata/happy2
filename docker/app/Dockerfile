FROM centos:centos7

RUN yum update -y
RUN yum install -y epel-release
RUN rpm -Uvh http://rpms.famillecollet.com/enterprise/remi-release-7.rpm
RUN yum install -y git httpd httpd-devel httpd-tools mod_ssl wget \
    ImageMagick ImageMagick-devel ImageMagick-perl gcc make

RUN yum install --enablerepo=remi,remi-php56 -y php php-devel php-mbstring php-pdo php-gd php-xml php-mcrypt phpmyadmin php-pear

RUN cd /usr/bin && wget http://pear.php.net/go-pear.phar && php go-pear.phar

RUN echo include_path=".:/usr/PEAR" >> /etc/php.ini && \
    sed -i -e 's/-C -n -q/-C -q/' /usr/bin/pecl && \
    pecl install imagick && \
    echo extension=imagick.so >> /etc/php.ini && \
    sed -i -e 's/^;default_charset/default_charset/' /etc/php.ini && \
    sed -i -e 's/upload_max_filesize = 2M/upload_max_filesize = 5M/' /etc/php.ini && \
    echo 'date.timezone = Asia/Tokyo' >> /etc/php.ini && \
    echo 'mbstring.language = Japanese' >> /etc/php.ini && \
    echo 'mbstring.internal_encoding = UTF-8' >> /etc/php.ini && \
    echo 'mbstring.http_input = pass' >> /etc/php.ini && \
    echo 'mbstring.encoding_translation = Off' >> /etc/php.ini && \
    echo 'mbstring.detect_order =  UTF-8,EUC-JP,SJIS,JIS,ASCII' >> /etc/php.ini && \
    echo 'mbstring.substitute_character = none;' >> /etc/php.ini

RUN yum install -y telnet mysql sudo bind-utils net-tools expect

RUN cd /var/www && git clone --depth 1 https://github.com/sakamata/happy2.git

RUN mkdir -p /var/www/hidden  /var/hidden/ && \
    ln -s /var/www/happy2 /var/www/happy2/

COPY info.php /var/hidden/
COPY virtual.conf /etc/httpd/conf.d/
COPY docker-entrypoint.sh /
COPY htdigest-admin.exp /

# create admin user
# add digest password of DigestController.php
RUN expect htdigest-admin.exp
RUN DIGEST=`cat /tmp/htdigest | awk -F: '{print $3}'` && \
    sed -i -e "/admin929/a 'admin' => '$DIGEST'," /var/www/happy2/controllers/DigestController.php
# update admin password
RUN HASH=`echo -n passwordzyx7532cba | sha1sum | awk '{print $1}'` && \
    sql="update adtbus set usPs = \"$HASH\" where usId = \"admin\";" && \
    script="/update-admin-password.sh" && \
    echo "#!/bin/sh" > $script && \
    echo "mysql -hdb -uroot -pmysql happy2 -e'$sql'" >> $script && \
    chmod a+x $script

# CMD httpd -DFOREGROUND

EXPOSE 80 443 8000

ENTRYPOINT [ "/docker-entrypoint.sh" ]

