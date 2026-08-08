<?php

    /**
     * Premailer
     * 
     * @author  Oliver Nassar <onassar@gmail.com>
     * @see     https://github.com/alexdunae/premailer/
     * @see     http://premailer.dialect.ca/
     */
    class Premailer
    {
        /**
         * _markup
         * 
         * @access  protected
         * @var     null|string (default: null)
         */
        protected $_markup = null;

        /**
         * _markupPath
         *
         * Path of the temp file holding the markup for the current
         * {getInlinedMarkup} run; written before the shell command is built
         * and removed after it runs. (from Darrin)
         *
         * @access  protected
         * @var     null|string (default: null)
         */
        protected $_markupPath = null;

        /**
         * _options
         * 
         * @note    Some servers bugged outwhen the following were turned on:
         *          - remove_classes
         *          - remove_ids
         * @see     https://github.com/premailer/premailer/blob/master/lib/premailer/premailer.rb
         * @access  protected
         * @var     array
         */
        protected $_options = array(
            'css_to_attributes'         => true,
            'include_link_tags'         => true,
            'include_style_tags'        => true,
            'input_encoding'            => 'UTF-8',
            'preserve_reset'            => true,
            'preserve_styles'           => true,
            'remove_classes'            => false,
            'remove_comments'           => false,
            'remove_ids'                => false,
            'remove_scripts'            => true,
            'replace_html_entities'     => false
        );

        /**
         * __construct
         * 
         * @access  public
         * @return  void
         */
        public function __construct()
        {
        }

        /**
         * _getCLIOptions
         * 
         * @access  protected
         * @return  array
         */
        protected function _getCLIOptions(): array
        {
            $cliOptions = array();
            $markupPath = $this->_markupPath;
            $this->_options['markup_path'] = escapeshellarg($markupPath);
            $this->_options['with_html_string'] = true;
            $options = $this->_options;
            foreach ($options as $key => $value) {
                if ($value === false) {
                    continue;
                }
                if ($value === true) {
                    $cliOption = '--' . ($key);
                    array_push($cliOptions, $cliOption);
                    continue;
                }
                $cliOption = '--' . ($key) . ' ' . ($value);
                array_push($cliOptions, $cliOption);
            }
            return $cliOptions;
        }

        /**
         * _getCLIOptionsString
         * 
         * @see     http://rubyforge.org/docman/view.php/735/281/README.html
         * @access  protected
         * @return  string
         */
        protected function _getCLIOptionsString()
        {
            $options = $this->_getCLIOptions();
            $options = implode(' ', $options);
            return $options;
        }

        /**
         * _getMarkupPath
         *
         * Writes the markup to a temp file and returns the file's path. The
         * markup never touches the shell command line this way, so the shell
         * can't expand (eg. {$15} becoming {5}) or execute anything inside
         * it, and the 128KB per-argument limit no longer applies. (from
         * Darrin)
         *
         * @access  protected
         * @return  string
         */
        protected function _getMarkupPath(): string
        {
            $markup = $this->_markup;
            $markupPath = tempnam(sys_get_temp_dir(), 'premailer-');
            file_put_contents($markupPath, $markup);
            return $markupPath;
        }

        /**
         * getInlinedMarkup
         * 
         * @access  public
         * @return  string
         */
        public function getInlinedMarkup()
        {
            $scriptPath = dirname(__FILE__) . '/converter.rb';
            $output = array();
            $returnVar = 0;
            $this->_markupPath = $this->_getMarkupPath();
            $command = ($scriptPath) . ' ' . $this->_getCLIOptionsString();
            $response = exec($command, $output, $returnVar);
            unlink($this->_markupPath);
            if ($returnVar === 1) {
                $msg = 'Error';
                throw new Exception($msg);
            }
            $response = implode("\n", $output);
            return $response;
        }

        /**
         * setMarkup
         * 
         * @access  public
         * @param   string $markup
         * @return  void
         */
        public function setMarkup(string $markup): void
        {
            $this->_markup = $markup;
        }

        /**
         * setOption
         * 
         * @see     https://github.com/alexdunae/premailer/blob/master/lib/premailer/premailer.rb#L164
         * @access  public
         * @param   string $key
         * @param   mixed $value
         * @return  void
         */
        public function setOption(string $key, $value): void
        {
            $this->_options[$key] = $value;
        }
    }
