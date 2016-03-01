<?php

/* index.phtml */
class __TwigTemplate_113751a20660ae2b1680a402faf4049b84c5d43824a9de4d6a500376c472b1b1 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.phtml", "index.phtml", 1);
        $this->blocks = array(
        );
    }

    protected function doGetParent(array $context)
    {
        return "layout.phtml";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    public function getTemplateName()
    {
        return "index.phtml";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  11 => 1,);
    }
}
/* {% extends "layout.phtml" %}*/
/* */
/* */
