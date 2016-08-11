<?php
namespace TextAnalysis\Extracts;

use TextAnalysis\Interfaces\IExtractStrategy;
use TextAnalysis\Utilities\Text;
use DateTime;

/**
 * Check if the given text is a date
 * @author yooper
 * 
 */
class DateExtract implements IExtractStrategy
{  
    /**
     * 
     * @param string $token
     * @return false|\DateTime
     */
    public function filter($token) 
    {
        $date = Text::findDate($token);
        if(!empty($date) && $this->verify($date)) {
            return new DateTime("{$date['year']}-{$date['month']}-{$date['day']}");
        }
        return false;
    }
    
    /**
     * Verify all the required fields are set in the array
     * @param array $date
     * @return boolean
     */
    protected function verify(array $date)
    {
        return (!empty($date['year']) &&  !empty($date['month']) && !empty($date['day']));
    }

}
