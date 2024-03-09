# Form2db

WP plugin that stores the form data in the database and displays a table with the data.

## Installation

Install easily by going to the plugins menu > add new plugin and upload the ZIP file.

Or FTP to wp-content > plugins then unzip the zip file.

## Usage

Once the add-in is installed, there are two ways to use it

### 1. the first way is using the default form through the shortcode inside any page 
```php
[display_form]
```
### 2. the second way requires the creation of a form with the fields required by the user, it is important to add the following id or class to the form
```php
id="miFormulario" or class="plug-form" 
```
### 3. the third way requires basic php knowledge, 
The third way requires basic PHP knowledge, the procedure is to access the plugin folder, find the file 'form_shortcode.php' and edit 
```php 
build_form() 
``` 
the function that contains the form and then use it as the first option.

### To display the data you only need to use the following shortcode and the table will be generated automatically
```php
[show_records]
```
### Note:
for a more secure use, please remember to use the password on the page containing the form.

## Contributing

Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License

GNU v2 or later
(https://www.gnu.org/licenses/gpl-2.0.html)
