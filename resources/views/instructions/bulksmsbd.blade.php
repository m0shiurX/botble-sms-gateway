<h3>{{ trans('plugins/fob-sms-gateway::bulksmsbd.instructions.configuration_guide') }}</h3>
<ol>
    <li>
        <strong>{{ trans('plugins/fob-sms-gateway::bulksmsbd.instructions.sign_up') }}:</strong>
        <p>
            {!! BaseHelper::clean(trans('plugins/fob-sms-gateway::bulksmsbd.instructions.sign_up_description', [
                'link' => Html::link('https://bulksmsbd.net', 'BulkSMSBD', ['target' => '_blank']),
            ])) !!}
        </p>
    </li>
    <li>
        <strong>{{ trans('plugins/fob-sms-gateway::bulksmsbd.instructions.get_api_key') }}:</strong>
        <p>
            {!! BaseHelper::clean(trans('plugins/fob-sms-gateway::bulksmsbd.instructions.get_api_key_description')) !!}
        </p>
    </li>
    <li>
        <strong>{{ trans('plugins/fob-sms-gateway::bulksmsbd.instructions.get_senderid') }}:</strong>
        <p>{!! BaseHelper::clean(trans('plugins/fob-sms-gateway::bulksmsbd.instructions.get_senderid_description')) !!}</p>
    </li>
    <li>
        <strong>{{ trans('plugins/fob-sms-gateway::bulksmsbd.instructions.configure_from') }}:</strong>
        <p>{!! BaseHelper::clean(trans('plugins/fob-sms-gateway::bulksmsbd.instructions.configure_from_description')) !!}</p>
    </li>
</ol>
