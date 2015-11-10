<?php
/**
 * Created by PhpStorm.
 * User: maxchirkov
 * Date: 11/10/15
 * Time: 10:01 AM
 */
namespace PivotalTrackerV5\Rest;


/**
 * Helper class that provides some basic REST functionality.
 *
 * This class provides helper methods for common HTTP request methods like
 * <em>GET</em>, <em>POST</em> and <em>PUT</em>.
 *
 * <code>
 * $client = new Client( 'http://example.com' );
 * $client->get( '/objects' );
 * $client->put( '/objects', $obj );
 * $client->post( '/objects', $obj );
 * </code>
 *
 * The ctor of this class expects the remote REST server as argument. This
 * includes host/ip, port and protocol.
 */
interface RestClientInterface
{

    /**
     * Adds an additional request header.
     *
     * @param string $name
     * @param string $value
     *
     * @return void
     */
    public function addHeader($name, $value);


    /**
     * Execute a HTTP GET request to the remote server
     *
     * Returns the raw response from the remote server.
     *
     * @param string $path
     * @param array  $query
     * @param mixed  $body
     *
     * @return mixed
     */
    public function get($path, array $query = null, $body = null);


    /**
     * Execute a HTTP POST request to the remote server
     *
     * Returns the raw response from the remote server.
     *
     * @param string $path
     * @param mixed  $body
     *
     * @return mixed
     */
    public function post($path, $body = null);


    /**
     * Execute a HTTP PUT request to the remote server
     *
     * Returns the raw response from the remote server.
     *
     * @param string $path
     * @param mixed  $body
     *
     * @return mixed
     */
    public function put($path, $body = null);


    /**
     * Execute a HTTP request to the remote server
     *
     * Returns the raw response from the remote server.
     *
     * @param string $method
     * @param string $path
     * @param mixed  $body
     *
     * @return mixed
     */
    public function request($method, $path, $body = null);
}