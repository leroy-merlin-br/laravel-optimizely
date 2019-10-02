<?php
namespace LeroyMerlin\Optimizely\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WebhookRequest extends FormRequest
{
    public function rules() {
        return [
            'data.cdn_url' => 'required|string'
        ];
    }

    public function getDatafileUrl(): string {
        return $this->input('data')['cdn_url'];
    }
}
