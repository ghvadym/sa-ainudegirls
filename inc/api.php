<?php

require_once(__DIR__ . '/lib/anthropic-sdk-php/vendor/autoload.php');
require_once(__DIR__ . '/lib/open-ai/vendor/autoload.php');

function faq_answers_generation(int $postId = 0)
{
    if (!$postId) {
        return;
    }

    $questions = get_field('faq_questions', 'options');
    $modelFields = get_field('fanvue_data', $postId);
    $name = $modelFields['fanvue_name'] ?? '';

    if (empty($questions) || empty($modelFields) || empty($name)) {
        return;
    }

    foreach ($questions as $item) {
        if (empty($item['question'])) {
            continue;
        }

        $question = model_fields_replacement($item['question'], $modelFields);
        $keywords = "$name nude model";
        $wordCount = 75;
        $guide = "Include information about $name's identity, her professional activities, and why she's popular on Fanvue.";
        $promptBody = get_field('prompt_body', 'options');

        ask_question($modelFields, $question, $promptBody, $keywords, $wordCount, $guide);
    }
}

function ask_question($modelInfo, $question, $promptBody, $keywords, $wordCount, $guide)
{
    if (empty($modelInfo) || empty($question) || empty($promptBody)) {
        return;
    }

    $questionBlank = model_fields_replacement($promptBody, $modelInfo);

    if (empty($questionBlank)) {
        return;
    }

    $fullQuestion = "
$questionBlank

Question: $question (Keywords: $keywords)
Word count: $wordCount
Guide: $guide

Please provide an answer based on the information given and the instructions provided earlier.
";

    try {
        $finalAnswer = '';

        $answer = prompt($fullQuestion);

        if ($answer) {
            $finalAnswer = $answer;
        }

        $textUndetectable = makeTextUndetectable($answer);

        if ($textUndetectable) {
            $finalAnswer = $textUndetectable;
        }

        $finalText = changeTextWithOpenAI($textUndetectable, $question, $keywords);

        if ($finalText) {
            $finalAnswer = $finalText;
        }

        return $finalAnswer;
    } catch (Exception $e) {
        print_r('An error occurred during text generation or processing: ' . $e->getMessage());
    }
}

function model_fields_replacement(string $string = '', array $modelInfo = []): string
{
    if (empty($modelInfo) || !$string) {
        return '';
    }

    return str_replace(
        [
            '[id]',
            '[name]',
            '[platform]',
            '[photos_count]',
            '[videos_count]',
            '[likes_count]',
            '[pricing]',
            '[description]'
        ],
        [
            $modelInfo['fanvue_username'] ?? '',
            $modelInfo['fanvue_name'] ?? '',
            'Fanvue',
            $modelInfo['fanvue_photos_count'] ?? '',
            $modelInfo['fanvue_videos_count'] ?? '',
            $modelInfo['fanvue_likes_count'] ?? '',
            $modelInfo['fanvue_pricing'] ?? '',
            $modelInfo['fanvue_description'] ?? ''
        ],
        $string
    );
}

function prompt(string $question = '')
{
    if (empty($question)) {
        return null;
    }

    $apiKey = get_field('anthropic_api_key', 'options');

    if (empty($apiKey)) {
        return null;
    }

    try {
        $anthropic = new \WpAi\Anthropic\AnthropicAPI($apiKey);

        $messages = [
            [
                'role'    => 'user',
                'content' => $question
            ],
        ];

        $options = [
            'model'     => 'claude-3-5-sonnet-20240620',
            'maxTokens' => 1024,
            'messages'  => $messages
        ];

        $response = $anthropic->messages()->create($options);

        return !empty($response->content) ? $response->content[0]['text'] : null;
    } catch (Exception $e) {
        print_r('Error during text processing in Anthropic API: ' . $e->getMessage());
    }
}

function makeTextUndetectable($text)
{
    if (empty($text)) {
        return null;
    }

    $apiKey = get_field('undetectableai_api_key', 'options');

    if (empty($apiKey)) {
        return null;
    }

    try {
        $response = api_request([
            'method'    => 'POST',
            'curl_url'  => 'https://api.undetectable.ai/submit',
            'headers'   => [
                'Content-Type: application/json',
                "api-key: $apiKey"
            ],
            'data' => [
                'content'    => $text,
                'readability'=> "Journalist",
                'purpose'    => "General Writing",
                'strength'   => "More Human"
            ]
        ]);

        if (empty($response->id)) {
            return null;
        }

        $uniqueTextResponse = null;

        while (empty($uniqueTextResponse)) {
            sleep(2);

            $checkResponse = api_request([
                'method'    => 'POST',
                'curl_url'  => 'https://api.undetectable.ai/document',
                'headers'   => [
                    'Content-Type: application/json',
                    "api-key: 1721729181930x813884018359923200"
                ],
                'data' => [
                    'id'   => $response->id
                ]
            ]);

            if ($checkResponse->status === 'done') {
                $uniqueTextResponse = $checkResponse->output ?? null;
            }
        }

        return $uniqueTextResponse;
    } catch (Exception $e) {
        print_r('Error during text processing in undetectable.ai: ' . $e->getMessage());
    }
}

function changeTextWithOpenAI($text, $question, $keywords)
{
    if (empty($text)) {
        return null;
    }

    $apiKey = get_field('openai_api_key', 'options');

    if (empty($apiKey)) {
        return null;
    }

    $prompt = "
Process the given answer text as follows: 1. Insert the exact keywords only once if they are not already present in the text. 2. Place the keywords as a single, unbroken phrase where most relevant in the text. 3. Do not separate the keywords with commas, spaces, or any other punctuation or words. 4. Do not change or remove any existing content in the text. 5. Replace <Name of model> with the actual model's name. 6. Insert the keywords only once, even if there are multiple relevant places. 7. Return only the modified answer text, without the question or any additional comments.

Question: $question
Keywords: $keywords

Answer text:
$text

Process the text and return only the modified version with keywords inserted as specified.    
";

    try {
        $client = OpenAI::client($apiKey);

        $response = $client->chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ]
        ]);

        return !empty($response['choices']) ? $response['choices'][0]['message']['content'] : null;
    } catch (Exception $e) {
        print_r('Error during text processing in OpenAI: ' . $e->getMessage());
    }
}