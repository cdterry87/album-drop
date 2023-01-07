<?php

namespace App\Traits;

use Livewire\WithPagination;

trait PaginationTrait
{
    use WithPagination;

    public function gotoPage($page)
    {
        $this->setPage($page);
        $this->emit('scrollTop');
    }

    public function nextPage()
    {
        $this->setPage($this->page + 1);
        $this->emit('scrollTop');
    }

    public function previousPage()
    {
        $this->setPage(max($this->page - 1, 1));
        $this->emit('scrollTop');
    }
}
