<?php


namespace Kagatan\SmsRu;


class SmsRuMessage
{
    /**
     * Static factory method.
     *
     * @param mixed ...$arguments
     *
     * @return static|self
     */
    public static function create(...$arguments)
    {
        return new static(...$arguments);
    }


    /**
     * Set key
     *
     * @param $key
     * @return $this
     */
    public function key($key)
    {
        $this->key = (string)$key;
        return $this;
    }

    /**
     * Set phone
     *
     * @param $phone
     * @return $this
     */
    public function phone($phone)
    {
        $this->phone = (string)$phone;
        return $this;
    }

    public function checkId($id)
    {
        $this->check_id = (string)$id;
        return $this;
    }

    /**
     * Set a sender name.
     *
     * @param string $sender_name
     *
     * @return static|self
     */
    public function from($sender_name)
    {
        $this->from = (string)$sender_name;
        return $this;
    }

    /**
     * Set receiver phone number (the message should be sent to).
     *
     * @param string $phone_number
     *
     * @return static|self
     */
    public function to($phone_number)
    {
        $this->to = (string)$phone_number;
        return $this;
    }

    /**
     * Set the content of SMS message.
     *
     * @param string $content
     *
     * @return static|self
     */
    public function content($content)
    {
        $this->text = (string)$content;
        return $this;
    }


    /**
     *  To JSON
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }


    /**
     * To Array
     *
     * @return array
     */
    public function toArray()
    {
        return (array)$this;
    }
}

