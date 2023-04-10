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
- maxLength: Puts a maximum length to text entries.
- minLength: Puts a minimum length to text entries.
- uniqueValues: Checks if given field has unique values or not.
- username: Validate username ID of users. It's default valid user formats contains charasters, underline and numbers.
- equalTo: Takes a field name and checks if current filed name and this given field name are same values.
- email: Checks if entry is a valid email address.
- usaDate: Checks if entry is a valid USA data: mm/dd/yyyy
- englishDate: Checks if entry is a valid English date: dd/mm/yyyy
- standardDate: Checks if entry is a valid Standard date: yyyy/mm/dd
- standardTime: Checks if entry is a valid Standard time: Hours:Minutes:Seconds
- url: Validates entry to makes sure it's a valid URL address.
- required: This option forces an entry for the assigned field.
- fileTypes: This option uses for file uploading and it's value must be the valid file types, seprating by | symbol, for example: "jpg|png|bmp"
- maxImageWidth: Takes an integer value and checks if image width exceeds that or not.
- maxImageHeight: Takes an integer value and checks if image height exceeds that or not.
- maxFileSize: Takes an integer value and checks if given file size exceeds that or not.

# Customizing messages
Although RubikIdeaComValidate has default messages it supports custom messages as well. for example ({0} will replace by numeric value of that option):
$messages = array(
            'name' => array(
                'required' => 'Name is required.',
                'maxLength' => 'Name max length must be less than {0} chars.'
            ),
            'family' => array(
                'maxLength' => 'family max length must be less than {0} chars.',
            ),
            'email' => array(
                'email' => 'please enter a valid email.'
            ),
            'username' => array(
                'minLength' => 'user name min length is {0}.',
                'username' => 'Please enter a valid user name.'
            ),
            'password' => array(), // Uses default messages
            'confirm' => array(
                'equalTo'=>'confirm password must be equal to password.'
            )
        );

use RubikIdeaCom\Validate as Validate;
$ricValidateObject = new Validate();
$data = $ricValidateObject->init($rawData, $rules, $messages);
if(end($data) !== 'Error') {
  // Success
} else {
  // Failure
}

# How to use
<?php
        require_once './Validate.php';
        use RubikIdeaCom\Validate as Validate;

        $ricValidateObject = new Validate();
        
        $rules = array(
            'title' => array(
                'title' => 'Title',
                'makeItSafe'=>'string',
                'maxLength'=>70,
                'minLength'=>20,
                'required'=>true
            ),
            'cats' => array(
                'title' => 'Categories',
                'makeItSafe'=>'int',
                'required'=>false,
                'isArray'=>true
            ),
            'image' => array(
                'title' => 'Image',
                'required'=>false,
                'fileTypes' => 'jpg | png | gif',
                'isArray'=>true
            ),
            'text' => array(
                'title' => 'Text',
                'makeItSafe'=>'html-text',
                'required'=>true
            ),
            'author' => array(
                'title'=>'نویسنده مطلب نمایش',
                'makeItSafe'=>'string',
                'required'=>false
            ),
            'standardDate' => array(
                'title'=>'Date',
                'makeItSafe'=>'none',
                'required'=>true,
                'standardDate'=>true
            ),
            'standardTime' => array(
                'title'=>'Time',
                'makeItSafe'=>'none',
                'required'=>true,
                'standardTime'=>true
            )
        );
        
        /*
            $rawData in real world must be replace by $_POST or $_GET arrays, like this:
            $rawData = $_POST;
        */
        $rawData = array(
            'title' => 'Simple content title goes here',
            'cats' => array(
                'IT', 'Gaming', 'Big Data'
            ),
            'image' => array(),
            'text' => '<h1>Header goes here</h1> <p>Simple paragraph goes here.</p>',
            'author' => 'Ali Seyedabadi',
            'standardDate' => '1988/09/18',
            'standardTime' => '18:20:33'
        );

        $data = $ricValidateObject->init($rawData, $rules);
        if(end($data) !== 'Error') {
            // Success
        } else {
            // Failure
        }
    ?>
