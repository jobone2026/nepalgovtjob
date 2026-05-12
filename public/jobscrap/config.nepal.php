<?php
/**
 * Nepal-specific configuration for LokSewaAlert Job Scraper
 */

return [
    // API Configuration
    'JOBONE_API' => 'http://13.206.244.237/api',
    'JOBONE_TOKEN' => 'loksewa_sk_live_8f7c9e2d4a1b6f3c5e9a2b7d4f1c8e3a',
    'JOBONE_SITE_URL' => 'http://13.206.244.237',
    'JOBONE_SITE_NAME' => 'LokSewaAlert',
    
    // AI Configuration (if using OpenAI)
    'AI_MODEL' => 'gpt-4o-mini',
    'AI_API_URL' => 'https://api.openai.com/v1/chat/completions',
    'AI_API_KEY' => '', // Add your OpenAI key if needed
    
    // Image Generation
    'IMAGE_MODEL' => 'gpt-image-1',
    'IMAGE_API_URL' => 'https://api.openai.com/v1/images/generations',
    
    // Social Media Channels
    'TG_CHANNEL' => 'https://t.me/loksewaalert',
    'WA_CHANNEL' => 'https://whatsapp.com/channel/0029VbAn',
    
    // IndexNow Configuration
    'INDEXNOW_KEY' => 'YOUR_32CHAR_GUID_KEY_HERE',
    'INDEXNOW_HOST' => '13.206.244.237',
    
    // PDF Storage
    'PDF_STORAGE_DIR' => '/var/www/loksewaalert/public/pdfs/',
    'PDF_STORAGE_URL' => 'http://13.206.244.237/pdfs/',
    
    // Nepal-specific settings
    'DEFAULT_COUNTRY' => 'Nepal',
    'DEFAULT_STATE' => 'All Nepal',
    'TIMEZONE' => 'Asia/Kathmandu',
    
    // Nepal Provinces mapping
    'NEPAL_PROVINCES' => [
        'koshi' => 'Koshi Province',
        'madhesh' => 'Madhesh Province',
        'bagmati' => 'Bagmati Province',
        'gandaki' => 'Gandaki Province',
        'lumbini' => 'Lumbini Province',
        'karnali' => 'Karnali Province',
        'sudurpashchim' => 'Sudurpashchim Province',
        'all' => 'All Nepal',
    ],
    
    // Nepal Categories mapping
    'NEPAL_CATEGORIES' => [
        'lok sewa' => 'Lok Sewa Aayog',
        'psc' => 'Lok Sewa Aayog',
        'police' => 'Nepal Police',
        'army' => 'Nepal Army',
        'teaching' => 'Teaching Service',
        'health' => 'Health Service',
        'engineering' => 'Engineering Service',
        'bank' => 'Banking Jobs',
        'local level' => 'Local Level',
        'admit card' => 'Admit Card',
        'result' => 'Results',
        'answer key' => 'Answer Key',
        'syllabus' => 'Syllabus',
    ],
];
