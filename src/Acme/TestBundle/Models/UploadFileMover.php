<?php

namespace Acme\TestBundle\Models;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadFileMover
{
      public function moveUploadedFile(UploadedFile $file, $uploadBasePath)
    {
        $originalName = $file->getClientOriginalName();
        
        // use filemtime() to have a more determenistic way to determine the subpath, otherwise its hard to test.
        $relativePath = date('Y-m', filemtime($file->getPath()));
        $targetFileName = $originalName;
        
        $targetFilePath = $uploadBasePath . DIRECTORY_SEPARATOR . $targetFileName;
        
        $ext = pathinfo($originalName,PATHINFO_EXTENSION);
        $i = 1;
        while (file_exists($targetFilePath) && md5_file($file->getPath()) != md5_file($targetFilePath)) {
            if ($ext) {
                $targetFilePath = $uploadBasePath . DIRECTORY_SEPARATOR . time().".".$ext;
            } else {
                $targetFilePath = $targetFilePath . $i++;
            }
        }
        
        $targetDir = $uploadBasePath;
        
        if (!is_dir($targetDir)) {
            $ret = mkdir($targetDir, unmask(), true);
            if (!$ret) {
                throw new \RuntimeException("Could not create target directory to move temporary file into.");
            }
        }
        
        $file->move($targetDir, basename($targetFilePath));
       
        return str_replace($uploadBasePath . DIRECTORY_SEPARATOR, "", $targetFilePath);
    }
}