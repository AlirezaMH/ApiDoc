<?php
define('DS',DIRECTORY_SEPARATOR);
define('_PATH_ROOT',dirname(__FILE__));
define('_DIR_NAME', substr(dirname(__FILE__), strrpos(dirname(__FILE__), DS)));

//printAlFiles(createDirsMap());
//$content = file_get_contents('.\_inc\admin.class.php');
//var_dump(classFinder($content));

class api{
    private $dirs = array();
    
    public function __construct(){
        $this->dirs = $this->createDirsMap();
        //echo '<pre>';
        //print_r($this->dirs); exit;
        //die();
    }
    private function fileFinder($path){
        return glob($path.DS.'*.php');
    }
    
    private function createDirsMap($path='..'){
        $output = array();
        //if(empty($path)){
        //    //$path = '..'.DS.'*';
        //    //return array('..'.DS.'*' => array());
        //    //return $output[$path] = $this->createDirsMap($path);
        //    return array('..' => $this->createDirsMap('..'));
        //}
        $dirs = glob($path,GLOB_ONLYDIR);
        if(!count($dirs)){
            return NULL;
        }
        
        foreach($dirs as $value){
            if($value == '..'._DIR_NAME){
                continue;
            }
            //$val = $this->createDirsMap($value.DS.'*');
            $output[$value] = $this->createDirsMap($value.DS.'*');
            
        }
        return $output;//array('..'.DS => $output);// $output;
    }
    
    public function printAlFiles($dir=array()){
        if(empty($dir)){
            $dir = $this->dirs;
        }
        foreach($dir as $key => $value){
            //$class = $this->classFinder(file_get_contents($key));
            
                foreach($this->fileFinder($key) as $file){
                    $content = file_get_contents($file);
                    $class = $this->classFinder($content);
                    echo "<tr>";
                    echo '<td style="word-wrap: break-word;"><small>'.str_replace(DS, DS.' ',substr($file,1)).'</small></td><td>';
                    for($i=0; $i< count($class[0]); $i++){
                        if($i){
                            echo '<hr/>';
                        }
                        if(isset($class[1][$i])){    //class
                            echo '<h6 id="'.$class[1][$i].'">'.$class[1][$i].'</h6>';
                            if($class[2][$i] =='extends'){
                                echo '<code>'.$class[2][$i]. ' <a href="#'.$class[3][$i].'">' .$class[3][$i].'</a></code>';
                            }
                            echo '';
                        }
                    }
                    
                    
                    //echo "";
                    $funcs = $this->functionFinder($content);
                    echo '</td><td>';
                    for($i = 0; $i < count($funcs[0]); $i++){
                        if(!isset($funcs[0][$i])){
                            break;
                        }
                        //echo ($funcs[0][$i] ?: '').'<br/>';
                        
                        if($funcs[2][$i] == 'static'){
                            if($funcs[1][$i] == 'public' || empty($funcs[1][$i])){
                                echo '<span class="label label-info">'.$funcs[1][$i].' '.$funcs[2][$i].'</span>';
                            }else{
                                echo '<span class="label label-warning" style="background-color: #FF8484;">'.$funcs[1][$i].' '.$funcs[2][$i].'</span>';
                            }
                        }elseif($funcs[1][$i] == 'public'){
                            echo '<span class="label label-success">'.$funcs[1][$i].'</span>';
                        }elseif($funcs[1][$i] == 'private'){
                            echo '<span class="label label-warning">'.$funcs[1][$i].'</span>';
                        }elseif($funcs[1][$i] == 'protected'){
                            echo '<span class="label label-error">'.$funcs[1][$i].'</span>';
                        }

                        echo ' <code>'.$funcs[3][$i].'</code><small class="muted"> ('.$funcs[4][$i].')</small><br/>';
                        //echo '<pre>';
                        //print_r($func);
                        //echo '</pre>';
                    }
                    echo "</td></tr>";
                }
            
            
            if(is_array($value)){
                $this->printAlFiles($value);
            }
        }
    }
    //----------------
    public function classFinder($content){ //is not correct
        preg_match_all('%class\s*([a-zA-Z0-9_]+)(?:\s*(extends)\s*([a-zA-Z0-9_]+))?\s*\{%', $content, $classes);
        return $classes;
    }
    //---------------
    public function functionFinder($content){
        //$content = file_get_contents($file);
        $validName = '[a-zA-Z0-9_]';
        //$parenthesis = '\(\s*(:?[^\)]\s*\(\s*(:?[^\)])*\s*\)\)*\s*\)';  //'\(\s*(:?[^\)])*\s*\)'
        //$parenthesis = '\(\s*(:?[^\)])*\s*\)';
        $parenthesis = '\(((?:\s*\,?\s*\$'.$validName.'+(?:\s*=\s*'.$validName.'+|\s*=\s*".*"|\s*=\s*\'.*\')?)*)\s*\)';
        //$parenthesis = '';
        preg_match_all('%(public|private|protected)?\s*(static)?\s*function\s+('.$validName.'+)\s*'.$parenthesis.'%u', $content, $functions);
        return $functions;
    }

}



?>