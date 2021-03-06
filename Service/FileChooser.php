<?php
namespace Evans\FilepickerBundle\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Finder\Finder;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ChooserFile {
    public $relativePath;
    public $relativePathname;
    public $thumbnailPath;
    public $url;
}

class FileChooser
{
    protected $folders;
    protected $baseUrl;
    protected $uploadDestination;
    protected $allowedMimeTypes;

    public function __construct(ContainerInterface $container) {

        $appRoot = $container->getParameter('kernel.root_dir');
        $config = $container->getParameter('evans.filepicker.config');

        $this->allowedMimeTypes = $config['allowed_mimes'];

        $this->folders = [];
        $this->baseUrl = $config['uploads_destination'];
        $uploadDestination = $appRoot.'/../web/'.ltrim($config['uploads_destination'],'/');

        $fs = new Filesystem();
        try {
            $fs->mkdir($uploadDestination);
        } catch (IOExceptionInterface $e) {

        }

        $this->uploadDestination = realpath($uploadDestination);
        $this->initFolders();
    }

    protected function initFolders() {
        if($this->uploadDestination !== false) {
            $finder = new Finder();
            $finder->ignoreUnreadableDirs();
            $finder->directories()->in($this->uploadDestination);
            $this->folders = iterator_to_array($finder);
        }
    }

    public function getBaseUrl() {
        return $this->baseUrl;
    }

    public function getFolders() {
        return $this->folders;
    }

    public function getFiles($path = '') {
        $path = $this->uploadDestination .'/'. $path;

        if($path === false || $this->uploadDestination === false) {
            return collect([]);
        }

        $finder = new Finder();

        $files = collect($finder->files()->in($path)->sort(
            function($a, $b) {
                return ($b->getMTime() - $a->getMTime());
            }
        ))->values();

        $files->transform(function($file) {
            $fi = new ChooserFile();
            $fi->relativePath = $file->getRelativePath();
            $fi->relativePathname = $file->getRelativePathname();
            $fi->url = $this->baseUrl.'/'.$file->getRelativePathname();

            // freeze this feature for the future - no thumbnails for now
            //$fi->thumbnailPath = $this->imager->filter($fi->url,'chooser_thumb');
            $fi->thumbnailPath = $fi->url;

            return $fi;
        });

        return $files;
    }

    public function upload($files) {
        $uploadDestination = $this->uploadDestination.'/'.date('Y').'/'. strtolower(date('M'));
        $uploadedFiles = [];

        if(is_array($files)) {
            foreach($files as $file) {
                if($file instanceof UploadedFile) {
                    if(in_array($file->getMimeType(), $this->allowedMimeTypes)) {
                        $fileName = $file->getClientOriginalName();
                        $file->move($uploadDestination, $this->uniqueFilename($uploadDestination, $fileName));
                        $uploadedFiles[] = $file;
                    }
                }
            }
        }

        return $uploadedFiles;
    }

    private function uniqueFilename($path, $filename) {
        $res = "$path/$filename";
        if (!file_exists($res)) return $res;
        $fnameNoExt = pathinfo($filename,PATHINFO_FILENAME);
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        $i = 1;
        while(file_exists("$path/$fnameNoExt ($i).$ext")) $i++;
        return "$fnameNoExt ($i).$ext";
    }
}
