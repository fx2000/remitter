<?php
/**
 * NOMBRE_SOFTWARE
 * Copyright (c) NOMBRE_EMPRESA
 *
 * @copyright Copyright (c) NOMBRE_EMPRESA (URL_EMPRESA)
 * @link      URL_APLICACION
 * @since     0.1
 */

App::uses('Component', 'Controller');

/**
 * Image upload component
 *
 */
class ImageUploadComponent extends Component
{
    var $_file;
    var $_filepath;
    var $_destination;
    var $_name;
    var $_short;
    var $_rules;
    var $_allowed;
    var $errors;

    /*
     * ¿Qué hace esta función?
     */
    function startup (&$controller) {
        // This method takes a reference to the controller which is loading it.
        // Perform controller initialization here.
    }

    /*
     * ¿Qué hace esta función?
     */
    function GetExt ($file) {
            $ext = trim(substr($file,strrpos($file,".")+1,strlen($file)));
            return $ext;
        }

    /*
     * Image size check
     */
    function img_size($siz)
    {
        $fsize = ($siz) / (1024 * 1024);
        if (!(($fsize <= 10) && ($fsize != 0))) {
            $size_val =  0;
        } else {
            $size_val =  1;
        }
        return $size_val;
    }

    /*
     * ¿Qué hace esta función?
     */
    function myupload ($file, $destination, $name = NULL, $rules = NULL, $allowed = NULL, $id)
    {
        $this->result = false;
        $this->error = false;
        $this->_file = $file;
        $this->_destination = $destination;
        if (!is_null($rules)) {
            $this->_rules = $rules;
        }
        if (!is_null($allowed)) {
            $this->_allowed = $allowed;
        } else {
            $this->_allowed = array(
                'jpg',
                'jpeg',
                'gif',
                'png'
            );
        }
        if (substr($this->_destination, -1) != '/') {
            $this->_destination .= '/';
        }
        if (isset($file) && is_array($file) && !$this->upload_error($file['error'])) {
            $fileName = ($name == NULL) ? $this->uniquename($destination . $file['name'], $id) : $destination . $name;
            $fileTmp = $file['tmp_name'];
            $fileSize = $file['size'];
            $fileType = $file['type'];
            $fileError = $file['error'];
            $this->_name = $fileName;
            if (!in_array($this->ext($fileName), $this->_allowed)) {
                $this->error(__('File type not allowed'));
            } else {
                if (is_uploaded_file($fileTmp)) {
                    if ($rules == NULL) {
                        $output = $fileName;
                        if (move_uploaded_file($fileTmp, $output)) {
                            chmod($output, 0644);
                            $this->result = basename($this->_name);
                        } else {
                            $this->error(__('Could not move ' . $fileName . __(' to ') . $destination);
                        }
                    } else {
                        if (function_exists("imagecreatefromjpeg")) {
                            if (!isset($rules['output'])) {
                                $rules['output'] = NULL;
                            }
                            if (!isset($rules['quality'])) {
                                $rules['quality'] = NULL;
                            }
                            if (isset($rules['type'])) {
                                $this->image($this->_file, $rules['type'], $rules['size'], $rules['output'], $rules['quality']);
                            } else {
                                $this->error(__("Invalid \"rules\" parameter"));
                            }
                        } else {
                            $this->error(__("GD library is not installed"));
                        }
                    }
                } else {
                    $this->error(__("Possible file upload attack on ") . $fileName);
                }
            }
       } else {
            $this->error(__("Possible file upload attack"));
        }
    }

    /*
     * ¿Qué hace esta función?
     */
    function ext ($file) {
        $ext = trim(substr($file, strrpos($file, ".") + 1, strlen($file)));
        return $ext;
    }

    /*
     * ¿Qué hace esta función?
     */
    function error ($message) {
        if (!is_array($this->errors)) $this->errors = array();
        array_push($this->errors, $message);
    }

    /*
     * ¿Qué hace esta función?
     */
    function image ($file, $type, $size, $output = NULL, $quality = NULL)
    {
        if (is_null($type)) {
            $type = 'resize';
        }
        if (is_null($size)) {
            $size = 100;
        }
        if (is_null($output)) {
            $output = 'jpg';
        }
        if (is_null($quality)) {
            $quality = 75;
        }

        $type = strtolower($type);
        $output = strtolower($output);
        if (is_array($size)) {
            $maxW = intval($size[0]);
            $maxH = intval($size[1]);
        } else {
            $maxScale = intval($size);
        }
        if (isset($maxScale)) {
            if (!$maxScale) {
                $this->error(__("Max scale must be set"));
            }
        } else {
            if (!$maxW || !$maxH) {
                $this->error(__("Size width and height must be set"));
                return;
            }
            if ($type == 'resize') {
                $this->error(__("Provide only one number for size"));
            }
        }
        if ($output != 'jpg' && $output != 'png' && $output != 'gif') {
            $this->error(__("Cannot output file as ") . strtoupper($output));
        }
        if (is_numeric($quality)) {
            $quality = intval($quality);
            if ($quality > 100 || $quality < 1) {
                $quality = 75;
            }
        } else {
            $quality = 75;
        }
        $uploadSize = getimagesize($file['tmp_name']);
        $uploadWidth  = $uploadSize[0];
        $uploadHeight = $uploadSize[1];
        $uploadType = $uploadSize[2];
        if ($uploadType != 1 && $uploadType != 2 && $uploadType != 3) {
            $this->error (__("File type must be GIF, PNG, or JPG to resize"));
        }
        switch ($uploadType) {
            case 1: $srcImg = imagecreatefromgif($file['tmp_name']); break;
            case 2: $srcImg = imagecreatefromjpeg($file['tmp_name']); break;
            case 3: $srcImg = imagecreatefrompng($file['tmp_name']); break;
            default: $this->error (__("File type must be GIF, PNG, or JPG to resize"));
        }
        switch ($type) {
            case 'nosize':

                    $newX = $uploadWidth;
                    $newY = $uploadHeight;
                
                $dstImg = imagecreatetruecolor($newX, $newY);
                imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $newX, $newY, $uploadWidth, $uploadHeight);
                
                break;
        
            case 'resize':
                if ($uploadWidth > $maxScale || $uploadHeight > $maxScale) {
                    if ($uploadWidth > $uploadHeight) {
                        $newX = $maxScale;
                        $newY = ($uploadHeight*$newX)/$uploadWidth;
                    } else if ($uploadWidth < $uploadHeight) {
                        $newY = $maxScale;
                        $newX = ($newY*$uploadWidth)/$uploadHeight;
                    } else if ($uploadWidth == $uploadHeight) {
                        $newX = $newY = $maxScale;
                    }
                } else {
                  $newX = $uploadWidth;
                    $newY = $uploadHeight;
                }
                
                $dstImg = imagecreatetruecolor($newX, $newY);
                imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $newX, $newY, $uploadWidth, $uploadHeight);
                
                break;
                
            case 'resizemin':
                $ratioX = $maxW / $uploadWidth;
                $ratioY = $maxH / $uploadHeight;

                if (($uploadWidth == $maxW) && ($uploadHeight == $maxH)) {
                    $newX = $uploadWidth;
                    $newY = $uploadHeight;
                } else if (($ratioX * $uploadHeight) > $maxH) {
                    $newX = $maxW;
                    $newY = ceil($ratioX * $uploadHeight);
                } else {
                    $newX = ceil($ratioY * $uploadWidth);
                    $newY = $maxH;
                }

                $dstImg = imagecreatetruecolor($newX,$newY);
                imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $newX, $newY, $uploadWidth, $uploadHeight);
            
                break;
            
            case 'resizecrop':
                $ratioX = $maxW / $uploadWidth;
                $ratioY = $maxH / $uploadHeight;

                if ($ratioX < $ratioY) { 
                    $newX = round(($uploadWidth - ($maxW / $ratioY))/2);
                    $newY = 0;
                    $uploadWidth = round($maxW / $ratioY);
                    $uploadHeight = $uploadHeight;
                } else { 
                    $newX = 0;
                    $newY = round(($uploadHeight - ($maxH / $ratioX))/2);
                    $uploadWidth = $uploadWidth;
                    $uploadHeight = round($maxH / $ratioX);
                }

                $dstImg = imagecreatetruecolor($maxW, $maxH);
                imagecopyresampled($dstImg, $srcImg, 0, 0, $newX, $newY, $maxW, $maxH, $uploadWidth, $uploadHeight);
                
                break;
            
            case 'crop':
                $startY = ($uploadHeight - $maxH)/2;
                $startX = ($uploadWidth - $maxW)/2;

                $dstImg = imageCreateTrueColor($maxW, $maxH);
                ImageCopyResampled($dstImg, $srcImg, 0, 0, $startX, $startY, $maxW, $maxH, $maxW, $maxH);
            
                break;
            
    case 'resizewidth':
                if ($uploadWidth > $maxScale || $uploadHeight > $maxScale) {
                    if ($uploadWidth > $uploadHeight) {
                          $new =$uploadWidth/$uploadHeight;

                                  $newX = $new*50;
                        $newY = 50;
                    } else if ($uploadWidth < $uploadHeight) {
                        $newY = 50;
                                
                                $new =$uploadWidth/$uploadHeight;
                             $newX = $new*50;
                    } else if ($uploadWidth == $uploadHeight) {
                        $newX = 50;
                                $newY =  50;
                    }
                } else {
                  $newX = $uploadWidth;
                    $newY = 50;
                }
                $dstImg = imagecreatetruecolor($newX, $newY);
                imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $newX, $newY, $uploadWidth, $uploadHeight);
                
                break; 
            default: $this->error (__('Resize function \"$type\" does not exist'));
        }    

        switch ($output) {
            case 'jpg':
                $write = imagejpeg($dstImg, $this->_name, $quality);
                break;
     case 'jpeg':
                $write = imagejpeg($dstImg, $this->_name, $quality);
                break;
     case 'JPEG':
                $write = imagejpeg($dstImg, $this->_name, $quality);
                break;
            case 'png':
                $write = imagepng($dstImg, $this->_name . ".png", $quality);
                break;
            case 'gif':
                $write = imagegif($dstImg, $this->_name . ".gif", $quality);
                break;
    case 'JPG':
                $write = imagejpeg($dstImg, $this->_name, $quality);
                break;
            case 'PNG':
                $write = imagepng($dstImg, $this->_name . ".png", $quality);
                break;
            case 'GIF':
                $write = imagegif($dstImg, $this->_name . ".gif", $quality);
                break;
        }
        imagedestroy($dstImg);
        if ($write) {
            $this->result = basename($this->_name);
        } else {
            $this->error(__("Could not write ") . $this->_name . __(" to ") . $this->_destination);
        }
    }

    /*
     * ¿Qué hace esta función?
     */
    function newname ($file) {
        return time() . "." . $this->ext($file);
    }

    /*
     * ¿Qué hace esta función?
     */
    function uniquename ($file,$id) {
        $parts = pathinfo($file);
        $dir = $parts['dirname'];
        $file = ereg_replace('[^[:alnum:]_.-]','',$parts['basename']);
        $ext = $parts['extension'];
        if ($ext) {
            $ext = '.'.$ext;
            $file = substr($file,0,-strlen($ext));
        }
        $i = 0;
        while (file_exists($dir.'/'.$file.$i.$ext)) {
            $i++;
        }
        return $dir.'/'.$id.$ext; //Modified 
    }

    /*
     * ¿Qué hace esta función?
     */
    function remove_file($path){
        $ext = explode(".", $path);
        if (isset($ext) && !empty($ext[1])) {
            if (file_exists($path)){
                unlink($path);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /*
     * ¿Qué hace esta función?
     */
    function upload_error ($errorobj) {
        $error = false;
        switch ($errorobj) {
           case UPLOAD_ERR_OK: break;
           case UPLOAD_ERR_INI_SIZE: $error = __("The uploaded file exceeds the upload_max_filesize directive (") . ini_get("upload_max_filesize") . __(") in php.ini."); break;
           case UPLOAD_ERR_FORM_SIZE: $error = __("The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form."); break;
           case UPLOAD_ERR_PARTIAL: $error = __("The uploaded file was only partially uploaded."); break;
           case UPLOAD_ERR_NO_FILE: $error = __("No file was uploaded."); break;
           case UPLOAD_ERR_NO_TMP_DIR: $error = __("Missing a temporary folder."); break;
           case UPLOAD_ERR_CANT_WRITE: $error = __("Failed to write file to disk"); break;
           default: $error = __("Unknown File Error");
        }
        return ($error);
    }
}
