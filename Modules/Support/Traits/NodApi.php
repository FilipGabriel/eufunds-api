<?php

namespace Modules\Support\Traits;

use GuzzleHttp\Client;

trait NodApi
{
    /**
     * Client name
     *
     * @var string
     * */
    protected $_client;

    /**
     * Client auth key used for API auth
     *
     * @var string
     * */
    protected $_authKey;

    /**
     * Class constructor - set some values
     *
     * @return void
     * @throws Exception
     * */
    public function __construct()
    {
        $this->_client = config('services.nod.user');
        $this->_authKey = config('services.nod.key');

        parent::__construct();
    }

    private function getRequest($queryString = null)
    {
        return $this->request($queryString);
    }

    private function request(string $queryString = null)
    {
        $request = (new Client([
            'base_uri' => config('services.nod.url'),
            'headers' => [
                'X-NodWS-Date' => gmdate('r'),
                'X-NodWS-User' => $this->_client,
                'X-NodWS-Auth' => $this->_getSignatureString($this->_authKey, 'GET', rawurldecode($queryString)),
                'X-NodWS-Navigation' => 1,
                'X-NodWS-Accept' => 'json'
            ]
        ]))->get($queryString);

        $response = json_decode($request->getBody()->getContents());

        return $response;
    }

    /**
     * The function creates authorization string
     *
     * @param string $authKey
     * @param string $httpVerb
     * @param string $queryString
     * @return string authorization key
     * */
    private function _getSignatureString($authKey, $httpVerb, $queryString)
    {
        //HTTP verb , Query String , / , client, GMT date
        $signatureString = $httpVerb . trim($queryString, '/') . '/' . $this->_client . gmdate('r');
        return $this->_hmacSha1($authKey, $signatureString);
    }

    /**
     * Encrypts message with HMAC-SHA-1
     *
     * @param string $authKey
     * @param string $msg
     * @return string with crypted message
     * @see http://en.wikipedia.org/wiki/HMAC#Implementation
     * */
    private function _hmacSha1($authKey, $msg)
    {
        $blocksize = 64;
        $opad = str_repeat(chr(0x5c), $blocksize);
        $ipad = str_repeat(chr(0x36), $blocksize);
        $key = (strlen($authKey) < $blocksize) ? ($authKey . str_repeat(chr(0),
            ($blocksize - strlen($authKey)))) : $authKey;
        $hmac = sha1(($key ^ $opad) . sha1(($key ^ $ipad) . $msg, true), true);
        return base64_encode($hmac);
    }
}
