<?php
namespace RubikIdeaCom {
    class Validate {
        
        private $rules = array();
        private $fieldName = null;
        private $title = null;
        private $equalToTitle = null;
        private $rawData = array();
        private $ripeData = array();
        private $requiredData = array();
        private $messages = array();
        private $imageTypes = array('jpg', 'jpeg', 'gif', 'tif', 'tiff', 'png');
        private $imageHeight = 0;
        private $imageWidth = 0;
        private $isArrayField = false;
        private $isImage = false;
        private $ignoreBlanks = true;
        private $dbConnection;
        
        public $firstErrorBreak = false;
        
        public function __construct($dbConnection = null) {
            $this->dbConnection = $dbConnection;
        }
        
        static function prepareToDisplay($value, $type = 'string') {
            switch ($type) {
                case 'str':
                case 'string':
                case 'string-text':
                case 'text':
                case 'html':
                case 'html-text':
                case 'script':
                    return stripslashes(strval($value));
                break;
                
                case 'int':
                case 'integer':
                    return intval($value);
                break;
                
                case 'real':
                case 'float':
                case 'double':
                    return doubleval($value);
                break;
                
                default:
                    return "\"$type\" is not supported as input type!";
                break;
            }
        }
        
        private function makeItSafe($type) {
            
            if(isset($this->rawData[$this->fieldName])) {
                $value = $this->rawData[$this->fieldName];
            
                switch ($type) {
                    case 'none':
                        return true;
                    break;
                    case 'str':
                    case 'string':
                        if($this->isArrayField !== true) {
                            $value = strval($value);
                            $value = nl2br($value);
                            $value = strip_tags($value);
                            $value = is_null($this->dbConnection) ? $value : $this->dbConnection->real_escape_string($value);
                            $this->rawData[$this->fieldName] = addslashes($value);
                        } else {
                            $arrayLenght = count($value);
                            for($i = 0; $i < $arrayLenght; $i++) {
                                if(!empty($value[$i])) {
                                    $value[$i] = strval($value[$i]);
                                    $value[$i] = nl2br($value[$i]);
                                    $value[$i] = strip_tags($value[$i]);
                                    $value[$i] = is_null($this->dbConnection) ? $value[$i] : $this->dbConnection->real_escape_string($value[$i]);
                                    $value[$i] = addslashes($value[$i]);
                                } else {
                                    $value[$i] = null;
                                }
                            }
                            $this->rawData[$this->fieldName] = $value;
                        }
                        return true;
                    break;
                    
                    case 'string-text':
                        if($this->isArrayField !== true) {
                            $value = strval($value);
                            $value = nl2br($value);
                            $value = strip_tags($value);
                            $this->rawData[$this->fieldName] = addslashes($value);
                        } else {
                            $arrayLenght = count($value);
                            for($i = 0; $i < $arrayLenght; $i++) {
                                $value[$i] = strval($value[$i]);
                                $value[$i] = nl2br($value[$i]);
                                $value[$i] = strip_tags($value[$i]);
                                $value[$i] = addslashes($value[$i]);
                            }
                            $this->rawData[$this->fieldName] = $value;
                        }
                        return true;
                    break;                               
                    
                    case 'text':
                        if($this->isArrayField !== true) {
                            $this->rawData[$this->fieldName] = nl2br(addslashes(strip_tags(strval($value))));
                        } else {
                            $arrayLenght = count($value);
                            for($i = 0; $i < $arrayLenght; $i++) {
                                $value[$i] = strval($value[$i]);
                                $value[$i] = strip_tags($value[$i]);
                                $value[$i] = addslashes($value[$i]);
                                $value[$i] = nl2br($value[$i]);
                            }
                            $this->rawData[$this->fieldName] = $value;
                        }
                        return true;
                    break;
                    
                    case 'html-text':
                        if($this->isArrayField !== true) {
                            $value = strval($value);
                            $value = addslashes($value);
                            $this->rawData[$this->fieldName] = nl2br($value);
                        } else {
                            $arrayLenght = count($value);
                            for($i = 0; $i < $arrayLenght; $i++) {
                                $value[$i] = strval($value[$i]);
                                $value[$i] = addslashes($value[$i]);
                                $value[$i] = nl2br($value[$i]);
                            }
                            $this->rawData[$this->fieldName] = $value;
                        }
                        return true;
                    break;
                    
                    case 'html':
                        if($this->isArrayField !== true) {
                            $value = strval($value);
                            $this->rawData[$this->fieldName] = addslashes($value);
                        } else {
                            $arrayLenght = count($value);
                            for($i = 0; $i < $arrayLenght; $i++) {
                                $value[$i] = strval($value[$i]);
                                $value[$i] = addslashes($value[$i]);
                            }
                            $this->rawData[$this->fieldName] = $value;
                        }
                        return true;
                    break;
                    
                    case 'script':
                        if($this->isArrayField !== true) {
                            $value = strval($value);
                            $value = htmlspecialchars($value);
                            $this->rawData[$this->fieldName] = addslashes($value);
                        } else {
                            $arrayLenght = count($value);
                            for($i = 0; $i < $arrayLenght; $i++) {
                                $value[$i] = strval($value[$i]);
                                $value[$i] = htmlspecialchars($value[$i]);
                                $value[$i] = addslashes($value[$i]);
                            }
                            $this->rawData[$this->fieldName] = $value;
                        }
                        return true;
                    break;
                    
                    case 'int':
                    case 'integer':
                        if($this->isArrayField !== true) {
                            $this->rawData[$this->fieldName] = intval($value);
                        } else {
                            $arrayLenght = count($value);
                            for($i = 0; $i < $arrayLenght; $i++) {
                                $value[$i] = intval($value[$i]);
                            }
                            $this->rawData[$this->fieldName] = $value;
                        }
                        return true;
                    break;
                    
                    case 'real':
                    case 'float':
                    case 'double':
                        if($this->isArrayField !== true) {
                            $this->rawData[$this->fieldName] = doubleval($value);
                        } else {
                            $arrayLenght = count($value);
                            for($i = 0; $i < $arrayLenght; $i++) {
                                $value[$i] = doubleval($value[$i]);
                            }
                            $this->rawData[$this->fieldName] = $value;
                        }
                        return true;
                    break;
                    
                    default:
                        return "\"$type\" is an invalid input type to make it safe! valid input types are: none, {str|string}, string-text, text, html-text, html, script, {int|integer},  {real|float|double}.";
                    break;
                }
            } else {
                return "\"$this->fieldName\" is not a correct \"makeItSafe\" field name, check for misspelling!";
            }
        }
        
        private function title($title) {
            $this->title = $title;
            return true;
        }
        
        private function equalToTitle($title) {
            $this->equalToTitle = $title;
            return true;
        }
        
        /**
         * Email validate here.
        */
        private function email($trueOrFalse) {
            if(isset($this->rawData[$this->fieldName])) {
                switch ($trueOrFalse) {
                    case true:
                        if($this->isArrayField !== true) {
                            if(filter_var($this->rawData[$this->fieldName], FILTER_VALIDATE_EMAIL) === false) {
                                if(isset($this->messages[$this->fieldName]['email'])) {
                                    return $this->messages[$this->fieldName]['email'];
                                } else {
                                    return 'Please enter a valid E-Mail address.';
                                }
                                
                            } else {
                                return true;
                            }
                        } else {
                            $arrayLenght = count($this->rawData[$this->fieldName]);
                            for($i = 0; $i < $arrayLenght; $i++) {
                                if(filter_var($this->rawData[$this->fieldName][$i], FILTER_VALIDATE_EMAIL) === false) {
                                    if(isset($this->messages[$this->fieldName]['email'])) {
                                        return $this->messages[$this->fieldName]['email'];
                                    } else {
                                        return 'Please enter a valid E-Mail address.';
                                    }
                                }
                            }
                            return true;
                        }
                    break;
                    case false:
                        return true;
                    break;
                    default:
                        return 'Error on \"email\" rules entry: Just use "true" or "false" keywords.';
                    break;
                }
            } else {
                return "\"$this->fieldName\" is not a correct \"email\" field name, check for misspelling!";
            }
        }
        
        private function makeItStandardDate() {
            if($this->isArrayField !== true) {
                $this->rawData[$this->fieldName] = str_replace(array('\'', '-', '.', ',', ' ', '\\', ':'), '/', $this->rawData[$this->fieldName]);
            } else {
                $arrayLenght = count($this->rawData[$this->fieldName]);
                for($i = 0; $i < $arrayLenght; $i++) {
                    $this->rawData[$this->fieldName][$i] = str_replace(array('\'', '-', '.', ',', ' ', '\\', ':'), '/', $this->rawData[$this->fieldName][$i]);
                }
            }
        }
        
        /**
         * Validate USA date: mm/dd/yyyy
         */
        private function usaDate($trueOrFalse) {
            if(isset($this->rawData[$this->fieldName])) {
                switch ($trueOrFalse) {
                    case true:
                        if($this->isArrayField !== true) {
                            $this->makeItStandardDate();
                            $date = explode('/', $this->rawData[$this->fieldName]);
                            if(count($date) == 3) {
                                if(!checkdate(intval($date[0]), intval($date[1]), intval($date[2]))) {
                                    if(isset($this->messages[$this->fieldName]['dateUSA'])) {
                                        return $this->messages[$this->fieldName]['dateUSA'];
                                    } else {
                                        return 'Invalid USA date format!';
                                    }
                                } else {
                                    return true;
                                }
                            } else {
                                if(isset($this->messages[$this->fieldName]['dateUSA'])) {
                                    return $this->messages[$this->fieldName]['dateUSA'];
                                } else {
                                    return 'Invalid USA date format!';
                                }
                            }
                        } else {
                            $arrayLenght = count($this->rawData[$this->fieldName]);
                            $this->makeItStandardDate();
                            for($i = 0; $i < $arrayLenght; $i++) {
                                $date = explode('/', $this->rawData[$this->fieldName][$i]);
                                if(count($date) == 3) {
                                    if(!checkdate(intval($date[0]), intval($date[1]), intval($date[2]))) {
                                        if(isset($this->messages[$this->fieldName]['dateUSA'])) {
                                            return $this->messages[$this->fieldName]['dateUSA'];
                                        } else {
                                            return 'Invalid USA date format!';
                                        }
                                    } else {
                                        return true;
                                    }
                                } else {
                                    if(isset($this->messages[$this->fieldName]['dateUSA'])) {
                                        return $this->messages[$this->fieldName]['dateUSA'];
                                    } else {
                                        return 'Invalid USA date format!';
                                    }
                                }
                            }
                            return true;
                        }
                    break;
                    case false:
                        return true;
                    break;
                    default:
                        return 'Error on \"usaDate\" rules entry: Just use "true" or "false" keywords.';
                    break;
                }
            } else {
                return "\"$this->fieldName\" is not a correct \"usaDate\" field name, check for misspelling!";
            }
        }
        
        /**
         * Validate English date: dd/mm/yyyy
         */
        private function englishDate($trueOrFalse) {
            if(isset($this->rawData[$this->fieldName])) {
                switch ($trueOrFalse) {
                    case true:
                        if($this->isArrayField !== true) {
                            $this->makeItStandardDate();
                            $date = explode('/', $this->rawData[$this->fieldName]);
                            if(count($date) == 3) {
                                if(!checkdate(intval($date[1]), intval($date[0]), intval($date[2]))) {
                                    if(isset($this->messages[$this->fieldName]['dateEng'])) {
                                        return $this->messages[$this->fieldName]['dateEng'];
                                    } else {
                                        return 'Invalid date format!';
                                    }
                                } else {
                                    return true;
                                }
                            } else {
                                if(isset($this->messages[$this->fieldName]['dateEng'])) {
                                    return $this->messages[$this->fieldName]['dateEng'];
                                } else {
                                    return 'Invalid date format!';
                                }
                            }
                        } else {
                            $arrayLenght = count($this->rawData[$this->fieldName]);
                            $this->makeItStandardDate();
                            for($i = 0; $i < $arrayLenght; $i++) {
                                $date = explode('/', $this->rawData[$this->fieldName][$i]);
                                if(count($date) == 3) {
                                    if(!checkdate(intval($date[1]), intval($date[0]), intval($date[2]))) {
                                        if(isset($this->messages[$this->fieldName]['dateEng'])) {
                                            return $this->messages[$this->fieldName]['dateEng'];
                                        } else {
                                            return 'Invalid date format!';
                                        }
                                    } else {
                                        return true;
                                    }
                                } else {
                                    if(isset($this->messages[$this->fieldName]['dateEng'])) {
                                        return $this->messages[$this->fieldName]['dateEng'];
                                    } else {
                                        return 'Invalid date format!';
                                    }
                                }
                            }
                            return true;
                        }
                    break;
                    case false:
                        return true;
                    break;
                    default:
                        return 'Error on \"englishDate\" rules entry: Just use "true" or "false" keywords.';
                    break;
                }
            } else {
                return "\"$this->fieldName\" is not a correct \"englishDate\" field name, check for misspelling!";
            }
        }
        
        /**
         * Validate Standard Date: yyyy/mm/dd
         */
        private function standardDate($trueOrFalse) {
            if(isset($this->rawData[$this->fieldName])) {
                if(empty($this->rawData[$this->fieldName]) and $this->requiredData[$this->fieldName] === false) {
                    return true;
                } else {
                    switch ($trueOrFalse) {
                        case true:
                            if($this->isArrayField !== true) {
                                $this->makeItStandardDate();
                                $date = explode('/', $this->rawData[$this->fieldName]);
                                if(count($date) == 3) {
                                    if(!checkdate(intval($date[1]), intval($date[2]), intval($date[0]))) {
                                        if(isset($this->messages[$this->fieldName]['standardDate'])) {
                                            return $this->messages[$this->fieldName]['standardDate'];
                                        } else {
                                            return 'Invalid standard date format!';
                                        }
                                    } else {
                                        return true;
                                    }
                                } else {
                                    if(isset($this->messages[$this->fieldName]['standardDate'])) {
                                        return $this->messages[$this->fieldName]['standardDate'];
                                    } else {
                                        return 'Invalid standard date format!';
                                    }
                                }
                            } else {
                                $arrayLenght = count($this->rawData[$this->fieldName]);
                                $this->makeItStandardDate();
                                for($i = 0; $i < $arrayLenght; $i++) {
                                    $date = explode('/', $this->rawData[$this->fieldName][$i]);
                                    if(count($date) == 3) {
                                        if(!checkdate(intval($date[1]), intval($date[2]), intval($date[0]))) {
                                            if(isset($this->messages[$this->fieldName]['standardDate'])) {
                                                return $this->messages[$this->fieldName]['standardDate'];
                                            } else {
                                                return 'Invalid standard date format!';
                                            }
                                        } else {
                                            return true;
                                        }
                                    } else {
                                        if(isset($this->messages[$this->fieldName]['standardDate'])) {
                                            return $this->messages[$this->fieldName]['standardDate'];
                                        } else {
                                            return 'Invalid standard date format!';
                                        }
                                    }
                                }
                                return true;
                            }
                        break;
                        case false:
                            return true;
                        break;
                        default:
                            return 'Error on \"standardDate\" rules entry: Just use "true" or "false" keywords.';
                        break;
                    }
                }
            } else {
                return "\"$this->fieldName\" is not a correct \"standardDate\" field name, check for misspelling!";
            }
        }
        
        private function makeItStandardTime() {
            if($this->isArrayField !== true) {
                $this->rawData[$this->fieldName] = str_replace(array('\'', '-', '.', ',', ' ', '\\', '/'), ':', $this->rawData[$this->fieldName]);
            } else {
                $arrayLenght = count($this->rawData[$this->fieldName]);
                for($i = 0; $i < $arrayLenght; $i++) {
                    $this->rawData[$this->fieldName][$i] = str_replace(array('\'', '-', '.', ',', ' ', '\\', '/'), ':', $this->rawData[$this->fieldName][$i]);
                }
            }
        }
        
        private function checktime($hours, $minutes, $seconds) {
            if($hours >= 0 and $hours <= 23 and $minutes >= 0 and $minutes <= 59 and $seconds >= 0 and $seconds <= 59 ) {
                return true;
            } else {
                return false;
            }
        }
        
        /**
         * Validate Standard Time: Hours:Minutes:Seconds
         */
        private function standardTime($trueOrFalse) {
            if(isset($this->rawData[$this->fieldName])) {
                switch ($trueOrFalse) {
                    case true:
                        if($this->isArrayField !== true) {
                            $this->makeItStandardTime();
                            $time = explode(':', $this->rawData[$this->fieldName]);
                            if(count($time) == 3) {
                                if(!$this->checktime(intval($time[0]), intval($time[1]), intval($time[2]))) {
                                    if(isset($this->messages[$this->fieldName]['standardTime'])) {
                                        return $this->messages[$this->fieldName]['standardTime'];
                                    } else {
                                        return 'Invalid time format!';
                                    }
                                } else {
                                    return true;
                                }
                            } else {
                                if(isset($this->messages[$this->fieldName]['standardTime'])) {
                                    return $this->messages[$this->fieldName]['standardTime'];
                                } else {
                                    return 'Invalid time format!';
                                }
                            }
                        } else {
                            $arrayLenght = count($this->rawData[$this->fieldName]);
                            $this->makeItStandardTime();
                            for($i = 0; $i < $arrayLenght; $i++) {
                                $time = explode(':', $this->rawData[$this->fieldName][$i]);
                                if(count($time) == 3) {
                                    if(!$this->checktime(intval($time[0]), intval($time[1]), intval($time[2]))) {
                                        if(isset($this->messages[$this->fieldName]['standardTime'])) {
                                            return $this->messages[$this->fieldName]['standardTime'];
                                        } else {
                                            return 'Invalid time format!';
                                        }
                                    } else {
                                        return true;
                                    }
                                } else {
                                    if(isset($this->messages[$this->fieldName]['standardTime'])) {
                                        return $this->messages[$this->fieldName]['standardTime'];
                                    } else {
                                        return 'Invalid time format!';
                                    }
                                }
                            }
                            return true;
                        }
                    break;
                    case false:
                        return true;
                    break;
                    default:
                        return 'Error on \"standardTime\" rules entry: Just use "true" or "false" keywords.';
                    break;
                }
            } else {
                return "\"$this->fieldName\" is not a correct \"standardTime\" field name, check for misspelling!";
            }
        }
        
        private function url($trueOrFalse) {
            if(isset($this->rawData[$this->fieldName])) {
                switch ($trueOrFalse) {
                    case true:
                        if($this->isArrayField !== true) {
                            if(filter_var($this->rawData[$this->fieldName], FILTER_VALIDATE_URL) === false) {
                                if(isset($this->messages[$this->fieldName]['url'])) {
                                    return $this->messages[$this->fieldName]['url'];
                                } else {
                                    return 'Invalid URL!';
                                }
                            } else {
                                return true;
                            }
                        } else {
                            $arrayLenght = count($this->rawData[$this->fieldName]);
                            for($i = 0; $i < $arrayLenght; $i++) {
                                if(filter_var($this->rawData[$this->fieldName][$i], FILTER_VALIDATE_URL) === false) {
                                    if(isset($this->messages[$this->fieldName]['url'])) {
                                        return $this->messages[$this->fieldName]['url'];
                                    } else {
                                        return 'Invalid URL!';
                                    }
                                }
                            }
                            return true;
                        }
                    break;
                    case false:
                        return true;
                    break;
                    default:
                        return 'Error on \"url\" rules entry: Just use "true" or "false" keywords.';
                    break;
                }
            } else {
                return "\"$this->fieldName\" is not a correct \"url\" field name, check for misspelling!";
            }
        }
        
        private function required($trueOrFalse) {
            if(isset($this->rawData[$this->fieldName])) {
                switch ($trueOrFalse) {
                    case true:
                        $this->requiredData[$this->fieldName] = true;
                        if($this->isArrayField !== true) {
                            if(empty($this->rawData[$this->fieldName])) {
                                if(isset($this->messages[$this->fieldName]['required'])) {
                                    return $this->messages[$this->fieldName]['required'];
                                } else {
                                    return "$this->title is required";
                                }
                            } else {
                                return true;
                            }
                        } else {
                            foreach($this->rawData[$this->fieldName] as $key => $value) {
                                if(empty($this->rawData[$this->fieldName][$key])) {
                                    if(isset($this->messages[$this->fieldName]['required'])) {
                                        return $this->messages[$this->fieldName]['required'];
                                    } else {
                                        return "$this->title is required";
                                    }
                                }
                            }
                        }
                        return true;
                    break;
                    case false:
                        $this->requiredData[$this->fieldName] = false;
                        return true;
                    break;
                    default:
                        return 'Error on \"required\" rules entry: Just use "true" or "false" keywords.';
                    break;
                }
            } else {
                return "\"$this->fieldName\" is not a correct \"required\" field name, check for misspelling!";
            }
        }
        
        private function maxImageWidth($maxImageWidth) {
            $imgDetails = getimagesize($_FILES[$this->fieldName]['tmp_name']);
            $width = $imgDetails[0];
            if($width > intval($maxImageWidth)) {
                if(isset($this->messages[$this->fieldName]['maxImageWidth'])) {
                    return str_replace('{0}', $maxImageWidth, $this->messages[$this->fieldName]['maxImageWidth']);
                } else {
                    return "Maximum width for $this->title is {$maxImageWidth}px.";
                }
            } else {
                return true;
            }
        }
        
        private function maxImageHeight($maxImageHeight) {
            $imgDetails = getimagesize($_FILES[$this->fieldName]['tmp_name']);
            $height = $imgDetails[1];
            if($height > intval($maxImageHeight)) {
                if(isset($this->messages[$this->fieldName]['maxImageHeight'])) {
                    return str_replace('{0}', $maxImageHeight, $this->messages[$this->fieldName]['maxImageHeight']);
                } else {
                    return "Maximum length for $this->title is {$maxImageHeight}px.";
                }
            } else {
                return true;
            }
        }
        
        private function validateImage($imageTemp) {
            $imageDetails = @getimagesize($imageTemp);
            $width = $imageDetails[0];
            $height = $imageDetails[1];
            if($height == 0 or $width == 0) {
                return false;
            } else {
                $this->imageHeight = $height;
                $this->imageWidth = $width;
                return true;
            }
        }
        
        private function getFileType($name) {
            $str = strrev($name);
            $typePos = strpos($str,'.');
            if($typePos < 5) {
                $str = strrev($str);
                $type = substr($str, -$typePos);
                return (string)(strtolower($type));
            }
            else {
                return false;
            }
        }
        
        private function maxFileSize($maxFileSize) {
            $formFileSize = filesize($_FILES[$this->fieldName]['tmp_name']);
            $regex = '/^([0-9]+)([MKB])$/i';
            $matches = array();
            preg_match($regex, $maxFileSize, $matches);
            if(count($matches) == 3) {
                switch(strtolower($matches[2])) {
                    case 'b':
                        if($formFileSize <= $matches[1]) {
                            return true;
                        }
                    break;
                    case 'k':
                        if($formFileSize <= ($matches[1] / 1024)) {
                            return true;
                        }
                    break;
                    case 'm':
                        if($formFileSize <= (($matches[1] / 1024) / 1024)) {
                            return true;
                        }
                    break;
                    default:
                        return 'Invalid size type in \"maxFileSize\" method.';
                    break;
                }
                
                if(isset($this->messages[$this->fieldName]['maxFileSize'])) {
                    return str_replace('{0}', $matches[0], $this->messages[$this->fieldName]['maxFileSize']);
                } else {
                    return "Maximum size of $this->title is {$maxFileSize}.";
                }
            } else {
                return 'Invalid file size parse in \"maxFileSize\" method.';
            }
        }
        
        private function isUploadableFile($errorState) {
            switch($errorState) {
                case 0:
                    return true;
                break;
                case 1:
                    return 'The size of the input file "'.$_FILES[$this->fieldName]['name'].'" exceeds the limit.';
                break;
                case 2:
                    return 'The size of the input file "'.$_FILES[$this->fieldName]['name'].' exceeds the form limit.';
                break;
                case 3:
                    return 'The input file "'.$_FILES[$this->fieldName]['name'].'" partially uploaded.';
                break;
                case 4:
                    return 'Upload progress of input file '.$_FILES[$this->fieldName]['name'].'" was not successfull';
                break;
                default:
                    return 'Unknow error type in \"isUploadableFile\" method.';
                break;
            }
        }
        
        private function fileTypes($fileTypes) {
            if(isset($this->rawData[$this->fieldName])) {
                $fileTypes = explode('|', $fileTypes);
                for($i = 0; $i < count($fileTypes); $i++) {
                    $fileTypes[$i] = strtolower(trim($fileTypes[$i]));
                    if(in_array($fileTypes[$i], $this->imageTypes)) {
                        $this->isImage = true;
                    }
                }
                
                if(false) {
                    // MULTIPLE FILE UPLOAD
                } else {
                    if(!empty($_FILES[$this->fieldName]['name'])) {
                        $fileType = $this->getFileType($_FILES[$this->fieldName]['name']);
                        if(is_string($fileType)) {
                            if(in_array($fileType, $fileTypes)) {
                                if($this->isImage === true and !$this->validateImage($_FILES[$this->fieldName]['tmp_name'])) {
                                    if(isset($this->messages[$this->fieldName]['invalid_image'])) {
                                        return $this->messages[$this->fieldName]['invalid_image'];
                                    } else {
                                        return "$this->title is not a standard file.";
                                    }
                                } else {
                                    $isUploadableFile = $this->isUploadableFile($_FILES[$this->fieldName]['error']);
                                    if($isUploadableFile === true) {
                                        $this->rawData[$this->fieldName] = $_FILES[$this->fieldName];
                                        $this->rawData[$this->fieldName]['fileType'] = $fileType;
                                        $this->rawData[$this->fieldName]['imageHeight'] = $this->imageHeight;
                                        $this->rawData[$this->fieldName]['imageWidth'] = $this->imageWidth;
                                        return true;
                                    } else {
                                        return $isUploadableFile;
                                    }
                                }
                            } else {
                                if(isset($this->messages[$this->fieldName]['invalid_format'])) {
                                    return $this->messages[$this->fieldName]['invalid_format'];
                                } else {
                                    return "File type of \"$this->title\" is not acceptable, valid file types are: ".implode(' ,', $fileTypes);
                                }
                            }
                        } else {
                            if(isset($this->messages[$this->fieldName]['unknown_format'])) {
                                return $this->messages[$this->fieldName]['unknown_format'];
                            } else {
                                return "File format of \"$this->title\" is Unknows.";
                            }
                        }
                    } else {
                        return true;
                    }
                }
            } else {
                return "\"$this->fieldName\" is not a correct \"fileTypes\" field name, check for misspelling!";
            }
        }
        
        private function maxLength($maxLength) {
            if(isset($this->rawData[$this->fieldName])) {
                if($this->isArrayField !== true) {
                    if(strlen($this->rawData[$this->fieldName]) > $maxLength) {
                        if(isset($this->messages[$this->fieldName]['maxLength'])) {
                            return str_replace('{0}', $maxLength, $this->messages[$this->fieldName]['maxLength']);
                        } else {
                            return "Maximum length of \"$this->title\" is {$maxLength}.";
                        }
                    } else {
                        return true;
                    }
                } else {
                    $arrayLenght = count($this->rawData[$this->fieldName]);
                    for($i = 0; $i < $arrayLenght; $i++) {
                        if(!empty($this->rawData[$this->fieldName][$i])) {
                            if(strlen($this->rawData[$this->fieldName][$i]) > $maxLength) {
                                if(isset($this->messages[$this->fieldName]['maxLength'])) {
                                    return str_replace('{0}', $maxLength, $this->messages[$this->fieldName]['maxLength']);
                                } else {
                                    return "Maximum length of \"$this->title\" is {$maxLength}.";
                                }
                            }
                        }
                    }
                    return true;
                }
            } else {
                return "\"$this->fieldName\" is not a correct \"maxLength\" field name, check for misspelling!";
            }
        }
        
        private function minLength($minLength) {
            if(isset($this->rawData[$this->fieldName])) {
                if($this->isArrayField !== true) {
                    if(strlen($this->rawData[$this->fieldName]) < $minLength and !empty($this->rawData[$this->fieldName])) {
                        if(isset($this->messages[$this->fieldName]['minLength'])) {
                            return str_replace('{0}', $minLength, $this->messages[$this->fieldName]['minLength']);
                        } else {
                            return "Minimum length of \"$this->title\" is {$minLength}.";
                        }
                    } else {
                        return true;
                    }
                } else {
                    $arrayLenght = count($this->rawData[$this->fieldName]);
                    for($i = 0; $i < $arrayLenght; $i++) {
                        if(strlen($this->rawData[$this->fieldName][$i]) < $minLength and !empty($this->rawData[$this->fieldName][$i])) {
                            if(isset($this->messages[$this->fieldName]['minLength'])) {
                                return str_replace('{0}', $minLength, $this->messages[$this->fieldName]['minLength']);
                            } else {
                                return "Minimum length of \"$this->title\" is {$minLength}.";
                            }
                        }
                    }
                }
            } else {
                return "\"$this->fieldName\" is not a correct \"minLength\" field name, check for misspelling!";
            }
        }
        
        private function equalTo($equalTo) {
            if(isset($this->rawData[$this->fieldName])) {
                if($this->isArrayField !== true) {
                    if($this->rawData[$this->fieldName] != $this->rawData[$equalTo]) {
                        if(isset($this->messages[$this->fieldName]['equalTo'])) {
                            return $this->messages[$this->fieldName]['equalTo'];
                        } else {
                            return "\"$this->title\" must be equal to {$this->equalToTitle}.";
                        }
                    } else {
                        return true;
                    }
                } else {
                    $arrayLenght = count($this->rawData[$this->fieldName]);
                    for($i = 0; $i < $arrayLenght; $i++) {
                        if($this->rawData[$this->fieldName][$i] != $this->rawData[$equalTo]) {
                            if(isset($this->messages[$this->fieldName]['equalTo'])) {
                                return $this->messages[$this->fieldName]['equalTo'];
                            } else {
                                return "\"$this->title\" must be equal to {$this->equalToTitle}.";
                            }
                        }
                    }
                }
            } else {
                return "\"$this->fieldName\" is not a correct \"equalTo\" field name, check for misspelling!";
            }
        }
        
        private function uniqueValues($trueOrFalse) {
            if(isset($this->rawData[$this->fieldName])) {
                switch ($trueOrFalse) {
                    case true:
                        if(isset($this->rawData[$this->fieldName])) {
                            $firstArray = $this->rawData[$this->fieldName];
                            $secondArray = array_unique($firstArray);
                            if(count($firstArray) != count($secondArray)) {
                                if(isset($this->messages[$this->fieldName]['uniqueValues'])) {
                                    return $this->messages[$this->fieldName]['uniqueValues'];
                                } else {
                                    return "Input values of $this->title must be unique.";
                                }
                            }
                        }
                        return true;
                    break;
                    case false:
                        return true;
                    break;
                    default:
                        return 'Error on \"uniqueValues\" rules entry: Just use "true" or "false" keywords.';
                    break;
                }
            } else {
                return "\"$this->fieldName\" is not a correct \"uniqueValues\" field name, check for misspelling!";
            }
        }
        
        private function username($trueOrFalse) {
            if(isset($this->rawData[$this->fieldName])) {
                switch ($trueOrFalse) {
                    case true:
                        if($this->isArrayField !== true) {
                            if(preg_match('/^[a-z]+((_)?[a-z0-9]+)*$/', $this->rawData[$this->fieldName])) {
                                return true;
                            } else {
                                if(isset($this->messages[$this->fieldName]['username'])) {
                                    return $this->messages[$this->fieldName]['username'];
                                } else {
                                    return 'Username invalid!';
                                }
                            }
                        } else {
                            $arrayLenght = count($this->rawData[$this->fieldName]);
                            for($i = 0; $i < $arrayLenght; $i++) {
                                if(preg_match('/^[a-z]+((_)?[a-z0-9]+)*$/', $this->rawData[$this->fieldName][$i])) {
                                    return true;
                                } else {
                                    if(isset($this->messages[$this->fieldName]['username'])) {
                                        return $this->messages[$this->fieldName]['username'];
                                    } else {
                                        return 'Username invalid!';
                                    }
                                }
                            }
                        }
                    break;
                    case false:
                        return true;
                    break;
                }
            } else {
                return "\"$this->fieldName\" is not a correct \"username\" field name, check for misspelling!";
            }
        }
        
        private function isArray($trueOrFalse) {
            if(isset($this->rawData[$this->fieldName])) {
                switch ($trueOrFalse) {
                    case true:
                        if($this->ignoreBlanks) {
                            if(isset($this->rawData[$this->fieldName])) {
                                $keys = array();
                                $values = array();
                                foreach($this->rawData[$this->fieldName] as $key => $value) {
                                    if(!empty($value)) {
                                        array_push($keys, $key);
                                        array_push($values, $value);
                                    }
                                }
                                if(count($values) > 0) {
                                    $this->rawData[$this->fieldName] = array_combine($keys, $values);
                                }
                            }
                        }
                        return true;
                    break;
                    case false:
                        return true;
                    break;
                    default:
                        return 'Error on \"isArray\" rules entry: Just use "true" or "false" keywords.';
                    break;
                }
            } else {
                return "\"$this->fieldName\" is not a correct \"isArray\" field name, check for misspelling!";
            }
        }
        
        private function setIsArrayFirst($fieldRules) {
            if(array_key_exists('isArray', $fieldRules)) {
                $this->isArrayField = true;
                $isArray = $fieldRules['isArray'];
                unset($fieldRules['isArray']);
                $fieldRules = array('isArray' => $isArray) + $fieldRules;
            }
            return $fieldRules;
        }
        
        private function setMakeItSafeFirst($fieldRules) {
            if(array_key_exists('makeItSafe', $fieldRules)) {
                $makeItSafe = $fieldRules['makeItSafe'];
                unset($fieldRules['makeItSafe']);
                $fieldRules = array('makeItSafe' => $makeItSafe) + $fieldRules;
            }
            return $fieldRules;
        }
        
        private function initIgnoreBlanks($fieldRules) {
            if(array_key_exists('ignoreBlanks', $fieldRules)) {
                $this->ignoreBlanks = (boolean)($fieldRules['ignoreBlanks']);
                unset($fieldRules['ignoreBlanks']);
            } else {
                $this->ignoreBlanks = true;
            }
            return $fieldRules;
        }
        
        private function validate($rules) {
            $flag = 0;
            $errors = array();
            foreach ($rules as $fieldName => $fieldRules) {
                $this->fieldName = $fieldName;
                $this->title = null;
                $makeItSafe = null;
                $this->isArrayField = false;
                
                $fieldRules = $this->initIgnoreBlanks($fieldRules);
                $fieldRules = $this->setMakeItSafeFirst($fieldRules);
                $fieldRules = $this->setIsArrayFirst($fieldRules);
                
                foreach ($fieldRules as $function => $argument) {
                    $result = $this->$function($argument);
                    if($result !== true) {
                        array_push($errors, $result);
                        if($this->firstErrorBreak === true) {
                            break 2;
                        }
                        $flag = 1;
                    } else {
                        if($flag == 0) {
                            if(isset($this->rawData[$fieldName])) {
                                $this->ripeData[$fieldName] = $this->rawData[$fieldName];
                            }
                        }
                    }
                }
                $flag = 0;
            }
            if(count($errors) > 0) {
                array_push($errors, 'Error');
                return (array)($errors);
            } else {
                return (array)($this->ripeData);
            }
        }
        
        function init($dataArray, $rules, $messages = null) {
            $this->rawData = $dataArray;
            $this->ripeData = array();
            $this->messages = $messages;
            
            return $this->validate($rules);
        }
    };
};

/**
 * $messages = array(
            'name' => array(
                'required' => 'Name is required.',
                'maxLength' => 'Name max length must be less than 20 chars.'
            ),
            'family' => array(
                'maxLength' => 'family max length must be less than {0} chars.',
            ),
            'email' => array(
                'email' => 'please enter a valid email.'
            ),
            'username' => array(
                'minLength' => 'user name min length is {0}.',
                'username' => 'please enter a valid user name.'
            ),
            'password' => array(
                
            ),
            'confirm' => array(
                'equalTo'=>'confirm password must be equal to password.'
            )
        );
 */
?>