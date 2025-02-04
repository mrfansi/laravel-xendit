<?php

namespace Mrfansi\LaravelXendit\Enums;

enum NotificationChannel: string
{
    case WHATSAPP = 'whatsapp';
    case EMAIL = 'email';
    case VIBER = 'viber';
    case SMS = 'sms';
}
