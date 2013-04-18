<?php
class DevNick_Rotate360_Img360Controller extends Mage_Core_Controller_Front_Action
{
    private $options;
    private $id;
    private $titles;
    
    function init($options=null) {
        $this->options = array(
            'script_url' => $_SERVER['PHP_SELF'],
            'upload_dir' => Mage::getBaseDir('media') . DS . "catalog" . DS ."img360" . DS . $this->id . DS . 'Deck_Shoe' . DS . 'Lv1/',
            'upload_url' => Mage::getUrl() . 'media/catalog/img360/'.$this->id. "/Deck_Shoe/Lv1/",
            'param_name' => 'files',
            // The php.ini settings upload_max_filesize and post_max_size
            // take precedence over the following max_file_size setting:
            'max_file_size' => null,
            'min_file_size' => 1,
            'accept_file_types' => '/.+$/i',
            'max_number_of_files' => null,
            'discard_aborted_uploads' => true,
            'image_versions' => array(
                // Uncomment the following version to restrict the size of
                // uploaded images. You can also add additional versions with
                // their own upload directories:
                /*
                'large' => array(
                    'upload_dir' => dirname(__FILE__).'/files/',
                    'upload_url' => dirname($_SERVER['PHP_SELF']).'/files/',
                    'max_width' => 1920,
                    'max_height' => 1200
                ),
                */
                'thumbnail' => array(
                    'upload_dir' => Mage::getBaseDir('media') . DS . "catalog" . DS ."img360" . DS . $this->id . DS . 'Deck_Shoe' . DS . 'Lv2/',
                    'upload_url' => Mage::getUrl() . 'media/catalog/img360/'.$this->id.'/Deck_Shoe/Lv2/',
                    'max_width' => 400,
                    'max_height' => 200
                )
            )
        );
        if ($options) {
            $this->options = array_replace_recursive($this->options, $options);
        }
    }
    
    private function get_file_object($file_name) {
        $file_path = $this->options['upload_dir'].$file_name;
        if (is_file($file_path) && $file_name[0] !== '.') {
            $file = new stdClass();
            $file->name = $file_name;
            $file->size = filesize($file_path);
            $file->url = $this->options['upload_url'].rawurlencode($file->name);
            foreach($this->options['image_versions'] as $version => $options) {
                if (is_file($options['upload_dir'].$file_name)) {
                    $file->{$version.'_url'} = $options['upload_url']
                        .rawurlencode($file->name);
                }
            }
            $file->title = 'trololo';
            $file->delete_url = $this->options['script_url']
                .'?file='.rawurlencode($file->name);
            $file->delete_type = 'DELETE';
            return $file;
        }
        return null;
    }
    
    private function get_file_objects() {
        return array_values(array_filter(array_map(
            array($this, 'get_file_object'),
            scandir($this->options['upload_dir'])
        )));
    }

    private function create_scaled_image($file_name, $options) {
        $file_path = $this->options['upload_dir'].$file_name;
        $new_file_path = $options['upload_dir'].$file_name;
        if (!is_dir($options['upload_dir'])) {
                mkdir($options['upload_dir'], 0777, true);
            }
        list($img_width, $img_height) = @getimagesize($file_path);
        if (!$img_width || !$img_height) {
            return false;
        }
        $scale = min(
            $options['max_width'] / $img_width,
            $options['max_height'] / $img_height
        );
        if ($scale > 1) {
            $scale = 1;
        }
        $new_width = intval($img_width * $scale);
        $new_height = intval($img_height * $scale);
        $image = new Varien_Image($file_path);
        $image->resize($new_width, $new_height);
        $image->save($options['upload_dir'], $file_name);
        return true;
    }
    
    private function has_error($uploaded_file, $file, $error) {
        if ($error) {
            return $error;
        }
        if (!preg_match($this->options['accept_file_types'], $file->name)) {
            return 'acceptFileTypes';
        }
        if ($uploaded_file && is_uploaded_file($uploaded_file)) {
            $file_size = filesize($uploaded_file);
        } else {
            $file_size = $_SERVER['CONTENT_LENGTH'];
        }
        if ($this->options['max_file_size'] && (
                $file_size > $this->options['max_file_size'] ||
                $file->size > $this->options['max_file_size'])
            ) {
            return 'maxFileSize';
        }
        if ($this->options['min_file_size'] &&
            $file_size < $this->options['min_file_size']) {
            return 'minFileSize';
        }
        if (is_int($this->options['max_number_of_files']) && (
                count($this->get_file_objects()) >= $this->options['max_number_of_files'])
            ) {
            return 'maxNumberOfFiles';
        }
        return $error;
    }
    
    private function trim_file_name($name, $type) {
        // Remove path information and dots around the filename, to prevent uploading
        // into different directories or replacing hidden system files.
        // Also remove control characters and spaces (\x00..\x20) around the filename:
        $file_name = trim(basename(stripslashes($name)), ".\x00..\x20");
        // Add missing file extension for known image types:
        if (strpos($file_name, '.') === false &&
            preg_match('/^image\/(gif|jpe?g|png)/', $type, $matches)) {
            if ($matches[1] == 'jpeg') {
                $matches[1] = 'jpg';
            }
            $file_name .= '.'.$matches[1];
        }
        return $file_name;
    }
    
    private function handle_file_upload($uploaded_file, $name, $size, $type, $error, $key = 0) {
        $file = new stdClass();
        $file->name = $this->trim_file_name("img" . (($this->titles['title'][$key] < 10)?"0" . $this->titles['title'][$key]:$this->titles['title'][$key]), $type);
        $file->size = intval($size);
        $file->type = $type;
        $error = $this->has_error($uploaded_file, $file, $error);
        if (!$error && $file->name) {
            if (!is_dir($this->options['upload_dir'])) {
                mkdir($this->options['upload_dir'], 0777, true);
            }
            $img360Model = Mage::getModel('rotate360/img360');
            $img360Model->setProductId($this->id)
                       ->setPath($file->name)
                       ->setTitle($this->titles['title'][$key]);
            $img360Id = $img360Model->save()->getId();
            
            $file->title = $this->titles['title'][$key];
            $file_path = $this->options['upload_dir'].$file->name;
            $append_file = !$this->options['discard_aborted_uploads'] &&
                is_file($file_path) && $file->size > filesize($file_path);
            clearstatcache();
            if ($uploaded_file && is_uploaded_file($uploaded_file)) {
                // multipart/formdata uploads (POST method uploads)
                if ($append_file) {
                    file_put_contents(
                        $file_path,
                        fopen($uploaded_file, 'r'),
                        FILE_APPEND
                    );
                } else {
                    move_uploaded_file($uploaded_file, $file_path);
                }
            } else {
                // Non-multipart uploads (PUT method support)
                file_put_contents(
                    $file_path,
                    fopen('php://input', 'r'),
                    $append_file ? FILE_APPEND : 0
                );
            }
            $file_size = filesize($file_path);
            if ($file_size === $file->size) {
                $file->url = $this->options['upload_url'].rawurlencode($file->name);
                foreach($this->options['image_versions'] as $version => $options) {
                    if ($this->create_scaled_image($file->name, $options)) {
                        $file->{$version.'_url'} = $options['upload_url']
                            .rawurlencode($file->name);
                    }
                }
            } else if ($this->options['discard_aborted_uploads']) {
                unlink($file_path);
                $file->error = 'abort';
            }
            $file->size = $file_size;
            $file->delete_url = $this->options['script_url'] . "img360id/" . $img360Id;
            $file->delete_type = 'DELETE';
        } else {
            $file->error = $error;
        }
        return $file;
    }
    
    public function get() {
        $file_name = isset($_REQUEST['file']) ?
            basename(stripslashes($_REQUEST['file'])) : null; 
        if ($file_name) {
            $info = $this->get_file_object($file_name);
        } else {
            $info = $this->get_file_objects();
        }
        header('Content-type: application/json');
        echo json_encode($info);
    }
    
    public function post() {
        $this->titles = Mage::app()->getRequest()->getPost();
        $upload = isset($_FILES[$this->options['param_name']]) ?
            $_FILES[$this->options['param_name']] : null;
        $info = array();
        if ($upload && is_array($upload['tmp_name'])) {
            foreach ($upload['tmp_name'] as $index => $value) {
                $info[] = $this->handle_file_upload(
                    $upload['tmp_name'][$index],
                    isset($_SERVER['HTTP_X_FILE_NAME']) ?
                        $_SERVER['HTTP_X_FILE_NAME'] : $upload['name'][$index],
                    isset($_SERVER['HTTP_X_FILE_SIZE']) ?
                        $_SERVER['HTTP_X_FILE_SIZE'] : $upload['size'][$index],
                    isset($_SERVER['HTTP_X_FILE_TYPE']) ?
                        $_SERVER['HTTP_X_FILE_TYPE'] : $upload['type'][$index],
                    $upload['error'][$index], $index
                );
            }
        } elseif ($upload) {
            $info[] = $this->handle_file_upload(
                $upload['tmp_name'],
                isset($_SERVER['HTTP_X_FILE_NAME']) ?
                    $_SERVER['HTTP_X_FILE_NAME'] : $upload['name'],
                isset($_SERVER['HTTP_X_FILE_SIZE']) ?
                    $_SERVER['HTTP_X_FILE_SIZE'] : $upload['size'],
                isset($_SERVER['HTTP_X_FILE_TYPE']) ?
                    $_SERVER['HTTP_X_FILE_TYPE'] : $upload['type'],
                $upload['error']
            );
        }
        header('Vary: Accept');
        if (isset($_SERVER['HTTP_ACCEPT']) &&
            (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
            header('Content-type: application/json');
        } else {
            header('Content-type: text/plain');
        }
        echo json_encode($info);
    }
    
    public function delete() {
        $img360Id = Mage::app()->getRequest()->getParam('img360id');
        $img360Model = Mage::getModel('rotate360/img360')->load($img360Id);
        $data = $img360Model->getData();
        $img360Model->delete();
        $file_name = $data['path'];
        $file_path = $this->options['upload_dir'].$file_name;
        $success = is_file($file_path) && $file_name[0] !== '.' && unlink($file_path);
        if ($success) {
            foreach($this->options['image_versions'] as $version => $options) {
                $file = $options['upload_dir'].$file_name;
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
        header('Content-type: application/json');
        echo json_encode($success);
    }

    public function getImg360s()
    {
        $img360s = Mage::getModel('rotate360/img360')
            ->getCollection()
            ->addFilter('product_id',$this->id)
            ->getData();
        $data = array();
        foreach ($img360s as $key => $val)
        {
            $file = new stdClass();
            $file->name = $val['path'];
            $file->title = $val['title'];
            $file->url = $this->options['upload_url'] . $val['path'];
            $file->thumbnail_url = $this->options['image_versions']['thumbnail']['upload_url'] . $val['path'];
            $file->delete_url = $this->options['script_url'] . "img360id/" . $val['id'];
            $file->delete_type = 'DELETE';
            $file->size = filesize($this->options['upload_dir'] . $val['path']);
            
            $data[] = $file;
        }
        echo json_encode($data);
    }

    public function doAction()
    {
        //$data = Mage::app()->getRequest()->getPost();
        $data = Mage::getModel('rotate360/img360')->getCollection()->getData();
        $this->id = Mage::app()->getRequest()->getParam('id');
        $this->init();
        header('Pragma: no-cache');
        header('Cache-Control: private, no-cache');
        header('Content-Disposition: inline; filename="files.json"');
        header('X-Content-Type-Options: nosniff');
        
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'HEAD':
            case 'GET':
                $this->getImg360s();
                break;
            case 'POST':
                $this->post();
                break;
            case 'DELETE':
                $this->delete();
                break;
            case 'OPTIONS':
                break;
            default:
                header('HTTP/1.0 405 Method Not Allowed');
        }
    }
    public function getimg360Action()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
}