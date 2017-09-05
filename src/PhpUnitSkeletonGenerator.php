<?php 

class PhpUnitSkeletonGenerator
{
    public function __construct($templating) {
        $this->templating = $templating;
    }

    public function generateSkeleton($sourceFolder , $destinationFolder) {
        $directoriesAndFiles = $this->getAllDirectoriesAndFilesFromSourceFolder($sourceFolder);
        $this->processTheFilesAndDirectoriesToCreateTestFiles($directoriesAndFiles , $sourceFolder ,  $destinationFolder);
    }

    private function getAllDirectoriesAndFilesFromSourceFolder($dir) {
        $path = $dir;
        $objects = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path),
                RecursiveIteratorIterator::SELF_FIRST
                );
        foreach ($objects as $file => $object) {
            $basename = $object->getBasename();
            if ($basename == '.' or $basename == '..') {
                continue;
            }
            if ($object->isDir()) {
                continue;
            }
            $fileData[] = $object->getPathname();
        }
        return $fileData;
    }

    private function processTheFilesAndDirectoriesToCreateTestFiles($directoriesAndFiles , $sourceFolder , $destinationFolder) {
        foreach($directoriesAndFiles as $file) {
            $content = $this->getContentToWriteForFile($file) ;
            $str = substr($file, 0, strrpos($file, "/"));
            //exec("mkdir -p $destinationFolder");
            $destinationFolderToWrite = "";
            $destinationFolderToWrite = $destinationFolder ."/" . substr($str, strlen($sourceFolder));
            exec("mkdir -p $destinationFolderToWrite");
            if(file_exists($destinationFolderToWrite."/".$content['fileName'])) {
                echo $destinationFolderToWrite."/".$content['fileName']." file already exists.\n";
            }else {
                file_put_contents($destinationFolderToWrite."/".$content['fileName'] , $content['template']);
            }
        } 
    }

    private function getContentToWriteForFile($file) {
	$t = $this->templating->getTemplateForFile($file);
        $a = explode( ".", end(explode("/" , $file)));
        $filename = $a[0]."Test.php";
        return array(
                'fileName' => $filename,
                'template' => $t
                );
    }
}
