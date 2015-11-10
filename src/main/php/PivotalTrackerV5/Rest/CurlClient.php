<?php
namespace PivotalTrackerV5\Rest;


class CurlClient extends Client
{
    /**
     * Query encoding type
     * @url http://php.net/http_build_query
     */
    private static $enc_type = PHP_QUERY_RFC1738;


    public function setEncType($enc_type)
    {
        self::$enc_type = $enc_type;
    }


    public function get( $path, array $query = null, $body = null )
    {
        if ( $query )
        {
            $path .= '?' . http_build_query( $query, null, null, self::$enc_type );
        }
        return $this->request( self::GET, $path, $body );
    }


    public function request( $method, $path, $body = null )
    {
        $ch = curl_init($this->server . $path);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if ($method == self::POST || $method == self::PUT)
        {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}