<?php

class Pagination
{
    private int $page;
    private int $perPage;
    private int $totalRecords;
    private int $totalPages;

    public function __construct(
        int $totalRecords,
        int $perPage = 12
    ) {
        $this->totalRecords = $totalRecords;
        $this->perPage = $perPage;

        $this->page = max(
            1,
            (int)($_GET['page'] ?? 1)
        );

        $this->totalPages = (int)ceil(
            $this->totalRecords / $this->perPage
        );
    }

    public function currentPage(): int
    {
        return $this->page;
    }

    public function perPage(): int
    {
        return $this->perPage;
    }

    public function totalPages(): int
    {
        return $this->totalPages;
    }

    public function totalRecords(): int
    {
        return $this->totalRecords;
    }

    public function offset(): int
    {
        return ($this->page - 1) * $this->perPage;
    }

    public function hasPages(): bool
    {
        return $this->totalPages > 1;
    }

    public function hasPrevious(): bool
    {
        return $this->page > 1;
    }

    public function hasNext(): bool
    {
        return $this->page < $this->totalPages;
    }

    public function previousPage(): int
    {
        return max(1, $this->page - 1);
    }

    public function nextPage(): int
    {
        return min(
            $this->totalPages,
            $this->page + 1
        );
    }

    public function limit(): string
    {
        return " LIMIT {$this->offset()}, {$this->perPage}";
    }

    public function render(): string
    {
        if (!$this->hasPages()) {
            return '';
        }

        $query = $_GET;

        $html = '<nav>';
        $html .= '<ul class="pagination justify-content-center">';

        if ($this->hasPrevious()) {

            $query['page'] = $this->previousPage();

            $html .= '
                <li class="page-item">
                    <a class="page-link" href="?' . http_build_query($query) . '">
                        Previous
                    </a>
                </li>';
        }

        for ($i = 1; $i <= $this->totalPages; $i++) {

            $query['page'] = $i;

            $active = $i == $this->page
                ? ' active'
                : '';

            $html .= '
                <li class="page-item'.$active.'">
                    <a class="page-link" href="?' . http_build_query($query) . '">
                        '.$i.'
                    </a>
                </li>';
        }

        if ($this->hasNext()) {

            $query['page'] = $this->nextPage();

            $html .= '
                <li class="page-item">
                    <a class="page-link" href="?' . http_build_query($query) . '">
                        Next
                    </a>
                </li>';
        }

        $html .= '</ul>';
        $html .= '</nav>';

        return $html;
    }
}