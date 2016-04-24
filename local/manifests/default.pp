###########################################################
#     Initial Update
###########################################################
exec { "apt-get update":
  command => "/usr/bin/apt-get update"
}
package { "python-software-properties":
  ensure => present,
  require => Exec["apt-get update"],
}


###########################################################
#     Hosts File
###########################################################
file { "/etc/hosts":
  ensure  => file,
  mode    => 644,
  source  => "/vagrant/local/config/hosts",
}


###########################################################
#     Mail Setup
###########################################################
package {"sendmail":
  ensure => present,
  require => Exec["apt-get update"],
}

file { "/etc/mail/sendmail.conf":
  ensure  => file,
  mode    => 644,
  source  => "/vagrant/local/config/sendmail.conf",
  require => Package["sendmail"],
}

###########################################################
#     Apache
###########################################################
file { "/etc/apache2/envvars":
  ensure  => file,
  mode    => 644,
  source  => "/vagrant/local/config/apache-envvars",
  require => Package["apache2"],
}

package { "apache2":
  ensure => present,
  require => Exec["apt-get update"],
}

file { "/etc/apache2/sites-available/000-default.conf":
  ensure  => file,
  mode    => 644,
  source  => "/vagrant/local/config/apache-default-site",
  require => Package["apache2"],
  notify  => Service["apache2"],
}

exec { "a2enmod rewrite":
  command => "/usr/sbin/a2enmod rewrite",
  require => Package["apache2"],
  notify  => Service["apache2"],
}

exec { "a2enmod ssl":
  command => "/usr/sbin/a2enmod ssl",
  require => Package["apache2"],
  notify  => Service["apache2"],
}

exec { "a2enmod env":
  command => "/usr/sbin/a2enmod env",
  require => Package["apache2"],
  notify  => Service["apache2"],
}

exec { "a2enmod headers":
  command => "/usr/sbin/a2enmod headers",
  require => Package["apache2"],
  notify  => Service["apache2"],
}

service { "apache2":
  ensure => running,
  require => Package["apache2"],
}

file { "/var/app":
  ensure => link,
  mode => 0777,
  target => "/media/app",
  notify => Service["apache2"],
  force  => true,
}

###########################################################
#     PHP
###########################################################
exec { "add-apt-php":
  command => "/usr/bin/add-apt-repository ppa:ondrej/php",
  require => Package["python-software-properties"],
}

exec { "apt-get-update-php":
  command => "/usr/bin/apt-get update"
}

exec { "purge-php5":
  command => "/usr/bin/apt-get purge php5-common -y",
  require => Exec["apt-get-update-php"],
}

package { "php7.0":
  ensure => present,
  require => Exec["purge-php5"],
}

package { "libapache2-mod-php7.0":
  ensure => present,
  require => [ Package["php7.0"], Package["apache2"] ],
  notify => Service["apache2"],
}

package { "php7.0-curl":
  ensure => present,
  require => Package["libapache2-mod-php7.0"],
  notify => Service["apache2"],
}

package { "php7.0-gd":
  ensure => present,
  require => Package["libapache2-mod-php7.0"],
  notify => Service["apache2"],
}

package { "php7.0-mcrypt":
  ensure => present,
  require => Package["libapache2-mod-php7.0"],
  notify => Service["apache2"],
}

package { "php7.0-mbstring":
  ensure => present,
  require => Package["libapache2-mod-php7.0"],
  notify => Service["apache2"],
}

file { "/etc/php/7.0/apache2/php.ini":
  ensure => file,
  mode   => 644,
  source => "/vagrant/local/config/php.ini",
  require => [ Package["php7.0"], Package["apache2"] ],
  notify => Service["apache2"],
}

###########################################################
#     MySQL
###########################################################
package {"mysql-server":
  ensure => present,
  require => Exec["apt-get update"],
}

file { "/etc/mysql/my.cnf":
  ensure  => file,
  mode    => 644,
  source  => "/vagrant/local/config/mysql-my.cnf",
  require => Package["mysql-server"],
  notify  => Service["mysql"],
}

service { "mysql":
  ensure    => running,
  require   => Package["mysql-server"],
}

package { "php7.0-mysql":
  ensure => present,
  require => [ Package["php7.0"], Package["mysql-server"] ],
  notify => [ Service["apache2"], Service["mysql"] ],
}

###########################################################
#     PHPMyAdmin
###########################################################

package { "phpmyadmin":
  ensure => present,
  require => [ Package["php7.0-mysql"], Package["apache2"]],
  notify => [ Service["apache2"], Service["mysql"] ],
}

file { "/etc/phpmyadmin":
  ensure  => directory,
  mode    => 755,
  require => [Package['phpmyadmin'], Package["apache2"]],
}

file { "/etc/phpmyadmin/apache.conf":
  ensure  => file,
  mode    => 644,
  source  => "/vagrant/local/config/phpmyadmin-apache.conf",
  require => File['/etc/phpmyadmin'],
  notify => Service["apache2"],
}

file { "/etc/apache2/conf-enabled/phpmyadmin.conf":
  ensure => link,
  target => "/etc/phpmyadmin/apache.conf",
  notify => Service["apache2"],
  require => File['/etc/phpmyadmin/apache.conf'],
}

file { "/etc/phpmyadmin/config.inc.php":
  ensure  => file,
  mode    => 644,
  source  => "/vagrant/local/config/phpmyadmin-config.inc.php",
  require => File['/etc/phpmyadmin'],
  notify => Service["apache2"],
}

###########################################################
#     Tools
###########################################################

package { "vim":
  ensure => present,
}

###########################################################
#     MySQL Database Scripts
###########################################################
exec{ "initialize database":
  command => "/usr/bin/mysql -u root < /media/database/migration_scripts/init.sql",
  require => Service["mysql"],
}

file { "/home/vagrant/dump.sh":
  ensure => link,
  target => "/media/database/migration_scripts/dump.sh",
}

file { "/home/vagrant/load.sh":
  ensure => link,
  target => "/media/database/migration_scripts/load.sh",
}

file { "/media/database/migration_scripts/dump.sh":
  mode => 755,
}

file { "/media/database/migration_scripts/load.sh":
  mode => 755,
}
