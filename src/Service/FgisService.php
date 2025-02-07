<?php

namespace App\Service;

use App\Dto\UveDataDto;
use App\Message\UveListProcessing;
use App\Service\AbstractFgisComponent;
use DateTimeInterface;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Компонент для работы с API FGIS.
 *
 * @link    https://fgis.gost.ru/fundmetrology/eapi/uve
 * @package modules\fgis\components
 */
class FgisService extends AbstractFgisComponent
{
    public const string BASE_URL = 'https://fgis.gost.ru/fundmetrology/eapi/uve';
    protected int $insertSuccessNpeCounter = 0;
    protected int $insertErrorsNpeCounter = 0;
    protected int $insertSuccessGradeCounter = 0;
    protected int $insertErrorsGradeCounter = 0;

    #[NoReturn] public function startFetching(SymfonyStyle $io, ?int $part = null): int
    {
        $this->io = $io;

        $scriptStartTime = new \DateTime();

        $this->printLog('Скрипт начал выгрузку...');
        $this->printLog('Время начала: ' . $scriptStartTime->format(DateTimeInterface::RFC850));

        $highLevelData = [];
        $pages = range(1, 9);

        $this->createPaginationRequests($highLevelData, $pages);

        $totalCount = array_sum(array_column($highLevelData, 'totalCount'));
        $this->printLog('Общее количество элементов - ' . $totalCount);

        $client = $this->client;

        $requests = static function ($data) use ($client) {
            for ($i = 0; $i <= $data['totalCount']; $i += 100) {
                $uri = static::BASE_URL . "?start={$i}&rows=100&number={$data['digit']}*";

                yield new Request('GET', $uri);
            }
        };

        $requestsPool = array_map($requests, $highLevelData);

        $requests = [];

        foreach ($this->merged(...$requestsPool) as $value) {
            $requests[] = $value;
        }

        $countRequests = count($requests);
        $counter = 0;
        $pool = new Pool(
            client: $this->client,
            requests: $requests,
            config: [
                'concurrency' => 2,
                'fulfilled'   => function (Response $response, $index) use (&$counter, $countRequests) {
                    $decodedResponse = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

                    $this->bus->dispatch(new UveListProcessing(
                        list: $decodedResponse['result']['items'],
                        url: static::BASE_URL
                    ));

                    $counter++;
                    $this->printLog('Завершено на: ' . round($counter / $countRequests * 100, 1) . '%');
                },
                'rejected'    => function (RequestException | ConnectException $reason, $index) {
                    echo $reason->getMessage();
                },
            ]);

        $pool->promise()->wait();

        $scriptEndTime = new \DateTime();
        $this->printLog('Скрипт закончил выгрузку. Время окончания: ' .
                        $scriptEndTime->format(DateTimeInterface::RFC850));
        $this->printLog('Выполнение заняло - ' .
                        $scriptEndTime->diff($scriptStartTime)->format('%h hours, %i minutes, %s seconds'));

        $this->printLogCountInsert();

        return Command::SUCCESS;
    }

    protected function getBaseUrl(): string
    {
        return self::BASE_URL;
    }

    protected function printLogCountInsert(): void
    {
        parent::printLogCountInsert();
        $this->printLog('Количество добавленных записей npe:' . $this->insertSuccessNpeCounter);
        $this->printLog('Количество неудачных записей npe:' . $this->insertErrorsNpeCounter);

        $this->printLog('Количество добавленных записей grade:' . $this->insertSuccessGradeCounter);
        $this->printLog('Количество неудачных записей grade:' . $this->insertErrorsGradeCounter);
    }

    private function createPaginationRequests(array &$data, array &$pages): void
    {
        foreach ($pages as $key => $page) {
            $uri = "?start={$this->start}&rows={$this->rows}&number={$page}*";

            $response = $this->request($uri);

            if (array_key_exists('result', $response)) {
                if ($response['result']['count'] > 99999) {
                    $range = array_map(static function ($n) {
                        return sprintf('3.%01d', $n);
                    }, range(1, 9));

                    $this->createPaginationRequests($data, $range);
                }

                if ($response['result']['count'] >= 1 && $response['result']['count'] < 99999) {
                    unset($pages[$key]);

                    $data[] = [
                        'totalCount' => $response['result']['count'],
                        'digit'      => $page,
                    ];
                }
            }
        }
    }
}
