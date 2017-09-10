<?php
namespace Evans\FilepickerBundle\Controller;

use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Evans\FilepickerBundle\Service\Serializer;
use Evans\FilepickerBundle\Service\FileChooser;

class FilepickerController extends Controller
{
    public function listAction(FileChooser $chooser, Serializer $serializer) {
        $files = $chooser->getFiles();

        return $serializer->JsonResponse(['files'=>$files->toArray()]);
    }

    public function uploadAction(Request $request, Serializer $serializer, FileChooser $chooser) {
        $files = $request->files->get('chooserfiles');

        $uploadedFiles = $chooser->upload($files);

        return $serializer->JsonResponse(['file' => $uploadedFiles]);
    }
}
