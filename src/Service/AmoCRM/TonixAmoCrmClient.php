<?php


namespace App\Service\AmoCRM;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TonixAmoCrmClient
{
    private $userLogin;
    private $userHash;
    private $subdomain;
    private $cookiePath;

    private $hasAuthenticated = false;

    const AUTH_RESULT_SUCCESS = 1;
    const AUTH_RESULT_FAIL = 0;
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    public function __construct($userLogin, $userHash, $subdomain, $cookiePath)
    {
        $this->userLogin = $userLogin;
        $this->userHash = $userHash;
        $this->subdomain = $subdomain;
        $this->cookiePath = $cookiePath.'/cookie.txt';
    }

    private function setCookiePath($path)
    {
        $this->cookiePath = $path;
    }

    private function getCookiePath()
    {
        return $this->cookiePath;
    }

    private function getAuthLink()
    {
        return 'https://' . $this->subdomain . '.amocrm.ru/private/api/auth.php?type=json';
    }

    private function getPreparedCurlInstance($link = false, $postItem = false)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');

        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $this->getCookiePath()); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
        curl_setopt($curl, CURLOPT_COOKIEJAR, $this->getCookiePath()); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

        if($link){
            curl_setopt($curl, CURLOPT_URL, $link);
        }

        if($postItem){
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postItem));
        }

        return $curl;
    }

    private function handleResponseCode($code)
    {
        $code = (int) $code;

        $errors = array(
            301 => 'Moved permanently',
            400 => 'Bad request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not found',
            500 => 'Internal server error',
            502 => 'Bad gateway',
            503 => 'Service unavailable',
        );

        try
        {
            if ($code != 200 && $code != 204) {
                throw new \Exception(isset($errors[$code]) ? $errors[$code] : 'Undescribed error', $code);
            }

        } catch (\Exception $E) {
            die('Ошибка: ' . $E->getMessage() . PHP_EOL . 'Код ошибки: ' . $E->getCode());
        }
    }

    public function doAuth()
    {
        $user = array(
            'USER_LOGIN' => $this->userLogin, #Ваш логин (электронная почта)
            'USER_HASH' => $this->userHash, #Хэш для доступа к API (смотрите в профиле пользователя)
        );

        $response = $this->postObject($user, $this->getAuthLink());


        $response = $response['response'];
        if (isset($response['auth'])) #Флаг авторизации доступен в свойстве "auth"
        {
            $this->hasAuthenticated = true;
            return self::AUTH_RESULT_SUCCESS;
        }

        return self::AUTH_RESULT_FAIL;
    }

    public function getObjectURL($objectType)
    {
        return 'https://' . $this->subdomain . '.amocrm.ru/api/v2/' . $objectType;
    }

    private function makeRequest($curl){

        $out = curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE); #Получим HTTP-код ответа сервера
        curl_close($curl); #Завершаем сеанс cURL

        $this->handleResponseCode($code);


        $response = json_decode($out, true);


        return $response;
    }

    public function postObject($object, $objectURL)
    {
        $curl = $this->getPreparedCurlInstance($objectURL, $object);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');

        return $this->makeRequest($curl);
    }


    public function getObject($objectURL, array $params)
    {
        $params = http_build_query($params);

        $objectURL = $objectURL . '?' . $params;


        $curl = $this->getPreparedCurlInstance($objectURL);

        return $this->makeRequest($curl);

    }
}
