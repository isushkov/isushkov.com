<div class="content">
    <!-- if home -->
        <div class="block-filter">
            <h3>Filter by:</h3>
            <p> - DEV?ART</p>
            <p> - AVE</p>
            <p> - Created at</p>
            <p> - TAGS</p>
        </div>
        <div class="work-wrapper">
            <?php for ($work = 0; $work < 10; $work++): ?>
                <div class="work-item">
                    <p>img</p>
                    <p>Name. WORK N<?php echo $work+1 ?></p>
                    <p>DEV?ART:</p>
                    <p>ITEM D?A:</p>
                    <p>AVE:</p>
                    <p>Created at:</p>
                    <p>TAGS:</p>
                </div>
            <?php endfor ?>
            </div>
        </div>
    <!-- else -->
        <!-- another -->
    <!-- end if -->
</div>
