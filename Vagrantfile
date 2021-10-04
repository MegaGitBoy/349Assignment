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
  #config.vm.box = "ubuntu/xenial64"
  

  config.vm.provider :aws do |aws, override|
    # We will gather the data for these three aws configuration
    # parameters from environment variables (more secure than
    # committing security credentials to your Vagrantfile).
    
  
    aws.access_key_id = "ASIA5CFUOESGSEBVYKN3"
    aws.secret_access_key = "TsXs7WKBqol1BKbYM4XSLnQJvDbR1qntsG6TiDkq"
    aws.session_token = "FwoGZXIvYXdzENX//////////wEaDJ5mIuE9wsKSSp4r8SLMAXHgKWvT6yKh5lrj1AmWgZBobxvCeua6vuGokxPv0aLnXMWKNP973xh6PG6y2io6HraukxRUZmE+0lSQjJscwYhBDsiqGwewjuY7pv3/Vra/Wk5ht68HZqrdBhY3CTMNDv22iAOVdR/xc4n0BlSXTLIB6DFRjiv8quX+Exr31kt6NMltZQAhNTTuIp/zvnCTR/10k7gontkav2RCy+YYbDS3q9m/JKEcWgl7okjpzWXaXcM3gHIVYOK8wto+EsI8NdFRKPs/YsbtiUfjDSjX8OmKBjItiCyfiblUlw8uZqaJleG6pnFpox9ZL8u9Qxo9iAgBwcjh7CCNyDTe+CE0ZXe5"
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



   config.vm.provision "shell", inline: <<-SHELL
     apt-get update

      apt install -y python3-pip awscli
    export LC_ALL="en_US.UTF-8"
    pip3 install boto3

      apt-get install -y apache2 php libapache2-mod-php php-mysql  
      cp /vagrant/test-website.conf /etc/apache2/sites-available/
      # activate our website configuration ...
      #a2ensite 000-default
      # ... and disable the default website provided with Apache
      #a2dissite test-website


	a2ensite test-website
      # ... and disable the default website provided with Apache
      a2dissite 000-default
      # Reload the webserver configuration, to pick up our changes
      service apache2 reload



      
      

   SHELL
  
    
   $script = <<-SCRIPT
    mkdir ~/PythonFiles
      mkdir ~/.aws
      


      cp /vagrant/GetData.py ~/PythonFiles
      
      cat /vagrant/credentials.txt > ~/.aws/credentials.txt
      python3 ~/GetData.py > /vagrant/www/index.php
    SCRIPT
    config.vm.provision "shell", inline: $script, privileged: false


end