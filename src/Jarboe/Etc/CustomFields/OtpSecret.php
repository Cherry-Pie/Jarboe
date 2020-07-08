<?php

namespace Yaro\Jarboe\Etc\CustomFields;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Fields\Traits\Placeholder;
use Yaro\Jarboe\Table\Fields\Traits\Tooltip;

class OtpSecret extends AbstractField
{
    use Tooltip;
    use Placeholder;

    public function value(Request $request)
    {
        return app(Google2FA::class)->generateSecretKey();
    }

    public function shouldSkip(Request $request)
    {
        return (bool) parent::value($request);
    }

    public function getListView($model)
    {
        return view('jarboe::custom_fields.otp_secret.list');
    }

    public function getEditFormView($model)
    {
        return view('jarboe::custom_fields.otp_secret.edit', [
            'field' => $this,
            'model' => $model,
        ]);
    }

    public function getCreateFormView()
    {
        return view('jarboe::custom_fields.otp_secret.create', [
            'field' => $this,
        ]);
    }

    public function qrSvg($model): string
    {
        $url = app(Google2FA::class)->getQRCodeUrl(
            config('jarboe.admin_panel.two_factor_auth.company_name', config('app.name')),
            $model->email,
            $model->{$this->name()}
        );

        $writer = new Writer(new ImageRenderer(
            new RendererStyle(180),
            new SvgImageBackEnd()
        ));

        return $writer->writeString($url);
    }
}
