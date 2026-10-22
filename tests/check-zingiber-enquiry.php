<?php
// Run: php tests/check-zingiber-enquiry.php /path/to/local/wordpress/wp-load.php
require $argv[1];

$check = static function ($condition, string $message): void {
    if (!$condition) {
        throw new RuntimeException($message);
    }
};
$check(function_exists('zingiber_submit_enquiry'), 'Missing enquiry submission handler.');

$_SERVER['REMOTE_ADDR'] = '192.0.2.82';
$key = 'zingiber_enquiry_' . wp_hash($_SERVER['REMOTE_ADDR']);
$mail = [];
$mailResult = true;
add_filter('pre_wp_mail', static function ($return, $attributes) use (&$mail, &$mailResult) {
    $mail[] = $attributes;
    return $mailResult; // Never deliver test messages.
}, 10, 2);
$valid = [
    'zingiber_nonce' => wp_create_nonce('zingiber_enquiry'),
    'enquiry_name' => 'Test Guest',
    'enquiry_email' => 'guest@example.test',
    'enquiry_message' => 'A table for four, please.',
    'enquiry_website' => '',
];

try {
    delete_transient($key);
    $result = zingiber_submit_enquiry(array_replace($valid, ['zingiber_nonce' => 'invalid']));
    $check(!empty($result['errors']['form']) && !$mail, 'Invalid nonce must not send mail.');
    foreach ([
        ['enquiry_name', '', 'name'],
        ['enquiry_email', "guest@example.test\r\nBcc: other@example.test", 'email'],
        ['enquiry_email', ['guest@example.test'], 'email'],
        ['enquiry_message', '', 'message'],
        ['enquiry_message', str_repeat('x', 5001), 'message'],
    ] as [$field, $value, $error]) {
        $result = zingiber_submit_enquiry(array_replace($valid, [$field => $value]));
        $check(isset($result['errors'][$error]) && !$mail, 'Invalid ' . $field . ' must not send mail.');
    }
    $result = zingiber_submit_enquiry(array_replace($valid, ['enquiry_website' => 'spam']));
    $check(!empty($result['errors']) && !$mail, 'Honeypot must reject automated submissions.');

    $result = zingiber_submit_enquiry($valid);
    $check(!empty($result['sent']) && count($mail) === 1, 'Valid enquiry must submit once.');
    $check($mail[0]['to'] === 'reservations@zingiber.ae', 'Recipient must be the approved restaurant address.');
    $check(in_array('Reply-To: guest@example.test', $mail[0]['headers'], true), 'Reply-To must use the validated guest email.');
    $check(strpos($mail[0]['message'], 'A table for four, please.') !== false, 'Message must retain the enquiry.');
    $result = zingiber_submit_enquiry($valid);
    $check(!empty($result['errors']['form']) && count($mail) === 1, 'Repeated submission must be rate limited.');

    delete_transient($key);
    $mailResult = false;
    $result = zingiber_submit_enquiry($valid);
    $check(empty($result['sent']) && !empty($result['errors']['form']), 'Mail failure must not claim success.');
    $check($result['values']['message'] === 'A table for four, please.', 'Mail failure must retain the draft.');
    echo "PASS: enquiry validation, recipient, rate limit, failure and draft retention; mail intercepted.\n";
} finally {
    delete_transient($key);
}
