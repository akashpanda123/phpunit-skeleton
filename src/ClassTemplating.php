<?php

class ClassTemplating
{
    public function __construct() {
    }
	
    public function getTemplateForFile($filePath) {
        $classes = $this->getClassesAndFunctionsFromFilePath($filePath);
        $template = "<?php\n";
        foreach($classes as $class) {
           $template .= $this->genericTemplate($class['name']);
           $template .= $this->getFunctionsTemplate($class);  
           $template .= "\n}\n\n";
        }
        return $template;
    }

    private function genericTemplate($classname) {
        $str = "class ".$classname."Test extends PHPUnit_Framework_TestCase
{
    public function setUp() {
        ".'$this->obj = new '.$classname.";
    }    
";
        return $str;
    }

    private function getClassesAndFunctionsFromFilePath($filepath) {
        $cp = new ClassParser();
        $cp->parse($filepath);
        return $cp->getClasses();        
    }
    private function getFunctionsTemplate ($classDetails) {
        $functionTemplates = "";
        $methodsDetails = $classDetails['functions'];
        foreach($methodsDetails as $methodName=>$methodDetail) {
            if($methodName == "__construct") {
                continue;
            }
            $functionTemplates .= "
    public function test".ucfirst($methodName)."() {\n
    }
            ";
        }
        return $functionTemplates;
    }
}
