<?php

namespace App\MessageHandler;

use App\Message\SendNotifyToTelegram;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Notifier\Bridge\Telegram\Reply\Markup\Button\InlineKeyboardButton;
use Symfony\Component\Notifier\Bridge\Telegram\Reply\Markup\InlineKeyboardMarkup;
use Symfony\Component\Notifier\Bridge\Telegram\TelegramOptions;
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Message\ChatMessage;

#[AsMessageHandler]
class SendNotifyToTelegramHandler
{
    public function __construct(
        private readonly ChatterInterface $chatter
    )
    {
    }

    public function __invoke(SendNotifyToTelegram $message): void
    {
        $chatMessage = new ChatMessage($message->getMessage());

        $telegramOptions = (new TelegramOptions())
            ->chatId($message->getChatId())
            ->parseMode('MarkdownV2')
            ->disableWebPagePreview(true)
            ->disableNotification(false)
            ->replyMarkup((new InlineKeyboardMarkup())
                ->inlineKeyboard([
                    (new InlineKeyboardButton('Я крут(ая)'))->callbackData('yes'),
                    (new InlineKeyboardButton('Google'))->url('https://google.com'),
                ])
            );

        $chatMessage->options($telegramOptions);

        $this->chatter->send($chatMessage);
    }
}