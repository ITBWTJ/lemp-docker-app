<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10.11.18
 * Time: 11:16
 */

namespace Src\Http\Request;

use Psr\Http\Message\RequestInterface;

class RequestParseFactory
{
    /**
     *
     */
    private const JSON_CONTENT_TYPE = 'application/json';

    /**
     *
     */
    private const TEXT_PLAIN_CONTENT_TYPE = 'text/plain';

    /**
     *
     */
    private const FORM_DATA_CONTENT_TYPE = 'multipart/form-data';

    /**
     *
     */
    private const FORM_URLENCODED_CONTENT_TYPE = 'application/x-www-form-urlencoded';

    /**
     * @var RequestParser
     */
    private $parser;


    /**
     * @param RequestInterface $request
     * @return RequestParser
     */
    public function make(RequestInterface $request): RequestParser
    {
        switch ($this->getContentType($request)) {
            case self::JSON_CONTENT_TYPE:
                $this->parser = new JsonParser();
                break;
            case self::TEXT_PLAIN_CONTENT_TYPE:
            case self::FORM_URLENCODED_CONTENT_TYPE:
                $this->parser = new TextPlainParser();
                break;
            case self::FORM_DATA_CONTENT_TYPE:
                $this->parser = new FormDataParser();
                break;

            default:
                $this->parser = new TextPlainParser();
        }

        $this->parser->setRequest($request);

        return $this->parser;
    }

    /**
     * @param RequestInterface $request
     * @return null|string
     */
    private function getContentType(RequestInterface $request): ?string
    {
        $header = $request->getHeader('Content-Type');

        if (!empty($header[0])) {
            return $this->explodeHeader($header[0]);
        }

        return null;
    }

    /**
     * @param string $header
     * @return string
     */
    private function explodeHeader(string $header): string
    {
        if (strpos($header, ';') !== false) {
            $explode = explode(';', $header);

            return $explode[0];
        }

        return $header;
    }


}