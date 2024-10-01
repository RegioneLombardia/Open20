<?php
$customSummaryClass = (isset($customSummaryClass)) ? $customSummaryClass : '';
$customListClass = (isset($customListClass)) ? $customListClass : '';
$customPaginationClass = (isset($customPaginationClass)) ? $customPaginationClass : '';
?>
<div class='summary-design d-flex justify-content-between w-100 <?= $customSummaryClass ?>'>
    {summary}
</div>
<div class='list-view-design row it-list-wrapper <?= $customListClass ?>'>
    {items}
</div>
<nav role="navigation" aria-label="Pagination" class='pagination-wrapper justify-content-center pagination-design <?= $customPaginationClass ?>'>
    {pager}
</nav>