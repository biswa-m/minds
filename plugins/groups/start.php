<?php
/**
 * Minds Groups
 */
namespace Minds\plugin\groups;

use Minds\Components;
use Minds\Core;
use Minds\Api;
use Minds\Entities\Factory as EntityFactory;

class start extends Components\Plugin
{
    public function __construct()
    {
        Core\SEO\Manager::add('/groups/profile', function ($slugs = array()) {
            $guid = $slugs[0];
            $group = new entities\Group($guid);
            if (!$group->name) {
                return array();
            }

            return $meta = array(
                'title' => $group->name,
                'description' => $group->briefdescription
            );
        });


        $create_link = new Core\Navigation\Item();
        $create_link
      ->setPriority(1)
      ->setIcon('add')
      ->setName('Create')
      ->setTitle('Create (Groups)')
      ->setPath('/Groups-Create')
      ->setParams(array())
      ->setVisibility(0); //only show for loggedin
        $featured_link = new Core\Navigation\Item();
        $featured_link
            ->setPriority(2)
            ->setIcon('star')
            ->setName('Featured')
            ->setTitle('Featured (Groups)')
            ->setPath('/Groups')
            ->setParams(array('filter'=>'featured'));
        $my_link = new Core\Navigation\Item();
        $my_link
            ->setPriority(3)
            ->setIcon('person_pin')
            ->setName('My')
            ->setTitle('My (Groups)')
            ->setPath('/Groups')
            ->setParams(array('filter'=>'member'))
            ->setVisibility(2); //only show for loggedin

        $root_link = new Core\Navigation\Item();
        Core\Navigation\Manager::add($root_link
            ->setPriority(7)
            ->setIcon('group_work')
            ->setName('Groups')
            ->setTitle('Groups')
            ->setPath('/Groups')
            ->setParams(array('filter'=>'featured'))
      ->addSubItem($create_link)
            ->addSubItem($featured_link)
            ->addSubItem($my_link)
        );

        Api\Routes::add('v1/groups', '\\minds\\plugin\\groups\\api\\v1\\groups');
        Api\Routes::add('v1/groups/group', '\\minds\\plugin\\groups\\api\\v1\\group');
        Api\Routes::add('v1/groups/membership', '\\minds\\plugin\\groups\\api\\v1\\membership');

        \elgg_register_plugin_hook_handler('entities_class_loader', 'all', function ($hook, $type, $return, $row) {
            if ($row->type == 'group') {
                return new entities\Group($row);
            }
        });

        Core\Events\Dispatcher::register('acl:read', 'activity', function ($e) {
            $params = $e->getParameters();
            $entity = $params['entity'];
            $user = $params['user'];
            $e->setResponse(helpers\Membership::isMember($entity->access_id, $user->guid));
        });
    }
}