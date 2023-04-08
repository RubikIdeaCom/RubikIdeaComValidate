<!DOCTYPE html>
<html lang="en">
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
        <meta name='robots' content='all' />
        <meta name='robots' content='index, follow' />
        <meta name='robots' content='noodp, noydir' />
        <meta name='keywords' content='calculator, rubikidea, rubikidea.com, php, validate, validation' />
        <meta name='description' content='PHP server-side validation by RubikIdeaCom\Validate' />
        <meta name='title' content='RubikIdea.com' />
        <meta name='HandheldFriendly' content='true'/>
        <meta name='rating' content='general' />
        <meta name='rating' content='safe for kids' />
        <meta name='revisit-after' content='7 Days' />
            
        <!-- Viewport -->
        <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, minimal-ui' />
        <meta name='apple-mobile-web-app-capable' content='yes' />
        <meta name='apple-mobile-web-app-status-bar-style' content='black' />
        <meta name='apple-mobile-web-app-title' content='RubikIdea.com' />
        
        <!-- Author -->
        <meta name='author' content='rubikidea.com' />
        <meta name='signet:authors' content='RubikIdea.com' />
        <meta name='signet:links' content='https://rubikidea.com/' />
        
        <!-- Facebook Preview -->
        <meta property='og:locale' content='en' />
        <meta property='og:site_name' content='RubikIdea.com' />
        <meta property='og:title' content='RubikIdea.com' />
        <meta property='og:description' content='PHP server-side validation by RubikIdeaCom\Validate' />
        <meta property='og:type' content='website' />
        <meta property='og:url' content='https://rubikidea.com/' />
        <meta property='og:image' content='https://rubikidea.com/assets/images/logos/fb.png' />
        <meta property='og:image:type' content='image/png' />
        <meta property='og:image:width' content='227' />
        <meta property='og:image:height' content='227' />
            
        <!-- Twitter Card -->
        <meta name='twitter:card' content='summary' />
        <meta name='twitter:site' content='@rubikideacom' />
        <meta name='twitter:title' content='rubikideacom' />
        <meta name='twitter:description' content='PHP server-side validation by RubikIdeaCom\Validate' />
        <meta name='twitter:image:src' content='https://rubikidea.com/assets/images/logos/fb.png' />
        <meta name='twitter:domain' content='https://rubikidea.com/' />

        <link rel='help' href='https://rubikidea.com/Contact.php' />
        <link rel='search' href='https://rubikidea.com/Search.php' />
        <link rel='canonical' href='https://rubikidea.com/' />
        <link rel='shortlink' href='https://rubikidea.com/' />
        <link rel='icon' type='image/png' href='https://rubikidea.com/assets/images/logos/favicon.png' />
        <link rel='shortcut icon' type='image/png' href='https://rubikidea.com/assets/images/logos/favicon.png' />
        <link rel='image_src' href='https://rubikidea.com/assets/images/logos/favicon.png' />
            
        <!-- App Icons -->
        <meta name='msapplication-TileColor' content='#fff' />
        <meta name='msapplication-TileImage' content='https://rubikidea.com/assets/images/logos/favicon.png' />
        <link rel='apple-touch-icon-precomposed' sizes='57x57' href='https://rubikidea.com/assets/images/logos/apple-touch-icon-57-precomposed.png' />
        <link rel='apple-touch-icon-precomposed' sizes='72x72' href='https://rubikidea.com/assets/images/logos/apple-touch-icon-72-precomposed.png' />
        <link rel='apple-touch-icon-precomposed' sizes='114x114' href='https://rubikidea.com/assets/images/logos/apple-touch-icon-114-precomposed.png' />
        <link rel='apple-touch-icon-precomposed' sizes='144x144' href='https://rubikidea.com/assets/images/logos/apple-touch-icon-144-precomposed.png' />
        <link rel='apple-touch-icon' sizes='60x60' href='https://rubikidea.com/assets/images/logos/apple-touch-icon-iphone.png' />
        <link rel='apple-touch-icon' sizes='76x76' href='https://rubikidea.com/assets/images/logos/touch-icon-ipad.png' />
        <link rel='apple-touch-icon' sizes='120x120' href='https://rubikidea.com/assets/images/logos/touch-icon-iphone-retina.png' />
        <link rel='apple-touch-icon' sizes='152x152' href='https://rubikidea.com/assets/images/logos/touch-icon-ipad-retina.png' />
    <title>RubikIdea.com Validate</title>
</head>
<body>
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
            print_r($data);
        } else {
            echo '<pre>';
            print_r($data);
        }
        
    ?>
    
</body>
</html>