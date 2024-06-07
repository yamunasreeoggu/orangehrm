sudo su -
dnf update

echo Intstalling Apche Server
dnf install httpd
status_check

echo Start Apche Server
systemctl start httpd
status_check

echo enable Apche Server
systemctl enable httpd
status_check

echo status of Apche Server
systemctl status httpd
status_check

echo restart Apche Server
systemctl restart httpd
status_check

echo copy subscription manager
sudo subscription-manager repos --enable codeready-builder-for-rhel-9-$(arch)-rpms

echo install EPEL
dnf install https://dl.fedoraproject.org/pub/epel/epel-release-latest-9.noarch.rpm
status_check

echo install remirepo
dnf install https://rpms.remirepo.net/enterprise/remi-release-9.rpm
status_check

echo install php
sudo dnf module install php:remi-8.3
status_check

echo check pho installed or not
php -v
status_check

echo install mysql
dnf install mysql-server
status_check

echo start mysql
systemctl start mysqld.service
status_check

echo enable mysql
systemctl enable mysqld.service
status_check

echo check status
systemctl status mysqld.service
status_check

echo restart mysql
systemctl restart mysqld.service
status_check
