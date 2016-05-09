<?php

namespace Kanboard\Plugin\Relationgraph\Controller;

use Kanboard\Controller\BaseController;
use Kanboard\Filter\TaskProjectFilter;
use Kanboard\Plugin\Relationgraph\Formatter\TaskRelationgraphFormatter;

class RelationgraphController extends BaseController
{
    public function project()
    {
        $project = $this->getProject();
        $search = $this->helper->projectHeader->getSearchQuery($project);
        $filter = $this->taskLexer->build($search)->withFilter(new TaskProjectFilter($project['id']));

        $formatter = new TaskRelationgraphFormatter($this->container);
        $tasks = $filter->format($formatter);
        $edges = array();
        foreach($tasks AS $task) {
            foreach ($this->taskLinkModel->getAllGroupedByLabel($task['id']) as $type => $links) {
                foreach ($links as $link) {
                    if (!isset($edges[$task['id']][$link['task_id']]) && !isset($edges[$link['task_id']][$task['id']])) {
                        $edges[$task['id']][$link['task_id']] = $type;
        
//                         if ($link['project_id'] != $project['id']) {
//                             $tasks[$link['task_id']] = $formatter->format($this->taskFinder->getDetails($link['task_id']));
//                         }
                    }
                }
            }
        }
        
        $this->response->html($this->helper->layout->app('relationgraph:project/show', array(
            'project' => $project,
            'title' => $project['name'],
            'description' => $this->helper->projectHeader->getDescription($project),
            'graph' => array(
                'nodes' => $tasks,
                'edges' => $edges
            )
        )));
    }
    
    public function show()
    {
        $task = $this->getTask();

        $graph = $this->createGraph($task);

        $this->response->html(
            $this->helper->layout->task(
                'relationgraph:task/show',
                [
                    'title' => $task['title'],
                    'task' => $task,
                    'graph' => $graph,
                    'project' => $this->projectModel->getById($task['project_id'])
                ]
            )
        );
    }

    /**
     * @param $project
     * @return array
     * @throws \Exception
     */
    protected function createProjectGraph($project, $paginator)
    {
        $graph = [];
        $graph['tasks'] = [];
        $graph['edges'] = [];

        foreach($paginator->getCollection() AS $task) {
            $graph['tasks'][$task['id']] = array(
                'id' => $task['id'],
                'title' => $task['title'],
                'active' => $task['is_active'],
                'project_id' => $task['project_id'],
                'project' => $task['project_name'],
                'score'=> $task['score'],
                'column' => $task['column_title'],
                'priority' => $task['priority'],
                'assignee' => $task['assignee_name'] ?: $task['assignee_username'],
                'color' => $this->colorModel->getColorProperties($task['color_id'])
            );
            //~ $graph['edges'][$task['id']][$link['task_id']] = $type;
        }

        $graphData = [
            'nodes' => $graph['tasks'],
            'edges' => $graph['edges']
        ];

        return $graphData;
    }
    
    /**
     * @param $task
     * @return array
     * @throws \Exception
     */
    protected function createGraph($task)
    {
        $graph = [];
        $graph['tasks'] = [];
        $graph['edges'] = [];

        $this->traverseGraph($graph, $task);

        $graphData = [
            'nodes' => $graph['tasks'],
            'edges' => $graph['edges']
        ];

        return $graphData;
    }

    protected function traverseGraph(&$graph, $task)
    {
        if (!isset($graph['tasks'][$task['id']])) {
            $graph['tasks'][$task['id']] = [
                'id' => $task['id'],
                'title' => $task['title'],
                'active' => $task['is_active'],
                'project_id' => $task['project_id'],
                'project' => $task['project_name'],
                'score'=> $task['score'],
                'column' => $task['column_title'],
                'priority' => $task['priority'],
                'assignee' => $task['assignee_name'] ?: $task['assignee_username'],
                'color' => $this->colorModel->getColorProperties($task['color_id'])
            ];
        }

        foreach ($this->taskLinkModel->getAllGroupedByLabel($task['id']) as $type => $links) {
            foreach ($links as $link) {
                if (!isset($graph['edges'][$task['id']][$link['task_id']]) &&
                    !isset($graph['edges'][$link['task_id']][$task['id']])) {
                    $graph['edges'][$task['id']][$link['task_id']] = $type;

                    $this->traverseGraph(
                        $graph,
                        $this->taskFinderModel->getDetails($link['task_id'])
                    );
                }
            }
        }
    }
}
