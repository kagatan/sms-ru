<?php


namespace Kagatan\SmsRu;


class SmsRuClient
{
    /**
     * Server
     *
     * @var string
     */
    protected $server = 'https://sms.ru';


    /**
     * API Key
     *
     * @var null
     */
    protected $key = null;


    /**
     * From
     *
     * @var null
     */
    protected $from = null;


    /**
     * Last response
     *
     * @var array
     */
    private $_last_response = array();

    /**
     * Errors
     *
     * @var array
     */
    protected $_errors = array();

    /**
     * Количество попыток достучаться до сервера если он не доступен
     *
     * @var int
     */
    private $count_repeat = 5;


    public function __construct($key = null)
    {
        if (!empty($key)) {
            $this->key = $key;
        }
    }


    /**
     * Send SMS
     *
     * @param $params
     * @return string
     */
    public function send($params = array())
    {
        if (empty($params['from'])) {
            $params['from'] = $this->from;
        }

        $url = $this->server . '/sms/send';

        $result = $this->execute($url, $params);

        if ($result->status_code == 100) {
            $temp = (array)$result->sms;
            $temp = array_pop($temp);

            if ($temp->status_code == 100) {
                //OK
                return $temp->sms_id;
            } else {
                $this->_errors[] = "Error " . $temp->status_code . ". " . $temp->status_text;
            }
        } else {
            $this->_errors[] = "Error " . $result->status_code . ". " . $result->status_text;
        }

        return false;
    }

    /**
     * Call check add
     *
     * @param array $params
     * @return bool|mixed|null
     */
    public function callCheckAdd($params = array())
    {
        $url = $this->server . '/callcheck/add';

        $result = $this->execute($url, $params);

        if ($result->status_code == 100) {
            return $result;
        } else {
            $this->_errors[] = "Error " . $result->status_code . ". " . $result->status_text;
        }

        return false;
    }


    /**
     * Get call check status
     *
     * @param array $params
     * @return bool|mixed|null
     */
    public function callCheckStatus($params = array())
    {
        $url = $this->server . '/callcheck/status';

        $result = $this->execute($url, $params);

        if ($result->status_code == 100 ) {
            if($result->check_status == 401){
                return true;
            }else{
                $this->_errors[] = "Error " . $result->status_code . ". " . $result->check_status_text;
            }
        } else {
            $this->_errors[] = "Error " . $result->status_code . ". " . $result->status_text;
        }

        return false;
    }

    /**
     * Send request
     *
     * @param array $params
     * @return mixed|null
     */
    protected function execute($url, $params = array())
    {
        //Если не переопределения праметром используем default
        if (empty($params['key'])) {
            $params['api_id'] = $this->key;
        } else {
            $params['api_id'] = $params['key'];
        }

        $host = $url . "?json=1";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($ch, CURLOPT_VERBOSE, 1);


        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));

        curl_setopt($ch, CURLOPT_URL, $host);

        $response = curl_exec($ch);


        if ($response === FALSE) {
            $error = curl_error($ch);
        } else {
            $error = FALSE;
        }

        curl_close($ch);

        // If error
        if ($error && $this->count_repeat > 0) {
            $this->count_repeat--;
            $response = $this->execute($url, $params);
        }

        $this->_last_response = $response;

        return json_decode($this->_last_response);
    }

    /**
     * Get last response
     *
     * @return array
     */
    public function getResponse()
    {
        return $this->_last_response;
    }

    /**
     * Return array of errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * Returns number of errors
     * @return int
     */
    public function hasErrors()
    {
        return count($this->_errors);
    }

}

