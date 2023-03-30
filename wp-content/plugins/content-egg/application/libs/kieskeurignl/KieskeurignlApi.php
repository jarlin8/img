<?php

namespace ContentEgg\application\libs\kieskeurignl;

defined('\ABSPATH') || exit;

use ContentEgg\application\libs\RestClient;

/**
 * KieskeurignlApi class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2023 keywordrush.com
 *
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'RestClient.php';

class KieskeurignlApi extends RestClient
{
    const API_URI_BASE = 'http://rest-ext.kieskeurig.nl/nl';

    protected $token;
    protected $affiliate_id;
    protected $country = 'NL';
    protected $_responseTypes = array(
        'xml',
    );

    public function __construct($token, $affiliate_id, $country = 'NL')
    {
        $this->token = $token;
        $this->affiliate_id = $affiliate_id;
        $this->country = $country;
        $this->setUri(self::API_URI_BASE);
        $this->setResponseType('xml');
    }

    public function search($keyword, array $options = array())
    {
        $options['q'] = $keyword;
        $response = $this->restGet('/product.nsf/wssearch', $options);
        return $this->_decodeResponse($response);
    }

    /**
     * Search by Product ID or EAN
     */
    public function searchId($id, array $options = array())
    {
        $options['productid'] = $id;
        $response = $this->restGet('/product.nsf/wsproddetailspecs', $options);
        return $this->_decodeResponse($response);
    }

    public function getOffers($id, array $options = array())
    {
        $options['productid'] = $id;
        $response = $this->restGet('/product.nsf/wsproductprices', $options);
        return $this->_decodeResponse($response);
    }

    public function restGet($path, array $query = null)
    {
        $query['_token'] = $this->token;
        $query['affid'] = $this->affiliate_id;
        $query['country'] = $this->country;
        return parent::restGet($path, $query);
    }
}
