<?php declare(strict_types=1);
$board   = $this->getData('board');
$columns = $board->getColumns();
?>
<!--
@todo Orange-Management/Modules#197
    Columns width should be in % but with min-width and on smaller screens full width
    The amount of columns depends on the user settings
-->
<div class="row">
    <?php $i = 0; foreach ($columns as $column) : $i++; $cards = $column->getCards(); ?>
    <div id="kanban-column-<?= $i; ?>" class="col-xs-12 col-sm-3 box" draggable="true">
        <header><?= $this->printHtml($column->name); ?></header>
        <?php $j = 0; foreach ($cards as $card) : $j++;
            $url = \phpOMS\Uri\UriFactory::build('{/prefix}kanban/card?{?}&id=' . $card->getId());
        ?>
            <section id="kanban-card-<?= $this->printHtml($i . '-' . $j); ?>" class="portlet" draggable="true">
                <div class="portlet-head"><a href="<?= $url; ?>"><?= $this->printHtml($card->name); ?></a><span class="floatRight tag"><?= $card->getCommentCount(); ?></span></div>
                <div class="portlet-body">
                    <article><?= $card->description; ?></article>
                </div>
                <div class="portlet-foot">
                    <div class="overflowfix">
                        <?php $tags = $card->getTags(); foreach ($tags as $tag) : ?>
                            <span class="tag" style="background: <?= $this->printHtml($tag->color); ?>"><?= $tag->icon !== null ? '<i class="' . $this->printHtml($tag->icon ?? '') . '"></i>' : ''; ?><?= $this->printHtml($tag->getL11n()); ?></span>
                        <?php endforeach; ?>
                        <a href="<?= $url; ?>" class="button floatRight"><?= $this->getHtml('More', '0', '0'); ?></a>
                    </div>
                </div>
            </section>
            </a>
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
</div>