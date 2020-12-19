<?php declare(strict_types=1);
$card     = $this->getData('card');
$comments = $card->getComments();
?>

<div class="row">
    <div class="col-xs-12">
        <section class="portlet">
            <div class="portlet-head"><?= $this->printHtml($card->name); ?></div>
            <div class="portlet-body">
                <article><?= $card->description; ?></article>
            </div>
        </section>
    </div>
</div>

<?php foreach ($comments as $comment) : ?>
<div class="row">
    <div class="col-xs-12">
        <section class="portlet">
            <div class="portlet-body">
                <article><?= $comment->description; ?></article>
            </div>
        </section>
    </div>
</div>
<?php endforeach; ?>