<?php

namespace PhpMailer;

class View
{
    protected string $outerlayout;

    protected string $innerlayout;

    protected string $title;

    protected array $data;

    protected array $stylesheets = [];

    protected array $javascripts = [];
    protected array $headscripts = [];


    public function setOuterlayout(string $pathToOuterlayout): static
    {
        $this->outerlayout = $pathToOuterlayout;
        return $this;
    }

    public function setInnerlayout(string $pathToInnerlayout): static
    {
        $this->innerlayout = $pathToInnerlayout;
        return $this;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function renderInnerLayout(): static
    {

        include $this->innerlayout;
        return $this;
    }

    public function setData(string $key, mixed $data): static
    {
        $this->data[$key] = $data;
        return $this;

    }




    public function render(): string
    {
        ob_start();

        include $this->outerlayout;

        return ob_get_clean();
    }

    public function addStylesheet(string $pathToStylesheet): static
    {
        $this->stylesheets[] = $pathToStylesheet;
        return $this;
    }


    public function addJavascript(string $pathToJS): static{

        $this->javascripts[] = $pathToJS;
        return $this;
    }


    public function addHeadScript(string $pathToHeadScript): static
    {
        $this->headscripts[] = $pathToHeadScript;
        return $this;
    }


}