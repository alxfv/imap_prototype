<?php

require_once 'Net/IMAP.php';

class KMImap extends Net_IMAP
{
    function getMailboxStatus($mailbox = '', $request = [])
    {
        if ($mailbox == '') {
            $mailbox = $this->getCurrentMailbox();
        }
        $ret = $this->cmdStatus($mailbox, $request);
        if ($ret instanceOf PEAR_Error) {
            return $ret;
        }
        if (strtoupper($ret['RESPONSE']['CODE']) != 'OK') {
            return new PEAR_Error($ret['RESPONSE']['CODE']
            . ', '
            . $ret['RESPONSE']['STR_CODE']);
        }
        return $ret['PARSED']['STATUS']['ATTRIBUTES'];
    }


    function getShortSummary($msg_id = null, $uidFetch = false)
    {
        if ($msg_id != null) {
            if (is_array($msg_id)) {
                $message_set = $this->_getSearchListFromArray($msg_id);
            } else {
                $message_set = $msg_id;
            }
        } else {
            $message_set = '1:*';
        }
        $fetchparam = '(INTERNALDATE FLAGS BODY.PEEK[HEADER.FIELDS (FROM CONTENT-TYPE SUBJECT)])';
        if ($uidFetch) {
            $ret = $this->cmdUidFetch($message_set, $fetchparam);
        } else {
            $ret = $this->cmdFetch($message_set, $fetchparam);
        }

        // $ret=$this->cmdFetch($message_set,"(RFC822.SIZE UID FLAGS ENVELOPE INTERNALDATE BODY[1.MIME])");
        if ($ret instanceOf PEAR_Error) {
            return $ret;
        }
        if (strtoupper($ret['RESPONSE']['CODE']) != 'OK') {
            return new PEAR_Error($ret['RESPONSE']['CODE']
            . ', '
            . $ret['RESPONSE']['STR_CODE']);
        }

        if (isset($ret['PARSED'])) {
            for ($i=0; $i<count($ret['PARSED']); $i++) {
                $a['MSG_NUM']      = $ret["PARSED"][$i]['NRO'];
                $a['UID']          = $ret["PARSED"][$i]['EXT']['UID'];
                $a['FLAGS']        = $ret["PARSED"][$i]['EXT']['FLAGS'];
                $a['INTERNALDATE'] = $ret["PARSED"][$i]['EXT']['INTERNALDATE'];
                if (isset($ret['PARSED'][$i]['EXT']['BODY[HEADER.FIELDS (FROM CONTENT-TYPE SUBJECT)]']['CONTENT'])) {
                    $content = $ret['PARSED'][$i]['EXT']['BODY[HEADER.FIELDS (FROM CONTENT-TYPE SUBJECT)]']['CONTENT'];
                    if (preg_match('/content-type: (.*);/iU',
                        $content,
                        $matches)) {
                        $a['MIMETYPE'] = strtolower($matches[1]);
                        if ($a['MIMETYPE'] === 'multipart/mixed') {
                            $a['HAS_ATTACH'] = true;
                        }
                    }
                    if (preg_match('/From: (.*)' . "\r\n" . '/iU',
                        $content,
                        $matches)) {
                        $a['FROM'] = $matches[1];
                    }
                    if (preg_match('/Subject: (.*)' . "\r\n" . '/iU',
                        $content,
                        $matches)) {
                        $a['SUBJECT'] = $matches[1];
                    }
                }
                $env[] = $a;
                $a     = null;
            }
            if ($env instanceOf PEAR_Error) {
                return $env;
            }

            $messages = [];
            foreach ($env as $msg) {
                $message = [
                    'num' => $msg['MSG_NUM'],
                    'uid' => $msg['UID'],
                    'date' => $msg['INTERNALDATE'],
                    'type' => !empty($msg['MIMETYPE']) ? $msg['MIMETYPE'] : '',
                    'from' => !empty($msg['FROM']) ? iconv_mime_decode($msg['FROM'], ICONV_MIME_DECODE_CONTINUE_ON_ERROR, 'UTF-8') : '',
                    'subject' => !empty($msg['SUBJECT']) ? iconv_mime_decode($msg['SUBJECT'], ICONV_MIME_DECODE_CONTINUE_ON_ERROR, 'UTF-8') : '',
                    'flags' => $msg['FLAGS'],
                ];
                $message['is_read'] = in_array('\Seen', $message['flags']);
                $message['has_attach'] = !empty($msg['HAS_ATTACH']) ? true : false;
                $message['date'] = date('d.m.Y H:i', strtotime($message['date']));

                $messages[] = $message;
            }

            return $messages;
        }
        return $ret;
    }
}