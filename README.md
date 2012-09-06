preachcaster
============
Dependencies:
Slim
Slim-Extras
Twig
mod_rewrite
cURL

To enable mod_rewrite in Ubuntu: 
sudo a2enmod rewrite
sudo service apache2 restart

Be sure to rename "_config.php" to "config.php" and populate the values

Be sure to set the value of $twigDirectory in class TwigView

Install cURL: sudo apt-get install php5-curl

