<?php

require_once 'PEAR.php';

class Mail
{
    /** @var Net_IMAP */
    protected $imap;

    protected $time = [];

    protected $logs = [];

    function __construct($imap)
    {
        $this->imap = $imap;
    }

    public function login($username, $password)
    {
        startTimer();

        $ret = $this->imap->login($username, $password, false, false);
        if ($ret instanceof PEAR_Error) {
            throw new \Exception($ret->getMessage());
        }
        $this->time[__FUNCTION__] = finishTimer();
        $this->logs[__FUNCTION__] = ob_get_contents();

        ob_clean();
    }

    public function mailboxes()
    {
        $mailboxes = [];
        startTimer();
        $list = $this->imap->getMailboxes();
        $time = finishTimer();
        printTime($time, true);
        foreach ($list as $mb) {
            startTimer();
            $mailboxes[] = [
                'name' => $mb,
                'status' => $this->imap->getMailboxStatus($mb, ['UNSEEN']),
            ];
            $tmpTime = finishTimer();
            $time += $tmpTime;
            printTime($tmpTime, true);
        }
        $this->time[__FUNCTION__] = $time;
        $this->logs[__FUNCTION__] = ob_get_contents();

        ob_clean();

        return $mailboxes;
    }

    public function messages()
    {
        startTimer();
        $ret = $this->imap->selectMailbox('INBOX');
        if ($ret instanceof PEAR_Error) {
            ob_end_clean();
            echo $ret->getMessage();
            die();
        }
        $time = finishTimer();
        printTime($time, true);


        startTimer();
        $ret = $this->imap->search('ALL');
        if ($ret instanceof PEAR_Error) {
            ob_end_clean();
            echo $ret->getMessage();
            die();
        }

        $time = finishTimer();
        printTime($time, true);

        startTimer();
        $messages = $this->imap->getShortSummary($ret);
        $messages = array_reverse($messages);
        $tmpTime = finishTimer();
        $time += $tmpTime;
        printTime($tmpTime, true);

        $this->time[__FUNCTION__] = $time;
        $this->logs[__FUNCTION__] = ob_get_contents();

        ob_end_clean();

        return $messages;
    }

    public function getTotalTime()
    {
        $sum = 0;
        foreach ($this->time as $time) {
            $sum += $time;
        }
        return $sum;
    }

    /**
     * @return \Net_IMAP
     */
    public function getImap()
    {
        return $this->imap;
    }

    /**
     * @return array
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @return array
     */
    public function getLogs()
    {
        return $this->logs;
    }
}