<?php

namespace Acme\TestBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Acme\TestBundle\Models\Document;

class AcmeTestBundle extends Bundle
{
    public function boot() 
    {
        Document::setUploadDirectory($this->container->getParameter('uploads_directory'));
    }
}
