<?php

require_once(__DIR__ . '/lib/open-ai/vendor/autoload.php');

function faq_answers_generation(int $postId = 0, int $step = 1)
{
    if (!$postId) {
        wp_send_json([
            'error'   => true,
            'message' => 'There is no Post ID'
        ]);

        return false;
    }

    $questions = get_field('faq_questions', 'options');
    $modelFields = prepare_model_fields(get_post_meta($postId));
    $name = $modelFields['fanvue_name'] ?? '';

    if (empty($questions)) {
        wp_send_json([
            'error'   => true,
            'message' => 'There is no prepared questions. Fill out them first here: <a href="'.admin_url('admin.php?page=theme-general-settings').'" target="_blank">Options</a>'
        ]);

        return false;
    }

    if (empty($modelFields) || empty($name)) {
        wp_send_json([
            'error'   => true,
            'message' => 'There is no necessary Fanvue data, check fields'
        ]);

        return false;
    }

    $countIteration = 1;
    $iteration = 1;

    foreach ($questions as $i => $item) {
        if (($i + 1) < $step) {
            continue;
        }

        if ($iteration > $countIteration) {
            break;
        }

        if (empty($item['question'])) {
            continue;
        }

        $question = model_fields_replacement($item['question'], $modelFields);
        $keywords = "$name nude model";
        $wordCount = 75;
        $guide = "Include information about $name's identity, her professional activities, and why she's popular on Fanvue.";
        $promptBody = get_field('prompt_body', 'options');

        $answer = ask_question($modelFields, $question, $promptBody, $keywords, $wordCount, $guide);

        if ($answer) {
            faq_update($postId, $question, $answer);
        }

        $iteration++;
        $step += 1;
    }

    return $step > count($questions) ? 'finish' : $step;
}

function ask_question($modelInfo, $question, $promptBody, $keywords, $wordCount, $guide)
{
    if (empty($modelInfo) || empty($question) || empty($promptBody)) {
        return '';
    }

    $questionBlank = model_fields_replacement($promptBody, $modelInfo);

    if (empty($questionBlank)) {
        return '';
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

        $answer = generateAnswer($fullQuestion, $questionBlank);

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
        wp_send_json([
            'error'       => true,
            'message'     => __('Something went wrong, try again', DOMAIN),
            'message_dev' => 'An error occurred during text generation or processing: ' . $e->getMessage()
        ]);

        return false;
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

function generateAnswer($fullQuestion, $questionBlank)
{
    if (empty($fullQuestion) || empty($questionBlank)) {
        return null;
    }

    $apiKey = get_field('openai_api_key', 'options');

    if (empty($apiKey)) {
        return null;
    }

    try {
        $client = OpenAI::client($apiKey);

        $response = $client->chat()->create([
            'model' => 'gpt-4o',
            'messages' => [
                ['role' => 'system', 'content' => $questionBlank],
                ['role' => 'user', 'content' => $fullQuestion]
            ],
            'max_tokens' => 1024
        ]);

        return !empty($response['choices']) ? $response['choices'][0]['message']['content'] : null;
    } catch (Exception $e) {
        wp_send_json([
            'error'       => true,
            'message'     => __('Something went wrong, try again', DOMAIN),
            'message_dev' => 'Error during the First text processing in OpenAI: ' . $e->getMessage()
        ]);

        return false;
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
        wp_send_json([
            'error'       => true,
            'message'     => __('Something went wrong, try again', DOMAIN),
            'message_dev' => 'Error during text processing in undetectable.ai: ' . $e->getMessage()
        ]);

        return false;
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
            'model' => 'gpt-4o',
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
            'max_tokens' => 1024
        ]);

        return !empty($response['choices']) ? $response['choices'][0]['message']['content'] : null;
    } catch (Exception $e) {
        wp_send_json([
            'error'       => true,
            'message'     => __('Something went wrong, try again', DOMAIN),
            'message_dev' => 'Error during the Last text processing in OpenAI: ' . $e->getMessage()
        ]);

        return false;
    }
}

function description_update($postId = 0): bool
{
    if (!$postId) {
        return false;
    }

    $modelFields = prepare_model_fields(get_post_meta($postId));
    $name = $modelFields['fanvue_name'] ?? '';

    if (empty($modelFields) || empty($name)) {
        wp_send_json([
            'error'   => true,
            'message' => 'There is no necessary Fanvue data, check fields'
        ]);

        return false;
    }

    $question = 'Describe me ' . $name;
    $keywords = "$name nude model";
    $wordCount = 75;
    $guide = "Include information about $name's identity, her professional activities, and why she's popular on Fanvue.";
    $promptBody = get_field('prompt_body', 'options');
    $answer = ask_question($modelFields, $question, $promptBody, $keywords, $wordCount, $guide);

    if (!$answer) {
        return false;
    }

    update_field('fanvue_description', $answer, $postId);
    update_field('fanvue_description_updated', $answer, $postId);

    return true;
}

function faq_update($postId = 0, $question = '', $answer = '')
{
    if (!$postId || !$question || !$answer) {
        return;
    }

    $faq = get_field('faq', $postId);
    $updatedFaq = !empty($faq) ? $faq : [];

    if (!empty($faq)) {
        $faqTitles = array_map('trim', array_column($faq, 'title'));

        /* If question exists rewrite answer or add new line */
        if (in_array(trim($question), $faqTitles)) {
            $faqTitlesIndex = array_search($question, $faqTitles);

            $updatedFaq[$faqTitlesIndex] = [
                'title' => $question,
                'text'  => $answer
            ];
        } else {
            $updatedFaq[] = [
                'title' => $question,
                'text'  => $answer
            ];
        }
    } else {
        $updatedFaq[] = [
            'title' => $question,
            'text'  => $answer
        ];
    }

    update_field('faq', $updatedFaq, $postId);
}