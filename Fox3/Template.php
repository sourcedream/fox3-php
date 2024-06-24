<?php

namespace Fox3;

class Template {

  /**
   * Template
   * @var string
   */
  protected $template;

  /**
   * Data
   * @var Array
   */
  protected $data;

  public function __construct(string $template, Array $data = []) {
    $this->template = $template;
    $this->data = $data;
  }

  public function render() : string {
    ob_start();
    
    extract($this->data);

    $template_file = 'Views/' . $this->template;
    include($template_file);

    return ob_get_clean();
  }
}