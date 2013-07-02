<h3>Messages</h3>
<table class="table table-striped">
    <?php foreach ($messages as $number => $message) : ?>
        <tr class="<?php if (!$message['is_read']) { echo 'unread'; } ?>">
            <td>
                <input type="checkbox"/>
            </td>
            <td style="width: 20px;">
                <?php if ($message['has_attach']) : ?>
                    <img src="http://megabox.ru/sites/all/themes/mailkmlight/img/i_attach.gif" alt=""/>
                <?php endif; ?>
            </td>
            <td>
                <a href="#"><?= $message['from'] ?></a>
            </td>
            <td>
                <a href="#"><?= $message['subject'] ?></a>
            </td>
            <td>
                <?= $message['date'] ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<div>
    <?= printTime($time['messages']) ?>
</div>
<div>
    <?= printLogs($logs['messages'], 'messages') ?>
</div>