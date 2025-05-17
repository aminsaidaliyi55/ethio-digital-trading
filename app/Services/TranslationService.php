<? php 
namespace App\Services;

use GuzzleHttp\Client;

class TranslationService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function translate($text, $targetLanguage)
    {
        $apiKey = env('GOOGLE_TRANSLATE_API_KEY');
        $url = "https://translation.googleapis.com/language/translate/v2";

        $response = $this->client->post($url, [
            'json' => [
                'q' => $text,
                'target' => $targetLanguage,
                'key' => $apiKey,
            ],
        ]);

        $data = json_decode($response->getBody(), true);
        return $data['data']['translations'][0]['translatedText'];
    }
}
?>