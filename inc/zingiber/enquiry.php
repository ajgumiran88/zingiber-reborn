<?php
/** Native enquiry handling for the shared Zingiber contact panel. */

function zingiber_submit_enquiry(array $post): array
{
    $values = [];
    foreach (['name', 'email', 'message'] as $field) {
        $value = $post['enquiry_' . $field] ?? '';
        $values[$field] = is_string($value) ? trim(wp_unslash($value)) : '';
    }
    $errors = [];
    $nonce = $post['zingiber_nonce'] ?? '';
    if (!is_string($nonce) || !wp_verify_nonce($nonce, 'zingiber_enquiry')) {
        $errors['form'] = __('Please refresh this page and try again. Your enquiry has not been sent.', 'vonaco');
    }
    if ($values['name'] === '' || strlen($values['name']) > 200) {
        $errors['name'] = __('Enter your name (up to 200 characters).', 'vonaco');
    }
    if (strlen($values['email']) > 254 || !is_email($values['email']) || preg_match('/[\r\n]/', $values['email'])) {
        $errors['email'] = __('Enter a valid email address.', 'vonaco');
    }
    if ($values['message'] === '' || strlen($values['message']) > 5000) {
        $errors['message'] = __('Tell us about your enquiry (up to 5,000 characters).', 'vonaco');
    }
    if (!empty($post['enquiry_website'])) {
        $errors['form'] = __('Your enquiry could not be sent. Please contact us by email.', 'vonaco');
    }
    if ($errors) {
        return ['values' => $values, 'errors' => $errors];
    }

    // Use the connection address, never a client-supplied forwarding header.
    $key = 'zingiber_enquiry_' . wp_hash($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    if (get_transient($key)) {
        return ['values' => $values, 'errors' => ['form' => __('Please wait one minute before trying again.', 'vonaco')]];
    }
    set_transient($key, 1, MINUTE_IN_SECONDS);
    $contact = zingiber_get_site_content()['contact']['details'];
    $sent = wp_mail(
        $contact['email'],
        __('Zingiber website enquiry', 'vonaco'),
        "Name: " . sanitize_text_field($values['name']) . "\nEmail: " . $values['email'] . "\n\n" . sanitize_textarea_field($values['message']),
        ['Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . $values['email']]
    );
    if (!$sent) {
        $errors['form'] = __('We could not send your enquiry. Your details are still below. Please try again in a minute or email us directly.', 'vonaco');
    }
    return ['values' => $values, 'errors' => $errors, 'sent' => $sent];
}

function zingiber_handle_enquiry(): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST' || ($_POST['zingiber_action'] ?? '') !== 'enquiry') {
        return;
    }
    nocache_headers();
    $GLOBALS['zingiber_enquiry'] = zingiber_submit_enquiry($_POST);
    if (!empty($GLOBALS['zingiber_enquiry']['sent'])) {
        wp_safe_redirect(home_url('/contact/?enquiry=sent#zingiber-enquiry'), 303);
        exit;
    }
}
add_action('template_redirect', 'zingiber_handle_enquiry');
