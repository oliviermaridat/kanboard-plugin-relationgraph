<li>
    <i class="fa fa-rotate-left fa-fw"></i>
    <?= $this->url->link(t('Relation graph'), 'RelationgraphController', 'project', array('plugin' => 'relationgraph', 'project_id' => $project['id'])) ?>
</li>
