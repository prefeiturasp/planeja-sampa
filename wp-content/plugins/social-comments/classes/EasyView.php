<?php
/**
 * EasyView
 *
 * A simple view class that helps separate your  ‘programming logic’ 
 * (functions or methods) from your ‘view’ (the resulting HTML mark-up). 
 *
 * @author  Ohad Raz <admin@bainternet.info>
 * @version 0.1
 */
if(!class_exists('EasyView')){
    class EasyView {
        /**
         * $template_dir 
         * 
         * Holds template directory
         * @author  Ohad Raz <admin@bainternet.info>
         * @since 0.1
         * @access protected
         * @var string
         */
        protected $template_dir = '';
        /**
         * $vars 
         * 
         * holds template vars
         * @author  Ohad Raz <admin@bainternet.info>
         * @since 0.1
         * @access protected
         * @var array
         */
        protected $vars = array();
        /**
         * $search_replace 
         * 
         * used for search and replace within a template
         * should be called by addSearchReplace method
         * @author  Ohad Raz <admin@bainternet.info>
         * @since 0.1
         * @access protected
         * @var array
         */
        protected $search_replace = array('search' => array(), 'replace' => array());
        /**
         * $get_mode 
         * 
         * holds a flag to either echo or return
         * @author  Ohad Raz <admin@bainternet.info>
         * @since 0.1
         * @access protected
         * @var string
         */
        protected $get_mode_echo = true;

        /**
         * __construct 
         *
         * @author  Ohad Raz <admin@bainternet.info>
         * @since 0.1
         * @access public
         * @param string $template_dir template directory
         * @param array  $Properties   template vars and properties  ex: 
         *     'get_mode_echo' => true
         *     'vars'          => array(
         *             'name'     => 'ohad',
         *             'lastname' => 'raz'
         *     )
         *     
         */
        public function __construct($template_dir = false, $Properties = array()) {
            if ($template_dir) {
                $this->template_dir = $template_dir;
            }
            $this->setProperties($Properties);
        }

        /**
         * setProperties 
         *
         * Used to set the template vars and properties
         * 
         * @author  Ohad Raz <admin@bainternet.info>
         * @since 0.1
         * @access public
         * @param array $args template vars to setup
         * @return Void
         */
        public function setProperties($args = array()){
            if (isset($args['vars'])){
                foreach ($args['vars'] as $key => $value) {
                    $this->vars[$key] = $value;
                }
                unset($args['vars']);
            }
            if (isset($args['object'])){
                $tmp = $args['object'];
                if (is_object($args['object']))
                    $tmp = $this->objectToArray($tmp);
                foreach ($tmp as $key => $value) {
                    $this->vars[$key] = $value;
                }
                unset($args['object']);
            }

            foreach ((array)$args as $key => $value) {
                $this->$key = $value;
            }
        }

        /**
         * Render 
         *
         * renders the template to the current buffer 
         * 
         * @author  Ohad Raz <admin@bainternet.info>
         * @since 0.1
         * @access public
         * @param string $template_file template file name
         * @return Voide
         */
        public function Render($template_file,$model = false) {
            if (file_exists($this->template_dir.$template_file)) {
                if ($model)
                    $this->setModel($model);
                extract($this->vars);
                include $this->template_dir.$template_file;
            } else {
                throw new Exception('no template file ' .$this->template_dir. $template_file . ' present');
            }
        }

        /**
         * getRender 
         * 
         * renders the template into a string
         * @author  Ohad Raz <admin@bainternet.info>
         * @since 0.1
         * @access public
         * @param  string $template_file template file name
         * @return string
         */
        public function getRender($template_file,$model = false) {
            if (file_exists($this->template_dir.$template_file)) {
                if ($model)
                    $this->setModel($model);
                ob_start();
                extract($this->vars);
                include $this->template_dir.$template_file;
                return str_replace($this->search_replace['search'], $this->search_replace['replace'], ob_get_clean());
            } else {
                throw new Exception('no template file ' .$this->template_dir. $template_file . ' present');
                return 'no template file ' .$this->template_dir. $template_file . ' present';
            }
        }
        
      

        /**
         * setModel
         * 
         * function to model set varibles in the vars array
         * @author  Ohad Raz <admin@bainternet.info>
         * @since 0.1
         * @access public
         * @param mixed $name  var name array or object
         * @param string $value  var value
         */
        public function setModel($name,$value = null) {
            if (is_object($name)){
                $this->setProperties(array('object' => $name));
            }elseif(is_array($name)){
                $this->setProperties(array('vars' => $name));
            }else
                $this->vars[$name] = $value;
        }

        /**
         * __set 
         * 
         * magic function to set varibles in the vars array
         * @author  Ohad Raz <admin@bainternet.info>
         * @since 0.1
         * @access public
         * @param string $name  var name
         * @param mixed $value  var value
         */
        public function __set($name, $value) {

            $this->vars[$name] = $value;
        }

        /**
         * __get 
         * 
         * magic get to get the template vars
         * @author  Ohad Raz <admin@bainternet.info>
         * @since 0.1
         * @access public
         * @param  string $name  variable name
         * @return mixed
         */
        public function __get($name) {
            if (isset($this->vars[$name]))
                $this->returnEcho($this->vars[$name]);
            else
                $this->returnEcho("");
        }

        /**
         * returnEcho 
         * 
         * used to determine if to echo or return
         * @author  Ohad Raz <admin@bainternet.info>
         * @since 0.1
         * @access public
         * @param  mixed $var var to return or echo based on config
         * @return mixed $var
         */
        public function returnEcho($var){

            if ($this->get_mode_echo)
                echo $var;
            else
                return $var;
        }

        /**
         * setMode 
         * 
         * used to control the get method 
         * true will echo and false will return
         * @author  Ohad Raz <admin@bainternet.info>
         * @since 0.1
         * @access public
         * @param boolean $mode set mode to echo  on true or return false
         * @return void
         */
        public function setMode($mode){
            
            $this->get_mode_echo = ($mode)? true : false;
        }

        /**
         * addSearchReplace 
         * 
         * used to add search and replace vars
         * @author  Ohad Raz <admin@bainternet.info>
         * @since 0.1
         * @access public
         * @param string $search  string to search
         * @param string $replace string to replace
         * @return void
         */
        public function addSearchReplace($search, $replace){

            $this->search_replace['search'][] = $search;
            $this->search_replace['replace'][] = $replace;
        }

        /**
         * objectToArray 
         *
         * need i say more? it turns an object to an array for easier extract
         * @author  Ohad Raz <admin@bainternet.info>
         * @since 0.1
         * @access public
         * @param  mixed $object 
         * @return array
         */
        public function objectToArray( $object ) {
            if( !is_object( $object ) && !is_array( $object ) ) {
                return $object;
            }
            if( is_object( $object ) ) {
                $object = (array) $object;
            }
            return array_map( array($this,'objectToArray'), $object );
        }
    }//end class
}//end if