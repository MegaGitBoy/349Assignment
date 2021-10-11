# 349Assignment2
This repository provides a set up of 3 virtual machines using Vagrant and Virtualbox to create a Haiku Generator.

HOW TO USE:

To use the appication first connect to the web server on the address https://ec2-3-80-25-74.compute-1.amazonaws.com/index.php
(Depending on the time that this link is used, the site may have an untrusted SSL certificate, these certificates can take a few 
days to process at the most so if it is untrusted simply proceed to the site anyway). From there you will be greeted by a home page
where you can enter a haiku that you wish to add to the database. There are a selection of 6 themes that you can choose from for your
haiku and the title of your Haiku must be unique within the theme that you have chosen. Any invalid input (such as incorrect syllable
count) will be reported back to you so you may try again. There is also a "Generate" button which you can use to create new haikus. 
The more you enter, the more variation you get! 

(NOTE: The process of determining syllables is not 100% accurate as the programn
overestimates syllables more than underestimates. You may notice certain Haikus within the database can not be re-entered manually
as the programn incorrectly estimates their syllables. These Haikus have been manually added for variation purposes and do not
represent a glitch in the system)

DESIGN OF APPLICATION:

web server: User interface and data entry

database server: Store haikus

processing server: Checks if entered haikus is indeed a haiku

This application has been designed with three virtual machines: Web/interface server, database server, and processing server which are launched to AWS ec2 instances.
When a user connects to the site and enters a haiku, the stanzas are first processed to remove ' as they cause errors
when eventually passed to the syllable estimator script. Within index.php (main site file retrieved from an s3 bucket usint GetData.py) A PHP SSH extension (php-ssh2) then sets up an SSH connection to the processing server that has been automtically set up during the build process. The method works like this: webserver creates SSH public key and uploads to s3 buecket through StoreKey.py, processserver retrieves key and stores in authorized_keys using GetKey.py. On the processing server the python package syllables (https://pypi.org/project/syllables/) has been installed and is used to calculate the syllabes in the stanzas.
These values are sent back to the webserver and the corresponding PHP file then uses the theme (Database name) and title 
(row code) to determine if the entry into the database is valid. If it is, a PDO object in the PHP file is then used to 
set up a connection to the database server where the haiku is split into three tables (first, second, third for each respective stanza) within
the database selected by the theme. When a user clicks the generate button. A random database and title (row) is selected for each stanza
through another PDO MYSQL query and sent back to the webserver to be displayed.

The settings for launcing these instances are in the vagrant file and are the same for all 3 instances. If you were to launch these instances yourself you would need an ec2 keypair which you would need to contact me (yoliboom18@gmail.com) to have access too (security reasons).


MANUAL INTERACTION TO SET UP:

The only manual interactions required in the application is entering in the Ip-addresses of the processserver and dbserver into the index.php file on the webserver to allow for SSH and PDO connections, and the activation of https connections on the apache2 server. As long as the VM’s are started with the webserver first followed by the other two, then these are the only manual interactions needed and can be easily done by SSH’ing into the webserver. The manual setup of the Ip addresses is necessary as these Ip addresses change upon creating a new ec2 instance and can only be found after the instances are created, therefore they must be done manually.

Therefore to run simply do: "vagrant up webserver" followed by "vagrant up processserver", "vagrant up dbserver" (assuming you have the ec2 keypair and set the appropriate key path in the vagrant file)


IMPORTANT NOTE:

If you change the database (Enter a haiku) and want to provision the VM afterwards, you must first either halt the VM (saves database) and 
then start it up again, or you must run “mysqldump -u root -pinsecure_mysqlroot_pw --all-databases > /vagrant/dump.sql” in the processserver VM
before provisioning so the changed database is saved then re-read in on provision. Otherwise, the database would resort back to the old state within the dump.sql file.

FILES:

dump.sql: Saved database

key.txt: Public key sent to process server to set up SSH

setup-database.sql: Code to create new database (theme) used by vagrant file

SyllableCounter.py: Uses syllable package to count syllabes in stanzas passed from webserver

test-website.conf: Basic web config

StoreKey.py: Store webserver public key in s3 bucket

GetKey.py: Get's public key from s3 bucket and stores in authorzied_keys

GetData.py: Get index.php file from s3 bucket and store in webserver to use

Credentials.txt: My AWS credentials






# 349Assignment (OLD COMMITS)
This repository provides a set up of 3 virtual machines using Vagrant and Virtualbox to create a Haiku Generator.

HOW TO USE:

To use the appication first connect to the web server on the address 127.0.0.1:8080. From there you will be greeted by a home page
where you can enter a haiku that you wish to add to the database. There are a selection of 5 themes that you can choose from for your
haiku and the title of your Haiku must be unique within the theme that you have chosen. Any invalid input (such as incorrect syllable
count) will be reported back to you so you may try again. There is also a "Generate" button which you can use to create new haikus. 
The more you enter, the more variation you get! 

(NOTE: The process of determining syllables is not 100% accurate as the programn
overestimates syllables more than underestimates. You may notice certain Haikus within the database can not be re-entered manually
as the programn incorrectly estimates their syllables. These Haikus have been manually added for variation purposes and do not
represent a glitch in the system)

DESIGN OF APPLICATION:

web server: User interface and data entry

database server: Store haikus

processing server: Checks if entered haikus is indeed a haiku

This application has been designed with three virtual machines: Web/interface server, database server, and processing server.
When a user connects to the web server and enters a haiku, the stanzas are first processed to remove ' as they cause errors
when eventually passed to the syllable estimator script. Within index.php (main site file) A PHP SSH extension (php-ssh2) then sets up an SSH connection to the
processing server that has been automtically set up during the build process by a Vagrant file. On the processing server the python
package syllables (https://pypi.org/project/syllables/) has been installed and is used to calculate the syllabes in the stanzas.
These values are sent back to the webserver and the corresponding PHP file then uses the theme (Database name) and title 
(row code) to determine if the entry into the database is valid. If it is, a PDO object in the PHP file is then used to 
set up a connection to the database server where the haiku is split into three tables (first, second, third for each respective stanza) within
the database selected by the theme. When a user clicks the generate button. A random database and title (row) is selected for each stanza
through another PDO MYSQL query and sent back to the webserver to be displayed.


IMPORTANT NOTE:

If you change the database (Enter a haiku) and want to provision the VM afterwards, you must first either halt the VM (saves database) and 
then start it up again, or you must run “mysqldump -u root -pinsecure_mysqlroot_pw --all-databases > /vagrant/dump.sql” in the processserver VM
before provisioning so the changed database is saved then re-read in on provision. Otherwise, the database would resort back to the old state within the dump.sql file.

FILES:

dump.sql: Saved database

key.txt: Public key sent to process server to set up SSH

setup-database.sql: Code to create new database (theme) used by vagrant file

SyllableCounter.py: Uses syllable package to count syllabes in stanzas passed from webserver

test-website.conf: Basic web config
