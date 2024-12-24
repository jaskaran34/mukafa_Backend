<?php

/**
 * Configuration settings for AI prompts.
 *
 * This file includes the available prompts along with their associated icons 
 * and prompt templates. Additionally, it provides default settings for 
 * max tokens and temperature.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | System Prompt
    |--------------------------------------------------------------------------
    |
    | The system prompt that provides context to the AI assistant.
    |
    */

    'system' => "You are a helpful assistant for a customer loyalty solution for digital savings cards.",

    /*
    |--------------------------------------------------------------------------
    | Prompts
    |--------------------------------------------------------------------------
    |
    | Configured AI prompts. Each prompt includes an associated action, icon, 
    | and a template string that specifies how the prompt should be constructed.
    |
    */

    'prompts' => [
        'complete' => [
            'icon' => 'sparkles',
            'template' => "Generate a complete response in :locale based on: :user_input. Output only the completed text and add a maximum of 15 words, without any introductory phrases, quotation marks, or additional comments."
        ],
        'shorten' => [
            'icon' => 'truncate',
            'template' => "Shorten this text in :locale: :user_input. Retain core meaning, make it concise. Output only the shortened text, without any quotation marks or additional characters."
        ],
        'extend' => [
            'icon' => 'ellipsis-horizontal',
            'template' => "Extend this text slightly in :locale: :user_input. Retain the core meaning, but add only a maximum of 5 words additional detail. Keep the extension very brief. Output only the extended text, without any introductory phrases or additional comments."
        ],
        'rephrase' => [
            'icon' => 'arrow-path-rounded-square',
            'template' => "Rephrase this text in :locale: :user_input. Maintain meaning, use different words. Output only the rephrased text."
        ],
        'simplify' => [
            'icon' => 'baby',
            'template' => "Simplify this text in :locale: :user_input. Retain the original meaning but use simpler language. Output only the simplified text."
        ],
        'spelling_grammar' => [
            'icon' => 'spelling',
            'template' => "Correct spelling and grammar in :locale: :user_input. Output only the corrected text."
        ],
        'divider' => [
            'icon' => 'divider'
        ],
        'tone_of_voice' => [
            'hasSub' => true,
            'icon' => 'microphone',
            'templates' => [
                'academic' => [
                    'template' => "Change the tone of this text to Academic in :locale: :user_input. Output only the academic text, without any introductory phrases, quotation marks, or additional comments."
                ],
                'confident' => [
                    'template' => "Change the tone of this text to Confident in :locale: :user_input. Output only the confident text, without any introductory phrases, quotation marks, or additional comments."
                ],
                'excited' => [
                    'template' => "Change the tone of this text to Excited in :locale: :user_input. Output only the excited text, without any introductory phrases, quotation marks, or additional comments."
                ],
                'formal' => [
                    'template' => "Change the tone of this text to Formal in :locale: :user_input. Output only the formal text, without any introductory phrases, quotation marks, or additional comments."
                ],
                'friendly' => [
                    'template' => "Change the tone of this text to Friendly in :locale: :user_input. Output only the friendly text, without any introductory phrases, quotation marks, or additional comments."
                ],
                'funny' => [
                    'template' => "Change the tone of this text to Funny in :locale: :user_input. Output only the funny text, without any introductory phrases, quotation marks, or additional comments."
                ]
            ]
        ],
        'translate' => [
            'hasSub' => true,
            'icon' => 'language',
            'template' => "Translate this text to :translate_to_locale: :user_input. Output only the translated text."
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Max Tokens
    |--------------------------------------------------------------------------
    |
    | The maximum number of tokens to generate in the completion. Must be 
    | between 1 and 2048.
    |
    */
    'max_tokens' => 100,

    /*
    |--------------------------------------------------------------------------
    | Temperature
    |--------------------------------------------------------------------------
    |
    | What sampling temperature to use, between 0 and 1. Higher values make 
    | the output more random, while lower values make it more focused and 
    | deterministic.
    |
    */
    'temperature' => 0.9
];
