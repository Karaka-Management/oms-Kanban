<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Kanban\tests\Controller;

use Model\CoreSettings;
use Modules\Admin\Models\AccountPermission;
use phpOMS\Account\Account;
use phpOMS\Account\AccountManager;
use phpOMS\Account\PermissionType;
use phpOMS\Application\ApplicationAbstract;
use phpOMS\Dispatcher\Dispatcher;
use phpOMS\Event\EventManager;
use phpOMS\Localization\L11nManager;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\HttpResponse;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Module\ModuleAbstract;
use phpOMS\Module\ModuleManager;
use phpOMS\Router\WebRouter;
use phpOMS\System\MimeType;
use phpOMS\Utils\TestUtils;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\Modules\Kanban\Controller\ApiController::class)]
final class ApiControllerTest extends \PHPUnit\Framework\TestCase
{
    protected ApplicationAbstract $app;

    /**
     * @var \Modules\Kanban\Controller\ApiController
     */
    protected ModuleAbstract $module;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->app = new class() extends ApplicationAbstract
        {
            protected string $appName = 'Api';
        };

        $this->app->dbPool         = $GLOBALS['dbpool'];
        $this->app->unitId         = 1;
        $this->app->accountManager = new AccountManager($GLOBALS['session']);
        $this->app->appSettings    = new CoreSettings();
        $this->app->moduleManager  = new ModuleManager($this->app, __DIR__ . '/../../../Modules/');
        $this->app->dispatcher     = new Dispatcher($this->app);
        $this->app->eventManager   = new EventManager($this->app->dispatcher);
        $this->app->l11nManager    = new L11nManager();
        $this->app->eventManager->importFromFile(__DIR__ . '/../../../Web/Api/Hooks.php');

        $account = new Account();
        TestUtils::setMember($account, 'id', 1);

        $permission       = new AccountPermission();
        $permission->unit = 1;
        $permission->app  = 2;
        $permission->setPermission(
            PermissionType::READ
            | PermissionType::CREATE
            | PermissionType::MODIFY
            | PermissionType::DELETE
            | PermissionType::PERMISSION
        );

        $account->addPermission($permission);

        $this->app->accountManager->add($account);
        $this->app->router = new WebRouter();

        $this->module = $this->app->moduleManager->get('Kanban');

        TestUtils::setMember($this->module, 'app', $this->app);
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testApiBoardCU() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest();

        $request->header->account = 1;
        $request->setData('title', 'Controller Test Board');
        $request->setData('plain', 'Controller Test Description');
        $request->setData('tags', '[{"title": "TestTitle", "color": "#f0f", "language": "en"}, {"id": 1}]');

        $this->module->apiKanbanBoardCreate($request, $response);

        self::assertEquals('Controller Test Board', $response->getDataArray('')['response']->name);
        self::assertGreaterThan(0, $bId = $response->getDataArray('')['response']->id);

        // update
        $response = new HttpResponse();
        $request  = new HttpRequest();

        $request->header->account = 1;
        $request->setData('id', $bId);
        $request->setData('title', 'New Controller Test Board');

        $this->module->apiKanbanBoardUpdate($request, $response);
        self::assertEquals('New Controller Test Board', $response->getDataArray('')['response']->name);
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testCreateColumn() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest();

        $request->header->account = 1;
        $request->setData('title', 'Controller Test Column');
        $request->setData('board', 1);

        $this->module->apiKanbanColumnCreate($request, $response);

        self::assertEquals('Controller Test Column', $response->getDataArray('')['response']->name);
        self::assertGreaterThan(0, $response->getDataArray('')['response']->id);
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testCreateCard() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest();

        $request->header->account = 1;
        $request->setData('title', 'Controller Test Card');
        $request->setData('plain', 'Controller Test Description');
        $request->setData('column', '1');
        $request->setData('tags', '[{"title": "TestTitle", "color": "#f0f", "language": "en"}, {"id": 1}]');

        if (!\is_file(__DIR__ . '/test_tmp.md')) {
            \copy(__DIR__ . '/test.md', __DIR__ . '/test_tmp.md');
        }

        TestUtils::setMember($request, 'files', [
            'file1' => [
                'name'     => 'test.md',
                'type'     => MimeType::M_TXT,
                'tmp_name' => __DIR__ . '/test_tmp.md',
                'error'    => \UPLOAD_ERR_OK,
                'size'     => \filesize(__DIR__ . '/test_tmp.md'),
            ],
        ]);

        $request->setData('media', \json_encode([1]));

        $this->module->apiKanbanCardCreate($request, $response);

        self::assertEquals('Controller Test Card', $response->getDataArray('')['response']->name);
        self::assertGreaterThan(0, $response->getDataArray('')['response']->id);
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testApiKanbanBoardCreateInvalidData() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest();

        $request->header->account = 1;
        $request->setData('invalid', '1');

        $this->module->apiKanbanBoardCreate($request, $response);
        self::assertEquals(RequestStatusCode::R_400, $response->header->status);
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testApiKanbanColumnCreateInvalidData() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest();

        $request->header->account = 1;
        $request->setData('invalid', '1');

        $this->module->apiKanbanColumnCreate($request, $response);
        self::assertEquals(RequestStatusCode::R_400, $response->header->status);
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testApiKanbanCardCreateInvalidData() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest();

        $request->header->account = 1;
        $request->setData('invalid', '1');

        $this->module->apiKanbanCardCreate($request, $response);
        self::assertEquals(RequestStatusCode::R_400, $response->header->status);
    }
}
