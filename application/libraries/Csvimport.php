<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * CodeIgniter CSV Import Class
 *
 * This library will help import a CSV file into
 * an associative array.
 * 
 * This library treats the first row of a CSV file
 * as a column header row.
 * 
 *
 * @package         CodeIgniter
 * @subpackage      Libraries
 * @category        Libraries
 * @author          Brad Stinson
 */
class Csvimport {
    private $handle = "";
    private $filepath = FALSE;
    private $column_headers = FALSE;
    private $initial_line = 0;
    private $delimiter = ",";
    private $detect_line_endings = FALSE;
   /**
     * Function that parses a CSV file and returns results
     * as an array.
     *
     * @access  public
     * @param   filepath        string  Location of the CSV file
     * @param   column_headers  array   Alternate values that will be used for array keys instead of first line of CSV
     * @param   detect_line_endings  boolean  When true sets the php INI settings to allow script to detect line endings. Needed for CSV files created on Macs.
     * @param   initial_line  integer  Sets the line of the file from which start parsing data.
     * @param   delimiter  string  The values delimiter (e.g. ";" or ",").
     * @return  array
     */
    public function get_array($filepath=FALSE, $column_headers=FALSE, $detect_line_endings=FALSE, $initial_line=FALSE, $delimiter=FALSE)
    {
        // Raise memory limit (for big files)
        ini_set('memory_limit', '20M');
        
        // File path
        if(! $filepath)
        {
            $filepath = $this->_get_filepath();    
        }
        else
        {   
            // If filepath provided, set it
            $this->_set_filepath($filepath);
        }
        // If file doesn't exists, return false
        if(! file_exists($filepath))
        {
            return FALSE;            
        }
        // auto detect row endings
        if(! $detect_line_endings)
        {
            $detect_line_endings = $this->_get_detect_line_endings();    
        }
        else
        {   
            // If detect_line_endings provided, set it
            $this->_set_detect_line_endings($detect_line_endings);
        }
        // If true, auto detect row endings
        if($detect_line_endings) 
        {
            ini_set("auto_detect_line_endings", TRUE);
        }
        // Parse from this line on
        if(! $initial_line)
        {
            $initial_line = $this->_get_initial_line();    
        }
        else
        {
            $this->_set_initial_line($initial_line);
        }
        // Delimiter
        if(! $delimiter)
        {
            $delimiter = $this->_get_delimiter();    
        }
        else
        {   
            // If delimiter provided, set it
            $this->_set_delimiter($delimiter);
        }
        // Column headers
        if(! $column_headers)
        {
            $column_headers = $this->_get_column_headers();    
        }
        else
        {
            // If column headers provided, set them
            $this->_set_column_headers($column_headers);
        }
        // Open the CSV for reading
        $this->_get_handle();
        
        $row = 0;
        while (($data = fgetcsv($this->handle, 0, $this->delimiter)) !== FALSE) 
        {     
            if ($data[0] != NULL) 
            {
                if($row < $this->initial_line)
                {
                    $row++;
                    continue;
                }
                // If first row, parse for column_headers
                if($row == $this->initial_line)
                {
                    // If column_headers already provided, use them
                    if($this->column_headers)
                    {
                        foreach ($this->column_headers as $key => $value)
                        {
                            $column_headers[$key] = trim($value);
                        }
                    }
                    else // Parse first row for column_headers to use
                    {
                        foreach ($data as $key => $value)
                        {
                              $column_headers[$key] = trim($value);
                        }                
                    }          
                }
                else
                {
                    $new_row = $row - $this->initial_line - 1; // needed so that the returned array starts at 0 instead of 1
                    foreach($column_headers as $key => $value) // assumes there are as many columns as their are title columns
                    {
                    $result[$new_row][$value] = utf8_encode(trim($data[$key]));
                    }
                }
            
                unset($data);
            
                $row++;
            }
        }
 
        $this->_close_csv();
        return $result;
    }
    
    /**
     * Sets the "detect_line_endings" flag
     *
     * @access  private
     * @param   detect_line_endings    bool  The flag bit
     * @return  void
     */
    private function _set_detect_line_endings($detect_line_endings)
    {
        $this->detect_line_endings = $detect_line_endings;
    }
    /**
     * Sets the "detect_line_endings" flag
     *
     * @access  public
     * @param   detect_line_endings    bool  The flag bit
     * @return  void
     */
    public function detect_line_endings($detect_line_endings)
    {
        $this->_set_detect_line_endings($detect_line_endings);
        return $this;
    }
    /**
     * Gets the "detect_line_endings" flag
     *
     * @access  private
     * @return  bool
     */
    private function _get_detect_line_endings()
    {
        return $this->detect_line_endings;
    }
    /**
     * Sets the initial line from which start to parse the file
     *
     * @access  private
     * @param   initial_line    int  Start parse from this line
     * @return  void
     */
    private function _set_initial_line($initial_line)
    {
       return $this->initial_line = $initial_line;
    }
    /**
     * Sets the initial line from which start to parse the file
     *
     * @access  public
     * @param   initial_line    int  Start parse from this line
     * @return  void
     */
    public function initial_line($initial_line)
    {
        $this->_set_initial_line($initial_line);
        return $this;
    }
    /**
     * Gets the initial line from which start to parse the file
     *
     * @access  private
     * @return  int
     */
    private function _get_initial_line()
    {
        return $this->initial_line;
    }
    /**
     * Sets the values delimiter
     *
     * @access  private
     * @param   initial_line    string  The values delimiter (eg. "," or ";")
     * @return  void
     */
    private function _set_delimiter($delimiter)
    {
        $this->delimiter = $delimiter;
    }
    /**
     * Sets the values delimiter
     *
     * @access  public
     * @param   initial_line    string  The values delimiter (eg. "," or ";")
     * @return  void
     */
    public function delimiter($delimiter)
    {
        $this->_set_delimiter($delimiter);
        return $this;
    }
    /**
     * Gets the values delimiter
     *
     * @access  private
     * @return  string
     */
    private function _get_delimiter()
    {
        return $this->delimiter;
    }
    /**
     * Sets the filepath of a given CSV file
     *
     * @access  private
     * @param   filepath    string  Location of the CSV file
     * @return  void
     */
    private function _set_filepath($filepath)
    {
        $this->filepath = $filepath;
    }
    /**
     * Sets the filepath of a given CSV file
     *
     * @access  public
     * @param   filepath    string  Location of the CSV file
     * @return  void
     */
    public function filepath($filepath)
    {
        $this->_set_filepath($filepath);
        return $this;
    }
    /**
     * Gets the filepath of a given CSV file
     *
     * @access  private
     * @return  string
     */
    private function _get_filepath()
    {
        return $this->filepath;
    }
   /**
     * Sets the alternate column headers that will be used when creating the array
     *
     * @access  private
     * @param   column_headers  array   Alternate column_headers that will be used instead of first line of CSV
     * @return  void
     */
    private function _set_column_headers($column_headers='')
    {
        if(is_array($column_headers) && !empty($column_headers))
        {
            $this->column_headers = $column_headers;
        }
    }
    /**
     * Sets the alternate column headers that will be used when creating the array
     *
     * @access  public
     * @param   column_headers  array   Alternate column_headers that will be used instead of first line of CSV
     * @return  void
     */
    public function column_headers($column_headers)
    {
        $this->_set_column_headers($column_headers);
        return $this;
    }
    /**
     * Gets the alternate column headers that will be used when creating the array
     *
     * @access  private
     * @return  mixed
     */
    private function _get_column_headers()
    {
        return $this->column_headers;
    }
   /**
     * Opens the CSV file for parsing
     *
     * @access  private
     * @return  void
     */
    private function _get_handle()
    {
        $this->handle = fopen($this->filepath, "r");
    }
   /**
     * Closes the CSV file when complete
     *
     * @access  private
     * @return  array
     */
    private function _close_csv()
    {
        fclose($this->handle);
    }    
}