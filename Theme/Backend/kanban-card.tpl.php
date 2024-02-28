<?php
/**
 * Jingga
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

use Modules\Comments\Models\CommentListStatus;
use phpOMS\Uri\UriFactory;

/** @var \Modules\Kanban\Models\KanbanCard $card */
$card = $this->data['card'];
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
                <?php $files = $card->files; foreach ($files as $file) : ?>
                     <span><a class="content" href="<?= UriFactory::build('{/base}/media/view?id=' . $file->id);?>"><?= $file->name; ?></a></span>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</div>

<?php
$commentList = $card->commentList;
if ($this->data['commentPermissions']['write'] && $commentList?->status === CommentListStatus::ACTIVE) :
  echo $this->getData('commentCreate')->render(1);
endif;

if ($this->data['commentPermissions']['list_modify']
    || ($this->data['commentPermissions']['list_read'] && $commentList->status !== CommentListStatus::INACTIVE)
) :
    echo $this->getData('commentList')->render($commentList);
endif;
