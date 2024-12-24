<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use OpenAI;
use HTMLPurifier;
use HTMLPurifier_Config;

class AiService
{
    /**
     * The OpenAI client instance.
     *
     * @var \OpenAI\Client
     */
    protected $client;

    /**
     * The OpenAI model to be used.
     *
     * @var string
     */
    protected $model;

    /**
     * Create a new AiService instance.
     */
    public function __construct()
    {
        $this->model = config('openai.model');
        $this->client = OpenAI::factory()
            ->withApiKey(config('openai.api_key'))
            ->withOrganization(config('openai.organization'))
            ->withBaseUri(config('openai.base_uri'))
            ->make();
    }

    /**
     * Handle AI requests for various actions.
     *
     * @param string $locale The locale for the request.
     * @param string $chatInput The input text for the AI.
     * @param string $action The action to be performed by the AI.
     * @param array $meta Additional meta data for the request.
     * @return string The AI response.
     */
    public function handleRequest(string $locale, string $chatInput, string $action, array $meta = []): string
    {
        $prompt = $this->generatePrompt($action, $chatInput, $meta);

        $messages = [
            ['role' => 'system', 'content' => config('prompts.system', 'You are a helpful assistant.')],
            ['role' => 'user', 'content' => $prompt]
        ];

        // Unique identifier for user executing prompt
        $user = Auth::guard($meta['guard'])->user()->email;

        $response = $this->client->chat()->create([
            'model' => $this->model,
            'messages' => $messages,
            // The maximum number of tokens to generate in the completion. Must be between 1 and 2048.
            'max_tokens' => (isset($meta['max_tokens'])) ? $meta['max_tokens'] : config('prompts.max_tokens', 50),
            // What sampling temperature to use, between 0 and 1. Higher values make the output more random, while lower values make it more focused and deterministic.
            'temperature' => (isset($meta['temperature'])) ? $meta['temperature'] : config('prompts.temperature', 1),
            // An alternative to sampling with temperature, called nucleus sampling. 0.1 means only the tokens comprising the top 10% probability mass are considered.
            'top_p' => 1.0,
            // How many completions to generate for each prompt. Must be between 1 and 2048.
            'n' => 1,
            // If true, partial message deltas will be sent, like in ChatGPT. Tokens will be sent as data-only server-sent events as they become available, with the stream terminated by a data: [DONE] message.
            'stream' => false,
            // Up to 4 sequences where the API will stop generating further tokens. The returned text will not contain the stop sequence.
            'stop' => null,
            // Include the log probabilities on the logprobs most likely tokens, as well as the chosen tokens. For example, if logprobs is 5, the API will return a list of the 5 most likely tokens.
            'logprobs' => null,
            // Number between -2.0 and 2.0. Positive values penalize new tokens based on whether they appear in the text so far, increasing the model's likelihood to talk about new topics.
            'presence_penalty' => 0.0,
            // Number between -2.0 and 2.0. Positive values penalize new tokens based on their existing frequency in the text so far, decreasing the model's likelihood to repeat the same line verbatim.
            'frequency_penalty' => 0.0,
            // Generates best_of completions server-side and returns the "best" (the one with the highest log probability per token). Results cannot be streamed.
            'logit_bias' => null,
            // A unique identifier representing your end-user, which can help OpenAI to monitor and detect abuse.
            'user' => $user,
        ]);

        // \Log::info($prompt);
        // \Log::info(json_decode(json_encode($response), true));

        $responseData = $this->sanitizeInput($response->choices[0]->message->content);
        return $responseData;
    }

    /**
     * Generate the prompt for the AI request based on the action.
     *
     * @param string $action The action to be performed.
     * @param string $chatInput The input text for the AI.
     * @param array $meta Additional meta data for the request.
     * @return string The generated prompt.
     */
    private function generatePrompt(string $action, string $chatInput, array $meta = []): string
    {
        $template = ($action == 'autofill') ? $meta['autoFillPrompt'] : config('prompts.prompts.'.$action.'.template');

        if ($template) {
            // Retrieve the i18n data from the application container
            $i18n = app()->make('i18n');
            // Initialize component properties with i18n data
            $locale = $i18n->language->current->locale;
            $localeSlug = $i18n->language->current->localeSlug;
            $language = explode('_', $i18n->language->current->locale)[0];
            $currency = $i18n->currency->id;
            $timezone = $i18n->time_zone;

            // Parse the string with variables
            $prompt = trans($template, [
                'locale' => isset($meta['locale']) ? $meta['locale'] : $locale,
                'localeSlug' => $localeSlug,
                'language' => $language,
                'currency' => $currency,
                'timezone' => $timezone,
                'user_input' => $chatInput,
                'translate_to_locale' => isset($meta['translate_to_locale']) ? $meta['translate_to_locale'] : $locale
            ]);
        } else {
            $prompt = $chatInput;
        }

        return $prompt;
    }

    /**
     * Sanitize input data.
     *
     * @param string $inputData The data to be sanitized.
     * @return string The sanitized data.
     */
    private function sanitizeInput(string $inputData): string
    {
        // Instantiate a new HTML Purifier
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', ''); // Do not allow any HTML tags
        $purifier = new HTMLPurifier($config);
        return $purifier->purify($inputData);
    }
}
