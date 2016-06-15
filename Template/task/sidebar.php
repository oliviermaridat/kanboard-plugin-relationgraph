<?php
    $routerController = $this->app->getRouterController();
    $routerPlugin = $this->app->getPluginName();

    $active = $routerController == 'RelationgraphController' && $routerPlugin == 'Relationgraph';
?>
<li class="<?= $active ? 'active' : '' ?>">
    <i class="fa fa-rotate-left fa-fw"></i>
    <?= $this->url->link(
        'Relation graph',
        'RelationgraphController',
        'show',
        ['plugin' => 'relationgraph', 'task_id' => $task['id']]
    ) ?>
</li>
