<?php

namespace BW\BlogBundle\Service;

use Doctrine\DBAL\Connection;

class NestedSet {
    
    /**
     * @var Doctrine\DBAL\Connection The database connection object
     */
    private $conn;
    
    /**
     * @var array Массив элементов, сгруппированных по уровням вложенности
     */
    private $nodesGroupedByLevel;
    
    /**
     * @var array Массив элементов, сгруппиррованных по ID родителя
     */
    private $nodesGroupedByParentId;
    
    
    public function __construct(Connection $conn) {
        $this->conn = $conn;
        $this->nodesGroupedByLevel = array();
        $this->nodesGroupedByParentId = array();
    }

    
    /**
     * Генерирование всего дерева. Нумерация и упорядочивание вложенных узлов
     * @param string $table Название таблицы в БД
     */
    public function regenerateTree($table) {
        $nestedNodes = $this->generateNestedNodes($table, array('lft', 'rgt'));
        
        $left = 0; // Left by default
        $right = 0; // Right by default
        
        $this->orderRecursively($table, $nestedNodes, $left, $right);
        //print_r($nestedNodes); die;
    }
    
    /**
     * Рекурсивный перебор массива вложенных узлов для упорядочивания
     * @param string $table Имя таблицы
     * @param array $nodes Массив вложенных узлов для упорядочивания
     * @param integer $left Левая граница
     * @param integer $right Правая граница
     */
    private function orderRecursively($table, &$nodes, &$left, &$right) {
        
        foreach ($nodes as $node) {
            if ($node['children']) {
                // Дочерние элементы присутствуют
                $lft = $right + 1; // Сохраняем текущее значение счетчика во временную переменную
                $right = $right + 1;
                
                $this->orderRecursively($table, $node['children'], $left, $right);
                $right = $right + 1;
                $this->conn->update($table, array(
                    'lft' => $lft,
                    'rgt' => $right,
                ), array(
                    'id' => $node['id'],
                ));
            } else {
                // Дочерние элементы отсутствуют
                $left = $right + 1;
                $right = $left + 1;
                
                $this->conn->update($table, array(
                    'lft' => $left,
                    'rgt' => $right,
                ), array(
                    'id' => $node['id'],
                ));
            }
        }
    }
    
    /**
     * Генерирование многомерного массива вложенных дочерних элементов
     * @param string $table Название таблицы
     * @param array $extra_fields Дополнительные поля. 
     * Следующие поля уже включены:
     * <ul>
     * <li>id</li>
     * <li>parent_id</li>
     * <li>level</li>
     * <li>NULL AS children</li>
     * </ul>
     * @return array Многомерный массив вложеннных дочерних элементов
     */
    public function generateNestedNodes($table, $extra_fields = array()) {
        // Массив обязательных полей
        $required_fields = array(
            'id',
            'parent_id',
            'level',
        );
        // Если нет дополнительного поля "children"
        if ( ! in_array('children', $extra_fields)) {
            // Добавляем в конец пустое поле "children"
            $extra_fields[] = 'NULL AS children';
        }
        // Объединяем обязательные и дополнительные поля
        $required_fields = array_merge($required_fields, $extra_fields);
        
        $stmt = $this->conn->query("
                SELECT 
                    ". implode(',', $required_fields) ." 
                FROM $table 
                ORDER BY level ASC 
            ");
        
        while ($node = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            // Группирование по уровням
            $this->nodesGroupedByLevel[$node['level']][$node['id']] = $node;
            // Группирование по ID родителя
            $this->nodesGroupedByParentId[$node['parent_id']][$node['id']] = $node;
        }
        
        $nestedNodes = $this->nodesGroupedByLevel[0];
        $this->recursionByParentId($nestedNodes);
        
        return $nestedNodes;
    }
    
    /**
     * Генерирование многомерного массива вложенных дочерних элементов из массива сущностей
     * @param array $entities Массив сущностей
     * @return array Многомерный массив вложеннных дочерних сущностей
     */
    public function generateNestedNodesFromEntities($entities) {
        $nestedNodes = array();
        
        foreach ($entities as $index => $entity) {
            // Группирование по уровням
            $this->nodesGroupedByLevel[$entity->getLevel()][$entity->getId()]['entity'] = $entity;
            // Группирование по ID родителя
            $this->nodesGroupedByParentId[$entity->getParent() ? $entity->getParent()->getId() : 0][$entity->getId()]['entity'] = $entity;
        }
        
        $nestedNodes = $this->nodesGroupedByLevel[0];
        $this->recursionByParentId($nestedNodes);
        
        return $nestedNodes;
    }
    
    /**
     * Метод работает с оригинальным массивом по ссылке
     * @param array $nodes Ссылка на массив элементов нулевого уровня
     * @param NULL
     */
    private function recursionByParentId(&$nodes) {
        foreach ($nodes as $id => $node) {
            //var_dump('*');
            if (isset($this->nodesGroupedByParentId[$id])) {
                $nodes[$id]['children'] = $this->nodesGroupedByParentId[$id];
                $this->recursionByParentId($nodes[$id]['children']);
            }
        }
        
        return NULL;
    }
    
}