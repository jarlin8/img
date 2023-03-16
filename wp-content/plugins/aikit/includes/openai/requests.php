<?php

add_action( 'rest_api_init', function () {
    register_rest_route( 'aikit/openai/v1', '/autocomplete', array(
        'methods' => 'POST',
        'callback' => 'aikit_rest_openai_autocomplete',
        'permission_callback' => function () {
            return is_user_logged_in() && current_user_can( 'edit_posts' );
        }
    ));

	register_rest_route( 'aikit/openai/v1', '/generate-images', array(
		'methods' => 'POST',
		'callback' => 'aikit_rest_openai_generate_images',
		'permission_callback' => function () {
			return is_user_logged_in() && current_user_can( 'edit_posts' );
		}
	));
} );

function aikit_rest_openai_generate_images ($data) {
	$count = $data['count'] ?? 1;
	$size = $data['size'] ?? 'small';
	$text = $data['text'] ?? '';

	if ( empty( $data['text'] ) ) {
		return new WP_Error( 'missing_param', 'Missing text parameter', array( 'status' => 400 ) );
	}

	$client = new \AIKit\Dependencies\GuzzleHttp\Client();
	$model = 'gpt-3.5-turbo-0301';
    $prompt = sprintf("Describe an image that would be best fit for this text:\n\n %s\n\n----\nCreative image description in one sentence of 6 words:\n", $text);
    $maxTokens = min(
        intval((str_word_count($text) + 150) * 1.33),
        aikit_get_max_tokens_for_model( $model )
    );

	try {
		$res = $client->request( 'POST', aikit_get_openai_text_completion_endpoint($model), [

			'body'    => aikit_build_text_generation_request_body($model, $prompt, $maxTokens, 0.7),
			'headers' => [
				'Authorization' => 'Bearer ' . get_option( 'aikit_setting_openai_key' ),
				'Content-Type'  => 'application/json',
			],
		] );

	} catch (\AIKit\Dependencies\GuzzleHttp\Exception\ClientException $e) {
		return new WP_Error( 'openai_error', json_encode([
			'message' => 'error while calling openai',
			'responseBody' => $e->getResponse()->getBody()->getContents(),
		]), array( 'status' => 500 ) );
	} catch (\AIKit\Dependencies\GuzzleHttp\Exception\GuzzleException $e) {
		return new WP_Error( 'openai_error', json_encode([
			'message' => 'error while calling openai',
			'responseBody' => $e->getMessage(),
		]), array( 'status' => 500 ) );
	}

	$body = $res->getBody();
	$json = json_decode($body, true);

	$choices = $json['choices'];

	if ( count( $choices ) == 0 ) {
		return new WP_Error( 'no_choices', 'Could not generate image prompt', array( 'status' => 400 ) );
	}

    if (strpos($model, 'gpt-3.5-turbo') === 0) {
        $imagePrompt = trim($choices[0]['message']['content']);
    } else {
        $imagePrompt = trim($choices[0]['text']);
    }

	$dimensions = '256x256';
	if ($size == 'medium') {
		$dimensions = '512x512';
	} else if ($size == 'large') {
		$dimensions = '1024x1024';
	}

	$stylesArray = get_option( 'aikit_setting_images_styles' );
	$stylesArray = explode("\n", $stylesArray);
	$imagePrompt = rtrim(rtrim($imagePrompt), '.');

	if (!empty($stylesArray)) {
		$style = $stylesArray[array_rand($stylesArray)];
		$imagePrompt .= ', ' . $style;
	}

	$imagePrompt = str_replace('"', '', $imagePrompt);
	$imagePrompt = str_replace("'", '', $imagePrompt);

	try {
		$imageResponse = $client->request( 'POST', 'https://api.openai.com/v1/images/generations', [
			'body'    => json_encode( [
				'prompt'      => $imagePrompt,
				'n'          => intval($count),
				'size' => $dimensions,
				'response_format' => 'url',
			] ),
			'headers' => [
				'Authorization' => 'Bearer ' . get_option( 'aikit_setting_openai_key' ),
				'Content-Type'  => 'application/json',
			],
		] );

	} catch (\AIKit\Dependencies\GuzzleHttp\Exception\ClientException $e) {
		return new WP_Error( 'openai_error', json_encode([
			'message' => 'error while calling openai',
			'responseBody' => $e->getResponse()->getBody()->getContents(),
		]), array( 'status' => 500 ) );
	} catch (\AIKit\Dependencies\GuzzleHttp\Exception\GuzzleException $e) {
		return new WP_Error( 'openai_error', json_encode([
			'message' => 'error while calling openai',
			'responseBody' => $e->getMessage(),
		]), array( 'status' => 500 ) );
	}

	$body = $imageResponse->getBody();
	$json = json_decode($body, true);
	$data = $json['data'] ?? [];

	$images = array();
	foreach ($data as $image) {
		$imageUrl = aikit_upload_file_by_url($image['url'], $dimensions, $imagePrompt);
		if ($imageUrl) {
			$images[] = $imageUrl;
		}
	}

	return new WP_REST_Response([
		'images' => $images,
		'prompt' => $imagePrompt,
	], 200);
}

function aikit_upload_file_by_url( $image_url, $dimensions, $imagePrompt) {

	$imagePromptWithOnlyLetters = preg_replace('/[^A-Za-z0-9\- ]/', '', $imagePrompt);
	$imagePromptWithOnlyLetters = str_replace(' ', '-', $imagePromptWithOnlyLetters);
	$imagePromptWithOnlyLetters = substr($imagePromptWithOnlyLetters, 0, 40);
	$imagePromptWithOnlyLetters = strtolower($imagePromptWithOnlyLetters);

	// it allows us to use download_url() and wp_handle_sideload() functions
	require_once( ABSPATH . 'wp-admin/includes/file.php' );

	// download to temp dir
	$temp_file = download_url( $image_url );

	// add extension to temp file (get it from mime type)
	$mime_type = mime_content_type( $temp_file );
	$extension = explode( '/', $mime_type )[1];

	$newFilename = $imagePromptWithOnlyLetters . '-' . $dimensions . '-' . rand( 0, 99999999 ) . '.' . $extension;

	rename ( $temp_file, $newFilename );
	$temp_file = $newFilename;

	if( is_wp_error( $temp_file ) ) {
		return false;
	}

	// move the temp file into the uploads directory
	$file = array(
		'name'     => basename( $temp_file ),
		'type'     => mime_content_type( $temp_file ),
		'tmp_name' => $temp_file,
		'size'     => filesize( $temp_file ),
	);

	$sideload = wp_handle_sideload(
		$file,
		array(
			'test_form'   => false // no needs to check 'action' parameter
		)
	);

	if( ! empty( $sideload[ 'error' ] ) ) {
		// you may return error message if you want
		return false;
	}

	// it is time to add our uploaded image into WordPress media library
	$attachment_id = wp_insert_attachment(
		array(
			'guid'           => $sideload[ 'url' ],
			'post_mime_type' => $sideload[ 'type' ],
			'post_title'     => basename( $sideload[ 'file' ] ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		),
		$sideload[ 'file' ]
	);

	if( is_wp_error( $attachment_id ) || ! $attachment_id ) {
		return false;
	}

	// update medatata, regenerate image sizes
	require_once( ABSPATH . 'wp-admin/includes/image.php' );

	wp_update_attachment_metadata(
		$attachment_id,
		wp_generate_attachment_metadata( $attachment_id, $sideload[ 'file' ] )
	);

	return [
		'id' => $attachment_id,
		'url' => wp_get_attachment_image_url($attachment_id, explode('x', $dimensions))
	];
}

function aikit_rest_openai_autocomplete( $data ) {
    $type = $data['type'] ?? '';

    $language = $data['language'] ?? 'en';

    return aikit_rest_openai_do_request( $data, $type, $language);
}

function aikit_get_max_tokens_for_model($model) {
    if ($model == 'text-davinci-002' || $model == 'text-davinci-003' || strpos($model, 'gpt-3.5-turbo') === 0) {
        return 4000;
    }

    return 2000;
}

function aikit_rest_openai_get_available_models($api_key) {
    $client = new \AIKit\Dependencies\GuzzleHttp\Client();

    try {
        $res = $client->request('GET', 'https://api.openai.com/v1/models', [
            'headers' => [
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type' => 'application/json',
            ],
        ]);
    } catch (\AIKit\Dependencies\GuzzleHttp\Exception\ClientException $e) {
        return false;
    }

    if ( $res->getStatusCode() !== 200 ) {
        return false;
    }

    $body = json_decode( $res->getBody(), true );

    if ( ! isset( $body['data'] ) ) {
        return false;
    }

    $models = [];
    foreach ( $body['data'] as $model ) {
        $models[] = $model['id'];
    }

    return $models;
}

function aikit_add_selected_text_to_prompt ($prompt, $selected_text) {
    return str_replace('[[text]]', $selected_text, $prompt);
}


function aikit_rest_openai_do_request ( $data, $type, $language ) {

    $prompt_manager = new AIKit_Prompt_Manager();
    $promptsObject = $prompt_manager->get_prompts_by_language($language);

    if ( ! isset( $promptsObject[$type] ) ) {
        return new WP_Error( 'invalid_type', 'Invalid type', array( 'status' => 400 ) );
    }

    if ( ! isset( $data['text'] ) ) {
        return new WP_Error( 'missing_param', 'Missing text parameter', array( 'status' => 400 ) );
    }

    $prompt = $promptsObject[$type]['prompt'];
    $temperature = floatval($promptsObject[$type]['temperature'] ?? 0.7);

    if ($promptsObject[$type]['requiresTextSelection']) {
        $prompt = aikit_add_selected_text_to_prompt($prompt, $data['text']);
    }

    $text = $data['text'];
    $client = new \AIKit\Dependencies\GuzzleHttp\Client();
	$model = get_option('aikit_setting_openai_model');

    $maxTokenMultiplier = intval(1 + intval(get_option( 'aikit_setting_openai_max_tokens_multiplier' ) ?? 0) / 10);

    $promptWordLengthType = $promptsObject[$type]['wordLength']['type'];
    $promptWordLength = $promptsObject[$type]['wordLength']['value'];

    if ($promptWordLengthType == AIKIT_WORD_LENGTH_TYPE_FIXED) {
        $maxTokensToGenerate = intval($promptWordLength * 1.33);
    } else {
        $maxTokensToGenerate = intval(str_word_count($text) * $promptWordLength * 1.33);
    }

    $maxTokensToGenerate *= $maxTokenMultiplier;

	$theoreticalMaxTokensToGenerate = aikit_get_max_tokens_for_model($model) - intval(str_word_count($prompt) * 1.33);

	$actualMaxTokensToGenerate = min($maxTokensToGenerate, $theoreticalMaxTokensToGenerate);

	if ($actualMaxTokensToGenerate < 0) {
		return new WP_Error( 'openai_error', json_encode([
			'message' => 'error while calling openai',
			'responseBody' => "Text is longer than model's context. Please try again with a shorter prompt.",
		]));
	}

    try {

        $res = $client->request('POST', aikit_get_openai_text_completion_endpoint($model), [
            'body' => aikit_build_text_generation_request_body($model, $prompt, $actualMaxTokensToGenerate, $temperature),
            'headers' => [
                'Authorization' => 'Bearer ' . get_option('aikit_setting_openai_key'),
                'Content-Type' => 'application/json',
            ],
        ]);
    } catch (\AIKit\Dependencies\GuzzleHttp\Exception\ClientException $e) {
        return new WP_Error( 'openai_error', json_encode([
            'message' => 'error while calling openai',
            'responseBody' => $e->getResponse()->getBody()->getContents(),
        ]), array( 'status' => 500 ) );
    } catch (\AIKit\Dependencies\GuzzleHttp\Exception\GuzzleException $e) {
        return new WP_Error( 'openai_error', json_encode([
            'message' => 'error while calling openai',
            'responseBody' => $e->getMessage(),
        ]), array( 'status' => 500 ) );
    }

    $body = $res->getBody();
    $json = json_decode($body, true);

    return aikit_parse_text_generation_response($json, $model, $maxTokensToGenerate, $theoreticalMaxTokensToGenerate);
}

function aikit_get_openai_text_completion_endpoint ($model) {
    if (strpos($model, 'gpt-3.5-turbo') === 0) {
        return 'https://api.openai.com/v1/chat/completions';
    }

    return 'https://api.openai.com/v1/completions';
}

function aikit_build_text_generation_request_body ($model, $prompt, $maxTokensToGenerate, $temperature = 0.7) {
    // if model starts with gpt-3.5-turbo then we need to use the new API
    if (strpos($model, 'gpt-3.5-turbo') === 0) {
        return json_encode([
            'model' => $model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ],
            "temperature" => $temperature,
            'max_tokens' => $maxTokensToGenerate,
        ]);
    }

    return json_encode([
        'model' => $model,
        'prompt' => $prompt,
        "temperature" => $temperature,
        'max_tokens' => $maxTokensToGenerate,
    ]);
}

function aikit_parse_text_generation_response ($responseJson, $model, $maxTokensToGenerate, $theoreticalMaxTokensToGenerate) {
    $choices = $responseJson['choices'];

    if ( count( $choices ) == 0 ) {
        return new WP_Error( 'no_choices', 'No completions found, please try again using different text.', array( 'status' => 400 ) );
    }

    if (strpos($model, 'gpt-3.5-turbo') === 0) {
        $resultText = trim($choices[0]['message']['content']);
    } else {
        $resultText = trim($choices[0]['text']);
    }

    return new WP_REST_Response([
        'text' => $resultText,
        'tokens' => min(
            $maxTokensToGenerate,
            $theoreticalMaxTokensToGenerate
        )
    ], 200);
}

function aikit_get_language_used() {
    // get language from the saved settings
    return get_option('aikit_setting_openai_language', 'en');
}
