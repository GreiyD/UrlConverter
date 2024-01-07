<?php

namespace App\Shortener\Service;

use App\Service\UrlConverterRepository;
use App\Shortener\Helpers\Validation\UrlValidator;
use App\Shortener\Interfaces\UrlConverter\InterfaceUrlDecoder;
use App\Shortener\Interfaces\UrlConverter\InterfaceUrlEncoder;
use Exception;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\RequestStack;

class UrlConverter implements InterfaceUrlDecoder, InterfaceUrlEncoder
{
    /**
     * @var UrlValidator
     */
    protected $validator;
    /**
     * @var UrlConverterRepository
     */
    protected $databaseRepository;
    /**
     * @var
     */
    protected $numberCharCode;
    /**
     * @var
     */
    protected $codeSalt;

    /**
     * @var bool
     */
    protected bool $saveToDatabase;

    /**
     * @var RequestStack
     */
    protected RequestStack $requestStack;


    /**
     * @param UrlValidator $validator
     * @param UrlConverterRepository $databaseRepository
     * @param $numberCharCode
     * @param $codeSalt
     * @param $saveToDatabase
     * @param RequestStack $requestStack
     */
    public function __construct(UrlValidator $validator, UrlConverterRepository $databaseRepository, $numberCharCode, $codeSalt, $saveToDatabase, RequestStack $requestStack)
    {
        $this->validator = $validator;
        $this->databaseRepository = $databaseRepository;
        $this->numberCharCode = $numberCharCode;
        $this->codeSalt = $codeSalt;
        $this->saveToDatabase = $saveToDatabase;
        $this->requestStack = $requestStack;
    }


    /**
     * @param string $url
     * @return string
     * @throws Exception
     */
    public function encode(string $url): string
    {
        return $this->prepareUrl($url);
    }

    /**
     * @param string $url
     * @return string
     */
    public function prepareUrl(string $url): string
    {
        $this->validator->validation($url);
        if (http_response_code() === 200) {
            if ($this->saveToDatabase) {

                $userId = $this->requestStack->getSession()->get('user_data')['user_id'];

                if (!$this->databaseRepository->checkUrlDatabase($url, $userId)) {
                    $code = $this->codingUrl($url);
                    if ($this->databaseRepository->saveAll($code, $url, $userId)) {
                        return $code;
                    } else {
                        throw new Exception("Код и URL не были сохранены - ");
                    }
                } else {
                    return $this->databaseRepository->getCode($url, $userId);
                }
            } else {
                if ($this->fileRepository->checkUrlFile($url)) {
                    $code = $this->codingUrl($url);
                    if ($this->fileRepository->saveAll($code, $url)) {
                        return $code;
                    } else {
                        throw new Exception("Код и URL не были сохранены - ");
                    }
                } else {
                    return $this->fileRepository->getCode($url);
                }
            }
        } elseif (http_response_code() === 400) {
            throw new InvalidArgumentException("URL не существует или недоступен - ");
        }
    }

    /**
     * @param string $url
     * @return string
     */
    protected function codingUrl(string $url): string
    {
        $codeSalt = $this->getCodeSalt();
        $numberCharCode = $this->getNumberCharCode();

        $url = $url . $codeSalt;
        $urlArray = str_split($url);
        shuffle($urlArray);
        $urlShuffled = implode('', $urlArray);
        return mb_substr($urlShuffled, 0, $numberCharCode);
    }

    /**
     * @param string $code
     * @return string
     */
    public function decode(string $code): string
    {
        if ($this->saveToDatabase) {
            $userId = $this->requestStack->getSession()->get('user_data')['user_id'];
            return $this->databaseRepository->getUrl($code, $userId);
        } else {
            return $this->fileRepository->getUrl($code);
        }
    }

    /**
     * @return mixed
     */
    public function getNumberCharCode()
    {
        return $this->numberCharCode;
    }

    /**
     * @return mixed
     */
    public function getCodeSalt()
    {
        return $this->codeSalt;
    }

}