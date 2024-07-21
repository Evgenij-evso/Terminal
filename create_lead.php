<?php

use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\Collections\NullTagsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\LeadsFilter;
use AmoCRM\Models\CompanyModel;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CustomFieldsValues\BirthdayCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\DateTimeCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\TextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\NullCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\TextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\TextCustomFieldValueModel;
use AmoCRM\Models\LeadModel;
use Carbon\Carbon;
use League\OAuth2\Client\Token\AccessTokenInterface;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;
use AmoCRM\Filters\NotesFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Collections\NotesCollection;
use AmoCRM\Models\Factories\NoteFactory;
use AmoCRM\Models\Interfaces\CallInterface;
use AmoCRM\Models\NoteType\CallInNote;
use AmoCRM\Models\NoteType\CommonNote;
use AmoCRM\Models\NoteType\ServiceMessageNote;
use AmoCRM\Models\NoteType\SmsOutNote;
use AmoCRM\Models\NoteType\MessageCashierNote;
use AmoCRM\Filters\ContactsFilter;
use AmoCRM\Models\Unsorted\FormsMetadata;

include_once __DIR__ . DIRECTORY_SEPARATOR .'config.php'; 
include_once __DIR__ . '/vendor/autoload.php';

$NAME_JSON_FILE = __DIR__ . '/tmp/token_info.json';
$list_subname = [];
$list_subname_id = [];

function update_token_refresh(){
    global $clientId,$clientSecret,$redirectUri,$NAME_JSON_FILE;
    $subdomain = 'autoschoolkoleso'; //Поддомен нужного аккаунта
    $link = 'https://' . $subdomain . '.amocrm.ru/oauth2/access_token'; //Формируем URL для запроса

    $json_access_token = file_get_contents($NAME_JSON_FILE);
    $json_access_token = json_decode($json_access_token);

    // var_dump($json_access_token);
    /** Соберем данные для запроса */
    $data = [
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'grant_type' => 'refresh_token',
        'refresh_token' => $json_access_token->refresh_token,
        'redirect_uri' => $redirectUri,
    ];

    /**
     * Нам необходимо инициировать запрос к серверу.
     * Воспользуемся библиотекой cURL (поставляется в составе PHP).
     * Вы также можете использовать и кроссплатформенную программу cURL, если вы не программируете на PHP.
     */
    $curl = curl_init(); //Сохраняем дескриптор сеанса cURL
    /** Устанавливаем необходимые опции для сеанса cURL  */
    curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
    curl_setopt($curl,CURLOPT_URL, $link);
    curl_setopt($curl,CURLOPT_HTTPHEADER,['Content-Type:application/json']);
    curl_setopt($curl,CURLOPT_HEADER, false);
    curl_setopt($curl,CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
    $out = curl_exec($curl); //Инициируем запрос к API и сохраняем ответ в переменную
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    /** Теперь мы можем обработать ответ, полученный от сервера. Это пример. Вы можете обработать данные своим способом. */
    $code = (int)$code;
    $errors = [
        400 => 'Bad request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not found',
        500 => 'Internal server error',
        502 => 'Bad gateway',
        503 => 'Service unavailable',
    ];

    try
    {
        /** Если код ответа не успешный - возвращаем сообщение об ошибке  */
        if ($code < 200 || $code > 204) {
            throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undefined error', $code);
        }
    }
    catch(\Exception $e)
    {
        die('Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());
    }

    /**
     * Данные получаем в формате JSON, поэтому, для получения читаемых данных,
     * нам придётся перевести ответ в формат, понятный PHP
     */
    // $response = json_decode($out, true);

    // $access_token = $response['access_token']; //Access токен
    // $refresh_token = $response['refresh_token']; //Refresh токен
    // $token_type = $response['token_type']; //Тип токена
    // $expires_in = $response['expires_in']; //Через сколько действие токена истекает
    file_put_contents($NAME_JSON_FILE, $out);
    return json_decode($out);
}
$accessToken = update_token_refresh();
function get_list_task() {
    global $accessToken;

    $path = '/api/v4/tasks?filter[task_type][]=2&filter[is_completed]=0';
    $url = "https://autoschoolkoleso.amocrm.ru" . $path;

    // Инициализируем сеанс cURL
    $ch = curl_init($url);
    
    // Настраиваем параметры cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $accessToken->access_token
    ]);
    // Выполняем запрос и получаем ответ
    $response = curl_exec($ch);
    
    // Проверяем на ошибки
    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new Exception("Ошибка cURL: " . $error);
    }
    
    // Закрываем сеанс cURL
    curl_close($ch);
    
    // Возвращаем ответ
    return $response;
}
function success_task($id) {
    global $accessToken;

    $path = '/api/v4/tasks/' . $id;
    $url = "https://autoschoolkoleso.amocrm.ru" . $path;

    $data = '{
        "is_completed": true,
        "result": {
            "text": "Клиент пришел."
        }
    }';
    $headers = [
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $accessToken->access_token
    ];
    
    $curlHeaders = [];
    foreach ($headers as $name => $value) {
        $curlHeaders[] = $name . ": " . $value;
    }

    $method = 'PATCH';

    // echo $method . ' ' . $url . PHP_EOL;
    foreach ($curlHeaders as $header) {
        // echo $header . PHP_EOL;
    }
    
    // Инициализируем сеанс cURL
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => $curlHeaders,
        CURLOPT_CONNECTTIMEOUT => 0,
        CURLOPT_TIMEOUT => 5,
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    $info = curl_getinfo($curl);
    curl_close($curl);
    
    // Возвращаем ответ
    return $info['http_code'];
}
function get_list_lead($id_task) {
    global $accessToken;

    $path = '/api/v4/leads/'.$id_task;
    $url = "https://autoschoolkoleso.amocrm.ru" . $path;

    // Инициализируем сеанс cURL
    $ch = curl_init($url);
    
    // Настраиваем параметры cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $accessToken->access_token
    ]);
    
    // Выполняем запрос и получаем ответ
    $response = curl_exec($ch);
    
    // Проверяем на ошибки
    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new Exception("Ошибка cURL: " . $error);
    }
    
    // Закрываем сеанс cURL
    curl_close($ch);
    
    // Возвращаем ответ
    return $response;
}
function get_field_func(){
    global $list_subname,$list_subname_id;
    $response = get_list_task();
    $json = json_decode($response);
    // var_dump($json);
    $name_subname = '';
    foreach($json->_embedded->tasks as $name => $value_)
    {
        // echo $value_->is_completed . PHP_EOL;
        if ($value_->is_completed == false){
            // echo $value_->entity_id . PHP_EOL;
            $json_lead = json_decode(get_list_lead($value_->entity_id));
            foreach($json_lead->custom_fields_values as $name => $value){
                if($value->field_name == 'Имя' || $value->field_name == 'Фамилия'){
                    $name_subname .= $value->values[0]->value . ' ';
                }
            }
            $list_subname[] = $name_subname;
            $list_subname_id[] = $value_->id;
            $name_subname = '';
        }
    }
}
