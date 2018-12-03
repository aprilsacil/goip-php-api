<?php // -->
/**
 * GoIP Client/Server Package based on
 * GoIP SMS Gateway Interface.
 *
 * (c) 2017 April Sacil
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace GoIP;

/**
 * Base Class
 *
 * @package  GoIP
 * @author   April Sacil <aprilvsacil@gmail.com>
 * @standard PSR-2
 */
class Sms extends Base
{
    /**
     * @const string Inbox route URL.
     */
    const INBOX_ROUTE = '/tools.html';
    /**
     * @const string Send Message route URL.
     */
    const SEND_SMS_ROUTE = '/sms_info.html';
    /**
     * @const string Send Sms Status route URL.
     */
    const SEND_SMS_STATUS_ROUTE = '/send_sms_status.xml';

    /**
     * Pulls All GoIp Modem Messages
     *
     * @return array $messages
     */
    public function getMessages()
    {
        $messages = [];
        $result = $this->connect(
            self::INBOX_ROUTE,
            ["type" => "sms_inbox"]
        );

        preg_match_all('/sms\=\s(.*\]);/', $result, $lines);
        foreach($lines[1] as $i => $dataRaw) {
            $line = $i + 1;
            $messages[$line] = [];

            $data = json_decode($dataRaw);
            
            if (is_null($data)) {
                $dataRaw = iconv("UTF-8", "UTF-8//IGNORE", $dataRaw);
                $data = json_decode($dataRaw);
            }
            foreach ($data as $message) {
                if (empty($message)) {
                    continue;
                }

                list($date, $sender, $text) = explode(',', $message, 3);
                $messages[$line][] = [
                    'date'      => $date,
                    'sender'    => $sender,
                    'text'      => $text,
                ];
            }
        }

        return $messages;
    }

    /**
     * Pulls Specific Line in GoIp Modem Messages
     *
     * @param int $line
     * @return array
     */
    public function getLineMessages($line)
    {
        $messages = $this->getMessages();
        return $messages[$line];
    }

    /**
     * Send Sms
     *
     * @param int $line
     * @param mixed $receiver
     * @param string $message
     * @return array
     */
    public function sendSms($line, $receiver, $message)
    {
        $smskey = rand();
        $data = array(
            'line' => $line,
            'smskey' => $smskey,
            'action' => 'sms',
            'telnum' => $receiver,
            'smscontent' => $message,
            'send' => 'send'
        );

        $this->connect(self::SEND_SMS_ROUTE, [], $data);

        $status = $this->getSmsSendStatus($line, $smskey);

        while($status['message'] == 'Sending ...') {
            $status = $this->getSmsSendStatus($line, $smskey);
        }
        
        return $status;
    }

    /**
     * Send Bulk Sms
     *
     * @param int $line
     * @param array $receivers
     * @param string $message
     * @return array
     */
    public function sendBulkSms($line, array $receivers, $message)
    {
        foreach ($receivers as $receiver) {
            $this->sendSms($line, $receiver, $message);
        }

        return $this;
    }

    /**
     * Get Sms Send Status
     *
     * @param int $line
     * @param mixed $key
     * @return array
     */
    public function getSmsSendStatus($line, $key)
    {
        $status = $this->connect(self::SEND_SMS_STATUS_ROUTE);
        $status = simplexml_load_string($status);
        $status = json_encode($status);
        $status = json_decode($status, true);
        $status = [
            'key' => $status['smskey'.$line],
            'status' => $status['status'.$line],
            'error' => $status['error'.$line]
        ];

        if ($status['key'] != $key) {
            return ['error' => true, 'message' => 'Error! Line Busy!'];
        }

        if (!$status['key']) {
            return ['error' => true, 'message' => 'Line is not active!'];
        }

        if ($status['error']) {
            return ['error' => true, 'message' => $status['error']];
        }

        if ($status['status'] == 'DONE') {
            return ['error' => false, 'message' => 'Successfully Sent!'];
        }

        if ($status['status'] == 'STARTED') {
            return ['error' => false, 'message' => 'Sending ...'];
        }
    }
}
