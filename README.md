# 349Assignment
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

If you change the database and want to provision the VM afterwards, you must first either halt the VM (saves database) and 
then start it up again, or you must run “mysqldump -u root -pinsecure_mysqlroot_pw --all-databases > /vagrant/dump.sql” in the processserver VM
before provisioning so the changed database is saved then re-read in on provision. Otherwise, the database would resort back to the old state within the dump.sql file.

FILES:

dump.sql: Saved database

key.txt: Public key sent to process server to set up SSH

setup-database.sql: Code to create new database (theme) used by vagrant file

SyllableCounter.py: Uses syllable package to count syllabes in stanzas passed from webserver

test-website.conf: Basic web config