<?php

namespace MDDD;

class UI {
  public static function screen_button( $key, $data, $id, $class ) {
    $field = $id;
    $defaults = array(
      'class'         => 'button-secondary',
      'desc_tip'      => false,
      'description'   => '',
      'title'         => __('Generate AccessClient token', 'circulair-geld'),
      'button_title'  => __('Generate token', 'circulair-geld')
    );

    $data = wp_parse_args( $data, $defaults );

    ob_start();
    ?>
    <tr valign="top">
      <th scope="row" class="titledesc">
        <label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
        <?php echo $class->get_tooltip_html( $data ); ?>
      </th>
      <td class="forminp">
        <fieldset>
          <legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
          <form method="post" name="accessClientForm" id="accessClientForm" action="">
            <input type="text" id="accessClientCode" name="accessClientCode" placeholder="<?php esc_attr_e('AccessClient Activation Code', 'circulair-geld'); ?>">
            <button type="submit" class="<?php echo esc_attr( $data['class'] ); ?>" 
              type="submit" 
              name="generateAccesclientButton" 
              id="generateAccesclientButton" 
            >
              <?php echo wp_kses_post( $data['button_title'] ); ?>
            </button>
            <p class="description">
              <?php __('Log in to your Circulair Currency account and go to:', 'circulair-geld'); ?>
              <br> <?php __('Personal > Settings > Webshop connections > accesscodes > Add > [Enter description] > Save > Activatiecode > Confirm', 'circulair-geld'); ?>
              <br> <?php __('Enter your four-digit code above and click', 'circulair-geld'); ?> <b><?php echo wp_kses_post( $data['button_title'] ); ?></b>.
              <br>
              <br> <u> <?php __('If you cannot find these settings in your CG-account, then these settings need to be activated for your account.', 'circulair-geld'); ?></u>
            </p>
          </form>
        </fieldset>
      </td>
    </tr>
    <?php
    return ob_get_clean();
  }
  
  public static function test_credentials_button( $key, $data, $id, $class ) {
    $field = $id;
    $defaults = array(
      'class'         => 'button-secondary',
      'desc_tip'      => false,
      'description'   => '',
      'title'         => __('Test credentials', 'circulair-geld'),
      'button_title'  => __('Test credentials', 'circulair-geld')
    );

    $data = wp_parse_args( $data, $defaults );

    ob_start();
    ?>
    <tr valign="top">
      <th scope="row" class="titledesc">
        <label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
        <?php echo $class->get_tooltip_html( $data ); ?>
      </th>
      <td class="forminp">
        <fieldset>
          <legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
          <form method="post" name="testUserCredentialsForm" id="testUserCredentialsForm" action="">
            <input type="hidden" id="testUserCredentials" name="testUserCredentials" value="test">
            <button type="submit" class="<?php echo esc_attr( $data['class'] ); ?>" 
              type="submit" 
              name="testUserCredentialsButton" 
              id="testUserCredentialsButton" 
            >
              <?php echo wp_kses_post( $data['button_title'] ); ?>
            </button>
            <p class="description">
              <?php __('Don\'t forget to save you\'re credentials with the button at the bottom of this page!', 'circulair-geld'); ?>
            </p>
          </form>
        </fieldset>
      </td>
    </tr>
    <?php
    return ob_get_clean();
  }
        
  public static function donate_img( $key, $data, $id ) {
    $field = $id;
    $defaults = array(
      'desc_tip'      => false,
      'description'   => '',
      'title'         => __('Scan the QR-code to donate to further the development of this plugin.', 'circulair-geld'),
    );
    $data = wp_parse_args($data, $defaults);

    ob_start();
    ?>
    <tr valign="top">
      <th scope="row" class="titledesc">
        <label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
      </th>
      <td>
        <img src="<?php echo MDDD_CG_PLUGIN_DIR_URL.'/assets/qr-code.png'?>" alt=<?php __( 'donate using qr-code', 'circulair-geld') ?> />
      </td>
    </tr>
    <?php
    return ob_get_clean();
  }
          
  public static function logo_dev( $key, $data, $id ) {
    $field = $id;
    $defaults = array(
      'desc_tip'     => false,
      'description'  => '',
      'title'      	=> __('This plugin is made possible by:', 'circulair-geld'),
    );
    $data = wp_parse_args( $data, $defaults );

    ob_start();
    ?>
    <tr valign="top">
      <th scope="row" class="titledesc">
        <label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
      </th>
      <td>
        <a href="https://mddd.nl" rel="noopener" target="_blank">
          <img src="<?php echo MDDD_CG_PLUGIN_DIR_URL.'/assets/logo_150px.gif'?>" alt="M.D. Design & Development" />
        </a>
      </td>
    </tr>
    <?php
    return ob_get_clean();
  }
}