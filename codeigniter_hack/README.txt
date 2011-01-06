FROM: http://codeigniter.com/wiki/CI_on_the_command_line/
-----------------------------------------------------------
CI on the command line
I have been working on a few random things in spare time at work, one of them is getting CI to work on the command line. Getting it to accept parameters was much easier than I expected, all it requires is a simple extension to the URI class.

Download
File:MY URI.zip

Installation
Copy the file to application/libraries/

Set-up
In your config, set one of the following values for uri_protocol.

$config['uri_protocol']    = "AUTO"; // Works for web and command line
$config['uri_protocol']    = "CLI"; // Command line only 
Or to have it working on web with a specific uri type and command line at the same time,  change path info to any of the normal CI uri types.

$config['uri_protocol']    = isset($_SERVER['REQUEST_URI']) ? 'PATH_INFO' : 'CLI'; 

cd into your CI set-up and run index.php like below.

php index.php controller method param1 etc

For easier interaction with command line input/output/prompts etc try this CLI library
