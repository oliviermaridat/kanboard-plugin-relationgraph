<?php

namespace Kanboard\Plugin\Relationgraph\Formatter;

use Kanboard\Core\Filter\FormatterInterface;
use Kanboard\Formatter\BaseFormatter;

/**
 * Task Gantt Formatter
 *
 * @package formatter
 * @author  Frederic Guillot
 */
class TaskRelationgraphFormatter extends BaseFormatter implements FormatterInterface
{
    /**
     * Local cache for project columns
     *
     * @access private
     * @var array
     */
    private $columns = array();
    
    /**
     * Apply formatter
     *
     * @access public
     * @return array
     */
    public function format()
    {
        $bars = array();

        foreach ($this->query->findAll() as $task) {
            $bars[] = $this->formatTask($task);
        }

        return $bars;
    }

    /**
     * Format a single task
     *
     * @access private
     * @param  array  $task
     * @return array
     */
    public function formatTask(array $task)
    {
        return array(
            'id' => $task['id'],
            'title' => $task['title'],
            'active' => $task['is_active'],
            'project_id' => $task['project_id'],
            'project' => $task['project_name'],
            'score'=> $task['score'],
            'column' => $task['column_name'],
            'priority' => $task['priority'],
            'nb_links' => $task['nb_links'],
            'assignee' => $task['assignee_name'] ?: $task['assignee_username'],
            'color' => $this->colorModel->getColorProperties($task['color_id'])
        );
    }
}
