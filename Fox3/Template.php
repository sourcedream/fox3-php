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
   * @var array
   */
  protected $data;

  public function __construct(string $template, array $data = []) {
    $this->template = $template;
    $this->data = $data;
  }

  public function render() : string {
    ob_start();
    
    extract($this->data);

    $template_file = 'Views/' . $this->template;
    include_once $template_file; //NOSONAR

    return ob_get_clean();
  }
}
