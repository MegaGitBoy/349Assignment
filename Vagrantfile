# -*- mode: ruby -*-
# vi: set ft=ruby :
class Hash
  def slice(*keep_keys)
    h = {}
    keep_keys.each { |key| h[key] = fetch(key) if has_key?(key) }
    h
  end unless Hash.method_defined?(:slice)
  def except(*less_keys)
    slice(*keys - less_keys)
  end unless Hash.method_defined?(:except)
end



Vagrant.configure("2") do |config|
  config.vm.box = "dummy"

  
config.vm.define "webserver" do |webserver|  
  config.vm.provider :aws do |aws, override|
    # We will gather the data for these three aws configuration
    # parameters from environment variables (more secure than
    # committing security credentials to your Vagrantfile).
    
  
    aws.access_key_id = "ASIA5CFUOESGYQZLP6NR"
    aws.secret_access_key = "KU+uTPwjkKw8sWugHUdYqEYO9EAiGnFDAlUI0Oqh"
    aws.session_token = "FwoGZXIvYXdzENv//////////wEaDPmee59zyptp/PtdcSLMAfxUZVBpD4+TNVZY4P8vAT/SlrOATZKEwbTFjc7YhJCqNzoRrfTToPfB4g3GFJBpkK7CMzDO8q0bi9nrbkegu60bp1ranlhxbWchM0u+RFSS5TgrYOrP9CO7Ibq1vZ//6f65r8p6J9MDC6ajoBd8UneycczMckm7lh++k721JdSySvtd63pyHcIkYR1nDCx+VfVOaNB9zEZiZyZtl9nCtwfhqN+JV4+mOMG7DL7gHcllDgaxA3XJnDeX+mN7DkSAiG30ZjJCaDDWwQtHQyjHj+uKBjItbcKM8iLYuLKykjr2VuZsGkyYUKAEN3xGT/wZFYhfChSBHPlpH7GV+jM6/TOl"
    # The region for Amazon Educate is fixed.
    aws.region = "us-east-1"

    # These options force synchronisation of files to the VM's
    # /vagrant directory using rsync, rather than using trying to use
    # SMB (which will not be available by default).
    override.nfs.functional = false
    override.vm.allowed_synced_folder_types = :rsync

    # Following the lab instructions should lead you to provide values
    # appropriate for your environment for the configuration variable
    # assignments preceded by double-hashes in the remainder of this
    # :aws configuration section.

    # The keypair_name parameter tells Amazon which public key to use.
    aws.keypair_name = "cosc349-2021"
    # The private_key_path is a file location in your macOS account
    # (e.g., ~/.ssh/something).
    # For Windows users, just point to the path where you have downloaded the keypair
    # (e.g., C:\\Users\\leo\\.ssh\\cosc349-2021.ppk). (Use double "\\" for folder path)
    override.ssh.private_key_path = "C:\\Users\\leo\\Downloads\\cosc349-2021.pem"

    # Choose your Amazon EC2 instance type (t2.micro is cheap).
    aws.instance_type = "t2.micro"

    # You need to indicate the list of security groups your VM should
    # be in. Each security group will be of the form "sg-...", and
    # they should be comma-separated (if you use more than one) within
    # square brackets.
    #
    aws.security_groups = ["sg-09a84024fbaef73a4"]

    # For Vagrant to deploy to EC2 for Amazon Educate accounts, it
    # seems that a specific availability_zone needs to be selected
    # (will be of the form "us-east-1a"). The subnet_id for that
    # availability_zone needs to be included, too (will be of the form
    # "subnet-...").
    aws.availability_zone = "us-east-1a"
    aws.subnet_id = "subnet-b3779cff"

    # You need to chose the AMI (i.e., hard disk image) to use. This
    # will be of the form "ami-...".
    # 
    # If you want to use Ubuntu Linux, you can discover the official
    # Ubuntu AMIs: https://cloud-images.ubuntu.com/locator/ec2/
    #
    # You need to get the region correct, and the correct form of
    # configuration (probably amd64, hvm:ebs-ssd, hvm).
    #
    aws.ami = "ami-0133407e358cc1af0"

    # If using Ubuntu, you probably also need to uncomment the line
    # below, so that Vagrant connects using username "ubuntu".
    override.ssh.username = "ubuntu"
  end



   webserver.vm.provision "shell", inline: <<-SHELL
     	apt-get update
     	sudo apt-get install php7.0-cli -y
     	sudo apt-get install libssh2-1 php-ssh2 -y
      	apt-get install -y apache2 php libapache2-mod-php php-mysql  

	apt install -y python3-pip awscli
    	export LC_ALL="en_US.UTF-8"
    	pip3 install boto3
      
	cp /vagrant/test-website.conf /etc/apache2/sites-available/


	a2ensite test-website
      	# ... and disable the default website provided with Apache
      	a2dissite 000-default
      	# Reload the webserver configuration, to pick up our changes
      	service apache2 reload



      
      

   SHELL
  
    
   $script = <<-SCRIPT
    mkdir ~/PythonFiles
      mkdir ~/.aws
      
    printf "/home/ubuntu/.ssh/id_rsa" | ssh-keygen
    echo "yes"
    


      cp /vagrant/GetData.py ~/PythonFiles
      cp /vagrant/StoreKey.py ~/PythonFiles
      
      cat /vagrant/credentials.txt > ~/.aws/credentials

      python3 ~/PythonFiles/GetData.py > /vagrant/www/index.php
      python3 ~/PythonFiles/StoreKey.py


    SCRIPT
    webserver.vm.provision "shell", inline: $script, privileged: false
   





#Copy SSH keys into new directory and set privilages for use by www-data user for PHP
   webserver.vm.provision "shell", inline: <<-SHELL
    sudo rm /opt/www-files -r
    sudo mkdir /opt/www-files
    sudo cp /home/ubuntu/.ssh/id_rsa.pub /opt/www-files/
    sudo cp /home/ubuntu/.ssh/id_rsa /opt/www-files/
    sudo chown www-data:www-data /opt/www-files/ 
    sudo su
    sudo chown www-data:www-data /opt/www-files/id_rsa.pub
    sudo chown www-data:www-data /opt/www-files/id_rsa
    sudo chmod 600 /opt/www-files/id_rsa
    sudo chmod 600 /opt/www-files/id_rsa.pub
    sudo chmod 700 /opt/www-files
    exit

SHELL



end
config.vm.box = "dummy"
config.vm.define "processserver" do |processserver|  
  config.vm.provider :aws do |aws, override|
    # We will gather the data for these three aws configuration
    # parameters from environment variables (more secure than
    # committing security credentials to your Vagrantfile).
    
  
    aws.access_key_id = "ASIA5CFUOESGVGRBSTO2"
    aws.secret_access_key = "4yM6bSyQ46STkcW+XmdLR9QHd1NF0tZIGVDg/PNO"
    aws.session_token = "FwoGZXIvYXdzENn//////////wEaDIQ5KfDkCIOVoiHPECLMAR8mwbRNY3iTicX4Kdhxs/GO+luqsvZnBCMAIlOoC8E34+flUPgWTL9OMiOCK8cnZm+WqeuFrgidUuZr/1jdSS9YIZaF7pVdep9GANuBXvZjydtRiE8PtNWUES8TQS0QpkgIUqQqK+FpfDDdAbmVLLsLMcWk/sndtYKBj7U5Vx4WYnSo4e34L0TNZGr8fgu6f7EQkWgogARCdLY7nXYUoX1B0gxAQmdq+ctsACak5kDF+72AfckEIqNoWLCD6RtHqpvZKANPa4r+RnmV0yjQ6OqKBjItJNvsLMQjrT9nVSvJySxeemgPbcwwNvnfLiUToYGtJsxNaH3Rro0mByIWny2n"
    # The region for Amazon Educate is fixed.
    aws.region = "us-east-1"

    # These options force synchronisation of files to the VM's
    # /vagrant directory using rsync, rather than using trying to use
    # SMB (which will not be available by default).
    override.nfs.functional = false
    override.vm.allowed_synced_folder_types = :rsync

    # Following the lab instructions should lead you to provide values
    # appropriate for your environment for the configuration variable
    # assignments preceded by double-hashes in the remainder of this
    # :aws configuration section.

    # The keypair_name parameter tells Amazon which public key to use.
    aws.keypair_name = "cosc349-2021"
    # The private_key_path is a file location in your macOS account
    # (e.g., ~/.ssh/something).
    # For Windows users, just point to the path where you have downloaded the keypair
    # (e.g., C:\\Users\\leo\\.ssh\\cosc349-2021.ppk). (Use double "\\" for folder path)
    override.ssh.private_key_path = "C:\\Users\\leo\\Downloads\\cosc349-2021.pem"

    # Choose your Amazon EC2 instance type (t2.micro is cheap).
    aws.instance_type = "t2.micro"

    # You need to indicate the list of security groups your VM should
    # be in. Each security group will be of the form "sg-...", and
    # they should be comma-separated (if you use more than one) within
    # square brackets.
    #
    aws.security_groups = ["sg-09a84024fbaef73a4"]

    # For Vagrant to deploy to EC2 for Amazon Educate accounts, it
    # seems that a specific availability_zone needs to be selected
    # (will be of the form "us-east-1a"). The subnet_id for that
    # availability_zone needs to be included, too (will be of the form
    # "subnet-...").
    aws.availability_zone = "us-east-1a"
    aws.subnet_id = "subnet-b3779cff"

    # You need to chose the AMI (i.e., hard disk image) to use. This
    # will be of the form "ami-...".
    # 
    # If you want to use Ubuntu Linux, you can discover the official
    # Ubuntu AMIs: https://cloud-images.ubuntu.com/locator/ec2/
    #
    # You need to get the region correct, and the correct form of
    # configuration (probably amd64, hvm:ebs-ssd, hvm).
    #
    aws.ami = "ami-0133407e358cc1af0"

    # If using Ubuntu, you probably also need to uncomment the line
    # below, so that Vagrant connects using username "ubuntu".
    override.ssh.username = "ubuntu"
  end

   

   processserver.vm.provision "shell", inline: <<-SHELL
apt-get update
    	apt install -y python3-pip awscli
    	export LC_ALL="en_US.UTF-8"
    	pip3 install boto3
        pip3 install syllables
        

   SHELL
  

$script = <<-SCRIPT
      mkdir ~/.aws
      cat /vagrant/credentials.txt > ~/.aws/credentials
python3 /vagrant/GetKey.py >> /home/ubuntu/.ssh/authorized_keys
    SCRIPT
    processserver.vm.provision "shell", inline: $script, privileged: false



end

  config.vm.box = "dummy"
  config.vm.define "dbserver" do |dbserver|
    config.vm.provider :aws do |aws, override|
    # We will gather the data for these three aws configuration
    # parameters from environment variables (more secure than
    # committing security credentials to your Vagrantfile).
    
  
    aws.access_key_id = "ASIA5CFUOESGYQZLP6NR"
    aws.secret_access_key = "KU+uTPwjkKw8sWugHUdYqEYO9EAiGnFDAlUI0Oqh"
    aws.session_token = "FwoGZXIvYXdzENv//////////wEaDPmee59zyptp/PtdcSLMAfxUZVBpD4+TNVZY4P8vAT/SlrOATZKEwbTFjc7YhJCqNzoRrfTToPfB4g3GFJBpkK7CMzDO8q0bi9nrbkegu60bp1ranlhxbWchM0u+RFSS5TgrYOrP9CO7Ibq1vZ//6f65r8p6J9MDC6ajoBd8UneycczMckm7lh++k721JdSySvtd63pyHcIkYR1nDCx+VfVOaNB9zEZiZyZtl9nCtwfhqN+JV4+mOMG7DL7gHcllDgaxA3XJnDeX+mN7DkSAiG30ZjJCaDDWwQtHQyjHj+uKBjItbcKM8iLYuLKykjr2VuZsGkyYUKAEN3xGT/wZFYhfChSBHPlpH7GV+jM6/TOl"
    # The region for Amazon Educate is fixed.
    aws.region = "us-east-1"

    # These options force synchronisation of files to the VM's
    # /vagrant directory using rsync, rather than using trying to use
    # SMB (which will not be available by default).
    override.nfs.functional = false
    override.vm.allowed_synced_folder_types = :rsync

    # Following the lab instructions should lead you to provide values
    # appropriate for your environment for the configuration variable
    # assignments preceded by double-hashes in the remainder of this
    # :aws configuration section.

    # The keypair_name parameter tells Amazon which public key to use.
    aws.keypair_name = "cosc349-2021"
    # The private_key_path is a file location in your macOS account
    # (e.g., ~/.ssh/something).
    # For Windows users, just point to the path where you have downloaded the keypair
    # (e.g., C:\\Users\\leo\\.ssh\\cosc349-2021.ppk). (Use double "\\" for folder path)
    override.ssh.private_key_path = "C:\\Users\\leo\\Downloads\\cosc349-2021.pem"

    # Choose your Amazon EC2 instance type (t2.micro is cheap).
    aws.instance_type = "t2.micro"

    # You need to indicate the list of security groups your VM should
    # be in. Each security group will be of the form "sg-...", and
    # they should be comma-separated (if you use more than one) within
    # square brackets.
    #
    aws.security_groups = ["sg-09a84024fbaef73a4"]

    # For Vagrant to deploy to EC2 for Amazon Educate accounts, it
    # seems that a specific availability_zone needs to be selected
    # (will be of the form "us-east-1a"). The subnet_id for that
    # availability_zone needs to be included, too (will be of the form
    # "subnet-...").
    aws.availability_zone = "us-east-1a"
    aws.subnet_id = "subnet-b3779cff"

    # You need to chose the AMI (i.e., hard disk image) to use. This
    # will be of the form "ami-...".
    # 
    # If you want to use Ubuntu Linux, you can discover the official
    # Ubuntu AMIs: https://cloud-images.ubuntu.com/locator/ec2/
    #
    # You need to get the region correct, and the correct form of
    # configuration (probably amd64, hvm:ebs-ssd, hvm).
    #
    aws.ami = "ami-0133407e358cc1af0"

    # If using Ubuntu, you probably also need to uncomment the line
    # below, so that Vagrant connects using username "ubuntu".
    override.ssh.username = "ubuntu"
  end
    dbserver.trigger.before :destroy, :halt do |trigger|
        trigger.run_remote = {inline: "mysqldump -u root -pinsecure_mysqlroot_pw --all-databases > /vagrant/dump.sql"}
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
      mysql -u root -pinsecure_mysqlroot_pw < /vagrant/dump.sql
      mysql -u root -pinsecure_mysqlroot_pw -e 'FLUSH PRIVILEGES'

      # Set the MYSQL_PWD shell variable that the mysql command will
      # try to use as the database password ...se
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