<?php

namespace app\Core\Plugin\Handler;

use app\Core\Bridge\BasePlugin;
use Silex\Application;
use Elastica;
use \Exception;

/**
 * Class IndexerHandler
 *
 * @package app\Core\Plugin\Handler
 */
class IndexerHandler extends BasePlugin
{

    const ELASTIC_NAMESPACE = 'ss';
    const NUMBER_OF_SHARDS = 4;
    const NUMBER_OF_REPLICAS = 1;

    /**
     *
     * @var Elastica\Client
     */
    private $_indexerObject;

    private $_indexes = [];

    public function __construct(Application $app)
    {
        $this->_indexerObject = $app['indexer.object'];
    }

    protected function _getIndexName()
    {
        return self::ELASTIC_NAMESPACE;
    }

    /**
     * @param $name
     * @return \Elastica\Index
     */
    protected function _getIndex($name)
    {
        if (!isset($this->_indexes[$name])) {
            $this->_indexes[$name] = $this->_indexerObject->getIndex($name);
        }
        if (!$this->_indexes[$name]->exists()) {
            $this->_generateIndex();
        }
        return $this->_indexes[$name];
    }


    /**
     * @param $type
     * @return \Elastica\Type
     */
    protected function _getIndexType($type)
    {
        $index = $this->_getIndex($this->_getIndexName());
        return $index->getType($type);
    }

    protected function _generateIndex()
    {
        $index = $this->_indexerObject->getIndex($this->_getIndexName());
        $element = [
            'number_of_shards' => self::NUMBER_OF_SHARDS,
            'number_of_replicas' => self::NUMBER_OF_REPLICAS,
            'analysis' => [
                'analyzer' => [
                    'indexAnalyzer' => [
                        'type' => 'custom',
                        'tokenizer' => 'standard',
                        'filter' => ['lowercase', 'mySnowball']
                    ],
                    'searchAnalyzer' => [
                        'type' => 'custom',
                        'tokenizer' => 'standard',
                        'filter' => ['standard', 'lowercase', 'mySnowball']
                    ]
                ],
                'filter' => [
                    'mySnowball' => [
                        'type' => 'snowball',
                        'language' => 'English'
                    ]
                ]
            ]
        ];
        $result = $index->create($element, true);
        return $result;
    }

    public function specifyUserMapping()
    {
        $indexType = $this->_getIndexType('user');

        // Define mapping
        $mapping = new Elastica\Type\Mapping();
        $mapping->setType($indexType);
        $mapping->setParam('index_analyzer', 'indexAnalyzer');
        $mapping->setParam('search_analyzer', 'searchAnalyzer');

        // Define boost field
        $mapping->setParam('_boost', ['name' => '_boost', 'null_value' => 1.0]);

        // Set mapping
        $mapping->setProperties([
            'id'      => ['type' => 'integer', 'include_in_all' => true],
            'first_name' => ['type' => 'string', 'include_in_all' => true],
            'dateAdded' => ['type' => 'integer', 'include_in_all' => false],
            '_boost'  => ['type' => 'float', 'include_in_all' => false]
        ]);

        // Send mapping to type
        $result = $mapping->send();
        return $result;
    }

    public function setUser($id, $data, $boost = 1)
    {
        $structure = [
            'id'      => $id,
            //... $data ...
            '_boost'  => $boost
        ];

        return $this->set('user', $id, $structure, $boost);
    }

    public function set($type, $id, $values, $boost = 1.0)
    {
        // Create a document
        $values['id'] = $id;
        $values['_boost'] = $boost;

        // First parameter is the id of document.
        $document = new Elastica\Document($id, $values);

        $indexType = $this->_getIndexType($type);
        $indexType->addDocument($document);

        // Refresh Index
        $resultRefresh = $indexType->getIndex()->refresh();
        return $resultRefresh;
    }

    public function update($type, $id, $values, $boost = null)
    {
        try {
            if (!is_null($boost)) {
                $values['_boost'] = $boost;
            }
            $document = new Elastica\Document($id, $values);
            $indexType = $this->_getIndexType($type);

            $indexType->getDocument($id);
            $indexType->updateDocument($document);

            $result = $indexType->getIndex()->refresh();
        } catch (Exception $e) {
            //Document doesn't exist, create it
            $values['id'] = $id;
            $result = $this->set($type, $id, $values, $boost);
        }

        // Refresh Index
        return $result;
    }

    public function search($params)
    {
        if (!is_array($params)) {
            $params = ['term' => $params];
        }

        $type = ['story', 'user'];
        $from = 0;
        $limit = 10;
        $term = '';
        extract($params);

        if (empty($term)) {
            return false;
        }

        $types = (array) $type;

        $queryString  = new Elastica\Query\QueryString();

        $queryString->setDefaultOperator('OR');
        $queryString->setQuery($term);

        $query = new Elastica\Query();
        $query->setFrom($from)->setSize($limit);
        $query->setQuery($queryString);

        $resultSet = null;
        foreach ($types as $type) {
            $indexType = $this->_getIndexType($type);
            $matches = $indexType->search($query);

            $resultSet[$type]['info']['totalHits'] = $matches->getTotalHits();

            foreach ($matches as $match) {
                $data = $match->getData();
                $data['_score'] = $match->getScore();

                $resultSet[$type]['data'][] = $data;
            }

        }

        return $resultSet;
    }

    public function delete($type, $id)
    {
        $indexType = $this->_getIndexType($type);

        $myDoc = $indexType->getDocument($id);
        $indexType->deleteDocument($myDoc);

        $result = $indexType->getIndex()->refresh();
        return $result;
    }

}
