<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\Kanban
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use phpOMS\Uri\UriFactory;

/** @var \Modules\Kanban\Models\KanbanCard $card */
$card = $this->getData('card');

$comments = $card->getComments();
?>

<div class="row">
    <div class="box">
        <a tabindex="0" class="button" href="<?= $this->request->getReferer() !== '' ? $this->request->getReferer() : UriFactory::build('kanban/dashboard'); ?>"><?= $this->getHtml('Back'); ?></a>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <section class="portlet">
            <div class="portlet-head"><?= $this->printHtml($card->name); ?></div>
            <div class="portlet-body">
                <article><?= $card->description; ?></article>
            </div>
            <div class="portlet-foot">
                <?php $files = $card->getMedia(); foreach ($files as $file) : ?>
                     <span><a class="content" href="<?= UriFactory::build('{/base}/media/single?id=' . $file->id);?>"><?= $file->name; ?></a></span>
                <?php endforeach; ?>
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
            <div class="portlet-foot">
                <?php $files = $comment->getMedia(); foreach ($files as $file) : ?>
                     <span><a class="content" href="<?= UriFactory::build('{/base}/media/single?id=' . $file->id);?>"><?= $file->name; ?></a></span>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</div>
<?php endforeach; ?>
