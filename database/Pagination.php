<?php

namespace Database;

class Pagination
{

    private $limit;
    private $results;
    private $pages;
    private $currentPage;


    public function __construct($results, $currentPage = 1, $limit = 10)
    {
        $this->results     = $results;
        $this->limit       = $limit;
        $this->currentPage = (is_numeric($currentPage) and $currentPage > 0) ? $currentPage : 1;
        $this->calculate();
    }

    
    private function calculate()
    {
        $this->pages = $this->results > 0 ? ceil($this->results / $this->limit) : 1;

        $this->currentPage = $this->currentPage <= $this->pages ? $this->currentPage : $this->pages;
    }

    
    public function getLimit()
    {
        $offset = ($this->limit * ($this->currentPage - 1));
        return $offset . ',' . $this->limit;
    }

    
    public function getPages()
    {
        if ($this->pages == 1) return [];

        $pages = [];
        for ($i = 1; $i <= $this->pages; $i++) 
        {
            $pages[] = [
                'page'    => $i,
                'current' => $i == $this->currentPage
            ];
        }

        return $pages;
    }
}