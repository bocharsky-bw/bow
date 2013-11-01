<?php

namespace BW\MenuBundle\Service;

class Recursion {
    
    /**
     * Количество вызовов рекурсивной функции
     */
    private $countRecursionCalls = 0;
    
    /**
     * Количество итераций цикла foreach
     */
    private $countForeachIterations = 0;
    
    
    public function __construct() {
    }
    
    
    public function getCountRecursionCalls() {
    
        return $this->countRecursionCalls;
    }
    
    public function getCountForeachIterations() {
    
        return $this->countForeachIterations;
    }
    
    
    /**
     * Рекурсивный метод для создания многомерного массива из 
     * связанных между собой родительских и дочерних элементов массива,
     * операясь на текущий уровень вложенности элемента и его родителя.
     * 
     * @param array $data Операции проводятся всегда над однми и тем же массивом $data, который рекурсивно передается по ссылке
     * @param integer $level Уровень вложенности элементов
     * @param integer $parent ID родительского элемента
     * @param boolean $isArrayGenerate Сгенерировать правильный массив данных с группированием по уровням и родителям
     * 
     * @return array $result Многомерный массив
     */
    public function levelParentArrayRecursion(array &$data, $level = 0, $parent = FALSE, $isArrayGenerate = TRUE) {
        $this->countRecursionCalls++; // Подсчет количества рекурсивных вызовов
        
        /* Генерирование правильного массива данных для удобного перебора по уровням и по родителям */
        if ($isArrayGenerate === TRUE) {
            $generatedArray = array();
            foreach ($data as $id => $item) {
                $generatedArray['levels'][ $item['level'] ][ $id ] = $item;
                $generatedArray['parents'][ $item['parent'] ][ $id ] = $item;
            }
            unset($data);
            $data =& $generatedArray;
        }
        
        /* Определение способа перебора элементов в зависимости от условий */
        // Если не было передано родителя
        if ($parent === FALSE) {
            // то выполнять перебор элементов по уровням
            $items =& $data['levels'][$level];
        } else {
            // иначе выполнять перебор дочерних элементов переданного родителя
            $items =& $data['parents'][$parent];
        }
        
        /* Формирование многомерного массива */
        foreach ($items as $id => $item) {
            $this->countForeachIterations++; // Подсчет количества итераций цикла foreach
            
            // Занести текущий элемент в результирующий массив
            $result[$id]['current'] = $item;
            
            // Если существуют дочерние элементы у текущего элемента
            if (isset($data['parents'][$item['id']])) {
                // то выполнить рекурсивный вызов для перебора его дочерних элементов
                $result[$id]['children'] = $this->levelParentRecursion($data, $level + 1, $item['id'], FALSE);
            }
        }
        
        return $result;
    }
    
    /**
     * Рекурсивный метод для создания многомерного массива из 
     * связанных между собой родительских и дочерних сущностей (entities) Doctrine,
     * операясь на текущий уровень вложенности сущности (entity) и его родителя.
     * 
     * @param array $data Операции проводятся всегда над однми и тем же массивом $data, который рекурсивно передается по ссылке
     * @param integer $level Уровень вложенности элементов
     * @param integer $parent ID родительского элемента
     * @param boolean $isArrayGenerate Сгенерировать правильный массив данных с группированием по уровням и родителям
     * 
     * @return array $result Многомерный массив сущностей (entity)
     */
    public function levelParentEntityRecursion(array &$data, $level = 0, $parent = FALSE, $isArrayGenerate = TRUE) {
        $this->countRecursionCalls++; // Подсчет количества рекурсивных вызовов
        
        /* Генерирование правильного массива данных для удобного перебора по уровням и по родителям */
        if ($isArrayGenerate === TRUE) {
            $generatedArray = array();
            foreach ($data as $id => $item) {
                $generatedArray['levels'][ $item->getLevel() ][ $id ] = $item;
                $generatedArray['parents'][ $item->getParent() ? $item->getParent()->getId() : 0 ][ $id ] = $item;
            }
            unset($data);
            $data =& $generatedArray;
        }
        
        /* Определение способа перебора элементов в зависимости от условий */
        // Если не было передано родителя
        if ($parent === FALSE) {
            // то выполнять перебор элементов по уровням
            $items =& $data['levels'][$level];
        } else {
            // иначе выполнять перебор дочерних элементов переданного родителя
            $items =& $data['parents'][$parent];
        }
        
        /* Формирование многомерного массива */
        foreach ($items as $id => $item) {
            $this->countForeachIterations++; // Подсчет количества итераций цикла foreach
            
            // Занести текущий элемент в результирующий массив
            $result[$id]['current'] = $item;
            
            // Если существуют дочерние элементы у текущего элемента
            if (isset($data['parents'][$item->getId()])) {
                // то выполнить рекурсивный вызов для перебора его дочерних элементов
                $result[$id]['children'] = $this->levelParentEntityRecursion($data, $level + 1, $item->getId(), FALSE);
            }
        }
        
        return $result;
    }
    
}