<?php

namespace Acme\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Acme\TestBundle\Models\Document;


class UploadController extends Controller
{
    /**
     * @Route("/upload",name="upload_file")
     * @Template()
     */
    public function UploadAction(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $uploadStatus = true;
            $message = '';
            $uploadedURL = '';
            $image = $request->files->get('image');
            
            if (($image instanceof UploadedFile) && ($image->getError() == '0')) {
                
                if ($image->getSize() < 2000000) {
                    
                    $origninalExtension = $image->getClientOriginalExtension();
                    
                    $validExtension = array("jpg","jpeg","bmp","png");
                    
                    if (in_array(strtolower($origninalExtension), $validExtension) != false) {
                        $document = new Document();
                        $document->setFile($image);
                        $document->setUploadDirectory("uploads");
                        $document->processFile();
                        
                        $uploadedURL = $document->getUploadDirectory(). DIRECTORY_SEPARATOR . $document->getFilePersistencePath();
                        
                    } else {
                        $uploadStatus = false;
                        $message = "Upload image files only. with jpg/jpeg/bmp/png extensions.";
                    }
                } else {
                    
                    $uploadStatus = false;
                    $message = "File size has been exceeds";
                }
            } else {
                
                $uploadStatus = false;
                $message = "Can not upload file with errors";
            }
            
            return array('status' => $uploadStatus,
                        'message' => $message,
                        'uploadURL' => $uploadedURL);            
        } else {
            return array();
        }
    }
}
