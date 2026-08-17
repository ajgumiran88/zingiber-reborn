<?php
/**
 * $Desc
 *
 * @version    $Id$
 * @package    wpbase
 * @author     Opal  Team <opalwordpress@gmail.com>
 * @copyright  Copyright (C) 2017 wpopal.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @website  http://www.wpopal.com
 * @support  http://www.wpopal.com/questions/
 */
/**
 * Enable/distable share box
 */

$heading = apply_filters('vonaco_social_heading', esc_html__('Share:', 'vonaco'));

if (vonaco_get_theme_option('social_share')) {
    ?>
    <div class="vonaco-social-share">
        <?php echo '<span class="social-share-header">' . esc_html($heading) . '</span>'; ?>
        <?php if (vonaco_get_theme_option('social_share_facebook')): ?>
            <a class="social-facebook"
               href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>&display=page"
               target="_blank" title="<?php esc_html_e('Share on facebook', 'vonaco'); ?>">
                <i class="vonaco-icon-facebook-f"></i>
                <span><?php esc_html_e('Facebook', 'vonaco'); ?></span>
            </a>
        <?php endif; ?>

        <?php if (vonaco_get_theme_option('social_share_twitter')): ?>
            <a class="social-twitter"
               href="http://twitter.com/home?status=<?php esc_url(get_the_title()); ?> <?php the_permalink(); ?>" target="_blank"
               title="<?php esc_html_e('Share on Twitter', 'vonaco'); ?>">
                <i class="vonaco-icon-twitter"></i>
                <span><?php esc_html_e('Twitter', 'vonaco'); ?></span>
            </a>
        <?php endif; ?>

        <?php if (vonaco_get_theme_option('social_share_linkedin')): ?>
            <a class="social-linkedin"
               href="http://linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>"
               target="_blank" title="<?php esc_html_e('Share on LinkedIn', 'vonaco'); ?>">
                <i class="vonaco-icon-linkedin-in"></i>
                <span><?php esc_html_e('Linkedin', 'vonaco'); ?></span>
            </a>
        <?php endif; ?>

        <?php if (vonaco_get_theme_option('social_share_google-plus')): ?>
            <a class="social-google" href="https://plus.google.com/share?url=<?php the_permalink(); ?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" target="_blank"
               title="<?php esc_html_e('Share on Google plus', 'vonaco'); ?>">
                <i class="vonaco-icon-google-plus-g"></i>
                <span><?php esc_html_e('Google+', 'vonaco'); ?></span>
            </a>
        <?php endif; ?>

        <?php if (vonaco_get_theme_option('social_share_pinterest')): ?>
            <a class="social-pinterest"
               href="http://pinterest.com/pin/create/button/?url=<?php echo esc_url(urlencode(get_permalink())); ?>&amp;description=<?php echo esc_url(urlencode(get_the_title())); ?>&amp;; ?>"
               target="_blank" title="<?php esc_html_e('Share on Pinterest', 'vonaco'); ?>">
                <i class="vonaco-icon-pinterest-p"></i>
                <span><?php esc_html_e('Pinterest', 'vonaco'); ?></span>
            </a>
        <?php endif; ?>

        <?php if (vonaco_get_theme_option('social_share_email')): ?>
            <a class="social-envelope" href="mailto:?subject=<?php the_title(); ?>&amp;body=<?php the_permalink(); ?>"
               title="<?php esc_html_e('Email to a Friend', 'vonaco'); ?>">
                <i class="vonaco-icon-envelope"></i>
                <span><?php esc_html_e('Email', 'vonaco'); ?></span>
            </a>
        <?php endif; ?>
    </div>
    <?php
}
?>
