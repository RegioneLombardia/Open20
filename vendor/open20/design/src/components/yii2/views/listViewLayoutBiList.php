<?php
$customSummaryClass = (isset($customSummaryClass)) ? $customSummaryClass : '';
$customListClass = (isset($customListClass)) ? $customListClass : '';
$customPaginationClass = (isset($customPaginationClass)) ? $customPaginationClass : '';
?>
<div class='summary-design d-flex justify-content-between <?= $customSummaryClass ?>'>
    {summary}
</div>
<div class='list-view-design row it-list-wrapper <?= $customListClass ?>'>
    <ul class='it-list'>
        {items}
    </ul>
</div>
<nav class='pagination-wrapper justify-content-center pagination-design <?= $customPaginationClass ?>'>
    {pager}
</nav>