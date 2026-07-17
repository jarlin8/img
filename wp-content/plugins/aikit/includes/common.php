<?php

function aikit_image_generation_request($prompt, $dimensions='1024x1024') {
    ###['image-generation-request']

    $default_api = get_option('aikit_setting_default_image_generation_api');

    if ($default_api == 'openai') {
        return aikit_openai_image_generation_request($prompt, $dimensions);
    } else if ($default_api == 'stability-ai') {
        $size = 'medium';
        if ($dimensions == '1024x1024') {
            $size = 'large';
        }

        return aikit_stability_ai_image_generation_request($prompt, $size);
    } else {
        return new WP_Error( 'image_generation_error', json_encode([
            'message' => 'no default image generation api set',
        ]), array( 'status' => 500 ) );
    }
}
