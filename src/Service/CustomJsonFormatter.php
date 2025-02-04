<?php

namespace App\Service;

use Monolog\Formatter\NormalizerFormatter;
use Monolog\Handler\Curl\Util;
use Monolog\LogRecord;

/**
 * Serializes a log message to Logstash Event Format
 */
class CustomJsonFormatter extends NormalizerFormatter
{
    /**
     * @var string the name of the system for the Logstash log message, used to fill the @source field
     */
    protected string $systemName;

    /**
     * @var string an application name for the Logstash log message, used to fill the @type field
     */
    protected string $applicationName;

    /**
     * @var string the key for 'extra' fields from the Monolog record
     */
    protected string $extraKey;

    /**
     * @var string the key for 'context' fields from the Monolog record
     */
    protected string $contextKey;

    /**
     * @param string $applicationName The application that sends the data, used as the "type" field of logstash
     * @param string|null $systemName The system/machine name, used as the "source" field of logstash, defaults to the hostname of the machine
     * @param string $extraKey The key for extra keys inside logstash "fields", defaults to extra
     * @param string $contextKey The key for context keys inside logstash "fields", defaults to context
     *
     */
    public function __construct(string $applicationName = 'app', ?string $systemName = '', string $extraKey = 'extra', string $contextKey = 'context')
    {
        parent::__construct('Y-m-d\TH:i:s.uP');

        $this->systemName = $systemName === "" ? (string) gethostname() : $systemName;
        $this->applicationName = $applicationName;
        $this->extraKey = $extraKey;
        $this->contextKey = $contextKey;
    }

    /**
     * @inheritDoc
     * @throws \JsonException
     */
    public function format(LogRecord $record): string
    {
        $recordData = parent::format($record);
        $cDate = new \DateTime();

        // собираем все интересующие нас данные в массив
        $message = [
            'created_at' => $cDate->format('Y-m-d H:i:s'),
            'datetime' => $cDate->getTimestamp(),
            'http_host' => isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] ? $_SERVER['HTTP_HOST'] : "",
            'uri' => isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] ? $_SERVER['REQUEST_URI'] : "",
            'get' => $_GET ? http_build_query($_GET) : "",
            'post' => $_POST ? http_build_query($_POST) : "",
            'cookie' => "",
            'http_referer' => isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : "",
            'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT'] ? $_SERVER['HTTP_USER_AGENT'] : "",
            'method' => isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] ? $_SERVER['REQUEST_METHOD'] : "",
            'req_time' => (float) (isset($_SERVER['REQUEST_TIME_FLOAT']) && $_SERVER['REQUEST_TIME_FLOAT'] ? number_format(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"], 2, '.', '') : 0.00),
            'status' => 500,
        ];

        // сопоставляем тип ошибки и получаем ее шорт код, чтобы по нему в дальнейшем проводить фильтрацию данных
        $levels = [
            'DEBUG' => [
                'code' => 100,
                'short_code' => 1,
                'description' => 'Detailed debug information',
                'color' => '#afafaf',
            ],
            'INFO' => [
                'code' => 200,
                'short_code' => 2,
                'description' => 'Interesting events. Examples: User logs in, SQL logs',
                'color' => '#9dc3ff',
            ],
            'NOTICE' => [
                'code' => 250,
                'short_code' => 3,
                'description' => 'Normal but significant events',
                'color' => '#a19dff',
            ],
            'WARNING' => [
                'code' => 300,
                'short_code' => 4,
                'description' => 'Exceptional occurrences that are not errors. Examples: Use of deprecated APIs, poor use of an API, undesirable things that are not necessarily wrong',
                'color' => '#dfad00',
            ],
            'ERROR' => [
                'code' => 400,
                'short_code' => 5,
                'description' => 'Runtime errors that do not require immediate action but should typically be logged and monitored',
                'color' => '#df5a00',
            ],
            'CRITICAL' => [
                'code' => 500,
                'short_code' => 6,
                'description' => 'Critical conditions. Example: Application component unavailable, unexpected exception',
                'color' => '#ff0000',
            ],
            'ALERT' => [
                'code' => 550,
                'short_code' => 7,
                'description' => 'Action must be taken immediately. Example: Entire website down, database unavailable, etc. This should trigger the SMS alerts and wake you up',
                'color' => '#ff00be',
            ],
            'EMERGENCY' => [
                'code' => 600,
                'short_code' => 8,
                'description' => 'Emergency: system is unusable',
                'color' => '#010001',
            ],
        ];

        // записываем остальные полезные данные
        $message['description'] = $recordData['message'] ?? "";
        $message['user_id'] = (int) isset($GLOBALS['USER']) ? $GLOBALS['USER']['id'] : 0;
        $message['level'] = (int) (isset($recordData['level_name']) ? (array_key_exists($recordData['level_name'], $levels) ? $levels[$recordData['level_name']]['short_code'] : 6) : 6);

        if ($recordData['context']) {
            $message['description'] .= 'Trace: ' . json_encode($recordData['context'], JSON_THROW_ON_ERROR);
        }

        // конвертируем в JSON и пишем в файл
        return $this->toJson($message) . "\n";
    }
}
