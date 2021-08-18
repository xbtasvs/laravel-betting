<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OddsController extends Controller
{
    public function __construct($redisClient = null, $redisPrefix = '0', $readIndex = '0', $readIndexKey = 'ReadIndex')
    {
        $this->redisClient = $redisClient;
        $this->redisPrefix = $redisPrefix;
        $this->readIndex = $readIndex;
        $this->readIndexKey = $readIndexKey;
    }

    public function redisOpen()
    {
        $this->redisClient = fsockopen('redis-19054.c11.us-east-1-2.ec2.cloud.redislabs.com', 19054, $errno, $errstr, 30);
        if (!$this->redisClient) {
            return false;
        }

        return $this->redisAuthenticate('password1234');
    }

    public function redisAuthenticate($password)
    {
        if(!$this->redisClient) {
            return false;
        }

        fwrite($this->redisClient, "*2\r\n$4\r\nAUTH\r\n$" . strlen($password) . "\r\n" . $password . "\r\n");
        $value = fgets($this->redisClient, 256);

        return ($value == "+OK\r\n");
    }

    public function redisGet($key)
    {
        if(!$this->redisClient) {
            return '';
        }

        fwrite($this->redisClient, "*2\r\n$3\r\nGET\r\n$" . strlen($key) . "\r\n" . $key . "\r\n");
        $bIsFirstLineCount = true;
        $value = '';
        $size = 0;

        while ($line = fgets($this->redisClient)) {
            $value .= $line;
            $size -= strlen($line);
            if (substr($value, strlen($value) - 2, 2) === "\r\n") {
                if ($bIsFirstLineCount) {
                    $bIsFirstLineCount = false;
                    $size = intval(substr($value, 1, strlen($value) - 3));
                    $value = '';
                }
            }

            if ($size <= 0) {
                break;
            }
        }

        $outValue = substr($value, 0, strlen($value) - 2);
        return $outValue;
    }

    public function redisClose()
    {
        if ($this->redisClient) {
            fclose($this->redisClient);
        }
    }

  	public function redisDaoOpen()
  	{
  		  return $this->redisOpen();
  	}

  	public function redisDaoSetInPlay($bIsInplay)
  	{
  		if ($bIsInplay) {
  			   $this->redisPrefix = '1:';
  		} else {
  			   $this->redisPrefix = '0:';
  		}

  		$this->readIndex = $this->redisGet($this->redisPrefix . $this->readIndexKey);
  	}

  	public function redisDaoGetReadKey($key)
  	{
    		return $this->redisPrefix . $this->readIndex . ':' . $key;
  	}

  	public function redisDaoGet($key)
  	{
    		$readKey = $this->redisDaoGetReadKey($key);
    		$value = $this->redisGet($readKey);
    		return $value;
  	}

  	public function redisDaoClose()
  	{
  		  $this->redisClose();
  	}

    public function inplay(Request $request)
    {
        $sportID = '';

        if ($request['sport_id']) {
            $sportID = $request['sport_id'];
        }

        if ($this->redisDaoOpen()) {
            $this->redisDaoSetInPlay(true);
            $json = $this->redisDaoGet('inplay-sport-' . $sportID);
            $this->redisDaoClose();
            if (strlen($json) == 0) {
                return response('Events not fount', 500);
            } else {
                return response()->json($json);
            }
        } else {
            return response('Internal Server Error', 500);
        }
    }

    public function upcoming(Request $request)
    {
        if (!$request['sport_id']) {
            return response('Missing required params {sport_id}', 500);
        }

        $sportID = $request['sport_id']; // required

        if ($this->redisDaoOpen()) {
            $this->redisDaoSetInPlay(false);
            $json = $this->redisDaoGet('upcoming-sport-' . $sportID);
            $this->redisDaoClose();
            if (strlen($json) == 0) {
                return response('Events not fount', 500);
            } else {
                return response()->json($json);
            }
        } else {
            return response('Internal Server Error', 500);
        }
    }

    public function markets(Request $request)
    {
        if (!$request['event_id']) {
            return response('Missing required params {event_id}', 500);
        }

        if (!$request['inplay']) {
            return response('Missing required params {inplay}', 500);
        }

        $eventID = $request['event_id']; // required
        $bIsInplay = $request['inplay']; // required in [0 or 1]

        if ($bIsInplay !== '0' && $bIsInplay !== '1') {
            return response('Invalid required params {inplay}', 500);
        }

        if ($this->redisDaoOpen()) {
            $this->redisDaoSetInPlay($bIsInplay === '1');
            $json = $this->redisDaoGet($eventID);
            $this->redisDaoClose();
            if (strlen($json) == 0) {
                return response('Markets not fount', 500);
            } else {
                return response()->json($json);
            }
        } else {
            return response('Internal Server Error', 500);
        }
    }
}
