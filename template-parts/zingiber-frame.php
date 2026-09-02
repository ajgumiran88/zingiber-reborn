<?php
/**
 * Zingiber Architectural Fluted & Botanical Decorative Frame
 *
 * Implements an ultra-refined, luxury architectural framing system inspired by
 * Zingiber's fluted terracotta interior, warm brushed copper inlays, and botanical artisanry.
 *
 * @package Vonaco
 */
?>
<div class="zingiber-frame" aria-hidden="true">
    <!-- Outer Fluted Strata Border Lines -->
    <div class="zingiber-frame__rail zingiber-frame__rail--top"></div>
    <div class="zingiber-frame__rail zingiber-frame__rail--bottom"></div>
    <div class="zingiber-frame__rail zingiber-frame__rail--left"></div>
    <div class="zingiber-frame__rail zingiber-frame__rail--right"></div>

    <!-- Whisper-Thin Architectural Jewel Corners -->
    <div class="zingiber-frame__corner zingiber-frame__corner--tl">
        <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="zingiber-frame__corner-svg">
            <path d="M0 0 H48 M0 0 V48" stroke="#B87333" stroke-width="0.8" opacity="0.65" />
            <path d="M4 4 H32 M4 4 V32" stroke="#9C4722" stroke-width="0.5" opacity="0.4" />
            <!-- Architectural Jewel Stud -->
            <circle cx="4" cy="4" r="1.5" fill="#B87333" />
            <circle cx="4" cy="4" r="0.6" fill="#EDE7E1" />
            <!-- Delicate Botanical Tendril -->
            <path d="M8 8 C16 9, 22 15, 24 24" stroke="#B87333" stroke-width="0.6" stroke-linecap="round" opacity="0.5" />
        </svg>
    </div>

    <div class="zingiber-frame__corner zingiber-frame__corner--tr">
        <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="zingiber-frame__corner-svg">
            <path d="M48 0 H0 M48 0 V48" stroke="#B87333" stroke-width="0.8" opacity="0.65" />
            <path d="M44 4 H16 M44 4 V32" stroke="#9C4722" stroke-width="0.5" opacity="0.4" />
            <!-- Architectural Jewel Stud -->
            <circle cx="44" cy="4" r="1.5" fill="#B87333" />
            <circle cx="44" cy="4" r="0.6" fill="#EDE7E1" />
            <!-- Delicate Botanical Tendril -->
            <path d="M40 8 C32 9, 26 15, 24 24" stroke="#B87333" stroke-width="0.6" stroke-linecap="round" opacity="0.5" />
        </svg>
    </div>

    <div class="zingiber-frame__corner zingiber-frame__corner--bl">
        <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="zingiber-frame__corner-svg">
            <path d="M0 48 H48 M0 48 V0" stroke="#B87333" stroke-width="0.8" opacity="0.65" />
            <path d="M4 44 H32 M4 44 V16" stroke="#9C4722" stroke-width="0.5" opacity="0.4" />
            <!-- Architectural Jewel Stud -->
            <circle cx="4" cy="44" r="1.5" fill="#B87333" />
            <circle cx="4" cy="44" r="0.6" fill="#EDE7E1" />
            <!-- Delicate Botanical Tendril -->
            <path d="M8 40 C16 39, 22 33, 24 24" stroke="#B87333" stroke-width="0.6" stroke-linecap="round" opacity="0.5" />
        </svg>
    </div>

    <div class="zingiber-frame__corner zingiber-frame__corner--br">
        <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="zingiber-frame__corner-svg">
            <path d="M48 48 H0 M48 48 V0" stroke="#B87333" stroke-width="0.8" opacity="0.65" />
            <path d="M44 44 H16 M44 44 V16" stroke="#9C4722" stroke-width="0.5" opacity="0.4" />
            <!-- Architectural Jewel Stud -->
            <circle cx="44" cy="44" r="1.5" fill="#B87333" />
            <circle cx="44" cy="44" r="0.6" fill="#EDE7E1" />
            <!-- Delicate Botanical Tendril -->
            <path d="M40 40 C32 39, 26 33, 24 24" stroke="#B87333" stroke-width="0.6" stroke-linecap="round" opacity="0.5" />
        </svg>
    </div>

    <!-- Editorial Coordinate Marker at Bottom Center -->
    <div class="zingiber-frame__marker">
        <span class="zingiber-frame__marker-line" aria-hidden="true"></span>
        <span class="zingiber-frame__marker-text"><?php esc_html_e('25°04′N 55°08′E · ZINGIBER DUBAI', 'vonaco'); ?></span>
        <span class="zingiber-frame__marker-line" aria-hidden="true"></span>
    </div>
</div>
