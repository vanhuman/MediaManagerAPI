<?php

namespace Handlers;

use Models\Label;

class LabelsHandler extends DatabaseHandler
{
    private const FIELDS = ['id', 'name'];
    private const MANDATORY_FIELDS = ['name'];

    private const SORT_FIELDS = ['id', 'name'];
    private const DEFAULT_SORT_FIELD = 'name';
    private const DEFAULT_SORT_DIRECTION = 'ASC';

    /**
     * @param array | int $params
     * @throws \Exception
     * @return Label | Label[]
     */
    public function select($params)
    {
        $id = $this->getIdFromParams($params);
        $sortBy = $this->getSortByFromParams($params, self::SORT_FIELDS, self::DEFAULT_SORT_FIELD);
        $sortDirection = $this->getSortDirectionFromParams($params, self::DEFAULT_SORT_DIRECTION);
        $page = array_key_exists('page', $params) ? $params['page'] : 1;
        $pageSize = array_key_exists('page_size', $params) ? $params['page_size'] : 50;
        $query = 'SELECT ' . implode(self::FIELDS, ',') . ' FROM label';
        if (isset($id)) {
            $query .= ' WHERE id = ' . $id;
        } else {
            $query .= ' ORDER BY ' . $sortBy . ' ' . $sortDirection;
            $queryWithoutLimit = $query;
            $query .= ' LIMIT ' . ($pageSize * ($page - 1)) . ',' . $pageSize;
        }
        try {
            $result = $this->db->query($query);
            if (isset($queryWithoutLimit)) {
                $resultWithoutLimit = $this->db->query($queryWithoutLimit);
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 500);
        }
        $totalRecords = isset($queryWithoutLimit) ? $resultWithoutLimit->rowCount() : 1;
        $object = [
            'total_number_of_records' => $totalRecords,
            'query' => $query,
            'sortby' => $sortBy,
            'sortdirection' => $sortDirection,
        ];
        if (isset($id)) {
            if ($result->rowCount() === 0) {
                $label = null;
            } else {
                $labelData = $result->fetch();
                $label = $this->createModelFromDatabaseData($labelData);
            }
            $object['body'] = $label;
            return $object;
        } else {
            $labelsData = $result->fetchAll();
            foreach ($labelsData as $labelData) {
                $newLabel = $this->createModelFromDatabaseData($labelData);
                $labels[] = $newLabel;
            }
            $labels = isset($labels) ? $labels : [];
            $object['body'] = $labels;
            return $object;
        }
    }

    /**
     * @param array $labelData
     * @return Label
     * @throws \Exception
     */
    public function insert($labelData)
    {
        try {
            $this->validatePostData($labelData);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        $postData = $this->formatPostdataForInsert($labelData);
        $query = 'INSERT INTO label (' . $postData['keys'] . ')';
        $query .= ' VALUES (' . $postData['values'] . ')';
        try {
            $this->db->query($query);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 500);
        };
        $id = $this->getLastInsertedRecordId('label');
        return $this->select($id);
    }

    /**
     * @param int $id
     * @param array $labelData
     * @return Label
     * @throws \Exception
     */
    public function update($id, $labelData)
    {
        try {
            $this->validatePostData($labelData);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        $postData = $this->formatPostdataForUpdate($labelData);
        $query = 'UPDATE label SET ' . $postData . ' WHERE id = ' . $id;
        try {
            $this->db->query($query);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 500);
        };
        return $this->select($id);
    }

    /**
     * @param array $labelData
     * @return Label
     */
    private function createModelFromDatabaseData($labelData)
    {
        $newLabel = new Label([
            'id' => $labelData['id'],
            'name' => $labelData['name'],
        ]);
        return $newLabel;
    }

    /**
     * @param array $postData
     * @throws \Exception
     */
    private function validatePostData($postData)
    {
        try {
            $this->validateMandatoryFields($postData, self::MANDATORY_FIELDS);
            $this->validateKeys($postData, self::FIELDS);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

}