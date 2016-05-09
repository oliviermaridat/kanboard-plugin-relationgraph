<section id="main">
    <?= $this->projectHeader->render($project, 'Relationgraph', 'show') ?>

    <div id="mynetwork" style="margin: 5px auto;width: 100%;height: 700px;border: 1px solid lightgray;"></div>

    <div id="graph-nodes" style="display:none">
        <?php $items = []; ?>
        <?php foreach ($graph['nodes'] as $node) : ?>
            <?php
            $titleItems = [];

            if ($node['project_id'] != $project['id']) {
                $titleItems[] = 'Project: ' . $node['project'];
            }

            if ($node['score'] > 0) {
                $titleItems[] = 'Score: ' . $node['score'];
            }

            if ($node['assignee'] != '') {
                $titleItems[] = 'Assignee: ' . $node['assignee'];
            }

            $titleItems[] = 'Priority: ' . $node['priority'];
            $titleItems[] = 'Column: ' . $node['column'];

            $items[] = [
                'id' => $node['id'],
                'label' => '#' . $node['id'] . ' ' . $node['title'],
                'color' => $node['color'],
                'shape' => 'box',
                'size' => '20',
                'shapeProperties' => $node['active'] ? array('borderDashes' => array()) : array('borderDashes' => array(5, 5)),
                'font' => array('color' => $node['active'] ? 'black' : 'gray'),
                'scaling' => [
                    'min' => 30,
                    'max' => 30
                ],
                'shadow' => 'true',
                'mass' => 2,
                'title' => join('<br>', $titleItems)
            ];
            ?>
        <?php endforeach ?>
        <?php echo json_encode($items) ?>
    </div>

    <div id="graph-edges" style="display:none">
        <?php $items = [] ?>
        <?php foreach ($graph['edges'] as $task => $links) : ?>
            <?php foreach ($links as $edge => $type) : ?>
                <?php
                $items[] = [
                    'from' => $task,
                    'to' => $edge,
                    'label' => t($type),
                    'length' => 200,
                    'font' => ['align' => 'top'],
                    'arrows' => 'to'
                ];
                ?>
            <?php endforeach ?>
        <?php endforeach ?>
        <?php echo json_encode($items) ?>
    </div>
</section>

<?= $this->asset->js('plugins/Relationgraph/Asset/Javascript/vis/vis.js') ?>
<?= $this->asset->css('plugins/Relationgraph/Asset/Javascript/vis/vis.css') ?>
<?= $this->asset->js('plugins/Relationgraph/Asset/Javascript/GraphBuilder.js') ?>