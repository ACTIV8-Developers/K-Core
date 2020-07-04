<?php

namespace Core\Services\FileUpload;

/**
 * File FileUpload class.
 */
class FileUpload
{
    /**
     * Allowed file types
     * @var array
     */
    protected $allowedTypes = ['png', 'jpg', 'jpeg', 'bmp', 'txt', 'doc', 'docx', 'xls', 'pdf', 'xlsx'];

    /**
     * Path of upload dir
     * @var string
     */
    protected $uploadPath = '';

    /**
     * Maximum allowed upload size
     * @var int
     */
    protected $maxSize = 1024;

    /**
     * Maximum allowed image width
     * @var int
     */
    protected $maxWidth = 0;

    /**
     *  Maximum allowed image height
     * @var int
     */
    protected $maxHeight = 0;

    /**
     * Error message
     * @var string
     */
    protected $error = '';

    /**
     * File extension
     * @var string
     */
    protected $fileExt = '';

    /**
     * File name override
     * @var string
     */
    protected $nameOverride = false;

    /**
     * File name override
     * @var string
     */
    protected $overwrite = false;

    /**
     * Remove spaces from name
     * @var bool
     */
    protected $removeSpaces = true;

    /**
     * Default field name
     * @var string
     */
    protected $field = 'file';

    /**
     * @var array
     */
    protected $files = [];

    /**
     * List of PHP upload errors
     * @var array
     */
    protected $uploadError = [
        'There is no error, the file uploaded with success.',
        'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
        'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
        'The uploaded file was only partially uploaded.',
        'No file was uploaded.',
        '',
        'Missing a temporary folder.',
        'Failed to write file to disk.',
        'A PHP extension stopped the file upload.'
    ];

    /**
     * @var String
     */
    private $fileName;

    /**
     * @var String
     */
    private $fileSize;

    /**
     * @var String
     */
    private $fileTemp;

    /**
     * Class constructor
     * @param array $files
     * @internal param ContainerInterface $container
     */
    public function __construct(array $files)
    {
        $this->files = $files;
    }

    /**
     * @param $field
     * @param $path
     * @param array $params
     * @return bool
     * @internal param $fileName
     */
    public function upload($field, $path, array $params = [])
    {
        $this->field = $field;
        $this->uploadPath = $path;

        // Load params from passed array
        foreach ($params as $key => $val) {
            $this->$key = $val;
        }

        // Check for valid uploaded file
        if (isset($this->files[$this->field])) {
            // Get uploaded file parameters
            $this->fileName = $this->prepFilename($this->files[$this->field]['name']);
            $this->fileSize = $this->files[$this->field]['size'];
            $this->fileTemp = $this->files[$this->field]['tmp_name'];
            $this->fileExt = $this->getExtension($this->fileName);
        } else {
            return false;
        }

        // Check for upload errors
        if ($this->files[$this->field]["error"] > 0) {
            $this->error = $this->uploadError[$this->files[$this->field]["error"]];
            return false;
        }

        // Is the file type allowed to be uploaded?
        if (!$this->isAllowedFiletype()) {
            $this->error = 'File type not allowed!';
            return false;
        }

        // Check upload path
        if (!$this->validateUploadPath()) {
            return false;
        }

        // Convert the file size to kilobytes
        $this->fileSize = round($this->fileSize / 1024, 2);
        // Check file size
        if ($this->fileSize > ($this->maxSize)) {
            $this->error = 'File size not allowed!';
            return false;
        }

        // Set new file name if override name is true
        if ($this->nameOverride) {
            $this->fileName = $this->nameOverride . $this->fileExt;
        }

        // Sanitize the file name for security
        $this->fileName = $this->cleanFileName($this->fileName);

        // Remove white spaces in the name
        if ($this->removeSpaces == true) {
            $this->fileName = preg_replace("/\s+/", "_", $this->fileName);
        }

        if (!$this->overwrite) {
            $i = 1;
            $temp = $this->fileName;
            while (file_exists($this->uploadPath . '/' . $temp)) {
                $temp = strstr($this->fileName, $this->fileExt, true) . '(' . $i++ . ')' . $this->fileExt;
            }
            $this->fileName = $temp;
        }

        /*
        * Move the file to the final destination
        * To deal with different server configurations
        * try to use copy() first. If that fails
        * move_uploaded_file() is used.
        */
        if (!@copy($this->fileTemp, $this->uploadPath . '/' . $this->fileName)) {
            if (!@move_uploaded_file($this->fileTemp, $this->uploadPath . '/' . $this->fileName)) {
                $this->error = 'Unable to copy file to filesystem!';
                return false;
            }
        }
        // Try to change mod of uploaded file
        chmod($this->uploadPath . '/' . $this->fileName, 0755);

        // If everything is fine return true
        return true;
    }

    /**
     * Prep Filename
     * Prevents possible script execution from Apache's handling of files multiple extensions
     * http://httpd.apache.org/docs/1.3/mod/mod_mime.html#multipleext
     * @param string
     * @return string
     */
    protected function prepFilename($filename)
    {
        if (strpos($filename, '.') === FALSE || $this->allowedTypes == '*') {
            return $filename;
        }
        $parts = explode('.', $filename);
        $ext = array_pop($parts);
        $filename = array_shift($parts);
        foreach ($parts as $part) {
            if (!in_array(strtolower($part), $this->allowedTypes)) {
                $filename .= '.' . $part . '_';
            } else {
                $filename .= '.' . $part;
            }
        }
        $filename .= '.' . $ext;
        return $filename;
    }

    /**
     * Get extension of file
     * @param string
     * @return string
     */
    protected function getExtension($filename)
    {
        $x = explode('.', $filename);
        return '.' . end($x);
    }

    /**
     * Verify that the file type is allowed
     * @return bool
     */
    public function isAllowedFiletype()
    {
        if ($this->allowedTypes == '*') {
            return true;
        }

        if (count($this->allowedTypes) == 0 || !is_array($this->allowedTypes)) {
            $this->error = 'No list of allowed file types set!';
            return false;
        }

        $ext = strtolower(ltrim($this->fileExt, '.'));

        if (!in_array($ext, $this->allowedTypes)) {
            return false;
        }

        // Images get some additional checks
        $imageTypes = ['gif', 'jpg', 'jpeg', 'png', 'jpe'];

        if (in_array($ext, $imageTypes)) {
            if (getimagesize($this->fileTemp) === false) {
                return false;
            }
        }
        return true;
    }

    /**
     * Validate upload path
     * @return bool
     */
    public function validateUploadPath()
    {
        if ($this->uploadPath == '') {
            $this->error = 'No upload path set!';
            return false;
        }

        if (function_exists('realpath') && @realpath($this->uploadPath) !== false) {
            $this->uploadPath = str_replace("\\", "/", realpath($this->uploadPath));
        }

        if (!@is_dir($this->uploadPath)) {
            $this->error = 'Invalid upload path!';
            return false;
        }

        $this->uploadPath = preg_replace("/(.+?)\/*$/", "\\1/", $this->uploadPath);
        return true;
    }

    /**
     * Clean the file name for security
     * @param string
     * @return string
     */
    public function cleanFileName($filename)
    {
        $bad = ["<!--",
            "-->",
            "'",
            "<",
            ">",
            '"',
            '&',
            '$',
            '=',
            ';',
            '?',
            '/',
            "%20",
            "%22",
            "%3c",  // <
            "%253c",// <
            "%3e",  // >
            "%0e",  // >
            "%28",  // (
            "%29",  // )
            "%2528",// (
            "%26",  // &
            "%24",  // $
            "%3f",  // ?
            "%3b",  // ;
            "%3d"   // =
        ];
        $filename = str_replace($bad, '', $filename);
        return stripslashes($filename);
    }


    /**
     * Helper function used to delete file.
     * @param string
     * @return bool
     */
    public function deleteFile($path)
    {
        if (is_file($path)) {
            return @unlink($path);
        }
        return false;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @return string
     */
    public function getFileExtension()
    {
        return $this->fileExt;
    }

    /**
     * @return int
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param string
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * @return array
     */
    public function getAllowedTypes()
    {
        return $this->allowedTypes;
    }

    /**
     * @param array
     */
    public function setAllowedTypes($allowedTypes)
    {
        $this->allowedTypes = $allowedTypes;
    }

    /**
     * @return string
     */
    public function getUploadPath()
    {
        return $this->uploadPath;
    }

    /**
     * @param string
     */
    public function setUploadPath($uploadPath)
    {
        $this->uploadPath = $uploadPath;
    }

    /**
     * @param string
     */
    public function setFileNameOverride($nameOverride)
    {
        $this->nameOverride = $nameOverride;
    }
}
