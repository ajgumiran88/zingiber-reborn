<?php
$siteContent = zingiber_get_site_content();
$contact = $siteContent['contact']['details'];
$state = $GLOBALS['zingiber_enquiry'] ?? [];
$values = $state['values'] ?? ['name' => '', 'email' => '', 'message' => ''];
$errors = $state['errors'] ?? [];
$sent = !$errors && ($_GET['enquiry'] ?? '') === 'sent';
?>
<section class="zingiber-enquiry" id="zingiber-enquiry" aria-labelledby="zingiber-enquiry-title">
    <div class="zingiber-container zingiber-enquiry__grid">
        <div class="zingiber-enquiry__art" data-zingiber-reveal aria-hidden="true">
            <img class="zingiber-enquiry__roots" src="<?php echo esc_url(zingiber_theme_asset_url('assets/images/zingiber/ginger-cluster.png')); ?>" alt="" width="1536" height="1024" loading="lazy">
            <?php zingiber_theme_image(['src' => 'assets/images/zingiber/food-dessert.jpg', 'alt' => ''], ['width' => 1024, 'height' => 1536, 'class' => 'zingiber-enquiry__photo', 'sizes' => '(min-width: 768px) 40vw, 90vw', 'aria_hidden' => true]); ?>
        </div>
        <div class="zingiber-enquiry__content">
            <p class="zingiber-eyebrow"><?php esc_html_e('Contact & Reservations', 'vonaco'); ?></p>
            <h2 id="zingiber-enquiry-title" data-zingiber-reveal><?php esc_html_e('Want to chat?', 'vonaco'); ?><br><?php esc_html_e('Get in touch.', 'vonaco'); ?></h2>
            <?php if (zingiber_current_page_content()['slug'] === 'contact') : ?>
                <p class="zingiber-enquiry__intro"><?php echo esc_html($siteContent['contact']['sections'][0]['body'][0]); ?></p>
            <?php endif; ?>
            <?php if ($sent || $errors) : ?>
                <div id="enquiry-feedback" class="zingiber-enquiry__feedback" role="<?php echo $errors ? 'alert' : 'status'; ?>" tabindex="-1" autofocus>
                    <?php if ($sent) : ?>
                        <p><?php esc_html_e('Thank you. Your enquiry has been submitted. Our team will reply by email. Reservations are confirmed only by our team.', 'vonaco'); ?></p>
                    <?php else : ?>
                        <p><?php echo esc_html($errors['form'] ?? __('Please check the highlighted fields. Your enquiry has not been sent.', 'vonaco')); ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <form method="post" action="<?php echo esc_url(home_url('/contact/#zingiber-enquiry')); ?>" class="zingiber-enquiry__form">
                <input type="hidden" name="zingiber_action" value="enquiry">
                <?php wp_nonce_field('zingiber_enquiry', 'zingiber_nonce', false); ?>
                <div class="zingiber-enquiry__fields">
                    <?php foreach (['name' => __('Name', 'vonaco'), 'email' => __('Email', 'vonaco'), 'message' => __('Comments / Event details', 'vonaco')] as $field => $label) : ?>
                        <div class="zingiber-enquiry__field zingiber-enquiry__field--<?php echo esc_attr($field); ?>">
                            <label for="enquiry-<?php echo esc_attr($field); ?>"><?php echo esc_html($label); ?> <span aria-hidden="true">*</span></label>
                            <?php if ($field === 'message') : ?>
                                <textarea id="enquiry-message" name="enquiry_message" rows="3" maxlength="5000" required<?php if (isset($errors[$field])) echo ' aria-invalid="true" aria-describedby="enquiry-message-error"'; ?>><?php echo esc_textarea($values[$field]); ?></textarea>
                            <?php else : ?>
                                <input id="enquiry-<?php echo esc_attr($field); ?>" name="enquiry_<?php echo esc_attr($field); ?>" type="<?php echo $field === 'email' ? 'email' : 'text'; ?>" autocomplete="<?php echo esc_attr($field); ?>" maxlength="<?php echo $field === 'email' ? '254' : '200'; ?>" value="<?php echo esc_attr($values[$field]); ?>" required<?php if (isset($errors[$field])) echo ' aria-invalid="true" aria-describedby="enquiry-' . esc_attr($field) . '-error"'; ?>>
                            <?php endif; ?>
                            <?php if (isset($errors[$field])) : ?><p class="zingiber-enquiry__error" id="enquiry-<?php echo esc_attr($field); ?>-error"><?php echo esc_html($errors[$field]); ?></p><?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div hidden><label for="enquiry-website">Leave this field empty</label><input id="enquiry-website" name="enquiry_website" type="text" tabindex="-1" autocomplete="off"></div>
                <div class="zingiber-enquiry__submit-row" data-zingiber-reveal>
                    <button class="zingiber-enquiry__submit" type="submit"><?php esc_html_e('Submit', 'vonaco'); ?><span aria-hidden="true">↗</span></button>
                    <p><?php esc_html_e('Prefer email?', 'vonaco'); ?><br><a href="mailto:<?php echo esc_attr($contact['email']); ?>"><?php echo esc_html($contact['email']); ?></a></p>
                </div>
            </form>
        </div>
    </div>
</section>
