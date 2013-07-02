<div class="accordion" id="accordion1">
    <?php foreach ($s as $key => &$line): ?>
        <?php $id = $num.'-'.$key; ?>
        <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapse<?=$id?>">
                    <?=$line['request']?>
                </a>
            </div>
            <div id="collapse<?=$id?>" class="accordion-body collapse">
                <div class="accordion-inner">
                    <?=$line['response']?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>