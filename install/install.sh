# Command file to set some system parameters for InOutDev site

# 1. Allow  'www-data' user (Apache2) to perform som root actions
usermod -a -G sudo www-data

# Allow 'www-data' user to not request for the password from User
echo '# Allow running anything as www-data without password' > /etc/sudoers.d/www-data
echo 'www-data ALL=NOPASSWD: ALL' >> /etc/sudoers.d/www-data

