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
      a2ensite test-websitevagra
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

   sudo mkdir /opt/www-files/
   sudo cp ~/.ssh/id_rsa* /opt/www-files/
   sudo chown www-data:www-data /opt/www-files/
   sudo chmod 600 /opt/www-files/*
   sudo chmod 700 /opt/www-files/
   sudo su
   chown www-data:www-data /opt/www-files/id_rsa*
   exit

    SCRIPT

    webserver.vm.provision "shell", inline: $script, privileged: false
    webserver.vm.provision "shell", inline: $script2, privileged: true
     
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
      printf "yes" | apt-get install python-pip 
      pip install syllables
      
    SHELL
  end
end
