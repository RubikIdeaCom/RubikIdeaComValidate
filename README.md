# RubikIdeaComValidate
RubikIdeaComValidate is a compelete server-side validating class using PHP. It also provides a wide range of form data sanitizing methods with a simple using instruction that comes in following sections. RubikIdeaComValidate also support custom error messages. It's also capable of handling form data as arrays of raw data and make them harmless data! Please report any bug to info@rubikidea.com 

# Sanitizing Features:
Sanitize following data types:
- str|string: Provide compelete clearing form data from any html tags, forbidden symbols, \n, etc. and return pure text. Useful for text box like name, family, address, etc.
- string-text: It's like str|string but don't use a db connection to real_scape_string.
- text: Provide a compelete data sanitizing on <textarea>'s data.
- html-text: It's like "text" but accept html tags as entry.
- html: It accepts any html tags as entry and makes them safe to insert into db. It's useful for receving data from WYSIWYG Editors like tinyMCE and etc.
- script: If you want to display some html special chars on the screen, like a anchor tag <a> or other html tags and prevent their nature as a html tags, you have to use this option.
- int|integer: Return an integere value.
- real|float|double: return a double value.

Please note that you can pass a DB connection object to constructor and your data sanitizing on str|string option will use real_scape_string method.

use RubikIdeaCom\Validate as Validate;
$ricValidateObject = new Validate($dbConnection);

# Validation Features:
- equalTo: Takes a field name and checks if current filed name and this given field name are same values.
- email: Checks if entry is a valid email address.
- usaDate: Checks if entry is a valid USA data: mm/dd/yyyy
- englishDate: Checks if entry is a valid English date: dd/mm/yyyy
- standardDate: Checks if entry is a valid Standard date: yyyy/mm/dd
- standardTime: Checks if entry is a valid Standard time: Hours:Minutes:Seconds
- url: Validates entry to makes sure it's a valid URL address.
- required: This option forces an entry for the assigned field.
