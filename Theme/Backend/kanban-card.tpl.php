<?php declare(strict_types=1);
$card     = $this->getData('card');
$comments = $card->getComments();
?>

<div class="row">
    <div class="col-xs-12">
        <section class="box wf-100">
            <header><h1><?= $this->printHtml($card->name); ?></h1></header>
            <div class="inner">
                <?= $this->printHtml($card->description); ?>
            </div>
        </section>
    </div>
</div>

<?php foreach ($comments as $comment) : ?>
<div class="row">
    <div class="col-xs-12">
        <section class="box wf-100">
            <div class="inner">
                <?= $this->printHtml($comment->description); ?>
            </div>
        </section>
    </div>
</div>
<?php endforeach; ?>