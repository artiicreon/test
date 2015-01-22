<?php

/**
 * Add body classes if certain regions have content.
 */
function drupalbasis_preprocess_html(&$variables) {
  $variables['classes_array'][] = 'new-class';  
  if (!empty($variables['page']['featured'])) {
    $variables['classes_array'][] = 'featured';
  }

  if (!empty($variables['page']['triptych_first'])
    || !empty($variables['page']['triptych_middle'])
    || !empty($variables['page']['triptych_last'])) {
    $variables['classes_array'][] = 'triptych';
  }

  if (!empty($variables['page']['footer_firstcolumn'])
    || !empty($variables['page']['footer_secondcolumn'])
    || !empty($variables['page']['footer_thirdcolumn'])
    || !empty($variables['page']['footer_fourthcolumn'])) {
    $variables['classes_array'][] = 'footer-columns';
  }

  // Add conditional stylesheets for IE
  drupal_add_css(path_to_theme() . '/css/ie.css', array('group' => CSS_THEME, 'browsers' => array('IE' => 'lte IE 7', '!IE' => FALSE), 'preprocess' => FALSE));
  drupal_add_css(path_to_theme() . '/css/ie6.css', array('group' => CSS_THEME, 'browsers' => array('IE' => 'IE 6', '!IE' => FALSE), 'preprocess' => FALSE));
}

/**
 * Override or insert variables into the page template for HTML output.
 */
function drupalbasis_process_html(&$variables) {
  // Hook into color.module.
  if (module_exists('color')) {
    _color_html_alter($variables);
  }
}

/**
 * Implements hook_preprocess_page().
 *
 * Override or insert variables into the page template.
 */
function drupalbasis_preprocess_page(&$vars) {
  // For node type news and events
  //print '<pre>';print_r($variables['node']);print '</pre>';
  if(isset($vars['node']->type) && !empty($vars['node']->type) && ($vars['node']->type == 'news_and_events')){
    $vars['news_and_events_image'] = $vars['node']->field_logo_image[LANGUAGE_NONE][0]['uri'];    
  }
  //print_r($vars['news_and_events_image']);
  global $base_url;
  //print_r(base_path());
  //echo '<img src="'.$base_url.'/sites/default/files/Icon-Placeholder_2_2.png">';
}

/**
 * Override or insert variables into the page template.
 */
function drupalbasis_process_page(&$variables) {    
  // Hook into color.module.
  if (module_exists('color')) {
    _color_page_alter($variables);
  }
  // Always print the site name and slogan, but if they are toggled off, we'll
  // just hide them visually.
  $variables['hide_site_name']   = theme_get_setting('toggle_name') ? FALSE : TRUE;
  $variables['hide_site_slogan'] = theme_get_setting('toggle_slogan') ? FALSE : TRUE;
  if ($variables['hide_site_name']) {
    // If toggle_name is FALSE, the site_name will be empty, so we rebuild it.
    $variables['site_name'] = filter_xss_admin(variable_get('site_name', 'Drupal'));
  }
  if ($variables['hide_site_slogan']) {
    // If toggle_site_slogan is FALSE, the site_slogan will be empty, so we rebuild it.
    $variables['site_slogan'] = filter_xss_admin(variable_get('site_slogan', ''));
  }
  // Since the title and the shortcut link are both block level elements,
  // positioning them next to each other is much simpler with a wrapper div.
  if (!empty($variables['title_suffix']['add_or_remove_shortcut']) && $variables['title']) {
    // Add a wrapper div using the title_prefix and title_suffix render elements.
    $variables['title_prefix']['shortcut_wrapper'] = array(
      '#markup' => '<div class="shortcut-wrapper clearfix">',
      '#weight' => 100,
    );
    $variables['title_suffix']['shortcut_wrapper'] = array(
      '#markup' => '</div>',
      '#weight' => -99,
    );
    // Make sure the shortcut link is the first item in title_suffix.
    $variables['title_suffix']['add_or_remove_shortcut']['#weight'] = -100;
  }
}

/**
 * Implements hook_preprocess_maintenance_page().
 */
function drupalbasis_preprocess_maintenance_page(&$variables) {
  // By default, site_name is set to Drupal if no db connection is available
  // or during site installation. Setting site_name to an empty string makes
  // the site and update pages look cleaner.
  // @see template_preprocess_maintenance_page
  if (!$variables['db_is_active']) {
    $variables['site_name'] = '';
  }
  drupal_add_css(drupal_get_path('theme', 'drupalbasis') . '/css/maintenance-page.css');
}

/**
 * Override or insert variables into the maintenance page template.
 */
function drupalbasis_process_maintenance_page(&$variables) {
  // Always print the site name and slogan, but if they are toggled off, we'll
  // just hide them visually.
  $variables['hide_site_name']   = theme_get_setting('toggle_name') ? FALSE : TRUE;
  $variables['hide_site_slogan'] = theme_get_setting('toggle_slogan') ? FALSE : TRUE;
  if ($variables['hide_site_name']) {
    // If toggle_name is FALSE, the site_name will be empty, so we rebuild it.
    $variables['site_name'] = filter_xss_admin(variable_get('site_name', 'Drupal'));
  }
  if ($variables['hide_site_slogan']) {
    // If toggle_site_slogan is FALSE, the site_slogan will be empty, so we rebuild it.
    $variables['site_slogan'] = filter_xss_admin(variable_get('site_slogan', ''));
  }
}

/**
 * Override or insert variables into the node template.
 */
function drupalbasis_preprocess_node(&$variables) {
  if ($variables['view_mode'] == 'full' && node_is_page($variables['node'])) {
    $variables['classes_array'][] = 'node-full';
  }
  // For node type news and events
  //print '<pre>';print_r($variables['node']);print '</pre>';
  if(isset($variables['node']->type) && !empty($variables['node']->type) && ($variables['node']->type == 'news_and_events')){
    $variables['news_and_events_image'] = $variables['node']->field_logo_image[LANGUAGE_NONE][0]['uri'];    
  }  
}

/**
 * Override or insert variables into the block template.
 */
function drupalbasis_preprocess_block(&$variables) {
  // In the header region visually hide block titles.
  if ($variables['block']->region == 'header') {
    $variables['title_attributes_array']['class'][] = 'element-invisible';
  }
}

/**
 * Implements theme_menu_tree().
 */
function drupalbasis_menu_tree($variables) {
  return '<ul class="menu clearfix">' . $variables['tree'] . '</ul>';
}

/**
 * Implements theme_field__field_type().
 */
function drupalbasis_field__taxonomy_term_reference($variables) {
  $output = '';

  // Render the label, if it's not hidden.
  if (!$variables['label_hidden']) {
    $output .= '<h3 class="field-label">' . $variables['label'] . ': </h3>';
  }

  // Render the items.
  $output .= ($variables['element']['#label_display'] == 'inline') ? '<ul class="links inline">' : '<ul class="links">';
  foreach ($variables['items'] as $delta => $item) {
    $output .= '<li class="taxonomy-term-reference-' . $delta . '"' . $variables['item_attributes'][$delta] . '>' . drupal_render($item) . '</li>';
  }
  $output .= '</ul>';

  // Render the top-level DIV.
  $output = '<div class="' . $variables['classes'] . (!in_array('clearfix', $variables['classes_array']) ? ' clearfix' : '') . '"' . $variables['attributes'] .'>' . $output . '</div>';

  return $output;
}

/**
 * Implements hook_preprocess_HOOK().
 * 
 */
function customers_preprocess_links(&$variables) {
  // Hack: Check if class 'language-switcher-locale-url' is set for the links
  //print '<pre>';print_r($variables);print '</pre>';
  if (isset($variables['attributes']['class']['0']) && $variables['attributes']['class']['0'] == 'language-switcher-locale-url') {
    foreach (array_keys($variables['links']) as $langcode) {
      $variables['links'][$langcode]['title'] = $langcode;
    }
  }
}

function customers_preprocess_node(&$variables) {
  // Hack: Check if class 'language-switcher-locale-url' is set for the links
  //print '<pre>';print_r($variables);print '</pre>';
  if (isset($variables['attributes']['class']['0']) && $variables['attributes']['class']['0'] == 'language-switcher-locale-url') {
    foreach (array_keys($variables['links']) as $langcode) {
      $variables['links'][$langcode]['title'] = $langcode;
    }
  }
}

function customers_preprocess_html(&$variables) {
  // Hack: Check if class 'language-switcher-locale-url' is set for the links
  //print '<pre>';print_r($variables);print '</pre>';
  if (isset($variables['attributes']['class']['0']) && $variables['attributes']['class']['0'] == 'language-switcher-locale-url') {
    foreach (array_keys($variables['links']) as $langcode) {
      $variables['links'][$langcode]['title'] = $langcode;
    }
  }
}

function customers_preprocess_breadcrumb(&$variables) {
  // Hack: Check if class 'language-switcher-locale-url' is set for the links
  //print '<pre>';print_r($variables);print '</pre>';
  if (isset($variables['attributes']['class']['0']) && $variables['attributes']['class']['0'] == 'language-switcher-locale-url') {
    foreach (array_keys($variables['links']) as $langcode) {
      $variables['links'][$langcode]['title'] = $langcode;
    }
  }
}

function customers_preprocess_book_navigation(&$variables) {
  // Hack: Check if class 'language-switcher-locale-url' is set for the links
  //print '<pre>';print_r($variables);print '</pre>';
  if (isset($variables['attributes']['class']['0']) && $variables['attributes']['class']['0'] == 'language-switcher-locale-url') {
    foreach (array_keys($variables['links']) as $langcode) {
      $variables['links'][$langcode]['title'] = $langcode;
    }
  }
}