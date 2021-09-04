# -*- mode: ruby -*-
# vi: set ft=ruby :


Vagrant.configure("2") do |config|
 
  config.vm.box = "ubuntu/xenial64"
  
  #Webserver
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
      #install packages so php can ssh into dbserver
      sudo apt-get install php7.0-cli -y
      sudo apt-get install libssh2-1 php-ssh2 -y
    
    #Script and shell to create SSH keys as user vagrant       
    SHELL
    $script = <<-SCRIPT
    printf "/home/vagrant/.ssh/id_rsa" | ssh-keygen
    echo "yes"
    cat /home/vagrant/.ssh/id_rsa.pub > /vagrant/key.txt
    SCRIPT
    webserver.vm.provision "shell", inline: $script, privileged: false

   #Copy SSH keys into new directory and set privilages for use by www-data user for PHP
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
  
  #Process server
  config.vm.define "processserver" do |processserver|
    processserver.vm.hostname = "processserver"
    processserver.vm.network "private_network", ip: "192.168.2.13"
    processserver.vm.synced_folder ".", "/vagrant", owner: "vagrant", group: "vagrant", mount_options: ["dmode=775,fmode=777"]
    
    processserver.vm.provision "shell", inline: <<-SHELL
      # Update Ubuntu software packages.
      apt-get update
      cat /vagrant/key.txt >> /home/vagrant/.ssh/authorized_keys
      echo "yes" | apt-get install python3-pip
      pip3 install syllables
      
    SHELL
  end

  config.vm.define "dbserver" do |dbserver|
    dbserver.vm.hostname = "dbserver"
    dbserver.vm.network "private_network", ip: "192.168.2.12"
    dbserver.vm.synced_folder ".", "/vagrant", owner: "vagrant", group: "vagrant", mount_options: ["dmode=775,fmode=777"]  
    #Save database out to a dump file upon halt/destroy
    dbserver.trigger.before :destroy, :halt do |trigger|
        trigger.run_remote = {inline: "mysqldump -u root -pinsecure_mysqlroot_pw --all-databases > /vagrant/dump.sql"}
    end
    
    #Populate dbserver database with sql dump file upon vagrant up
    dbserver.trigger.after :up do |trigger|    
        trigger.run_remote = {inline: "mysql -u root -pinsecure_mysqlroot_pw < /vagrant/dump.sql"}
    end

    
    dbserver.vm.provision "shell", inline: <<-SHELL
      # Update Ubuntu software packages.
      apt-get update

      # We create a shell variable MYSQL_PWD that contains the MySQL root password
      export MYSQL_PWD='insecure_mysqlroot_pw'
      echo "mysql-server mysql-server/root_password password $MYSQL_PWD" | debconf-set-selections 
      echo "mysql-server mysql-server/root_password_again password $MYSQL_PWD" | debconf-set-selections

      # Install the MySQL database server.
      apt-get -y install mysql-server

      # Set the MYSQL_PWD shell variable that the mysql command will
      # try to use as the database password ...
      export MYSQL_PWD='insecure_db_pw'

#ADD NEW DATABASE
#echo "CREATE DATABASE {your_dababase};" | mysql
#echo "GRANT ALL PRIVILEGES ON {your_dababase}.* TO 'webuser'@'%'" | mysql
#cat /vagrant/setup-database.sql | mysql -u root -pinsecure_mysqlroot_pw {your_dababase}

      sed -i'' -e '/bind-address/s/127.0.0.1/0.0.0.0/' /etc/mysql/mysql.conf.d/mysqld.cnf

      # We then restart the MySQL server to ensure that it picks up
      # our configuration changes.
      service mysql restart



    SHELL

    

  end
end
