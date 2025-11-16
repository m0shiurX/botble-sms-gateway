<?php

namespace FriendsOfBotble\Sms\Forms;

use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\TextField;

class BulkSmsBdGatewayForm extends SmsGatewayForm
{
    protected array $sensitiveFields = [
        'api_key',
    ];

    public function setup(): void
    {
        parent::setup();

        $this
            ->add(
                'api_key',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/fob-sms-gateway::bulksmsbd.api_key'))
                    ->required()
            )
            ->add(
                'senderid',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/fob-sms-gateway::bulksmsbd.senderid'))
                    ->helperText(trans('plugins/fob-sms-gateway::bulksmsbd.senderid_help'))
                    ->required()
            )
            ->add(
                'from',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/fob-sms-gateway::bulksmsbd.from'))
                    ->helperText(trans('plugins/fob-sms-gateway::bulksmsbd.from_help'))
                    ->required()
            );
    }
}
