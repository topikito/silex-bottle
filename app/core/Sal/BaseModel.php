<?php

namespace app\Core\SAL;

use Topikito\Acme\Helper;
use Silex\Application;

/**
 * Class Model
 *
 * @package app\core\SAL
 */
abstract class BaseModel
{
    /**
     * @var \Silex\Application
     */
    protected $_app;

    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $_dbRead;

    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $_dbWrite;

    /**
     * @var string
     */
    protected $_tableName;

    /**
     * @var string
     */
    protected $_tableAlias;

    /**
     * @var
     */
    protected $_id = ['id'];

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $classStructure = explode('\\',get_called_class());
        $className = array_pop($classStructure);

        if (empty($className)) {
            throw new \LogicException('Class name is not valid: "' . $className . '"');
        }

        $className = Helper\StringUtil::fromCamelCaseToUnderScore($className);
        $this->_tableName = $className;

        $this->_app = $app;

        $this->_dbRead = $this->_app['dbs']['mysql_read'];
        $this->_dbWrite = $this->_app['dbs']['mysql_write'];
    }

    /**
     * @param string    $sql
     * @param array     $params
     * @param array     $types
     *
     * @return array
     */
    public function fetchAll($sql, $params = [], $types = [])
    {
        return $this->_dbRead->fetchAll($sql, $params, $types);
    }

    public function save($data, $types = [])
    {
        $identifier = [];
        $saveData = $data;

        $keysAreSet = true;
        foreach ($this->_id as $field) {
            if (isset($data[$field])) {
                $identifier[$field] = $data[$field];
                unset($saveData[$field]);
            } else {
                $keysAreSet = false;
                break;
            }
        }

        if ($keysAreSet) {
            $result = $this->update($saveData, $identifier, $types);
        } else {
            $result = $this->insert($data);
        }

        return $result;
    }

    /**
     * @param array $data
     * @param array $types
     *
     * @return int
     */
    public function insert($data, $types = [])
    {
        $insertResult = $this->_dbWrite->insert($this->_tableName, $data, $types);

        $lastId = false;
        if ($insertResult) {
            $lastId = (int) $this->_dbWrite->lastInsertId();
        }

        return $lastId;
    }

    /**
     * @param array $data
     * @param array $identifier
     * @param array $types
     *
     * @return int
     */
    public function update($data, $identifier, $types = [])
    {
        return $this->_dbWrite->update($this->_tableName, $data, $identifier, $types);
    }

    /**
     * @param $message
     */
    protected function _throwUnexpectedType($message)
    {
        throw new \UnexpectedValueException($message);
    }

    /**
     * @param        $id
     * @param string $fields
     *
     * @return bool
     */
    public function getById($id, $fields = '*')
    {
        $tableIdKeys = (array) $this->_id;

        if (!is_array($id)) {
            if (count($tableIdKeys) > 1) {
                $this->_throwUnexpectedType('ID expected as array, string given');
            }

            $id = [array_pop($this->_id) => $id];
        }

        $queryBuilder = $this->_dbRead->createQueryBuilder();

        $queryBuilder
            ->select($fields)
            ->from($this->_tableName, $this->_tableAlias);

        $parameters = 0;
        foreach ($id as $key => $value) {
            if (!isset($id[$key])) {
                $this->_throwUnexpectedType('ID expected as array, string given');
            }

            $queryBuilder->where( $key . ' = ?')->setParameter($parameters, $value);
            $parameters++;
        }

        $result = $queryBuilder->execute()->fetchAll();

        if (empty($result) || !$result) {
            return false;
        }

        return $result[0];
    }

    /**
     * @param        $data
     * @param string $fields
     *
     * @return bool
     */
    public function getByData($data, $fields = '*')
    {
        if (!is_array($data)) {
            $this->_throwUnexpectedType('Data expected as array');
        }

        $queryBuilder = $this->_dbRead->createQueryBuilder();

        $queryBuilder
            ->select($fields)
            ->from($this->_tableName, $this->_tableAlias);

        $parameters = 0;
        foreach ($data as $key => $value) {
            switch (gettype($value)) {
                case 'array':
                    $operator = 'IN';
                    break;

                case 'NULL':
                    $operator = 'IS';
                    break;

                default:
                    $operator = '=';
                    break;
            }

            $queryBuilder->andWhere( $key . ' ' . $operator . ' ?')->setParameter($parameters, $value);
            $parameters++;
        }

        $result = $queryBuilder->execute()->fetchAll();

        if (empty($result) || !$result) {
            return false;
        }

        return $result;
    }

}
