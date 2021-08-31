# -*- mode: ruby -*-
# vi: set ft=ruby :


Vagrant.configure("2") do |config|
 
  config.vm.box = "ubuntu/xenial64"

  
  config.vm.define "webserver" do |webserver|
    # These are options specific to the webserver VM
    webserver.vm.hostname = "webserver"
    
   
    webserver.vm.network "forwarded_port", guest: 80, host: 8080, host_ip: "127.0.0.1"
    
   
    webserver.vm.network "private_network", ip: "192.168.2.11"

   
    webserver.vm.synced_folder ".", "/vagrant", owner: "vagrant", group: "vagrant", mount_options: ["dmode=775,fmode=777"]

    webserver.vm.provision "shell", inline: <<-SHELL
      apt-get update
      apt-get install -y apache2 php libapache2-mod-php php-mysql
            
      # Change VM's webserver's configuration to use shared folder.
      # (Look inside test-website.conf for specifics.)
      cp /vagrant/test-website.conf /etc/apache2/sites-available/
      # activate our website configuration ...
      a2ensite test-website
      # ... and disable the default website provided with Apache
      a2dissite 000-default
      # Reload the webserver configuration, to pick up our changes
      service apache2 reload
      sudo apt-get install php7.0-cli -y
      sudo apt-get install libssh2-1 php-ssh2 -y
           
    SHELL
    $script = <<-SCRIPT
    printf "/home/vagrant/.ssh/id_rsa" | ssh-keygen
    echo "yes"
    cat /home/vagrant/.ssh/id_rsa.pub > /vagrant/key.txt

    SCRIPT
   $script2 = <<-SCRIPT
   sudo mkdir /opt/www-files
   sudo cp /home/.ssh/id_rsa.pub /opt/www-files/
   sudo cp /home/.ssh/id_rsa /opt/www-files/
   sudo chown www-data:www-data /opt/www-files/
   sudo su
   sudo chmod 600 /opt/www-files/id.rsa
   sudo chmod 600 /opt/www-files/id.rsa.pub
   sudo chmod 700 /opt/www-files/
   
   chown www-data:www-data /opt/www-files/id_rsa*
   exit

    SCRIPT

    webserver.vm.provision "shell", inline: $script, privileged: false
  
    webserver.vm.provision "shell", inline: <<-SHELL
     sudo rm /opt/www-files -r
     sudo mkdir /opt/www-files
   sudo cp /home/vagrant/.ssh/id_rsa.pub /opt/www-files/
   sudo cp /home/vagrant/.ssh/id_rsa /opt/www-files/
   sudo chown www-data:www-data /opt/www-files/
   
   sudo su
   sudo chown www-data:www-data /opt/www-files/id_rsa.pub
   sudo chown www-data:www-data /opt/www-files/id_rsa
   sudo chmod 600 /opt/www-files/id.rsa
   sudo chmod 600 /opt/www-files/id.rsa.pub
   sudo chmod 700 /opt/www-files
   exit
   SHELL
     
  end
  

# Here is the section for defining the database server, which I have
  
  config.vm.define "processserver" do |processserver|
    processserver.vm.hostname = "processserver"
    # Note that the IP address is different from that of the webserver
    # above: it is important that no two VMs attempt to use the same
    # IP address on the private_network.
    processserver.vm.network "private_network", ip: "192.168.2.13"
    processserver.vm.synced_folder ".", "/vagrant", owner: "vagrant", group: "vagrant", mount_options: ["dmode=775,fmode=777"]
   
    

    processserver.vm.provision "shell", inline: <<-SHELL
      # Update Ubuntu software packages.
      apt-get update
      cat /vagrant/key.txt >> /home/vagrant/.ssh/authorized_keys
      apt-get install python3-pip
      pip3 install syllables
      
    SHELL
  end

  config.vm.define "dbserver" do |dbserver|
    dbserver.vm.hostname = "dbserver"
    # Note that the IP address is different from that of the webserver
    # above: it is important that no two VMs attempt to use the same
    # IP address on the private_network.
    dbserver.vm.network "private_network", ip: "192.168.2.12"
    dbserver.vm.synced_folder ".", "/vagrant", owner: "vagrant", group: "vagrant", mount_options: ["dmode=775,fmode=777"]
    
    dbserver.vm.provision "shell", inline: <<-SHELL
      # Update Ubuntu software packages.
      apt-get update
      
      # We create a shell variable MYSQL_PWD that contains the MySQL root password
      export MYSQL_PWD='insecure_mysqlroot_pw'

      # If you run the `apt-get install mysql-server` command
      # manually, it will prompt you to enter a MySQL root
      # password. The next two lines set up answers to the questions
      # the package installer would otherwise ask ahead of it asking,
      # so our automated provisioning script does not get stopped by
      # the software package management system attempting to ask the
      # user for configuration information.
      echo "mysql-server mysql-server/root_password password $MYSQL_PWD" | debconf-set-selections 
      echo "mysql-server mysql-server/root_password_again password $MYSQL_PWD" | debconf-set-selections

      # Install the MySQL database server.
      apt-get -y install mysql-server

      # Run some setup commands to get the database ready to use.
      # First create a database.
      echo "DROP DATABASE Happy;" | mysql
      echo "DROP DATABASE Sad;" | mysql
      echo "DROP DATABASE Angry;" | mysql
      echo "DROP DATABASE Animal;" | mysql
      echo "DROP DATABASE Coding;" | mysql
      echo "CREATE DATABASE Happy;" | mysql
      echo "CREATE DATABASE Sad;" | mysql
      echo "CREATE DATABASE Angry;" | mysql
      echo "CREATE DATABASE Animal;" | mysql
      echo "CREATE DATABASE Coding;" | mysql

      # Then create a database user "webuser" with the given password.
      echo "CREATE USER 'webuser'@'%' IDENTIFIED BY 'insecure_db_pw';" | mysql

      # Grant all permissions to the database user "webuser" regarding
      # the "fvision" database that we just created, above.
      echo "GRANT ALL PRIVILEGES ON *.* TO 'webuser'@'%'" | mysql
      
      # Set the MYSQL_PWD shell variable that the mysql command will
      # try to use as the database password ...
      export MYSQL_PWD='insecure_db_pw'

      # ... and run all of the SQL within the setup-database.sql file,
      # which is part of the repository containing this Vagrantfile, so you
      # can look at the file on your host. The mysql command specifies both
      # the user to connect as (webuser) and the database to use (fvision).
      cat /vagrant/setup-database.sql | mysql -u webuser Happy
cat /vagrant/setup-database.sql | mysql -u webuser Sad
cat /vagrant/setup-database.sql | mysql -u webuser Angry
cat /vagrant/setup-database.sql | mysql -u webuser Animal
cat /vagrant/setup-database.sql | mysql -u webuser Coding
      # By default, MySQL only listens for local network requests,
      # i.e., that originate from within the dbserver VM. We need to
      # change this so that the webserver VM can connect to the
      # database on the dbserver VM. Use of `sed` is pretty obscure,
      # but the net effect of the command is to find the line
      # containing "bind-address" within the given `mysqld.cnf`
      # configuration file and then to change "127.0.0.1" (meaning
      # local only) to "0.0.0.0" (meaning accept connections from any
      # network interface).
      sed -i'' -e '/bind-address/s/127.0.0.1/0.0.0.0/' /etc/mysql/mysql.conf.d/mysqld.cnf

      # We then restart the MySQL server to ensure that it picks up
      # our configuration changes.
      service mysql restart
    SHELL
  end
end
