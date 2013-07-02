<h3>Mailboxes</h3>
<div class="well sidebar-nav">
    <ul class="nav nav-list">
        <?php foreach ($mailboxes as $mailbox) : ?>
            <?php if (!$mailbox['status'] instanceOf PEAR_Error) : ?>
            <li><a href="#"><?= $mailbox['name'] ?> (<?= $mailbox['status']['UNSEEN'] ?>)</a></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>
<!--/.well -->
<div>
    <?= printTime($time['mailboxes']) ?>
</div>
<div>
    <?= printLogs($logs['mailboxes']) ?>
</div>