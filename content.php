<div class="content">
    <!-- if home -->
        <div class="work-wrapper">
            <div class="work-row">

            <?php for ($work = 0; $work < 10; $work++): ?>

                <?php if ($work != 0 && $work % 3 == 0): ?>
                    </div>
                    <div class="work-row">
                <?php endif ?>

                <div class="work-item">
                    <p>img</p>
                    <p>Name. WORK N<?php echo $work ?></p>
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
</div>
