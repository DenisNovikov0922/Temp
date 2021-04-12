<?php 
  if ( ! defined( 'ABSPATH' ) ) {
    exit;
  } // Exit if accessed directly
?>

<li class="menu-item-on menu-item-privacy_overview menu-item-selected">
  <button data-href="#privacy_overview" class="moove-gdpr-tab-nav" aria-label="<?php echo esc_attr( $content->overview->nav_label ); ?>">
    <span class="gdpr-svg-icon">
      <svg class="icon icon-privacy-overview" viewBox="0 0 26 32">
        <path d="M11.082 27.443l1.536 0.666 1.715-0.717c5.018-2.099 8.294-7.014 8.294-12.442v-5.734l-9.958-5.325-9.702 5.325v5.862c0 5.376 3.2 10.24 8.115 12.365zM4.502 10.138l8.166-4.506 8.397 4.506v4.813c0 4.838-2.893 9.19-7.347 11.034l-1.101 0.461-0.922-0.41c-4.352-1.894-7.194-6.195-7.194-10.957v-4.941zM12.029 14.259h1.536v7.347h-1.536v-7.347zM12.029 10.394h1.536v2.483h-1.536v-2.483z" fill="currentColor"></path>
      </svg>      
    </span> 
    <span class="gdpr-nav-tab-title"><?php echo esc_attr( $content->overview->nav_label ); ?></span>
  </button>
</li>

<?php if ( $content->strictly->show ) : ?>
  <li class="menu-item-strict-necessary-cookies menu-item-off">
    <button data-href="#strict-necessary-cookies" class="moove-gdpr-tab-nav" aria-label="<?php echo esc_attr( $content->strictly->nav_label ); ?>">
      <span class="gdpr-svg-icon">
        <svg class="icon icon-strict-necessary" viewBox="0 0 26 32">
          <path d="M22.685 5.478l-9.984 10.752-2.97-4.070c-0.333-0.461-0.973-0.538-1.434-0.205-0.435 0.333-0.538 0.947-0.23 1.408l3.686 5.094c0.179 0.256 0.461 0.41 0.768 0.435h0.051c0.282 0 0.538-0.102 0.742-0.307l10.854-11.699c0.358-0.435 0.333-1.075-0.102-1.434-0.384-0.384-0.998-0.358-1.382 0.026v0zM22.301 12.954c-0.563 0.102-0.922 0.64-0.794 1.203 0.128 0.614 0.179 1.229 0.179 1.843 0 5.094-4.122 9.216-9.216 9.216s-9.216-4.122-9.216-9.216 4.122-9.216 9.216-9.216c1.536 0 3.021 0.384 4.378 1.101 0.512 0.23 1.126 0 1.357-0.538 0.205-0.461 0.051-0.998-0.384-1.254-5.478-2.944-12.314-0.922-15.283 4.557s-0.922 12.314 4.557 15.258 12.314 0.922 15.258-4.557c0.896-1.638 1.357-3.482 1.357-5.35 0-0.768-0.077-1.51-0.23-2.253-0.102-0.538-0.64-0.896-1.178-0.794z" fill="currentColor"></path>
        </svg>
      </span> 
      <span class="gdpr-nav-tab-title"><?php echo esc_attr( $content->strictly->nav_label ); ?></span>
    </button>
  </li>
<?php endif; ?>


<?php if ( $content->third_party->show ) : ?>
  <li class="menu-item-off menu-item-third_party_cookies">
    <button data-href="#third_party_cookies" class="moove-gdpr-tab-nav" aria-label="<?php echo esc_attr( $content->third_party->nav_label ); ?>">
      <span class="gdpr-svg-icon">
        <svg class="icon icon-3rd-party" viewBox="0 0 26 32">
          <path d="M25.367 3.231c-0.020 0-0.040 0-0.060 0.020l-4.98 1.080c-0.16 0.040-0.2 0.16-0.080 0.28l1.42 1.42-10.060 10.040 1.14 1.14 10.060-10.060 1.42 1.42c0.12 0.12 0.24 0.080 0.28-0.080l1.060-5.020c0-0.14-0.080-0.26-0.2-0.24zM1.427 6.371c-0.74 0-1.4 0.66-1.4 1.4v19.6c0 0.74 0.66 1.4 1.4 1.4h19.6c0.74 0 1.4-0.66 1.4-1.4v-14.6h-1.6v14.4h-19.2v-19.2h14.38v-1.6h-14.58z" fill="currentColor"></path>
        </svg>        
      </span> 
      <span class="gdpr-nav-tab-title"><?php echo esc_attr( $content->third_party->nav_label ); ?></span>
    </button>
  </li>
<?php endif; ?>

<?php if ( $content->advanced->show ) : ?>
  <li class="menu-item-advanced-cookies menu-item-off">
    <button data-href="#advanced-cookies" class="moove-gdpr-tab-nav" aria-label="<?php echo esc_attr( $content->advanced->nav_label ); ?>">
      <span class="gdpr-svg-icon">
        <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
          <g data-name="1">
            <path d="M293.9,450H233.53a15,15,0,0,1-14.92-13.42l-4.47-42.09a152.77,152.77,0,0,1-18.25-7.56L163,413.53a15,15,0,0,1-20-1.06l-42.69-42.69a15,15,0,0,1-1.06-20l26.61-32.93a152.15,152.15,0,0,1-7.57-18.25L76.13,294.1a15,15,0,0,1-13.42-14.91V218.81A15,15,0,0,1,76.13,203.9l42.09-4.47a152.15,152.15,0,0,1,7.57-18.25L99.18,148.25a15,15,0,0,1,1.06-20l42.69-42.69a15,15,0,0,1,20-1.06l32.93,26.6a152.77,152.77,0,0,1,18.25-7.56l4.47-42.09A15,15,0,0,1,233.53,48H293.9a15,15,0,0,1,14.92,13.42l4.46,42.09a152.91,152.91,0,0,1,18.26,7.56l32.92-26.6a15,15,0,0,1,20,1.06l42.69,42.69a15,15,0,0,1,1.06,20l-26.61,32.93a153.8,153.8,0,0,1,7.57,18.25l42.09,4.47a15,15,0,0,1,13.41,14.91v60.38A15,15,0,0,1,451.3,294.1l-42.09,4.47a153.8,153.8,0,0,1-7.57,18.25l26.61,32.93a15,15,0,0,1-1.06,20L384.5,412.47a15,15,0,0,1-20,1.06l-32.92-26.6a152.91,152.91,0,0,1-18.26,7.56l-4.46,42.09A15,15,0,0,1,293.9,450ZM247,420h33.39l4.09-38.56a15,15,0,0,1,11.06-12.91A123,123,0,0,0,325.7,356a15,15,0,0,1,17,1.31l30.16,24.37,23.61-23.61L372.06,328a15,15,0,0,1-1.31-17,122.63,122.63,0,0,0,12.49-30.14,15,15,0,0,1,12.92-11.06l38.55-4.1V232.31l-38.55-4.1a15,15,0,0,1-12.92-11.06A122.63,122.63,0,0,0,370.75,187a15,15,0,0,1,1.31-17l24.37-30.16-23.61-23.61-30.16,24.37a15,15,0,0,1-17,1.31,123,123,0,0,0-30.14-12.49,15,15,0,0,1-11.06-12.91L280.41,78H247l-4.09,38.56a15,15,0,0,1-11.07,12.91A122.79,122.79,0,0,0,201.73,142a15,15,0,0,1-17-1.31L154.6,116.28,131,139.89l24.38,30.16a15,15,0,0,1,1.3,17,123.41,123.41,0,0,0-12.49,30.14,15,15,0,0,1-12.91,11.06l-38.56,4.1v33.38l38.56,4.1a15,15,0,0,1,12.91,11.06A123.41,123.41,0,0,0,156.67,311a15,15,0,0,1-1.3,17L131,358.11l23.61,23.61,30.17-24.37a15,15,0,0,1,17-1.31,122.79,122.79,0,0,0,30.13,12.49,15,15,0,0,1,11.07,12.91ZM449.71,279.19h0Z" fill="currentColor"/>
            <path d="M263.71,340.36A91.36,91.36,0,1,1,355.08,249,91.46,91.46,0,0,1,263.71,340.36Zm0-152.72A61.36,61.36,0,1,0,325.08,249,61.43,61.43,0,0,0,263.71,187.64Z" fill="currentColor"/>
          </g>
        </svg>
      </span> 
      <span class="gdpr-nav-tab-title"><?php echo esc_attr( $content->advanced->nav_label ); ?></span>
    </button>
  </li>
<?php endif; ?>

<?php if ( $content->cookiepolicy->show ) : ?>
  <li class="menu-item-moreinfo menu-item-off">
    <button data-href="#cookie_policy_modal" class="moove-gdpr-tab-nav" aria-label="<?php echo esc_attr( $content->cookiepolicy->nav_label ); ?>">
      <span class="gdpr-svg-icon">
        <svg class="icon icon-policy" viewBox="0 0 26 32">
          <path d="M21.936 10.816c0-0.205-0.077-0.384-0.23-0.538l-5.862-5.99c-0.154-0.154-0.333-0.23-0.538-0.23h-9.088c-1.408 0-2.56 1.152-2.56 2.56v18.765c0 1.408 1.152 2.56 2.56 2.56h13.158c1.408 0 2.56-1.152 2.56-2.56v-14.566zM16.022 6.669l3.558 3.635h-3.302c-0.154 0-0.256-0.102-0.256-0.256v-3.379zM19.376 26.381h-13.158c-0.563 0-1.024-0.461-1.024-1.024v-18.739c0-0.563 0.461-1.024 1.024-1.024h8.269v4.454c0 0.998 0.794 1.792 1.792 1.792h4.122v13.542c0 0.538-0.461 0.998-1.024 0.998zM16.893 18.419h-8.192c-0.435 0-0.768 0.333-0.768 0.768s0.333 0.768 0.768 0.768h8.192c0.435 0 0.768-0.333 0.768-0.768s-0.333-0.768-0.768-0.768zM16.893 14.528h-8.192c-0.435 0-0.768 0.333-0.768 0.768s0.333 0.768 0.768 0.768h8.192c0.435 0 0.768-0.333 0.768-0.768s-0.333-0.768-0.768-0.768z" fill="currentColor"></path>
        </svg>        
      </span> 
      <span class="gdpr-nav-tab-title"><?php echo esc_attr( $content->cookiepolicy->nav_label ); ?></span>
    </button>
  </li>
<?php endif; ?>