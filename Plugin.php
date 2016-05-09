<?php

namespace Kanboard\Plugin\Relationgraph;

use Kanboard\Core\Plugin\Base;

class Plugin extends Base
{
    public function initialize()
    {
        $this->route->addRoute('/relationgraph/:project_id/task/:task_id', 'relationgraph', 'show', 'relationgraph');
        $this->route->addRoute('/relationgraph/:project_id', 'relationgraph', 'project', 'relationgraph');

        $this->template->hook->attach('template:project:dropdown', 'relationgraph:project/dropdown');
        $this->template->hook->attach('template:task:sidebar:information', 'relationgraph:task/sidebar');
    }

    public function getPluginName()
    {
        return 'Relationgraph';
    }

    public function getPluginAuthor()
    {
        return 'Xavier Vidal <xavividal@gmail.com>, Olivier Maridat <https://github.com/oliviermaridat>';
    }

    public function getPluginVersion()
    {
        return '1.0.29-1';
    }

    public function getPluginDescription()
    {
        return 'Show relations between tasks using a graph';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/xavividal/kanboard-plugin-relation-graph';
    }
}
