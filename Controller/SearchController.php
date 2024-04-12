<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\Kanban
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Kanban\Controller;

use Modules\Kanban\Models\KanbanCardMapper;
use phpOMS\DataStorage\Database\Query\OrderType;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\System\MimeType;

/**
 * Search class.
 *
 * @package Modules\Kanban
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class SearchController extends Controller
{
    /**
     * Api method to search for tags
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function searchGeneral(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        /** @var \Modules\Kanban\Models\KanbanCard[] $docs */
        $docs = KanbanCardMapper::getAll()
            ->with('tags')
            ->with('tags/title')
            ->where('name', '%' . ($request->getDataString('search') ?? '') . '%', 'LIKE')
            ->where('descriptionRaw', '%' . ($request->getDataString('search') ?? '') . '%', 'LIKE', 'OR')
            ->where('tags/title/language', $response->header->l11n->language)
            ->sort('createdAt', OrderType::DESC)
            ->limit(8)
            ->executeGetArray();

        $results = [];
        foreach ($docs as $doc) {
            $results[] = [
                'title'     => $doc->name,
                'summary'   => '',
                'link'      => '{/base}/kanban/card/view?id=' . $doc->id,
                'account'   => '',
                'createdAt' => $doc->createdAt,
                'image'     => '',
                'tags'      => $doc->tags,
                'type'      => 'list_links',
                'module'    => 'Kanban',
            ];
        }

        $response->header->set('Content-Type', MimeType::M_JSON . '; charset=utf-8', true);
        $response->add($request->uri->__toString(), $results);
    }
}
