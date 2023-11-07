<?php

// 1. Input query
// 2. Input item_count
// 3. Input absolute current page
// 4. Process & export offset_val
// 5. Customize and return new query
// 6. Handle Buttons

class Pagination
{
    private String $query;
    private String $jsFunc;
    private int $item_count;
    private int $offset_val = 0;
    private int $curr_page = 1;
    private int $absolute_curr_page = 0;
    private int $max_pages;

    public function __construct(String $query, String $jsFunc, int $item_count = 5)
    {
        $this->query = $query;
        $this->jsFunc = $jsFunc;
        $this->item_count = $item_count;

        $this->initializeClass();
    }

    private function initializeClass()
    {
        $all_rs = Database::search($this->query);
        $all_num = $all_rs->num_rows;

        $this->max_pages = ceil($all_num / $this->item_count);
    }

    public  function newQuery()
    {
        $this->initializeClass();

        // $this->offset_val = $this->absolute_curr_page * $this->item_count;
        // $this->changeStatus();

        $new_query = $this->query . " LIMIT $this->item_count OFFSET $this->offset_val";

        return $new_query;    //BUG
    }

    public function controlButtons()
    {
        $this->initializeClass();

        $prev_page = $this->curr_page - 1;
        $next_page = $this->curr_page + 1;

        $markup = '
        <button style="opacity: ' . ($this->curr_page <= 1 ? '0' : '1') . '" class="wsh_btn_chip" ' . ($this->curr_page <= 1 ? '' : 'onclick="' . $this->jsFunc . '(' . $prev_page . ')"') . '>
        <img src="./assets/shared/icon/arrow-left.svg" style="margin-right: 8px;" alt="">Back ' . $prev_page . '
        </button>
        
        <button style="opacity: ' . ($this->curr_page >= $this->max_pages ? '0' : '1') . '" class="wsh_btn_chip" ' . ($this->curr_page >= $this->max_pages ? '' : 'onclick="' . $this->jsFunc . '(' . $next_page . ')"') . '>
            Next ' . $next_page . ' <img src="./assets/shared/icon/arrow-left.svg" style="margin-left: 8px; rotate: 180deg;" alt="">
            </button>';

        return $markup;
    }

    public function changeStatus($p)
    {
        if ($p != -1) {

            $this->curr_page = $p;
            $this->absolute_curr_page = $p - 1;
            $this->offset_val = $this->absolute_curr_page * $this->item_count;
        } else {
            return "\$p   --->  " . $p;
        }
    }
}