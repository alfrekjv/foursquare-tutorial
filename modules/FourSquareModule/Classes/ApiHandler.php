<?php

namespace FourSquareModule\Classes;

class ApiHandler
{

    protected $secret;
    protected $key;
    protected $cache;

    /**
     * @var string Foursquare's Categories ID.
     */
    private $_categories = "4bf58dd8d48988d14c941735 4bf58dd8d48988d14a941735 4bf58dd8d48988d1d3941735 4f04af1f2fb6e1c99f3db0bb 4bf58dd8d48988d149941735 4bf58dd8d48988d151941735";

    public function __construct() { }

    /**
     * Injection of the Cache Handler dependency
     *
     * @param $cache The Cache Handler
     */
    public function setCache($cache)
    {
        $this->cache = $cache;
    }

    /**
     * Gets the Cache Handler
     *
     * @return mixed The Cache Handler
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * Sets the foursquare's API Secret code.
     *
     * @param $secret The secret code.
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    /**
     * Sets the foursquare client_id.
     *
     * @param $key The key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     *
     * Fetch a subset of venues using the foursquare API
     * and returns a JSON object.
     *
     * @param $lat The user's latitude
     * @param $lng The user's longitude
     *
     * @disclaimer This function is for demo purposes,
     *              it needs further validations in case
     *              the API calls actually fails.
     *
     * @return JSON The object with all the venues (hopefully)
     */
    public function getVenues($lat, $lng)
    {

        $cache    = $this->getCache();
        $cacheKey = 'foursquare-venues-for-lat-' . $lat . '-and-long-' . $lng;

        // Verify if we have data in the Cache.
        if (!$cache->contains($cacheKey)) {

            $cat    = str_replace(" ", ',', $this->_categories);
            $url    = "https://api.foursquare.com/v2/venues/search?v=20120610&ll={$lat},{$lng}&intent=browse&radius=9500&limit=100&categoryId=" . $cat . "&client_id=" . $this->key . "&client_secret=" . $this->secret;
            $result = file_get_contents($url); // do the call.
            $venues = json_decode($result, true);
            $spots  = array('venues' => array());

            if ($venues['meta']['code'] == 200) {
                foreach ($venues['response'] as $venues) {
                    $i = 0;
                    foreach ($venues as $venue) {

                        // Check if the joint has more than 10 check-ins ever.
                        if ($venue['stats']['checkinsCount'] > 10) {
                            $spots['venues'][$i]['id']          = isset($venue['id']) ? $venue['id'] : '';
                            $spots['venues'][$i]['name']        = isset($venue['name']) ? $venue['name'] : '';
                            $spots['venues'][$i]['latitude']    = isset($venue['location']['lat']) ? $venue['location']['lat'] : '';
                            $spots['venues'][$i]['longitude']   = isset($venue['location']['lng']) ? $venue['location']['lng'] : '';
                            $spots['venues'][$i]['address']     = isset($venue['location']['address']) ? $venue['location']['address'] : '';
                            $spots['venues'][$i]['crossStreet'] = isset($venue['location']['crossStreet']) ? $venue['location']['crossStreet'] : '';
                            $spots['venues'][$i]['city']        = isset($venue['location']['city']) ? $venue['location']['city'] : '';
                            $spots['venues'][$i]['state']       = isset($venue['location']['state']) ? $venue['location']['state'] : '';
                            $spots['venues'][$i]['postalCode']  = isset($venue['location']['postalCode']) ? $venue['location']['postalCode'] : '';
                            $spots['venues'][$i]['country']     = isset($venue['location']['country']) ? $venue['location']['country'] : '';
                            $spots['venues'][$i]['url']         = isset($venue['url']) ? $venue['url'] : '';
                            $spots['venues'][$i]['people']      = isset($venue['hereNow']['count']) ? $venue['hereNow']['count'] : '';
                            $spots['venues'][$i]['categories']  = $venue['categories'];
                            $spots['venues'][$i]['contact']     = $venue['contact'];
                            $spots['venues'][$i]['stats']       = $venue['stats'];

                            $i++;
                        }
                    }
                }
            }

            // Store the array in the Cache.
            $cache->save($cacheKey, $spots, 600);
        } else {
            // Fetch the array in case we already had it in the cache.
            $spots = $cache->fetch($cacheKey);
        }

        // return the JSON Object.
        return json_encode($spots);
    }

}