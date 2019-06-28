# WHMCS Usernames

A very simple PHP script that you can direct your logins to in order to enable usernames.

## How-to

1. Create a custom field as shown below. Call it either "Username" or "username".

![field](screenshots/customfield.png?raw=true "customfield") 

2. Copy dologin2.php to the root of your WHMCS directory. Feel free to rename the file as you please.

3. Copy the clientDetailsValidation.php hook to WHMCS-root/includes/hooks

4. Edit your template as shown below. The $LANG objects should be changed/overriden to reflect that users can now log-in with usernames.

![template](screenshots/login-tpl.png?raw=true "template") 

5. Enjoy!

![login](screenshots/login.png?raw=true "login") 


## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details