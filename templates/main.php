<?php require_once 'templates/header.php'; ?>

    <div class="row-fluid">
        <div class="span3">

            <div class="imap-block">
                <?php require_once 'templates/login.php'; ?>
            </div>
            <div class="imap-block">
                <?php require_once 'templates/mailboxes.php'; ?>
            </div>
        </div>
        <!--/span-->
        <div class="span9 imap-block">
            <div class="row-fluid">
                <?php require_once 'templates/messages.php'; ?>
            </div>
            <!--/row-->
        </div>
        <!--/span-->
    </div>
    <!--/row-->

<?php require_once 'templates/footer.php'; ?>